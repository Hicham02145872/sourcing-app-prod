# Refund Rejected

Hello {{ $user->name }},

We regret to inform you that your refund request for order **#{{ $refundRequest->sourcingOrder->id }}** has been rejected.

**Reason/Notes from Administrator:**
{{ $refundRequest->admin_notes ?? 'No additional notes provided.' }}

@component('mail::button', ['url' => route('client.refund-requests.show', $refundRequest->id)])
View Refund Details
@endcomponent

If you have any questions, please contact our support team.

Best regards,
{{ config('app.name') }}
