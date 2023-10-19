<div class="modal fade" id="selectShippingAddressModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-start" id="exampleModalLabel">Select new address</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
             <ul id="error-select-address"></ul>

                <form id="form_select_address" action="{{route('user.order.shipping.address')}}" method="post">
                    @csrf
                    <div class="mb-4">
                        <input type="hidden" name="order_id" id="address_order_id2" value="">
                        <input type="hidden" name="address_type" id="address_type" value="select">
                        <div id="addresses" class="addresses">
                            @if(count(Auth::user()->addresses) > 0)
                                @php $first_loop = true;  @endphp
                                @foreach(Auth::user()->addresses as $address)
                                    <div class="address-card d-flex align-items-center mb-3">
                                        <div class="form-check px-3 py-3">
                                            <input class="form-check-input mt-2" type="radio"
                                                   id="shipping_address_{{ $address->id }}" name="shipping_address"
                                                   value="{{ $address->id }}"
                                                   @if($first_loop) checked @endif>
                                                   <label class="form-check-label text-start ms-3"
                                                   for="shipping_address_{{ $address->id }}">
                                                   {{ Auth::user()->first_name }}  {{ Auth::user()->last_name }} <br>
                                                   <div class="d-flex">
                                                    <div>
                                                        <i class="fas fa-map-marker-alt"></i>
                                                    </div>
                                                    <div class="ms-2">
                                                        <p class="mb-1">  
                                                            {{ $address->address_line }}{{ $address->address_line2?', '.$address->address_line2.',':'' }} {{ $address->city.', '.$address->state.' - '.$address->zipcode }}
                                                        </p>
                                                    </div>
                                                </div>
                                              
                                              
                                                <p class="mb-0"><i class="fas fa-phone-alt"></i> {{ Auth::user()->phone }}</p>
                                            </label>
                                        </div>
                                    </div>
                                    @php $first_loop = false;  @endphp
                                @endforeach

                                <div class="mt-3 text-center">
                                    <button type="submit" class="btn btn-peaks submitBtn">Update</button>
                                </div>
                            @endif
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
