<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollAnswers extends Model {

    protected $table = 'poll_answers';
    protected $fillable = ['id', 'poll_id', 'option_id', 'user_id', 'created_at', 'updated_at'];

}
