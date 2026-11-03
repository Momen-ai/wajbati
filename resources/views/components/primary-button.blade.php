<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary rounded-pill px-4 py-2 fw-semibold text-light shadow-sm']) }}>
    {{ $slot }}
</button>
