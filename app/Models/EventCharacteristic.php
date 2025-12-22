<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use stdClass;
use Cache;
use DB;

class EventCharacteristic extends Model
{
    public $table = 'event_characteristic';
    
    protected $connection = 'mysql';

    protected $fillable = ['id', 'event_id', 'participants', 'duration', 'consumption', 'consumption_remark', 'isOutdoor', 'status', 'created_at', 'updated_at'];
    
}
