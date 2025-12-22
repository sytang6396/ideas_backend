<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use stdClass;
use Cache;
use DB;

class EventPeriod extends Model
{
    public $table = 'event_period';
    
    protected $connection = 'mysql';

    protected $fillable = ['id', 'event_id', 'start_date', 'end_date', 'isMonday', 'mon_start_time', 'mon_end_time', 'isTuesday', 'tue_start_time', 'tue_end_time', 'isWednesday', 'wed_start_time', 'wed_end_time', 'isThursday', 'thu_start_time', 'thu_end_time', 'isFriday', 'fri_start_time', 'fri_end_time', 'isSaturday', 'sat_start_time', 'sat_end_time', 'isSunday', 'sun_start_time', 'sun_end_time', 'status', 'created_at', 'updated_at'];
    
}
