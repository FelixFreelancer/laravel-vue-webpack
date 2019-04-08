@component('mail::message')
Dear Admin,

New contact us enquiry is below.

@component('mail::table')
| Title       | Value         |
| ------------- |:-------------:|
| Name      | {!! $data->name !!}      |
| Email      | {!! $data->email !!} |
| Phone Number      | {!! $data->phone_no !!} |
| Subject      | {!! $data->subject !!} |
| Message      | {!! $data->message !!} |
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
