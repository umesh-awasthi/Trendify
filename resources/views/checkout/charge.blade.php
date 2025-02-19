
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        .StripeElement {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 60%;
        }
    </style>
</head>
<body>

    <h2>Enter Your Card Details</h2>

    <form id="payment-form" action="{{ route('payment.charge') }}" method="POST">
        @csrf
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div>
            <label for="amount">Amount to Pay ($):</label>
            <input type="text" name="amount" id="amount" value="{{ number_format($totalAfterDiscount, 2) }}" readonly>
        </div>

        <label for="card-element">Credit or Debit Card</label>
        <div id="card-element" class="StripeElement"></div>
        <div id="card-errors" role="alert"></div>

        <button type="submit" id="submit-button">Pay Now</button>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("payment-form");
            if (!form) {
                console.error("Payment form not found. Check your form's ID.");
                return; // Stop script execution if form doesn't exist
            }

            const stripePublicKey = "{{ env('STRIPE_KEY') }}";
            if (!stripePublicKey) {
                console.error("Stripe public key is missing. Check your .env file.");
                return;
            }

            const stripe = Stripe(stripePublicKey);
            const elements = stripe.elements();
            const card = elements.create("card");
            card.mount("#card-element");

            form.addEventListener("submit", async function (event) {
                event.preventDefault();

                const { token, error } = await stripe.createToken(card);
                if (error) {
                    document.getElementById("card-errors").textContent = error.message;
                } else {
                    const hiddenInput = document.createElement("input");
                    hiddenInput.type = "hidden";
                    hiddenInput.name = "stripeToken";
                    hiddenInput.value = token.id;
                    form.appendChild(hiddenInput);
                    form.submit();
                }
            });
        });
    </script>

</body>
</html>
