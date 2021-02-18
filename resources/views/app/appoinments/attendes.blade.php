@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <div style="display: flex; justify-content: space-between;">
                <h4 class="card-title">
                    Lista de Espera
                </h4>
            </div>
            <attendes-component
                v-bind:props_date="'{{ date('Y-m-d') }}'"
            ></attendes-component>
        </div>
    </div>
</div>
@endsection
