@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm mb-1 text-light opacity-90']) }}>
    {{ $value ?? $slot }}
</label>
