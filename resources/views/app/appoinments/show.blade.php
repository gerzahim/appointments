@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('appoinments.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.appoinments.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>@lang('crud.appoinments.inputs.date')</h5>
                    <span>{{ $appoinment->date }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.appoinments.inputs.start')</h5>
                    <span>{{ $appoinment->time ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.appoinments.inputs.name')</h5>
                    <span>{{ $appoinment->name ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.appoinments.inputs.email')</h5>
                    <span>{{ $appoinment->email ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.appoinments.inputs.phone')</h5>
                    <span>{{ $appoinment->phone ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.appoinments.inputs.note')</h5>
                    <span>{{ $appoinment->note ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.appoinments.inputs.analyst_id')</h5>
                    <span>
                        {{ optional($appoinment->analyst)->name ?? '-' }}
                    </span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.appoinments.inputs.service_id')</h5>
                    <span>
                        {{  optional($appoinment->service)->name ?? '-' }}
                    </span>
                </div>
            </div>

            <div class="mt-4">
                <a
                    href="{{ route('appoinments.index') }}"
                    class="btn btn-light"
                >
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\Appoinment::class)
                <a
                    href="{{ route('appoinments.create') }}"
                    class="btn btn-light"
                >
                    <i class="icon ion-md-add"></i> @lang('crud.common.create')
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
