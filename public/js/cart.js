document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function () {
            let cartId = this.dataset.id;
            let newQuantity = this.value;

            fetch(`/cart/update/${cartId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ quantity: newQuantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`total_price_${cartId}`).innerText = `$${data.total_price}`;
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
}); 