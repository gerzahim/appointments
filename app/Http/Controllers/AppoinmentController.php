<?php

namespace App\Http\Controllers;

use App\Models\Appoinment;
use App\Models\Service;
use App\Models\Analyst;
use Illuminate\Http\Request;
use Config;

use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmAppoinmentMail;

use App\Http\Requests\AppoinmentStoreRequest;
use App\Http\Requests\AppoinmentUpdateRequest;
use App\Http\Services\AppoinmentServices;
use Facade\Ignition\DumpRecorder\Dump;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class AppoinmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Appoinment $appoinment)
    {
        $search = $request->get('search', '');
        //$date = $request->get('date', '');
        $date_start = $request->get('date_start', '');
        $date_end   = $request->get('date_end', '');
        $analyst_id = $request->get('analyst_id', '');

        $appoinments = Appoinment::latest();
        
        // add has also value not empty or null 
        if($request->has('search') && !empty($request->input('search'))) {

            $filtered= $appoinments->filter(function($value) use ($search) {
                return (
                    (strpos(strtolower($value->name),  strtolower($search)) !== false ) || 
                    (strpos(strtolower($value->email), strtolower($search)) !== false ) ||
                    (strpos($value->phone, $search) !== false )
                );
            });
            $appoinments = $filtered;
        }
        /*
        if($request->has('date') && !empty($request->input('date'))){
            
            $filtered= $appoinments->where('date',$request->date);
            $appoinments = $filtered;
        }
        */

        if( $request->has('date_start') && !empty($request->input('date_start')) && $request->has('date_end') && !empty($request->input('date_end')) ) {
            $filtered = $appoinments->whereBetween('date', [$request->date_start, $request->date_end]);
            $appoinments = $filtered;

        }

        if($request->has('analyst_id') && !empty($request->input('analyst_id')) ){
            $filtered= $appoinments->where('analyst_id',$request->analyst_id);
            $appoinments = $filtered;
        }

        $appoinments = $appoinments->paginate(10);

        /*
        $appoinments = Appoinment::search($search)
                                 ->orWhere('date', $date)
                                 ->where('analyst_id', $analyst)
                                 ->latest()
                                 ->paginate(4);
        */

        $analysts = Analyst::all();

        return view('app.appoinments.index', compact('appoinments', 'analysts', 'search', 'date_start', 'date_end', 'analyst_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $analysts = Analyst::pluck('name', 'id');
        $services = Service::pluck('name', 'id');
        $appoinmentsTime = Config::get('app.appoinment_slots_time');
        $appoinmentsTime = $array_object = json_decode(json_encode($appoinmentsTime)); // convert array to Object
        
        return view('app.appoinments.create', compact('services', 'analysts', 'appoinmentsTime'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\AppoinmentStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AppoinmentStoreRequest $request)
    {

        $validated = $request->validated();

        //evaluate Appointment Services Logic
        $reservation = (new AppoinmentServices())->reservation($validated);

        if($reservation['status'] == 'error') {
            return back()->withInput()->with('error', $reservation['response']['message'] ); 
        }

        // Adding note, status and analyst_id to request
        $validated = array_merge(['analyst_id' => $reservation['analyst_id']], $validated);
        $validated = array_merge(['status' => 'reserved'], $validated);
        if(empty($request->note)){
            $validated = array_merge(['note' => ''], $validated);
        }
        /*
        $analyst = Analyst::first();

        // add status to request
        if(!empty($request->note)){
            $validated = array_merge(['note' => $request->note], $validated);
        }
        $validated = array_merge(['status' => 'confirmed'], $validated);
        $validated = array_merge(['analyst_id' => $analyst->id], $validated);
        */

        $appoinment = Appoinment::create($validated);

        // Sending Email
        $customerEmail = $validated['email'];   
        $mailData = [
            'title' => 'Appoinment Confirmation',
            'datetime' => date("F jS, Y - h:i A", strtotime($validated['date'].' '.$validated['time'])),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'note'  => (!empty($validated['note'])) ? $validated['note'] : '',
        ];

        
        // Sending mail Notification...
        try {
            Mail::to($customerEmail)->bcc( $reservation['analyst_id'] )->send(new ConfirmAppoinmentMail($mailData));
        } catch (\Exception $e) {
            //dump( 'Error - '.$e);
        }
  

        return redirect()
             ->route('appoinments.edit', $appoinment)
             ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appoinment $appoinment
     * @return \Illuminate\Http\Response
     */
    public function show(Appoinment $appoinment)
    {
        //dd($appoinment->load('service')->load('analyst'));

        return view('app.appoinments.show', compact('appoinment') );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Appoinment  $appoinment
     * @return \Illuminate\Http\Response
     */
    public function edit(Appoinment $appoinment)
    {
        $analysts = Analyst::pluck('name', 'id');
        $services = Service::pluck('name', 'id');
        
        $appoinmentsTime = Config::get('app.appoinment_slots_time');
        $appoinmentsTime = $array_object = json_decode(json_encode($appoinmentsTime)); // convert array to Object

        return view('app.appoinments.edit', compact('appoinment', 'services', 'analysts', 'appoinmentsTime'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\AppoinmentUpdateRequest $request
     * @param  \App\Models\Appoinment  $appoinment
     * @return \Illuminate\Http\Response
     */
    public function update(AppoinmentUpdateRequest $request, Appoinment $appoinment)
    {
        $validated = $request->validated();
        
        //$validated = array_merge(['is_active' => $request->is_active], $validated);
        $appoinment->update($validated);

        return redirect()
             ->route('appoinments.edit', $appoinment)
             ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appoinment  $appoinment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appoinment $appoinment)
    {
        $appoinment->delete();

        return redirect()
             ->route('appoinments.index')
             ->withSuccess(__('crud.common.removed'));
    }

    public function make(){
        return view('app.appoinments.new-appoinment');
    }

    public function onlycomponent() {
        $only_component = TRUE;
        return view('app.appoinments.only-component', compact('only_component'));
    }


    public function attendes(){
        return view('app.appoinments.attendes');
    }



}
