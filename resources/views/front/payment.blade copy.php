<x-front-layout title="Order Payment">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">


                {{-- <form id="payment-form" action="{{ route('confirm-payment') }}" method="post" class="mt-4">
                    @csrf
                    <div class="form-group">
                      <label for="card-element">Card Information</label>
                      <div id="card-element" class="form-control"></div>
                    </div>
                    <input type="hidden" name="payment_intent" value="{{ $paymentIntent->id }}">
                    <div class="form-group">
                      <button type="submit" id="submit" class="btn btn-primary">Pay ${{ $paymentIntent->amount / 100 }}</button>
                    </div>
                  </form> --}}

                  <form id="payment-form" action="{{ route('confirm-payment') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="card-element">Credit or debit card</label>
                        <div id="card-element"></div>
                        <div id="card-errors" role="alert"></div>
                    </div>
                    <button id="submit-payment">Pay</button>
                </form>


                <script src="https://js.stripe.com/v3/"></script>
                <script>
                    const stripe = Stripe("{{ config('services.stripe.publishable_key') }}");
                    const elements = stripe.elements();
                    const cardElement = elements.create('card');
                    cardElement.mount('#card-element');

                    const form = document.getElementById('payment-form');
                    const submitButton = document.getElementById('submit');

                    form.addEventListener('submit', async (event) => {
                        event.preventDefault();

                        submitButton.disabled = true;

                        const {
                            setupIntent,
                            error
                        } = await stripe.confirmCardSetup(
                            "{{ $client_secret }}", {
                                payment_method: {
                                    card: cardElement,
                                },
                            }
                        );

                        if (error) {
                            console.error(error);
                            submitButton.disabled = false;
                        } else {
                            const paymentMethodInput = document.createElement('input');
                            paymentMethodInput.setAttribute('type', 'hidden');
                            paymentMethodInput.setAttribute('name', 'payment_method');
                            paymentMethodInput.setAttribute('value', setupIntent.payment_method);
                            form.appendChild(paymentMethodInput);

                            form.submit();
                        }
                    });
                </script>


            </div>
        </div>
    </div>


</x-front-layout>
