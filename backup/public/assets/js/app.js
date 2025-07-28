// Global JavaScript functions for the wedding rental app

// Add to cart function
async function addToCart(productId) {
    console.log('Adding product to cart:', productId);
    
    if (!productId) {
        console.error('Product ID is required');
        showNotification('ID produk diperlukan', 'error');
        return;
    }
    
    try {
        const baseUrl = window.location.pathname.includes('/wedding-rental') ? '/wedding-rental' : '';
        console.log('Base URL:', baseUrl);
        console.log('Sending request to:', baseUrl + '/api/cart/add');
        
        const response = await fetch(baseUrl + '/api/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ product_id: productId })
        });
        
        console.log('Response status:', response.status);
        const result = await response.json();
        console.log('Response data:', result);
        
        if (result.success) {
            // Show success message
            showNotification('Produk ditambahkan ke keranjang!', 'success');
            // Update cart count in navbar
            updateCartCount();
        } else {
            console.error('Add to cart failed:', result.message);
            showNotification(result.message || 'Gagal menambahkan produk ke keranjang', 'error');
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        showNotification('Terjadi kesalahan', 'error');
    }
}

// Update cart count in navbar
function updateCartCount() {
    // This would be updated via AJAX or page reload
    location.reload();
}

// Show notification
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        type === 'info' ? 'bg-blue-500' :
        'bg-blue-500'
    }`;
    notification.textContent = message;
    
    // Add close button
    const closeBtn = document.createElement('button');
    closeBtn.innerHTML = '×';
    closeBtn.className = 'ml-2 text-white hover:text-gray-200';
    closeBtn.onclick = () => {
        notification.style.opacity = '0';
        setTimeout(() => {
            if (notification.parentNode) {
                document.body.removeChild(notification);
            }
        }, 300);
    };
    notification.appendChild(closeBtn);
    
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
            if (notification.parentNode) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, type === 'info' ? 3000 : 5000);
}

// Search functionality
function performSearch(searchTerm) {
    if (searchTerm.trim()) {
        const baseUrl = window.location.pathname.includes('/wedding-rental') ? '/wedding-rental' : '';
        window.location.href = `${baseUrl}/shop?search=${encodeURIComponent(searchTerm)}`;
    }
}

// Initialize app
document.addEventListener('DOMContentLoaded', function() {
    console.log('Aplikasi SewaDekorasi berhasil dimuat');
});