@php $editing = isset($analyst) @endphp

<div class="row">
    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="name"
            label="Name"
            value="{{ old('name', ($editing ? $analyst->name : '')) }}"
            maxlength="255"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.email
            name="email"
            label="Email"
            value="{{ old('email', ($editing ? $analyst->email : '')) }}"
            maxlength="255"
            required
        ></x-inputs.email>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="is_active" label="Status" required>
            <option value="1" {{ 1 == ($editing ? $analyst->is_active : '') ? 'selected' : '' }} >Active</option>
            <option value="0" {{ 0 == ($editing ? $analyst->is_active : '') ? 'selected' : '' }} >Inactive</option>
        </x-inputs.select>
    </x-inputs.group>
</div>
