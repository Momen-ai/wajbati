@extends('layouts.frontend')

@section('content')
<div class="container py-5">
  <div class="d-flex align-items-center justify-content-between mb-4">
      <div class="d-flex align-items-center gap-3">
          <h1 class="h2 fw-bold mb-0 text-light">{{ __('Shopping Cart') }}</h1>
          <span class="badge rounded-pill px-3 py-1 bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 fw-bold">
              {{ $count }} {{ __('items') }}
          </span>
      </div>
  </div>

  @if($byChef->isNotEmpty())
  <div class="row g-4 g-lg-5">
    <!-- Cart Items Grouped by Chef -->
    <div class="col-lg-8">
      @foreach($byChef as $chefId => $chefGroup)
      <div class="card p-0 rounded-4 border border-secondary border-opacity-10 mb-4 overflow-hidden shadow-sm" style="background: var(--surface-1);">
        <div class="card-header py-3 px-4 d-flex justify-content-between align-items-center border-bottom border-secondary border-opacity-10" style="background: var(--surface-2);">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-primary" style="width: 32px; height: 32px; background: var(--surface-1);">
                    <i class="fas fa-hat-chef small"></i>
                </div>
                <h5 class="mb-0 fw-bold text-light">
                    {{ $chefGroup['chef']->kitchen_name ?? $chefGroup['chef']->name ?? __('Chef') }}
                </h5>
            </div>
            <span class="badge rounded-pill px-3 py-1 bg-secondary bg-opacity-25 text-light small border border-secondary border-opacity-10">
                {{ __('Subtotal') }}: {{ money($chefGroup['subtotal']) }} + {{ __('Delivery') }}: {{ money($chefGroup['delivery_fee']) }}
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 text-light" style="--bs-table-bg: transparent;">
                    <thead>
                        <tr class="text-secondary small text-uppercase" style="border-bottom: 1px solid var(--border-subtle);">
                            <th class="p-3 ps-4" style="width: 42%;">{{ __('Meal') }}</th>
                            <th class="p-3">{{ __('Price') }}</th>
                            <th class="p-3 text-center">{{ __('Quantity') }}</th>
                            <th class="p-3 text-end pe-4">{{ __('Total') }}</th>
                            <th class="p-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chefGroup['items'] as $item)
                        @php $meal = $item['meal']; @endphp
                        <tr data-id="{{ $meal->id }}" style="border-bottom: 1px solid var(--border-subtle);">
                            <td class="p-3 ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-3 overflow-hidden flex-shrink-0" style="width: 56px; height: 56px; background: var(--surface-2);">
                                        @if($meal->image)
                                            <img src="{{ asset('storage/' . $meal->image->image_path) }}" class="w-100 h-100 object-fit-cover" alt="{{ $meal->name }}">
                                        @else
                                            <img src="{{ asset('assets/images/placeholder.svg') }}" class="w-100 h-100 object-fit-cover" alt="{{ $meal->name }}">
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold text-light">{{ $meal->name }}</h6>
                                        <small class="text-secondary">{{ $chefGroup['chef']->kitchen_name ?? $chefGroup['chef']->name ?? __('Chef') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="p-3 fw-semibold text-light price">{{ money($item['price']) }}</td>
                            <td class="p-3 text-center">
                                <div class="input-group input-group-sm mx-auto" style="width: 100px;">
                                    <input type="number" value="{{ $item['quantity'] }}" min="1" max="20"
                                        class="form-control text-center text-light border-secondary border-opacity-25 quantity-input"
                                        style="background: var(--surface-2);">
                                </div>
                            </td>
                            <td class="p-3 text-end pe-4 fw-bold text-primary row-total">{{ money($item['line_total']) }}</td>
                            <td class="p-3 text-end">
                                <button class="btn btn-sm btn-link text-danger p-0 remove-from-cart" title="{{ __('Remove') }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="border-top: 1px solid var(--border-subtle);">
                            <td colspan="3" class="p-3 ps-4 text-secondary small">
                                {{ __('Chef Order Delivery Fee') }}: {{ money($chefGroup['delivery_fee']) }}
                            </td>
                            <td colspan="2" class="p-3 pe-4 text-end fw-bold text-primary">
                                {{ __('Chef Subtotal + Fee') }}: {{ money($chefGroup['total']) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
      </div>
      @endforeach

      <div class="d-flex justify-content-between mb-4">
          <a href="{{ route('meals.index') }}" class="btn btn-outline-primary rounded-pill px-4">
              <i class="fas fa-arrow-left me-2"></i> {{ __('Continue Shopping') }}
          </a>
      </div>
    </div>

    <!-- Order Summary -->
    <div class="col-lg-4">
      <form action="{{ route('orders.store') }}" method="POST">
      @csrf
      <div class="card p-4 rounded-4 border border-secondary border-opacity-10 position-sticky shadow-lg" style="top: 90px; background: var(--surface-1);">
          <h5 class="fw-bold mb-4 text-light">{{ __('Order Summary') }}</h5>
          
          <div class="d-flex justify-content-between mb-2 text-secondary">
              <span>{{ __('Subtotal') }}</span>
              <span class="text-light fw-medium" id="cart-total">{{ money($subtotal) }}</span>
          </div>
          <div class="d-flex justify-content-between mb-3 text-secondary">
              <span>{{ __('Delivery Fee') }} ({{ count($byChef) }} {{ count($byChef) > 1 ? __('kitchens') : __('kitchen') }})</span>
              <span class="text-light fw-medium">{{ money($totalDeliveryFee) }}</span>
          </div>
          <hr class="border-secondary border-opacity-15 my-3">
          <div class="d-flex justify-content-between mb-4">
              <span class="fw-bold h5 mb-0 text-light">{{ __('Total') }}</span>
              <span class="fw-bold h4 text-primary mb-0" id="grand-total">{{ money($total) }}</span>
          </div>

          @auth
          @if(auth()->user()->addresses->count() > 0)
          <div class="mb-3">
              <label class="form-label small fw-semibold text-secondary">{{ __('Choose Saved Address') }}</label>
              <select name="address_id" id="address_select" class="form-select text-light border border-secondary border-opacity-25 py-2" style="background: var(--surface-2);">
                  <option value="">-- {{ __('Use New Address') }} --</option>
                  @foreach(auth()->user()->addresses as $addr)
                      <option value="{{ $addr->id }}">{{ ucfirst($addr->type) }}: {{ $addr->address }}</option>
                  @endforeach
              </select>
          </div>
          @endif
          @endauth

          <div class="mb-3" id="manual_address_box">
              <label class="form-label small fw-semibold text-secondary">{{ __('Delivery Address') }}</label>
              <textarea name="address" id="manual_address" class="form-control text-light border border-secondary border-opacity-25"
                  style="background: var(--surface-2);" placeholder="{{ __('Street, Building, Apartment...') }}" rows="2"></textarea>
          </div>
          
          <div class="mb-3">
              <label class="form-label small fw-semibold text-secondary">{{ __('Phone Number') }}</label>
              <input type="text" name="phone" class="form-control text-light border border-secondary border-opacity-25"
                  style="background: var(--surface-2);" required placeholder="05xxxxxxxx" value="{{ auth()->user()->phone ?? '' }}">
          </div>

          <div class="mb-4">
              <label class="form-label small fw-semibold text-secondary">{{ __('Payment Method') }}</label>
              <div class="p-3 rounded-3 border border-primary border-opacity-25 d-flex align-items-center justify-content-between" style="background: var(--primary-soft);">
                  <div class="d-flex align-items-center gap-2">
                      <input type="radio" name="payment_method" id="cash" value="cash" checked class="form-check-input">
                      <label class="text-light fw-semibold mb-0" for="cash">
                          <i class="fas fa-money-bill-wave text-primary me-1"></i> {{ __('Cash on Delivery') }}
                      </label>
                  </div>
                  <span class="badge bg-success bg-opacity-20 text-success rounded-pill px-2 py-1 small">{{ __('Available') }}</span>
              </div>
          </div>

          <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-lg mb-3" data-magnetic>
              {{ __('Place Order') }} <i class="fas fa-check-circle ms-2"></i>
          </button>
          
          <div class="text-center">
              <span class="text-secondary small"><i class="fas fa-shield-check text-primary me-1"></i> {{ __('Secure Checkout') }}</span>
          </div>
      </div>
      </form>
    </div>
  </div>
  @else
  <div class="col-12 py-5">
      <x-ui.empty-state
          icon="fa-shopping-basket"
          :title="__('Your cart is empty')"
          :message="__('Looks like you haven\'t added any meals yet.')"
          :actionUrl="route('meals.index')"
          :actionText="__('Discover Meals')" />
  </div>
  @endif
</div>

@push('scripts')
<script>
    $(".remove-from-cart").click(function (e) {
        e.preventDefault();
        var ele = $(this);
        if(confirm("{{ __('Are you sure want to remove?') }}")) {
            $.ajax({
                url: '{{ route('cart.remove') }}',
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}', 
                    id: ele.parents("tr").attr("data-id")
                },
                success: function (response) {
                    window.location.reload();
                }
            });
        }
    });

    $(".quantity-input").change(function (e) {
        e.preventDefault();
        var ele = $(this);
        $.ajax({
            url: '{{ route('cart.update') }}',
            method: "PATCH",
            data: {
                _token: '{{ csrf_token() }}', 
                id: ele.parents("tr").attr("data-id"), 
                quantity: ele.parents("tr").find(".quantity-input").val()
            },
            success: function (response) {
               window.location.reload();
            }
        });
    });

    // Address selection logic
    const addressSelect = document.getElementById('address_select');
    const manualAddress = document.getElementById('manual_address');
    
    if(addressSelect) {
        addressSelect.addEventListener('change', function() {
            if(this.value !== "") {
                manualAddress.removeAttribute('required');
                document.getElementById('manual_address_box').style.opacity = '0.5';
            } else {
                manualAddress.setAttribute('required', 'required');
                document.getElementById('manual_address_box').style.opacity = '1';
            }
        });
    } else {
        if(manualAddress) manualAddress.setAttribute('required', 'required');
    }
</script>
@endpush
@endsection
