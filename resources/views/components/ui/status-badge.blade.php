@props([
    'status' => 'pending',
])

@php
    $configs = [
        'pending' => [
            'color' => '#f5b045',
            'bg' => 'rgba(245, 176, 69, 0.14)',
            'label' => __('Pending'),
        ],
        'accepted' => [
            'color' => '#ff7a18',
            'bg' => 'rgba(255, 122, 24, 0.14)',
            'label' => __('Accepted'),
        ],
        'preparing' => [
            'color' => '#ff7a18',
            'bg' => 'rgba(255, 122, 24, 0.14)',
            'label' => __('Preparing'),
        ],
        'prepared' => [
            'color' => '#4ade80',
            'bg' => 'rgba(74, 222, 128, 0.14)',
            'label' => __('Prepared'),
        ],
        'ready_for_delivery' => [
            'color' => '#4ade80',
            'bg' => 'rgba(74, 222, 128, 0.14)',
            'label' => __('Ready for Delivery'),
        ],
        'out_for_delivery' => [
            'color' => '#4ade80',
            'bg' => 'rgba(74, 222, 128, 0.14)',
            'label' => __('Out for Delivery'),
        ],
        'delivered' => [
            'color' => '#4ade80',
            'bg' => 'rgba(74, 222, 128, 0.14)',
            'label' => __('Delivered'),
        ],
        'completed' => [
            'color' => '#4ade80',
            'bg' => 'rgba(74, 222, 128, 0.14)',
            'label' => __('Completed'),
        ],
        'cancelled' => [
            'color' => '#ff6b6b',
            'bg' => 'rgba(255, 107, 107, 0.14)',
            'label' => __('Cancelled'),
        ],
        'rejected' => [
            'color' => '#ff6b6b',
            'bg' => 'rgba(255, 107, 107, 0.14)',
            'label' => __('Rejected'),
        ],
    ];

    $cfg = $configs[$status] ?? [
        'color' => '#9a9a9a',
        'bg' => 'rgba(154, 154, 154, 0.14)',
        'label' => ucfirst(str_replace('_', ' ', $status)),
    ];
@endphp

<span class="mobile-status-badge d-inline-flex align-items-center gap-1.5 rounded-pill px-2.5 py-1"
      style="background: {{ $cfg['bg'] }}; border: 1px solid {{ $cfg['color'] }}33;">
    <span class="rounded-circle" style="width: 6px; height: 6px; background-color: {{ $cfg['color'] }};"></span>
    <span style="font-size: 11px; font-weight: 700; color: {{ $cfg['color'] }};">{{ $cfg['label'] }}</span>
</span>
