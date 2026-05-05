# Refund Approved

Hello {{ $user->name }},

Good news! Your refund request for order **#{{ $refundRequest->sourcingOrder->id }}** has been approved.

**Approved Amount:** ${{ number_format($refundRequest->amount_approved, 2) }}

**Administrator Notes:**
{{ $refundRequest->admin_notes ?? 'No additional notes.' }}

@component('mail::button', ['url' => route('client.refund-requests.show', $refundRequest->id)])
View Refund Details
@endcomponent

Thank you for your business,
{{ config('app.name') }}
