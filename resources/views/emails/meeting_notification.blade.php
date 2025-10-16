@component('mail::message')
# Meeting Scheduled: {{ $title }}

Dear Customer,

You have been invited to a meeting with the following details:

**Date**: {{ $date }}  
**Time**: {{ $time }}  
**Notes**: {{ $notes ?? 'No additional notes provided.' }}

Please contact us if you have any questions.

Best regards,  
{{ config('app.name') }}
@endcomponent