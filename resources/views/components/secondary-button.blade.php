<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-outline-secondary border-opacity-25 rounded-pill px-4 py-2 fw-semibold text-light shadow-sm']) }}>
    {{ $slot }}
</button>
