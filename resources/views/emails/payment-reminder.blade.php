<x-mail::message>
# Payment Reminder

Hello {{ $sourcingOrder->user->name }},

This is a friendly reminder that the payment for your sourcing order #{{ $sourcingOrder->id }} is still pending.

**Order Summary:**
- **Product:** {{ $sourcingOrder->quotation->sourcingRequest->product_name }}
- **Total Amount:** {{ number_format($sourcingOrder->total_amount, 2) }} {{ $sourcingOrder->quotation->currency }}

Please proceed with the payment at your earliest convenience. You can view your order details and upload your proof of payment by clicking the button below.

<x-mail::button :url="route('client.sourcing-orders.show', $sourcingOrder)">
View Order
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>