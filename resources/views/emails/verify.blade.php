@component('mail::message')
# Welcome!

<p>Dear {{$name}},</p>
<p>Thank you for registering! Please log in and enter your authorization code to confirm your email.</p>
<p>Here is your authorization code:</p>
@component('mail::panel')
{{$authorizationCode}}
@endcomponent

@component('mail::button', ['url' => route('api.login')])
Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
