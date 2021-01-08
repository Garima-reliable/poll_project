<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollDetails extends Model {
    
    /**
     * Set Pagination Size for this Model
     */
    const Pagination = 10;

    /**
     * Get poll status id poll is open or close
     */
    const PollOpen = 1;
    const PollClose = 2;

    protected $table = 'poll_details';
    protected $fillable = ['id', 'poll_name', 'poll_desc', 'poll_time', 'poll_status', 'created_at', 'updated_at'];

    public static function pollStatus() {
        return [
            self::PollOpen => 'Open',
            self::PollClose => 'Close'
        ];
    }

    public function getPollOption() {
        return $this->hasMany('App\Models\PollOptions', 'poll_id', 'id');
    }

}
