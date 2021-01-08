<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PollOptions extends Model {

    protected $table = 'poll_options';
    protected $fillable = ['id', 'poll_id', 'poll_optn', 'created_at', 'updated_at'];


}
