<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnalystStoreRequest;
use App\Http\Requests\AnalystUpdateRequest;
use App\Models\Analyst;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class AnalystController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $analysts = Analyst::search($search)
                           ->latest()
                           ->paginate(10);

        return view('app.analysts.index', compact('analysts','search') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('app.analysts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AnalystStoreRequest $request)
    {
        $validated = $request->validated();
        $validated = array_merge(['is_active' => 1], $validated);
        $analyst   = Analyst::create($validated);

        return redirect()
             ->route('analysts.edit', $analyst)
             ->withSuccess(__('crud.common.saved'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Analyst  $analyst
     * @return \Illuminate\Http\Response
     */
    public function show(Analyst $analyst)
    {
        return view('app.analysts.show', compact('analyst'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Analyst  $analyst
     * @return \Illuminate\Http\Response
     */
    public function edit(Analyst $analyst)
    {
        return view('app.analysts.edit', compact('analyst'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Analyst  $analyst
     * @return \Illuminate\Http\Response
     */
    public function update(AnalystUpdateRequest $request, Analyst $analyst)
    {
        $validated = $request->validated();
        $validated = array_merge(['is_active' => $request->is_active], $validated);
        $analyst->update($validated);

        return redirect()
             ->route('analysts.edit', $analyst)
             ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Analyst  $analyst
     * @return \Illuminate\Http\Response
     */
    public function destroy(Analyst $analyst)
    {
        $analyst->delete();

        return redirect()
             ->route('analysts.index')
             ->withSuccess(__('crud.common.removed'));
    }
}
