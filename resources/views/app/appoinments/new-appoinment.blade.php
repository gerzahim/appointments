@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="col-12">
                <div class="container row">
                    <div class="col-md-6 col-xs-12">
                        <img src="/images/make_appoinment.jpg" width="380" alt="wrapkit" class="rounded">
                    </div> 
                    <div class="col-md-6 col-xs-12">
                        <appoinment-component></appoinment-component>
                    </div>
                </div><!-- END ROW -->
            </div>
        </div>
    </div>
</div>
@endsection
