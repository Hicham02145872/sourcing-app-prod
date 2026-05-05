<x-mail::message>
# Pro-forma Invoice for Order #{{ $sourcingOrder->id }}

Hello {{ $sourcingOrder->user->name }},

Thank you for accepting the quotation! Your order has been created.

Please find your pro-forma invoice attached to this email.

You can also view your order details and upload your proof of payment by clicking the button below.

<x-mail::button :url="route('client.sourcing-orders.show', $sourcingOrder)">
View Order
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>