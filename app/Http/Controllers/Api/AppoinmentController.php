<?php

namespace App\Http\Controllers\Api;
use Config;
use App\Http\Resources\AppoinmentResource;
use App\Models\Appoinment;
use App\Models\Service;
use App\Models\Analyst;

use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmAppoinmentMail;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppoinmentStoreRequest;
use App\Http\Resources\ServiceResource;

use App\Http\Services\AppoinmentServices;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class AppoinmentController extends Controller
{

    /**
     * Getting all Appoinments
     */
    public function index()
    {
        //return Appoinment::select('id','date', 'name')->get();
        //return Appoinment::all();

        //$appoinments = Appoinment::all();
        //return AppoinmentResource::collection($appoinments);

        $appoinments =Appoinment::with('service', 'analyst')->get();
        return AppoinmentResource::collection($appoinments);
    }


    /**
     * Get info specific appoinment
     */
    public function show(Appoinment $appoinment)
    {
        //return $appoinment;
        return new AppoinmentResource($appoinment);
    }

    /**
     * Get Time availables for appoinment based on date Given
     */
    public function getTimeAvailable($date, $analysts_count){

        $max_slot_appointments = $analysts_count;
        $minutes_ahead_appoinment = (new AppoinmentServices())->minutes_ahead_appoinment;

        // Get all today appoinments
        $reserved = Appoinment::where('date', $date)->get();
        $appoinmentsTime = Config::get('app.appoinment_slots_time');

        //Looping all slot times and delete the not available
        foreach ($appoinmentsTime as $key => $value) {

            $start = strtotime(date("Y-m-d H:i:s"));
            $end   = strtotime($date.' '.$value['time']);
            $diff_mins  = ($end - $start) / 60; // 60 minutes

            // check if appointment slot is full or if is 2 hours in advances
            if( $reserved->where('time', $value['time'])->count() >= $max_slot_appointments
                || ($diff_mins < $minutes_ahead_appoinment)
            ){
                unset($appoinmentsTime[$key]);
            }
        }

        return $appoinmentsTime;
    }

    public function getAnalystActiveCount(){
        return Analyst::where('is_active',true)->count();
    }

    /**
     * Get all Services provided
     */
    public function getServices(){
        $services = Service::all();
        //return $services;
        return ServiceResource::collection($services);
    }

    /**
     * Create new Appointment
     */
    public function store(AppoinmentStoreRequest $request){

        // validate request param are valid data
        $validated = $request->validated();

        //evaluate Appointment Services Logic
        $reservation = (new AppoinmentServices())->reservation($validated);

        if($reservation['status'] == 'error') {
            return response($reservation['response'], Response::HTTP_BAD_REQUEST);
        }

        // Adding note, status and analyst_id to request
        $validated = array_merge(['analyst_id' => $reservation['analyst_id']], $validated);
        $validated = array_merge(['status' => 'reserved'], $validated);
        if(empty($request->note)){
            $validated = array_merge(['note' => ''], $validated);
        }
        
        // Create Appoinment
        $appoinment = Appoinment::create($validated);

        // Sending Email
        $analyst_email = Analyst::where('id', $reservation['analyst_id'])->pluck('email');

        $customerEmail = $validated['email'];
        $emails = [ $customerEmail, $analyst_email ];

        $mailData = [
            'title' => 'Appoinment Confirmation - Credito786.',
            'datetime' => date("F jS, Y - h:i A", strtotime($validated['date'].' '.$validated['time'])),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'note'  => (!empty($validated['note'])) ? $validated['note'] : '',
        ];

        // Sending mail Notification...
        try {
            Mail::to($customerEmail)->bcc($analyst_email)->send(new ConfirmAppoinmentMail($mailData));
            //dump('Mail send successfully');
        } catch (\Exception $e) {
            //dump( 'Error - '.$e);
        }

        return new AppoinmentResource($appoinment);
    }


    public function fetchAttendes($date){
        $appoinments = Appoinment::where('date', $date)->with('service', 'analyst')->orderBy('time')->get();

        $start = strtotime(date("Y-m-d H:i:s"));

        // keeps matching values
        $appoinments = $appoinments->filter(function ($appoinment, $key) use ($date, $start) {
            // date given is in the future ?
            if(strtotime($date.' '.$appoinment->time) > time()) {
                // convert Militar time into Human readable time with AM or PM
                return $appoinment->time = date("h:i A", strtotime($appoinment->time));
            }
        });

        return $appoinments;
    }

    public function confirmAppointment(Request $request)
    {
        //dd($request, $search = $request->get('id', '') );
        $appoinment = Appoinment::where('id', $request->id)->first();
        $appoinment->status = 'confirmed';
        $appoinment->save();
        return $appoinment;
    }


}
