<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use stdClass;
use Cache;
use DB;

class Event extends Model
{
    public $table = 'event';
    
    protected $connection = 'mysql';

    protected $fillable = ['id', 'district_id', 'name_tc', 'name_en', 'address_tc', 'address_en', 'description', 'website', 'remark', 'status', 'created_at', 'created_by'];

}
