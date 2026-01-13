@component('mail::message')
# Hello {{ $name }}!

Your {{ $userType }} account has been created successfully.

**Account Details:**  
**User ID:** {{ $userId }}  
**Email:** {{ $email }}  
**Password:** {{ $password }}  
**Account Type:** {{ ucfirst($userType) }}

@component('mail::button', ['url' => $loginUrl])
Login to Your Account
@endcomponent

**Important:** Please change your password after logging in for the first time.

Thanks,  
{{ config('app.name') }}
@endcomponent