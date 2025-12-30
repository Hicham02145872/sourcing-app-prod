<?php

namespace App\DTOs;

use App\Models\SourcingOrder;

class ShippingSheetRowDTO
{
    public function __construct(
        public int $id,
        public string $createdAt,
        public string $status,
        public string $productName,
        public int $quantity,
        public string $trackingNumber,
        public string $clientName,
        public string $address,
        public string $phone,
        public string $productImage,
        public string $weight = '',
        public string $notes = '',
        public bool $isCanceled = false
    ) {}

    public static function fromOrder(SourcingOrder $order): self
    {
        $sourcingRequest = $order->quotation->sourcingRequest;
        
        $imageFormula = '';
        if ($sourcingRequest->product_image) {
            $imageUrl = asset('storage/' . $sourcingRequest->product_image);
            $imageFormula = '=IMAGE("' . $imageUrl . '")';
        }

        return new self(
            id: $order->display_id,
            createdAt: $order->created_at->format('Y-m-d H:i:s'),
            status: $order->status,
            productName: $sourcingRequest->product_name,
            quantity: intval($sourcingRequest->destinations->sum('quantity')),
            trackingNumber: $order->tracking_number ?? '',
            clientName: $order->user->name,
            address: $sourcingRequest->address ?? 'N/A',
            phone: $sourcingRequest->phone_number ?? 'N/A',
            productImage: $imageFormula,
            weight: '', // Future implementation
            notes: '',  // Future implementation
            isCanceled: $order->status === 'shipment_canceled'
        );
    }

    public function toArray(): array
    {
        return [
            $this->id,
            $this->createdAt,
            $this->status,
            $this->productName,
            $this->quantity,
            $this->trackingNumber,
            $this->clientName,
            $this->address,
            $this->phone,
            $this->productImage,
            $this->weight,
            $this->notes,
        ];
    }
}
