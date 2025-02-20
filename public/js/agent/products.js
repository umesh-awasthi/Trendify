function openEditModal(productId) {
    fetch(`/agent/products/${productId}`)
        .then(response => response.json())
        .then(product => {
            document.getElementById('edit_name').value = product.name;
            document.getElementById('edit_description').value = product.description;
            document.getElementById('edit_price').value = product.price;
            document.getElementById('editForm').action = `/agent/products/${productId}`;
            document.getElementById('editModal').classList.remove('hidden');
        });
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('editModal');
    editModal.addEventListener('click', function(event) {
        if (event.target === editModal) {
            closeEditModal();
        }
    });
});
