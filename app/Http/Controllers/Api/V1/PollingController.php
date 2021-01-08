<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\CreatePoll;
use App\Http\Requests\Api\PollVoting;
use App\Models\PollAnswers;
use App\Models\PollDetails;
use App\Models\PollOptions;
use Illuminate\Support\Facades\DB;

class PollingController extends ApiController {

    /* 
     
        Function is used to create poll

    */
    public function createPolling(CreatePoll $request) {
        $input = request()->all();
        $input['poll_status'] = PollDetails::PollOpen;
        // create poll
        $createPoll = PollDetails::create($input);
        if (!$createPoll) {
            return $this->jsonResponse(false, 500, 'Something went wrong, Please try again.');
        }
        $error = false;
        foreach ($input['poll_optn'] as $options) {
            $optionFields = [
                'poll_id' => $createPoll->id,
                'poll_optn' => $options
            ];
            // create poll option
            $createOption = PollOptions::create($optionFields);
            if (!$createOption) {
                PollOptions::where(['poll_id' => $createPoll->id])->delete();
                PollDetails::where(['id' => $createPoll->id])->delete();
                $error = true;
                break;
            }
        }
        if ($error) {
            return $this->jsonResponse(false, 500, 'Something went wrong, Please try again.');
        } else {
            return $this->jsonResponse(true, 200, 'Poll Successfully Created .', $this->getPollById($createPoll->id));
        }
    }

    /* 
     Function is used to fetch details of poll
    */
    public function getPollById($id) {
        // fetch details of poll
        $pollDetails = PollDetails::find($id);
        if (!$pollDetails) {
            return [];
        }

        /**
         * Update poll status if it's not complete
         */
        if ($pollDetails->poll_time <= date('Y-m-d H:i:s')) {
            PollDetails::where(['id' => $id])->update(['poll_status' => PollDetails::PollOpen]);
            $pollDetails = PollDetails::find($id);
        }

        $pollingData = [];
        $pollingData['id'] = $id;
        $pollingData['poll_name'] = $pollDetails->poll_name;
        $pollingData['poll_status'] = PollDetails::pollStatus()[$pollDetails->poll_status];
        $pollingData['poll_desc'] = $pollDetails->poll_desc;
        $optn = [];
        $pollAnswer = PollAnswers::where(['poll_id' => $id])->count();
        foreach ($pollDetails->getPollOption as $option) {
            $optionData = [];
            $optionData['option_id'] = $option->id;
            $optionData['option'] = $option->poll_optn;
            $optionData['totalVotes'] = '0 %';
            /*Check option Percentage*/
            if ($pollAnswer) {
                $getOptionCount = PollAnswers::where(['poll_id' => $id, 'option_id' => $option->id])->count();
                $getPercentage = ($getOptionCount * 100) / $pollAnswer;
                $optionItem['totalVotes'] = number_format($getPercentage, '2', '.', '') . ' %';
            }
            $optn[] = $optionData;

        }
        $pollData['options'] = $optn;
        return $pollData;
    }

    /* 
     
        Function to get polling details

    */
    public function pollingDetails($id) {
        return $this->jsonResponse(true, 200, 'Details fetch successfully.', $this->getPollById($id));
    }


    /* 
        Function allow user allow to do voting
    */
    public function pollVoting(PollVoting $request) {
        $input = request()->all();
        $pollAnswer = PollOptions::where(['poll_id' => $input['poll_id'], 'id' => $input['option_id']])->first();
        if (!$pollAnswer) {
            return $this->jsonResponse(false, 422, 'Invalid Input data.');
        }
        $create = [
            'poll_id' => $input['poll_id'],
            'user_id' => auth()->id(),
        ];
        PollAnswers::where($create)->delete(); // this is so they can change their vode for their poll
        $create['option_id'] = $input['option_id'];
        PollAnswers::create($create);
        return $this->jsonResponse(true, 200, 'Voting successfully done.');
    }

    /* 
        Function show polling results
    */
    public function pollingResults($id) {
        $pollDetails = $this->getPollById($id);
        if (!$pollDetails) {
            return $this->jsonResponse(false, 422, 'No poll exist with given id.');
        }
       
        if(isset($pollDetails['poll_status'])){
            if ($pollDetails['poll_status'] == PollDetails::PollOpen) {
                return $this->jsonResponse(false, 200, 'Poll is still open.');
            }
            return $this->jsonResponse(false, 200, 'No Poll Available.');
        }
       
        $winnerPercentage = 0;
        foreach ($pollDetails['options'] as $option) {
            $optionPercent = (float)$option['totalVotes'];
            if ($winnerPercentage <= $optionPercent) {
                $winnerPercentage = $optionPercent;
                $pollDetails['winnerOption'] = [
                    'option_id' => $option['option_id'],
                    'option' => $option['option'],
                    'totalVotes' => $option['totalVotes'],
                ];
            }
        }
        return $this->jsonResponse(true, 200, 'Polling results fetch successfully.', $pollDetails);
    }

    /* 
        Function show all polling data
    */
    public function pollingData() {
        $pollingData = PollDetails::paginate(PollDetails::Pagination);
        return $this->jsonResponse(true, 200, 'Polling Data fetch successfully.', $pollingData);
    }

}
