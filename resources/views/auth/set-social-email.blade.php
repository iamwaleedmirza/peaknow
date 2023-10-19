<div class="modal fade" id="modalSetEmail" tabindex="-1" aria-labelledby="modalHomePageLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-0">
                <div class="section-modal">
                    <div class="modal-details">
                        <h4 class="text-uppercase">Need Email?</h4>
                        <p class="t-small">
                            We cant create your account without an email so please enter an email below
                        </p>
                        @if(session()->has('error'))
                        <div class="alert alert-danger text-center">
                            {{ session()->get('error') }}
                        </div>
                        @endif
        
                        @if ($errors->any())
                            <div class="alert alert-danger text-center mb-1">
                                @foreach ($errors->all() as $error)
                                    <p class="mb-0">{{$error}}</p>
                                @endforeach
                            </div>
                            <br>
                        @endif
                        {{--
                        <form action="{{route('set.social.email')}}" method="post"
                        id="">
                      @csrf
                      <x-honeypot />

                      <div class="mb-3">
                          <div id="errors" class="mt-2"></div>

                          <div class="form-group mb-3">
                              <input id="input_email" type="email" class="input-field input-primary input-small"
                                     name="email" placeholder="E-mail" required>
                          </div>
                         
                         
                      </div>
                      <div
                          class="d-flex flex-column align-items-center flex-md-row justify-content-md-around">
                         
                          <button class="btn btn-peaks" type="submit">Submit</button>
                      </div>
                  </form>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>