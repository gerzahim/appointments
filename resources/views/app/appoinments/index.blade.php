@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <div style="display: flex; justify-content: space-between;">
                <h4 class="card-title">
                    @lang('crud.appoinments.index_title')
                </h4>
            </div>

            <div class="searchbar mt-4 mb-5">
                <div class="row">
                   <div class="card w-100 bg-light mx-auto">
                        <form>
                        <div class="card-body">
                            <p class="card-text">                        
                                <div class="row">
                                    <div class="col-sm">
                                        <label for="date" class="label ">Search</label> 
                                        <input
                                                id="indexSearch"
                                                type="text"
                                                name="search"
                                                placeholder="{{ __('crud.common.search') }}"
                                                value="{{ $search ?? '' }}"
                                                class="form-control"
                                                autocomplete="off"
                                            />
                                    </div>
                                    <div class="col-sm">
                                        <label for="date_start" class="label ">Starting Date</label> 
                                        <input type="date" id="date_start" name="date_start" value="{{ $date_start ?? '' }}" autocomplete="off" class="form-control">                         
                                    </div>
                                    <div class="col-sm">
                                        <label for="date_end" class="label ">Finishing Date</label> 
                                        <input type="date" id="date_end" name="date_end" value="{{ $date_end ?? '' }}" autocomplete="off" class="form-control">                         
                                    </div>
                                    <div class="col-sm">
                                        <label for="analyst_id" class="label">Analyst</label> 
                                        <select id="analyst_id" name="analyst_id" autocomplete="off" class="form-control">
                                            <option disabled="disabled" value="" selected>Please select the Analyst</option>
                                            @foreach($analysts as $value => $analyst)
                                                <!-- Mark Selected if it's the same one -->
                                                @if($analyst_id == $analyst->id)
                                                    <option selected="selected" value="{{ $analyst->id }}">{{ $analyst->name }}</option>
                                                @else
                                                    <option value="{{ $analyst->id }}">{{ $analyst->name }}</option>
                                                @endif
                                            
                                            @endforeach
                                        </select>                            
                                    </div>
                                    <div class="col-sm mt-4 pt-1">
                                        <button type="submit" class="btn btn-primary ">
                                            <i class="icon ion-md-search"></i> Search
                                        </button> 
                                        <a
                                            href="{{ route('appoinments.index') }}"
                                            class="btn btn-secondary"
                                            >
                                            <i class="icon ion-md-refresh"></i>
                                            @lang('crud.common.reset')
                                        </a>                        
                                    </div>
                                </div>
                            </p>

                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless table-hover">
                    <thead>
                        <tr>
                            <th>@lang('crud.appoinments.inputs.date')</th>
                            <th>@lang('crud.appoinments.inputs.start')</th>
                            <th>@lang('crud.appoinments.inputs.name')</th>
                            <th>@lang('crud.appoinments.inputs.email')</th>
                            <th>@lang('crud.appoinments.inputs.phone')</th>
                            <th>@lang('crud.appoinments.inputs.status')</th>
                            <th>@lang('crud.appoinments.inputs.analyst_id')</th>
                            <th>@lang('crud.appoinments.inputs.service_id')</th>
                            <th class="text-center">
                                @lang('crud.common.actions')
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appoinments as $appoinment)
                        <tr>
                            <td>{{ $appoinment->date ? date("m-d-Y", strtotime($appoinment->date)) : '-' }}</td>
                            
                            <td>{{ $appoinment->time ?? '-' }}</td>
                            <td>{{ $appoinment->name ?? '-' }}</td>
                            <td>{{ $appoinment->email ?? '-' }}</td>
                            <td>{{ $appoinment->phone ?? '-' }}</td>
                            <td>{{ $appoinment->status ?? '-' }}</td>
                            <td>
                                {{ optional($appoinment->analyst)->name ?? '-'
                                }}
                            </td>
                            <td>
                                {{ optional($appoinment->service)->name ?? '-'
                                }}
                            </td>
                            <td class="text-center" style="width: 134px;">
                                <div
                                    role="group"
                                    aria-label="Row Actions"
                                    class="btn-group"
                                >
                                    <button 
                                        type="button" 
                                        class="btn btn-light text-success" 
                                        onclick="add( {{ $appoinment->id }} );"
                                    >
                                        <i class="icon ion-md-checkbox"></i>
                                    </button>
                                    <a
                                        href="{{ route('appoinments.edit', $appoinment) }}"
                                    >  
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-create"></i>
                                        </button>
                                    </a>
                                    <a
                                        href="{{ route('appoinments.show', $appoinment) }}"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-light"
                                        >
                                            <i class="icon ion-md-eye"></i>
                                        </button>
                                    </a>
                                    <form
                                        action="{{ route('appoinments.destroy', $appoinment) }}"
                                        method="POST"
                                        onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')"
                                    >
                                        @csrf @method('DELETE')
                                        <button
                                            type="submit"
                                            class="btn btn-light text-danger"
                                        >
                                            <i class="icon ion-md-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10">
                                No Appoinments Found 
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="8">{!! $appoinments->render() !!}</td>
                            <td>                        
                                <a
                                href="{{ route('appoinments.create') }}"
                                class="btn btn-primary"
                                >
                                <i class="icon ion-md-add"></i>
                                @lang('crud.common.create')
                                </a>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            
        </div>
    </div>
</div>



@endsection
