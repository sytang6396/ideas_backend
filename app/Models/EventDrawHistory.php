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
    
    public static function getEventDrawHistory($object = [])
    {
        $user_id = $object['user_id'] ?? null;
        $draw_date = $object['draw_date'] ?? null;
        $detail = $object['detail'] ?? false;

        $query = EventDrawHistory::select('event_draw_history.uid', 'event_draw_history.isAccept', 'event.name_tc', 'event.name_en', 'event.address_tc', 'event.address_en', 'event.description', 'event.website', 'event.remark', 'event_draw_history.draw_date');
        $query->leftJoin('event', 'event_draw_history.event_id', '=', 'event.id');
        if ($user_id) {
            $query->where('event_draw_history.user_id', $user_id);
        }
        if ($draw_date) {
            $query->where('event_draw_history.draw_date', $draw_date);
        }
        if ($detail) {
            $query->leftJoin('event_characteristic', 'event_draw_history.event_id', '=', 'event_characteristic.event_id');
            $query->leftJoin('event_period', 'event_draw_history.event_id', '=', 'event_period.event_id');
            //select more columns, keep the same as EventDrawHistory::select('event_draw_history.uid', 'event_draw_history.isAccept', 'event.name_tc', 'event.name_en', 'event.address_tc', 'event.address_en', 'event.description', 'event.website', 'event.remark', 'event_draw_history.draw_date');
            $query->addSelect('event_characteristic.participants', 'event_characteristic.duration', 'event_characteristic.consumption', 'event_characteristic.consumption_remark', 'event_characteristic.isOutdoor', 'event_period.start_date', 'event_period.end_date', 'event_period.isMonday', 'event_period.mon_start_time', 'event_period.mon_end_time', 'event_period.isTuesday', 'event_period.tue_start_time', 'event_period.tue_end_time', 'event_period.isWednesday', 'event_period.wed_start_time', 'event_period.wed_end_time', 'event_period.isThursday', 'event_period.thur_start_time', 'event_period.thur_end_time', 'event_period.isFriday', 'event_period.fri_start_time', 'event_period.fri_end_time', 'event_period.isSaturday', 'event_period.sat_start_time', 'event_period.sat_end_time', 'event_period.isSunday', 'event_period.sun_start_time', 'event_period.sun_end_time');
        }
        $query->where('event_draw_history.status', 1);
        $query->orderBy('event_draw_history.created_at', 'desc');
        $eventDrawHistory = $query->first();
        
        if (!$eventDrawHistory) {
            return null;
        }
        
        // Format output to match draw() method structure
        $formattedEvent = [
            'draw_id' => $eventDrawHistory->uid,
            'isAccept' => $eventDrawHistory->isAccept,
            'name_tc' => $eventDrawHistory->name_tc,
            'name_en' => $eventDrawHistory->name_en,
            'address_tc' => $eventDrawHistory->address_tc,
            'address_en' => $eventDrawHistory->address_en,
            'description' => $eventDrawHistory->description,
            'website' => $eventDrawHistory->website,
            'remark' => $eventDrawHistory->remark,
        ];
        
        // Add event_characteristic if detail is true
        if ($detail) {
            $formattedEvent['event_characteristic'] = [
                'participants' => isset($eventDrawHistory->participants) ? (int)$eventDrawHistory->participants : null,
                'duration' => isset($eventDrawHistory->duration) ? (int)$eventDrawHistory->duration : null,
                'consumption' => isset($eventDrawHistory->consumption) ? (int)$eventDrawHistory->consumption : null,
                'consumption_remark' => $eventDrawHistory->consumption_remark ?? null,
                'isOutdoor' => isset($eventDrawHistory->isOutdoor) ? (int)$eventDrawHistory->isOutdoor : null,
            ];
            
            $formattedEvent['event_period'] = [
                'start_date' => $eventDrawHistory->start_date ?? null,
                'end_date' => $eventDrawHistory->end_date ?? null,
                'isMonday' => $eventDrawHistory->isMonday ?? null,
                'mon_start_time' => $eventDrawHistory->mon_start_time ?? null,
                'mon_end_time' => $eventDrawHistory->mon_end_time ?? null,
                'isTuesday' => $eventDrawHistory->isTuesday ?? null,
                'tue_start_time' => $eventDrawHistory->tue_start_time ?? null,
                'tue_end_time' => $eventDrawHistory->tue_end_time ?? null,
                'isWednesday' => $eventDrawHistory->isWednesday ?? null,
                'wed_start_time' => $eventDrawHistory->wed_start_time ?? null,
                'wed_end_time' => $eventDrawHistory->wed_end_time ?? null,
                'isThursday' => $eventDrawHistory->isThursday ?? null,
                'thur_start_time' => $eventDrawHistory->thur_start_time ?? null,
                'thur_end_time' => $eventDrawHistory->thur_end_time ?? null,
                'isFriday' => $eventDrawHistory->isFriday ?? null,
                'fri_start_time' => $eventDrawHistory->fri_start_time ?? null,
                'fri_end_time' => $eventDrawHistory->fri_end_time ?? null,
                'isSaturday' => $eventDrawHistory->isSaturday ?? null,
                'sat_start_time' => $eventDrawHistory->sat_start_time ?? null,
                'sat_end_time' => $eventDrawHistory->sat_end_time ?? null,
                'isSunday' => $eventDrawHistory->isSunday ?? null,
                'sun_start_time' => $eventDrawHistory->sun_start_time ?? null,
                'sun_end_time' => $eventDrawHistory->sun_end_time ?? null,
            ];
        }
        
        return $formattedEvent;
    }

    public static function updateHistory($object = []){
        $uid = $object['uid'] ?? null;
        $user_id = $object['user_id'] ?? null;
        $isAccept = $object['isAccept'] ?? null;

        if(!$uid || !$user_id){
            return null;
        }

        $update = EventDrawHistory::where('uid', $uid)->where('user_id', $user_id)->first();
        
        if(!$update){
            return null;
        }

        if($isAccept && $isAccept != $update->isAccept && $update->isAccept != 1){
            $update->isAccept = $isAccept;
        }
        
        $update->save();
        
        return $update;
    }
}
