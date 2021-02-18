@php $editing = isset($appoinment) @endphp

<div class="row">
    <x-inputs.group class="col-sm-12">
        <x-inputs.date
            name="date"
            label="Date"
            value="{{ old('date', ($editing ? $appoinment->date : '')) }}"
            max="255"
            required
        ></x-inputs.date>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="time" label="Time" required>

            @php $selected = old('time', ($editing ? $appoinment->time : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Time</option>
            @foreach($appoinmentsTime as $appTime)

            <option value="{{ $appTime->time }}" {{ $selected == $appTime->time ? 'selected' : '' }} >{{ $appTime->name }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="name"
            label="Name"
            value="{{ old('name', ($editing ? $appoinment->name : '')) }}"
            maxlength="255"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.email
            name="email"
            label="Email"
            value="{{ old('email', ($editing ? $appoinment->email : '')) }}"
            maxlength="255"
            required
        ></x-inputs.email>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="phone"
            label="Phone"
            value="{{ old('phone', ($editing ? $appoinment->phone : '')) }}"
            maxlength="255"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="note"
            label="Note"
            value=".{{ old('note', ($editing ? $appoinment->note : '')) }}"
            maxlength="255"
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="status" label="Status" required>
            <option value="reserved" {{ 'reserved' == ($editing ? $appoinment->status : '') ? 'selected' : '' }} >reserved</option>
            <option value="confirmed" {{ 'confirmed' == ($editing ? $appoinment->status : '') ? 'selected' : '' }} >confirmed</option>
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="analyst_id" label="Analyst" required>
            @php $selected = old('analyst_id', ($editing ? $appoinment->analyst_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Analyst</option>
            @foreach($analysts as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="service_id" label="Service" required>
            @php $selected = old('service_id', ($editing ? $appoinment->service_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Service</option>
            @foreach($services as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>
</div>
