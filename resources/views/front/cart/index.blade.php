@extends('layouts.frontend')

@section('content')
<div class="container py-5">
  <div class="d-flex align-items-center mb-4">
      <h2 class="fw-bold mb-0 me-3">Shopping Cart</h2>
      <span class="badge bg-light text-dark rounded-pill border">{{ count($cart) }} items</span>
  </div>

  @if(count($cart) > 0)
  <div class="row g-4">
    <!-- Cart Items -->
    <div class="col-lg-8">
      <div class="card shadow-card border-0 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 p-3 ps-4" style="width: 40%;">Product</th>
                            <th class="border-0 p-3">Price</th>
                            <th class="border-0 p-3">Quantity</th>
                            <th class="border-0 p-3 text-end pe-4">Total</th>
                            <th class="border-0 p-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $id => $details)
                        <tr data-id="{{ $id }}">
                            <td class="p-3 ps-4">
                                <div class="d-flex align-items-center">
                                    @if(isset($details['image']))
                                    <img src="{{ asset('storage/' . $details['image']) }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                    <img src="https://placehold.co/60?text=Food" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                    @endif
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $details['name'] }}</h6>
                                        <small class="text-muted">{{ $details['chef'] }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="p-3 fw-bold price">${{ $details['price'] }}</td>
                            <td class="p-3">
                                <div class="input-group input-group-sm" style="width: 120px;">
                                    <input type="number" value="{{ $details['quantity'] }}" min="1" class="form-control text-center border-secondary quantity-input">
                                </div>
                            </td>
                            <td class="p-3 text-end pe-4 fw-bold row-total">${{ $details['price'] * $details['quantity'] }}</td>
                            <td class="p-3 text-end">
                                <button class="btn btn-link text-danger p-0 remove-from-cart"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
      </div>
      <div class="d-flex justify-content-between">
          <a href="{{ route('meals.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i> Continue Shopping</a>
      </div>
    </div>

    <!-- Order Summary -->
    <div class="col-lg-4">
      <form action="{{ route('orders.store') }}" method="POST">
      @csrf
      <div class="card shadow-card border-0 p-4 position-sticky" style="top: 90px;">
          <h5 class="fw-bold mb-4">Order Summary</h5>
          
          <div class="d-flex justify-content-between mb-2 text-muted">
              <span>Subtotal</span>
              <span id="cart-total">${{ $total }}</span>
          </div>
          <div class="d-flex justify-content-between mb-2 text-muted">
              <span>Delivery Fee</span>
              <span>$3.00</span>
          </div>
          <hr class="text-muted opacity-25">
          <div class="d-flex justify-content-between mb-4">
              <span class="fw-bold h5 mb-0">Total</span>
              <span class="fw-bold h5 text-primary mb-0" id="grand-total">${{ $total + 3 }}</span>
          </div>

          <div class="mb-3">
              <label class="form-label small fw-bold">Delivery Address</label>
              <input type="text" name="address" class="form-control" required placeholder="Street, Building, Apartment...">
          </div>
          
          <div class="mb-3">
              <label class="form-label small fw-bold">Phone Number</label>
              <input type="text" name="phone" class="form-control" required placeholder="05xxxxxxxx">
          </div>

          <div class="mb-4">
              <label class="form-label small fw-bold">Payment Method</label>
              <div class="d-flex gap-2">
                  <div class="w-50">
                      <input type="radio" class="btn-check" name="payment_method" id="cash" value="cash" checked>
                      <label class="btn btn-outline-secondary w-100" for="cash">
                          <i class="fas fa-money-bill-wave me-1"></i> Cash
                      </label>
                  </div>
                  <div class="w-50">
                      <input type="radio" class="btn-check" name="payment_method" id="card" value="card">
                      <label class="btn btn-outline-secondary w-100" for="card">
                          <i class="fas fa-credit-card me-1"></i> Card
                      </label>
                  </div>
              </div>
          </div>

          <button type="submit" class="btn btn-success btn-lg w-100 shadow-sm mb-3">
              Checkout <i class="fas fa-check-circle ms-2"></i>
          </button>
          
          <div class="text-center">
              <span class="text-muted small"><i class="fas fa-lock me-1"></i> Secure Checkout</span>
          </div>
      </div>
      </form>
    </div>
  </div>
  @else
  <div class="col-12 text-center py-5">
      <div class="mb-3 text-muted"><i class="fas fa-shopping-basket fa-4x opacity-50"></i></div>
      <h3>Your cart is empty</h3>
      <p class="text-muted mb-4">Looks like you haven't added any meals yet.</p>
      <a href="{{ route('meals.index') }}" class="btn btn-primary px-4 py-2">Discover Meals</a>
  </div>
  @endif
</div>

@push('scripts')
<script>
    $(".remove-from-cart").click(function (e) {
        e.preventDefault();
        var ele = $(this);
        if(confirm("Are you sure want to remove?")) {
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
</script>
@endpush
@endsection
