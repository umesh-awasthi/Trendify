class ProductComparison {
    constructor() {
        this.compareBar = document.querySelector('.comparison-bar');
        this.compareButton = document.getElementById('compareButton');
        this.compareCount = document.getElementById('compareCount');
        this.comparisonProducts = document.getElementById('comparisonProducts');
        this.compareButtons = document.querySelectorAll('.compare-btn');
        
        this.initializeComparison();
        this.setupEventListeners();
    }

    initializeComparison() {
        const selectedProducts = JSON.parse(localStorage.getItem('compareProducts')) || [];
        if (selectedProducts.length > 0) {
            this.updateComparisonUI(selectedProducts);
            this.updateCompareButtons(selectedProducts);
        }
    }

    setupEventListeners() {
        this.compareButtons.forEach(button => {
            button.addEventListener('click', (e) => this.handleCompareClick(e));
        });
    }

    async handleCompareClick(e) {
        const button = e.currentTarget;
        const productId = button.dataset.productId;
        const compareText = button.querySelector('.compare-text');

        if (button.classList.contains('active')) {
            await this.removeFromCompare(productId);
            button.classList.remove('active');
            compareText.textContent = 'Add to Compare';
        } else {
            const result = await this.addToCompare(productId);
            if (result.success) {
                button.classList.add('active');
                compareText.textContent = 'Remove from Compare';
            }
        }
    }

    async addToCompare(productId) {
        try {
            const response = await fetch(`/compare/add/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            
            if (response.ok) {
                this.updateComparisonUI(data.products);
                return { success: true };
            } else {
                alert(data.message);
                return { success: false };
            }
        } catch (error) {
            console.error('Error adding product to comparison:', error);
            return { success: false };
        }
    }

    async removeFromCompare(productId) {
        try {
            const response = await fetch(`/compare/remove/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            this.updateComparisonUI(data.products);
        } catch (error) {
            console.error('Error removing product from comparison:', error);
        }
    }

    updateComparisonUI(products) {
        // Update count
        const count = products.length;
        this.compareCount.textContent = count;
        
        // Show/hide floating button
        if (count > 0) {
            this.compareButton.classList.add('active');
        } else {
            this.compareButton.classList.remove('active');
        }
        
        // Update comparison bar if it exists
        if (this.comparisonProducts) {
            if (count > 0) {
                this.compareBar.classList.add('active');
                this.comparisonProducts.innerHTML = products.map(product => `
                    <div class="comparison-item">
                        <img src="${product.image_url}" alt="${product.name}">
                        <button class="remove-item" onclick="comparison.removeFromCompare(${product.id})">×</button>
                    </div>
                `).join('');
            } else {
                this.compareBar.classList.remove('active');
            }
        }
    }

    updateCompareButtons(products) {
        const productIds = products.map(p => p.id);
        this.compareButtons.forEach(button => {
            const productId = parseInt(button.dataset.productId);
            if (productIds.includes(productId)) {
                button.classList.add('active');
                button.querySelector('.compare-text').textContent = 'Remove from Compare';
            }
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.comparison = new ProductComparison();
}); 