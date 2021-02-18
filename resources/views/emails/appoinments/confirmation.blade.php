@component('mail::message')
# {{$mailData['title']}}

Dear {{$mailData['name']}},<br>
Congratulations! Your appoinment has been confirmed.<br>

## Reservation Details:<br>
{{$mailData['datetime']}}<br>
8242 W Flagler St, Miami, FL 33144<br><br>

## Customer Details:<br>
- {{$mailData['name']}}<br>
- {{$mailData['email']}}<br>
- {{$mailData['phone']}}<br>

Thanks,<br>
<img src="{{ asset('/images/x100.png') }}" alt="{{ config('app.name') }} Logo">
@endcomponent
