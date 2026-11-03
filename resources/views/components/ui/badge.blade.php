@props([
    'type' => null, // primary, success, warning, danger, info, secondary
    'status' => null,
    'icon' => null,
])

@php
    if ($status) {
        $statusMap = [
            'pending'            => ['type' => 'warning', 'label' => __('Pending')],
            'accepted'           => ['type' => 'info',    'label' => __('Accepted')],
            'preparing'          => ['type' => 'info',    'label' => __('Preparing')],
            'prepared'           => ['type' => 'success', 'label' => __('Prepared')],
            'ready_for_delivery' => ['type' => 'success', 'label' => __('Ready for Delivery')],
            'out_for_delivery'   => ['type' => 'primary', 'label' => __('Out for Delivery')],
            'delivered'          => ['type' => 'success', 'label' => __('Delivered')],
            'completed'          => ['type' => 'success', 'label' => __('Completed')],
            'cancelled'          => ['type' => 'danger',  'label' => __('Cancelled')],
            'rejected'           => ['type' => 'danger',  'label' => __('Rejected')],
        ];
        $mapped = $statusMap[$status] ?? ['type' => 'secondary', 'label' => ucfirst($status)];
        $type = $type ?? $mapped['type'];
        $slotText = $slot->isNotEmpty() ? $slot : $mapped['label'];
    } else {
        $type = $type ?? 'primary';
        $slotText = $slot;
    }

    $typeClasses = [
        'primary'   => 'badge-primary',
        'success'   => 'badge-success',
        'warning'   => 'badge-warning',
        'danger'    => 'badge-danger',
        'info'      => 'badge-info',
        'secondary' => 'bg-surface-3 text-secondary border border-subtle',
    ][$type] ?? 'badge-primary';
@endphp

<span {{ $attributes->merge(['class' => 'badge ' . $typeClasses . ' d-inline-flex align-items-center gap-1']) }}>
    @if($icon)
        <i class="{{ $icon }} small"></i>
    @endif
    {{ $slotText }}
</span>
