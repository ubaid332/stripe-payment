<!DOCTYPE html>
<html>
<head>
    <title>Stripe Payment</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <h2>Pay $50 with Stripe (Test Mode)</h2>
    <form id="payment-form">
        <div id="card-element"></div>
        <button type="submit" id="submit">Pay</button>
        <div id="payment-message"></div>
    </form>

    <script>
        const stripe = Stripe('{{ env("STRIPE_KEY") }}');

        fetch('/create-payment-intent', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            const clientSecret = data.clientSecret;
            const elements = stripe.elements();
            const card = elements.create('card');
            card.mount('#card-element');

            const form = document.getElementById('payment-form');
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const { paymentIntent, error } = await stripe.confirmCardPayment(clientSecret, {
                    payment_method: {
                        card: card,
                        billing_details: {
                            name: 'Test User'
                        }
                    }
                });

                const message = document.getElementById('payment-message');
                if (error) {
                    message.textContent = error.message;
                } else {
                    message.textContent = '🎉 Payment Successful!';
                }
            });
        });
    </script>
</body>
</html>
