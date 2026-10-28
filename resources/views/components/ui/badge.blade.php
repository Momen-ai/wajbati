@props([
    'type' => 'primary', // primary, success, warning, danger, info, secondary
    'icon' => null,
])

@php
    $typeClasses = [
        'primary' => 'badge-primary',
        'success' => 'badge-success',
        'warning' => 'badge-warning',
        'danger'  => 'badge-danger',
        'info'    => 'badge-info',
        'secondary' => 'bg-surface-3 text-secondary border border-subtle',
    ][$type] ?? 'badge-primary';
@endphp

<span {{ $attributes->merge(['class' => 'badge ' . $typeClasses . ' d-inline-flex align-items-center gap-1']) }}>
    @if($icon)
        <i class="{{ $icon }} small"></i>
    @endif
    {{ $slot }}
</span>
