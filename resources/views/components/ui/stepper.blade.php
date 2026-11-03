@props([
    'quantity' => 1,
    'min' => 1,
    'max' => 20,
    'mealId' => null,
])

<div class="mobile-stepper d-inline-flex align-items-center rounded-pill p-1"
     style="background: #1f1f1f; border: 1px solid rgba(255, 255, 255, 0.08);"
     data-stepper
     data-meal-id="{{ $mealId }}">
    <!-- Minus / Delete Button -->
    <button type="button"
            class="stepper-btn stepper-minus btn p-0 rounded-circle d-flex align-items-center justify-content-center text-secondary border-0"
            style="width: 30px; height: 30px; min-height: 30px; background: transparent; transition: all 0.2s ease;"
            aria-label="{{ __('Decrease quantity') }}">
        <i class="fas {{ $quantity <= 1 ? 'fa-trash-alt text-danger' : 'fa-minus' }}" style="font-size: 11px;"></i>
    </button>

    <!-- Quantity Display -->
    <span class="stepper-qty px-2 text-center text-light fw-bold"
          style="min-width: 26px; font-size: 14px;"
          aria-live="polite">
        {{ $quantity }}
    </span>

    <!-- Plus Button -->
    <button type="button"
            class="stepper-btn stepper-plus btn p-0 rounded-circle d-flex align-items-center justify-content-center text-secondary border-0"
            style="width: 30px; height: 30px; min-height: 30px; background: transparent; transition: all 0.2s ease;"
            aria-label="{{ __('Increase quantity') }}">
        <i class="fas fa-plus" style="font-size: 11px;"></i>
    </button>
</div>
