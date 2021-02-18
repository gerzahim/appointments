<?php

namespace App\Http\Services;

use App\Models\Appoinment;
use App\Models\Service;
use App\Models\Analyst;
use Config;

class AppoinmentServices {

    public $minutes_ahead_appoinment; // 120 minutes - 2 hours before Appointments
    public $max_slot_appointments;   //  2 Appointments by slot time depend if analyst active

    public function __construct() {

        $this->minutes_ahead_appoinment = 120;
        $this->max_slot_appointments    = Analyst::where('is_active',true)->count();

        // Rule :  after 4PM max_slot_appointments = 1
        /*
        $current_time = date("H");
        if ($current_time >= "16"){
            $this->max_slot_appointments = 1;
        }*/
    }

    /**
     *  Valid Logic of Reserve an Appointment
     */
    public function reservation($validated) {

        //converting array to object 
        $validated = json_decode(json_encode($validated), FALSE);

        // Check if date given is today's date or forward
        $current = strtotime(date("Y-m-d"));
        $date    = strtotime($validated->date);
        $datediff = $date - $current;
        $difference = floor($datediff/(60*60*24));


        if($difference<0) {
            $response = [
                'message' => 'Date field is not valid only today\'s date and forward.',
                'errors' => true
            ];
            return [
                'status'   => 'error',
                'response' => $response,
            ];
        }

        // Check if time is today so must be valid for $this->minutes_ahead_appoinment
        if ($difference == 0) {
            $start = strtotime(date("Y-m-d H:i:s"));
            $end   = strtotime($validated->date.$validated->time);
            $diff_mins  = ($end - $start) / 60; // 60 minutes

            if ( $diff_mins < $this->minutes_ahead_appoinment){
                
                $response = [
                    'message' => 'Time field is not valid for today\'s time must be 2 hours in advance.',
                    'errors' => true
                ];
                return [
                    'status'   => 'error',
                    'response' => $response,
                ];
            }
        }

        // Check is slot time given is valid
        $slotTimes = Config::get('app.appoinment_slots_time');
        $contained = false;
        foreach ($slotTimes as $slot )
        {
            if ($slot['time'] == $validated->time) {
                $contained = true;
            }
        }
        if (!$contained){
            $response = [
                'message' => 'Time field is not valid only allowed List of Appoinments Times',
                'errors' => true
            ];
            return [
                'status'   => 'error',
                'response' => $response,
            ];
        }

        // Get all today appoinments
        $appots = Appoinment::where('date', $validated->date)->get();


        // Only allow Once appoinment per day by Client
        if($appots->contains('phone', $validated->phone)){

            $response = [
                'message' => 'Only once appoinment per day by Client - Phone already registered',
                'errors' => true,
            ];
            return [
                'status'   => 'error',
                'response' => $response,
            ];
        }

        // Only allow Once appoinment per day by Client
        if($appots->contains('email', $validated->email)){

            $response = [
                'message' => 'Only once appoinment per day by Client - Email already registered',
                'errors' => true,
            ];
            return [
                'status'   => 'error',
                'response' => $response,
            ];
        }

        // Check if slot time given is available
        $appoinments_count = Appoinment::where('date', $validated->date)->where('time', $validated->time)->count();

        if ($appoinments_count >= $this->max_slot_appointments){

            $response = [
                'message' => 'Not Appoinment Available for given Date and Time',
                'errors' => true
            ];
            return [
                'status'   => 'error',
                'response' => $response,
            ];
        }

        // At this Point at least one analyst is available for the slot selected 

        // Determine analysts Booked
        $analysts_slot_booked = Appoinment::where('date', $validated->date)->where('time', $validated->time)->pluck('analyst_id');
        
        if($analysts_slot_booked->isNotEmpty()){
            // Find First Analyst Available with not booked at time selected
            $analyst_id = Analyst::whereNotIn('id', $analysts_slot_booked)->where('is_active',true)->pluck('id')->first();
        }else {
            // Slot time FREE, Any analyst is booked in the slot selected
            // we want to balance the appoinments between the analysts

            // Get list the appoinment for date selected
            $appots = Appoinment::where('date', $validated->date)->get();

            // Count the appoinment by Analysts
            $analyst_count = $appots->countBy(function ($appoinment) {
                return $appoinment['analyst_id'];
            });
            
            // convert to array
            $analyst_count = @json_decode(json_encode($analyst_count), true);

            // Get the analyst with less apponinmet in date selected
            if(!empty($analyst_count)){
                $analyst_id = array_search(min($analyst_count), $analyst_count);
            }else{
                // Any of the Analyst had appoinments set in date Selected 
                // This will be the first appoinment for this date
                $analyst = Analyst::where('is_active',true)->first();
                $analyst_id = $analyst->id;
            }
        }

        return [
            'status'   => 'success',
            'analyst_id' => $analyst_id,
        ];

    }
}