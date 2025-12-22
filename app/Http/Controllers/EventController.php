<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use stdClass;
use Log;
use App\Models\Event;
use App\Models\EventDrawHistory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request, $id = null)
    {
        $user = Auth::user();
        $user_id = $user->id;
        // $todayIsAcceptCount = EventDrawHistory::where('user_id', $user_id)->where('draw_date', Carbon::now()->toDateString())->where('isAccept', -1)->count();
        // if ($todayIsAcceptCount >= 2) {
        //     return response()->json([
        //         'message'=> 'You have reached the maximum number of attempts for today',
        //         'status' => '400',
        //         'data'=> []
        //     ], 400);
        // }
        EventDrawHistory::where('user_id', $user_id)->where('status', 1)->where('draw_date', Carbon::now()->toDateString())->update(['isAccept' => -1]);
        $last10EventIds = EventDrawHistory::orderBy('created_at', 'desc')->limit(10)->pluck('event_id');
        $event = Event::whereNotIn('event.id', $last10EventIds)
            ->join('event_characteristic', 'event.id', '=', 'event_characteristic.event_id')
            ->join('event_period', 'event.id', '=', 'event_period.event_id')
            ->where('event.status', 1)
            ->inRandomOrder()
            ->first([
                'event.id',
                'event.name_tc',
                'event.name_en',
                'event.address_tc',
                'event.address_en',
                'event.description',
                'event.website',
                'event.remark',
                'event_characteristic.participants',
                'event_characteristic.duration',
                'event_characteristic.consumption',
                'event_characteristic.consumption_remark',
                'event_characteristic.isOutdoor',
                'event_period.start_date',
                'event_period.end_date',
                'event_period.isMonday',
                'event_period.mon_start_time',
                'event_period.mon_end_time',
                'event_period.isTuesday',
                'event_period.tue_start_time',
                'event_period.tue_end_time',
                'event_period.isWednesday',
                'event_period.wed_start_time',
                'event_period.wed_end_time',
                'event_period.isThursday',
                'event_period.thur_start_time',
                'event_period.thur_end_time',
                'event_period.isFriday',
                'event_period.fri_start_time',
                'event_period.fri_end_time',
                'event_period.isSaturday',
                'event_period.sat_start_time',
                'event_period.sat_end_time',
                'event_period.isSunday',
                'event_period.sun_start_time',
                'event_period.sun_end_time'
            ]);
        if (!$event) {
            return response()->json([
                'message'=> 'No event found',
                'status' => '404',
                'data'=> []
            ], 404);
        }
        $uid = Str::random(50, 'abcdefghijklmnopqrstuvwxyz0123456789');
        EventDrawHistory::create([
            'uid' => $uid,
            'user_id' => $user_id,
            'event_id' => $event->id,
            'draw_date' => Carbon::now(),
            'status' => 1
        ]);
        $formattedEvent = [
            'draw_id' => $uid,
            'name_tc' => $event->name_tc,
            'name_en' => $event->name_en,
            'address_tc' => $event->address_tc,
            'address_en' => $event->address_en,
            'description' => $event->description,
            'website' => $event->website,
            'remark' => $event->remark,
            'event_characteristic' => [
                'participants' => (int)$event->participants,
                'duration' => (int)$event->duration,
                'consumption' => (int)$event->consumption,
                'consumption_remark' => $event->consumption_remark,
                'isOutdoor' => (int)$event->isOutdoor,
            ],
            'event_period' => [
                'start_date' => $event->start_date,
                'end_date' => $event->end_date,
                'isMonday' => $event->isMonday,
                'mon_start_time' => $event->mon_start_time,
                'mon_end_time' => $event->mon_end_time,
                'isTuesday' => $event->isTuesday,
                'tue_start_time' => $event->tue_start_time,
                'tue_end_time' => $event->tue_end_time,
                'isWednesday' => $event->isWednesday,
                'wed_start_time' => $event->wed_start_time,
                'wed_end_time' => $event->wed_end_time,
                'isThursday' => $event->isThursday,
                'thur_start_time' => $event->thur_start_time,
                'thur_end_time' => $event->thur_end_time,
                'isFriday' => $event->isFriday,
                'fri_start_time' => $event->fri_start_time,
                'fri_end_time' => $event->fri_end_time,
                'isSaturday' => $event->isSaturday,
                'sat_start_time' => $event->sat_start_time,
                'sat_end_time' => $event->sat_end_time,
                'isSunday' => $event->isSunday,
                'sun_start_time' => $event->sun_start_time,
                'sun_end_time' => $event->sun_end_time,
            ]
        ];
        
        return response()->json([
            'message'=> 'success',
            'status' => '200',
            'data'=> [
                'event' => $formattedEvent
            ],
        ]);
    }
}
