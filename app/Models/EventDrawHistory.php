<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use stdClass;
use Cache;
use DB;

class EventDrawHistory extends Model
{
    public $table = 'event_draw_history';
    
    protected $connection = 'mysql';

    protected $fillable = ['id', 'uid', 'user_id', 'event_id', 'draw_date', 'isAccept', 'status', 'created_at', 'created_by'];
    
}
