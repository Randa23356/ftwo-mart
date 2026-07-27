import "./bootstrap";
import "./notifications";
import Alpine from "alpinejs";
import QRCode from "qrcode";

// Make Alpine available globally
window.Alpine = Alpine;

// Start Alpine
Alpine.start();

// Cart Toast Notification System
class CartNotification {
    constructor() {
        this.init();
    }

    init() {
        // Create notification container if it doesn't exist
        if (!document.getElementById("toast-container")) {
            const container = document.createElement("div");
            container.id = "toast-container";
            container.className = "fixed top-4 right-4 z-50 space-y-2";
            document.body.appendChild(container);
        }
    }

    show(type, message, product = null) {
        const toast = this.createToast(type, message, product);
        const container = document.getElementById("toast-container");

        // Add toast to container
        container.appendChild(toast);

        // Trigger animation
        requestAnimationFrame(() => {
            toast.classList.remove("translate-x-full", "opacity-0");
            toast.classList.add("translate-x-0", "opacity-100");
        });

        // Auto remove after 4 seconds
        setTimeout(() => {
            this.remove(toast);
        }, 4000);
    }

    createToast(type, message, product) {
        const toast = document.createElement("div");
        const bgColor =
            type === "success"
                ? "bg-green-50 border-green-200"
                : "bg-red-50 border-red-200";
        const textColor =
            type === "success" ? "text-green-800" : "text-red-800";
        const iconColor =
            type === "success" ? "text-green-600" : "text-red-600";
        const icon = type === "success" ? "fa-check-circle" : "fa-times-circle";

        toast.className = `
            transform transition-all duration-300 ease-in-out
            translate-x-full opacity-0
            max-w-sm w-full ${bgColor} border rounded-lg shadow-lg p-4
            ${textColor}
        `;

        let productInfo = "";
        if (product) {
            productInfo = `
                <div class="flex items-center mt-2 pt-2 border-t border-green-200">
                    <img src="${product.image_url}" alt="${product.name}" class="w-10 h-10 object-cover rounded mr-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">${product.name}</p>
                        <p class="text-xs text-green-600">Qty: ${product.quantity} • ${product.formatted_price}</p>
                    </div>
                </div>
            `;
        }

        toast.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas ${icon} ${iconColor} text-lg"></i>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium">${message}</p>
                    ${productInfo}
                </div>
                <button type="button" class="ml-4 inline-flex text-gray-400 hover:text-gray-600 focus:outline-none" onclick="cartNotification.remove(this.closest('div[class*=transform]'))">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            ${
                product
                    ? `
                <div class="mt-3 flex space-x-2">
                    <a href="/cart" class="text-xs bg-green-600 text-white px-3 py-1 rounded-full hover:bg-green-700 transition-colors">
                        <i class="fas fa-shopping-cart mr-1"></i>Lihat Keranjang
                    </a>
                    <a href="/checkout" class="text-xs bg-amber-600 text-white px-3 py-1 rounded-full hover:bg-amber-700 transition-colors">
                        <i class="fas fa-bolt mr-1"></i>Checkout
                    </a>
                </div>
            `
                    : ""
            }
        `;

        return toast;
    }

    remove(toast) {
        if (!toast) return;

        toast.classList.remove("translate-x-0", "opacity-100");
        toast.classList.add("translate-x-full", "opacity-0");

        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }
}

// Initialize cart notification system
window.cartNotification = new CartNotification();

// Cart Counter Animation
function animateCartCounter() {
    const cartBadge = document.querySelector(".cart-badge");
    if (cartBadge) {
        cartBadge.classList.add("animate-bounce");
        setTimeout(() => {
            cartBadge.classList.remove("animate-bounce");
        }, 600);
    }
}

// Enhanced Add to Cart function with AJAX
window.addToCart = async function (productId, quantity, selectedVariants, event) {
    if (!quantity || isNaN(quantity) || quantity <= 0) {
        cartNotification.show("error", "Jumlah produk tidak valid");
        return;
    }

    const button = event ? event.target : null;

    try {
        if (button) {
            button.disabled = true;
            button.dataset.originalText = button.innerHTML;
            button.innerHTML =
                '<i class="fas fa-spinner fa-spin mr-1"></i> Menambahkan...';
        }

        const response = await fetch("/cart/add", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity,
                selected_variants: selectedVariants || {},
            }),
        });

        const data = await response.json();

        if (response.ok) {
            cartNotification.show("success", data.message, data.product);

            animateCartCounter();

            if (data.cart_count !== undefined) {
                const cartCounter = document.querySelector(".cart-counter");
                if (cartCounter) {
                    cartCounter.textContent = data.cart_count;
                    cartCounter.classList.remove("hidden");
                }
            }

            if (button) {
                button.classList.add("bg-green-600", "hover:bg-green-700");
                setTimeout(() => {
                    button.classList.remove("bg-green-600", "hover:bg-green-700");
                }, 2000);
            }
        } else {
            cartNotification.show(
                "error",
                data.message || "Gagal menambahkan produk ke keranjang",
            );
        }
    } catch (error) {
        console.error("Error adding to cart:", error);
        cartNotification.show(
            "error",
            "Terjadi kesalahan saat menambahkan produk ke keranjang",
        );
    } finally {
        if (button) {
            button.disabled = false;
            button.innerHTML = button.dataset.originalText || '<i class="fas fa-shopping-cart"></i> <span>Tambah Keranjang</span>';
            delete button.dataset.originalText;
        }
    }
};

// Enhanced Buy Now function
window.buyNow = async function (productId, quantity, selectedVariants, event) {
    if (!quantity || isNaN(quantity) || quantity <= 0) {
        cartNotification.show("error", "Jumlah produk tidak valid");
        return;
    }

    const button = event ? event.target : null;

    try {
        if (button) {
            button.disabled = true;
            button.dataset.originalText = button.innerHTML;
            button.innerHTML =
                '<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...';
        }

        const response = await fetch("/buy-now", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity,
                selected_variants: selectedVariants || {},
            }),
        });

        const data = await response.json();

        if (response.ok) {
            cartNotification.show(
                "success",
                "Produk berhasil ditambahkan, mengalihkan ke checkout...",
            );
            setTimeout(() => {
                window.location.href = "/checkout";
            }, 1500);
        } else {
            cartNotification.show(
                "error",
                data.message || "Gagal memproses pesanan",
            );
            if (button) {
                button.disabled = false;
                button.innerHTML = button.dataset.originalText || '<i class="fas fa-bolt"></i> <span>Beli Sekarang</span>';
                delete button.dataset.originalText;
            }
        }
    } catch (error) {
        console.error("Error in buy now:", error);
        cartNotification.show(
            "error",
            "Terjadi kesalahan saat memproses pesanan",
        );
        if (button) {
            button.disabled = false;
            button.innerHTML = button.dataset.originalText || '<i class="fas fa-bolt"></i> <span>Beli Sekarang</span>';
            delete button.dataset.originalText;
        }
    }
};

// Enhanced Update Quantity function
window.updateQuantity = async function (cartId, event) {
    const target = event ? event.target : null;
    const cartItem = target ? target.closest("[x-data]") : null;
    if (!cartItem || !cartItem._x_dataStack) return;
    const quantity = cartItem._x_dataStack[0].quantity;

    if (!quantity || isNaN(quantity) || quantity <= 0) {
        cartNotification.show("error", "Jumlah produk tidak valid");
        return;
    }

    try {
        if (cartItem._x_dataStack) {
            cartItem._x_dataStack[0].updating = true;
        }

        const response = await fetch(`/cart/${cartId}/update`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({
                quantity: quantity,
            }),
        });

        const data = await response.json();

        if (response.ok) {
            cartNotification.show(
                "success",
                "Jumlah produk berhasil diperbarui",
            );
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            cartNotification.show(
                "error",
                data.error || "Gagal memperbarui jumlah produk",
            );
        }
    } catch (error) {
        console.error("Error updating quantity:", error);
        cartNotification.show(
            "error",
            "Terjadi kesalahan saat memperbarui jumlah produk",
        );
    } finally {
        if (cartItem && cartItem._x_dataStack) {
            cartItem._x_dataStack[0].updating = false;
        }
    }
};

// Render QR code helper (used on operator order detail)
window.renderQrCode = async function (selector, text) {
    try {
        const canvas = document.querySelector(selector);
        if (!canvas) return;
        await QRCode.toCanvas(canvas, text, { width: 192 });
    } catch (e) {
        console.error("Failed to render QR:", e);
    }
};

// Auto-hide alerts after 5 seconds
document.addEventListener("DOMContentLoaded", function () {
    const alerts = document.querySelectorAll('[role="alert"]');
    alerts.forEach((alert) => {
        setTimeout(() => {
            if (alert && alert.__x) {
                alert.__x.$data.show = false;
            }
        }, 5000);
    });
});
