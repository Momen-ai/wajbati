<?php

namespace App\Services;

use ArrayAccess;
use Illuminate\Support\Collection;

class Quote implements ArrayAccess
{
    /**
     * @param  Collection  $byChef  Collection of per-chef arrays/objects
     */
    public function __construct(
        public Collection $byChef,
        public float $subtotal,
        public float $deliveryFee,
        public float $grandTotal,
        public int $itemCount
    ) {}

    public function isEmpty(): bool
    {
        return $this->byChef->isEmpty();
    }

    public function offsetExists(mixed $offset): bool
    {
        return in_array($offset, ['by_chef', 'byChef', 'subtotal', 'total_delivery_fee', 'delivery_fee', 'grand_total', 'total', 'count', 'item_count']);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return match ($offset) {
            'by_chef', 'byChef' => $this->byChef,
            'subtotal' => $this->subtotal,
            'total_delivery_fee', 'delivery_fee', 'deliveryFee' => $this->deliveryFee,
            'grand_total', 'total' => $this->grandTotal,
            'count', 'item_count', 'itemCount' => $this->itemCount,
            default => null,
        };
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        // Immutable DTO
    }

    public function offsetUnset(mixed $offset): void
    {
        // Immutable DTO
    }
}
