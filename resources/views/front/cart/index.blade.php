@extends('layouts.frontend')

@section('content')
<div class="container py-5">
  <div class="d-flex align-items-center mb-4">
      <h2 class="fw-bold mb-0 me-3 text-light">{{ __('Shopping Cart') }}</h2>
      <span class="badge bg-secondary text-light rounded-pill border">{{ $count }} {{ __('items') }}</span>
  </div>

  @if($byChef->isNotEmpty())
  <div class="row g-4">
    <!-- Cart Items Grouped by Chef -->
    <div class="col-lg-8">
      @foreach($byChef as $chefId => $chefGroup)
      <div class="card shadow-card border border-secondary border-opacity-10 mb-4">
        <div class="card-header bg-secondary bg-opacity-25 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-light">
                <i class="fas fa-utensils me-2 text-primary"></i>{{ $chefGroup['chef']->name ?? __('Chef') }}
            </h5>
            <span class="badge bg-primary rounded-pill">
                {{ __('Subtotal') }}: {{ money($chefGroup['subtotal']) }} + {{ __('Delivery') }}: {{ money($chefGroup['delivery_fee']) }}
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead class="bg-secondary">
                        <tr>
                            <th class="border-0 p-3 ps-4" style="width: 40%;">{{ __('Meal') }}</th>
                            <th class="border-0 p-3">{{ __('Price') }}</th>
                            <th class="border-0 p-3">{{ __('Quantity') }}</th>
                            <th class="border-0 p-3 text-end pe-4">{{ __('Total') }}</th>
                            <th class="border-0 p-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chefGroup['items'] as $item)
                        @php $meal = $item['meal']; @endphp
                        <tr data-id="{{ $meal->id }}">
                            <td class="p-3 ps-4">
                                <div class="d-flex align-items-center">
                                    @if($meal->image)
                                    <img src="{{ asset('storage/' . $meal->image->image_path) }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                    <img src="{{ asset('assets/images/placeholder.svg') }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                    @endif
                                    <div>
                                        <h6 class="mb-0 fw-bold text-light">{{ $meal->name }}</h6>
                                        <small class="text-light opacity-75">{{ $chefGroup['chef']->name ?? __('Chef') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="p-3 fw-bold price">{{ money($item['price']) }}</td>
                            <td class="p-3">
                                <div class="input-group input-group-sm" style="width: 120px;">
                                    <input type="number" value="{{ $item['quantity'] }}" min="1" max="20" class="form-control bg-secondary text-light border-secondary text-center quantity-input">
                                </div>
                            </td>
                            <td class="p-3 text-end pe-4 fw-bold row-total">{{ money($item['line_total']) }}</td>
                            <td class="p-3 text-end">
                                <button class="btn btn-link text-danger p-0 remove-from-cart" title="{{ __('Remove') }}"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-top border-secondary">
                        <tr>
                            <td colspan="3" class="p-3 ps-4 text-light opacity-75 small">
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
          <a href="{{ route('meals.index') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-left me-2"></i> {{ __('Continue Shopping') }}</a>
      </div>
    </div>

    <!-- Order Summary -->
    <div class="col-lg-4">
      <form action="{{ route('orders.store') }}" method="POST">
      @csrf
      <div class="card shadow-card border border-secondary border-opacity-10 p-4 position-sticky" style="top: 90px; background: rgba(30, 41, 59, 0.5); backdrop-filter: blur(10px);">
          <h5 class="fw-bold mb-4 text-light">{{ __('Order Summary') }}</h5>
          
          <div class="d-flex justify-content-between mb-2 text-light">
              <span>{{ __('Subtotal') }}</span>
              <span id="cart-total">{{ money($subtotal) }}</span>
          </div>
          <div class="d-flex justify-content-between mb-2 text-light">
              <span>{{ __('Delivery Fee') }} ({{ count($byChef) }} {{ count($byChef) > 1 ? __('kitchens') : __('kitchen') }})</span>
              <span>{{ money($totalDeliveryFee) }}</span>
          </div>
          <hr class="text-light opacity-25">
          <div class="d-flex justify-content-between mb-4">
              <span class="fw-bold h5 mb-0 text-light">{{ __('Total') }}</span>
              <span class="fw-bold h5 text-primary mb-0" id="grand-total">{{ money($total) }}</span>
          </div>

          @auth
          @if(auth()->user()->addresses->count() > 0)
          <div class="mb-3">
              <label class="form-label small fw-bold text-light">{{ __('Choose Saved Address') }}</label>
              <select name="address_id" id="address_select" class="form-select bg-secondary text-light border border-secondary border-opacity-10 py-2">
                  <option value="">-- {{ __('Use New Address') }} --</option>
                  @foreach(auth()->user()->addresses as $addr)
                      <option value="{{ $addr->id }}">{{ ucfirst($addr->type) }}: {{ $addr->address }}</option>
                  @endforeach
              </select>
          </div>
          @endif
          @endauth

          <div class="mb-3" id="manual_address_box">
              <label class="form-label small fw-bold text-light">{{ __('Delivery Address') }}</label>
              <textarea name="address" id="manual_address" class="form-control bg-secondary text-light border border-secondary border-opacity-10" placeholder="{{ __('Street, Building, Apartment...') }}" rows="2"></textarea>
          </div>
          
          <div class="mb-3">
              <label class="form-label small fw-bold text-light">{{ __('Phone Number') }}</label>
              <input type="text" name="phone" class="form-control bg-secondary text-light border border-secondary border-opacity-10" required placeholder="05xxxxxxxx" value="{{ auth()->user()->phone ?? '' }}">
          </div>

          <div class="mb-4">
              <label class="form-label small fw-bold text-light">{{ __('Payment Method') }}</label>
              <div class="d-flex gap-2">
                  <div class="w-100">
                      <input type="radio" class="btn-check" name="payment_method" id="cash" value="cash" checked>
                      <label class="btn btn-outline-primary w-100" for="cash">
                          <i class="fas fa-money-bill-wave me-1"></i> {{ __('Cash on Delivery') }}
                      </label>
                  </div>
              </div>
          </div>

          <button type="submit" class="btn btn-success btn-lg w-100 shadow-sm mb-3">
              {{ __('Place Order') }} <i class="fas fa-check-circle ms-2"></i>
          </button>
          
          <div class="text-center">
              <span class="text-light small"><i class="fas fa-lock me-1"></i> {{ __('Secure Checkout') }}</span>
          </div>
      </div>
      </form>
    </div>
  </div>
  @else
  <div class="col-12 text-center py-5">
      <div class="mb-3 text-light"><i class="fas fa-shopping-basket fa-4x opacity-50"></i></div>
      <h3 class="text-light">{{ __('Your cart is empty') }}</h3>
      <p class="text-light mb-4">{{ __('Looks like you haven\'t added any meals yet.') }}</p>
      <a href="{{ route('meals.index') }}" class="btn btn-primary px-4 py-2">{{ __('Discover Meals') }}</a>
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
