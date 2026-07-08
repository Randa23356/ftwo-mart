// Advanced Notification System
class NotificationManager {
    constructor() {
        this.container = null;
        this.notifications = [];
        this.recentMessages = new Map(); // Track recent messages to prevent duplicates
        this.init();
    }

    init() {
        // Create notification container
        this.container = document.createElement("div");
        this.container.id = "notification-container";
        this.container.className =
            "fixed top-4 right-4 z-50 space-y-3 pointer-events-none";
        document.body.appendChild(this.container);
    }

    show(options) {
        // Prevent duplicate notifications
        const messageKey = `${options.type || "info"}_${options.message}`;
        const now = Date.now();

        if (this.recentMessages.has(messageKey)) {
            const lastTime = this.recentMessages.get(messageKey);
            if (now - lastTime < 2000) {
                // Prevent duplicates within 2 seconds
                return null;
            }
        }

        this.recentMessages.set(messageKey, now);

        // Clean old entries (older than 5 seconds)
        for (const [key, time] of this.recentMessages.entries()) {
            if (now - time > 5000) {
                this.recentMessages.delete(key);
            }
        }

        const notification = this.createNotification(options);
        this.notifications.push(notification);
        this.container.appendChild(notification.element);

        // Animate in
        requestAnimationFrame(() => {
            notification.element.classList.remove(
                "translate-x-full",
                "opacity-0",
            );
            notification.element.classList.add("translate-x-0", "opacity-100");
        });

        // Auto remove
        if (options.duration !== false) {
            setTimeout(() => {
                this.remove(notification.id);
            }, options.duration || 5000);
        }

        return notification.id;
    }

    createNotification(options) {
        const id = Date.now() + Math.random();
        const {
            type = "info",
            title,
            message,
            icon,
            duration = 5000,
            dismissible = true,
            actions = [],
            position = "top-right",
        } = options;

        const notification = document.createElement("div");
        notification.className = `
            notification-item pointer-events-auto
            transform transition-all duration-300 ease-in-out
            translate-x-full opacity-0 max-w-sm w-full
            bg-white rounded-lg shadow-lg border-l-4 overflow-hidden
            ${this.getTypeClasses(type)}
        `;
        notification.dataset.id = id;

        const iconHtml = icon || this.getDefaultIcon(type);
        const titleHtml = title
            ? `<h4 class="font-semibold text-sm mb-1">${title}</h4>`
            : "";
        const actionsHtml =
            actions.length > 0 ? this.createActionsHtml(actions) : "";

        notification.innerHTML = `
            <div class="p-4 ${actionsHtml ? "pb-2" : ""}">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="${iconHtml} text-lg"></i>
                    </div>
                    <div class="ml-3 flex-1 min-w-0">
                        ${titleHtml}
                        <p class="text-sm text-gray-700">${message}</p>
                    </div>
                    ${
                        dismissible
                            ? `
                        <button type="button" class="ml-4 inline-flex text-gray-400 hover:text-gray-600 transition-colors" onclick="notificationManager.remove('${id}')">
                            <i class="fas fa-times"></i>
                        </button>
                    `
                            : ""
                    }
                </div>
                ${actionsHtml}
            </div>
            ${
                duration !== false
                    ? `
                <div class="notification-progress h-1 bg-black bg-opacity-10">
                    <div class="notification-progress-bar h-full transition-all ease-linear ${this.getProgressBarClass(type)}"
                         style="animation: progress ${duration}ms linear forwards;"></div>
                </div>
            `
                    : ""
            }
        `;

        return { id, element: notification };
    }

    createActionsHtml(actions) {
        if (actions.length === 0) return "";

        const buttonsHtml = actions
            .map(
                (action) => `
            <button type="button"
                    class="text-xs px-3 py-1 rounded-full font-medium transition-all duration-200 hover:scale-105 ${action.class || "bg-gray-100 text-gray-700 hover:bg-gray-200"}"
                    onclick="${action.onclick || ""}">
                ${action.icon ? `<i class="${action.icon} mr-1"></i>` : ""}
                ${action.text}
            </button>
        `,
            )
            .join("");

        return `<div class="mt-3 flex space-x-2">${buttonsHtml}</div>`;
    }

    getTypeClasses(type) {
        const classes = {
            success: "border-green-500",
            error: "border-red-500",
            warning: "border-yellow-500",
            info: "border-blue-500",
            loading: "border-gray-500",
        };
        return classes[type] || classes.info;
    }

    getDefaultIcon(type) {
        const icons = {
            success: "fas fa-check-circle text-green-500",
            error: "fas fa-exclamation-circle text-red-500",
            warning: "fas fa-exclamation-triangle text-yellow-500",
            info: "fas fa-info-circle text-blue-500",
            loading: "fas fa-spinner fa-spin text-gray-500",
        };
        return icons[type] || icons.info;
    }

    getProgressBarClass(type) {
        const classes = {
            success: "bg-green-500",
            error: "bg-red-500",
            warning: "bg-yellow-500",
            info: "bg-blue-500",
            loading: "bg-gray-500",
        };
        return classes[type] || classes.info;
    }

    remove(id) {
        const notification = this.container.querySelector(`[data-id="${id}"]`);
        if (!notification) return;

        notification.classList.remove("translate-x-0", "opacity-100");
        notification.classList.add("translate-x-full", "opacity-0");

        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
            this.notifications = this.notifications.filter((n) => n.id !== id);
        }, 300);
    }

    clear() {
        this.notifications.forEach((notification) => {
            this.remove(notification.id);
        });
        this.recentMessages.clear(); // Clear duplicate prevention cache
    }

    // Convenience methods
    success(message, options = {}) {
        return this.show({
            type: "success",
            message,
            ...options,
        });
    }

    error(message, options = {}) {
        return this.show({
            type: "error",
            message,
            duration: 7000, // Longer for errors
            ...options,
        });
    }

    warning(message, options = {}) {
        return this.show({
            type: "warning",
            message,
            ...options,
        });
    }

    info(message, options = {}) {
        return this.show({
            type: "info",
            message,
            ...options,
        });
    }

    loading(message, options = {}) {
        return this.show({
            type: "loading",
            message,
            duration: false, // Don't auto-dismiss loading notifications
            dismissible: false,
            ...options,
        });
    }
}

// Confirmation Dialog System
class ConfirmationDialog {
    constructor() {
        this.overlay = null;
        this.dialog = null;
    }

    show(options) {
        return new Promise((resolve, reject) => {
            const {
                title = "Konfirmasi",
                message = "Apakah Anda yakin?",
                confirmText = "Ya",
                cancelText = "Batal",
                confirmClass = "bg-red-600 hover:bg-red-700 text-white",
                cancelClass = "bg-gray-200 hover:bg-gray-300 text-gray-800",
                icon = "fas fa-exclamation-triangle text-yellow-500",
                danger = false,
            } = options;

            this.createDialog({
                title,
                message,
                confirmText,
                cancelText,
                confirmClass: danger
                    ? "bg-red-600 hover:bg-red-700 text-white"
                    : confirmClass,
                cancelClass,
                icon,
                onConfirm: () => {
                    this.close();
                    resolve(true);
                },
                onCancel: () => {
                    this.close();
                    resolve(false);
                },
            });
        });
    }

    createDialog(options) {
        // Create overlay
        this.overlay = document.createElement("div");
        this.overlay.className =
            "fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4";

        // Create dialog
        this.dialog = document.createElement("div");
        this.dialog.className =
            "bg-white rounded-lg shadow-xl max-w-md w-full transform transition-all duration-200 scale-95 opacity-0";

        this.dialog.innerHTML = `
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <i class="${options.icon} text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">${options.title}</h3>
                    </div>
                </div>
                <div class="mb-6">
                    <p class="text-sm text-gray-600">${options.message}</p>
                </div>
                <div class="flex space-x-3 justify-end">
                    <button type="button" class="confirm-cancel px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:scale-105 ${options.cancelClass}">
                        ${options.cancelText}
                    </button>
                    <button type="button" class="confirm-ok px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:scale-105 ${options.confirmClass}">
                        ${options.confirmText}
                    </button>
                </div>
            </div>
        `;

        // Add event listeners
        this.dialog
            .querySelector(".confirm-ok")
            .addEventListener("click", options.onConfirm);
        this.dialog
            .querySelector(".confirm-cancel")
            .addEventListener("click", options.onCancel);

        // Close on overlay click
        this.overlay.addEventListener("click", (e) => {
            if (e.target === this.overlay) {
                options.onCancel();
            }
        });

        // Close on escape
        document.addEventListener(
            "keydown",
            (this.handleEscape = (e) => {
                if (e.key === "Escape") {
                    options.onCancel();
                }
            }),
        );

        this.overlay.appendChild(this.dialog);
        document.body.appendChild(this.overlay);

        // Animate in
        requestAnimationFrame(() => {
            this.dialog.classList.remove("scale-95", "opacity-0");
            this.dialog.classList.add("scale-100", "opacity-100");
        });
    }

    close() {
        if (!this.overlay) return;

        this.dialog.classList.remove("scale-100", "opacity-100");
        this.dialog.classList.add("scale-95", "opacity-0");

        setTimeout(() => {
            if (this.overlay && this.overlay.parentNode) {
                this.overlay.parentNode.removeChild(this.overlay);
            }
            document.removeEventListener("keydown", this.handleEscape);
            this.overlay = null;
            this.dialog = null;
        }, 200);
    }
}

// Loading Overlay System
class LoadingOverlay {
    constructor() {
        this.overlay = null;
    }

    show(message = "Loading...") {
        if (this.overlay) return; // Already showing

        this.overlay = document.createElement("div");
        this.overlay.className =
            "fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center";

        this.overlay.innerHTML = `
            <div class="bg-white rounded-lg p-6 flex items-center space-x-3 shadow-xl">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-amber-600"></div>
                <span class="text-gray-700 font-medium">${message}</span>
            </div>
        `;

        document.body.appendChild(this.overlay);

        // Fade in
        requestAnimationFrame(() => {
            this.overlay.style.opacity = "0";
            this.overlay.style.transition = "opacity 0.2s ease";
            requestAnimationFrame(() => {
                this.overlay.style.opacity = "1";
            });
        });
    }

    hide() {
        if (!this.overlay) return;

        this.overlay.style.opacity = "0";
        setTimeout(() => {
            if (this.overlay && this.overlay.parentNode) {
                this.overlay.parentNode.removeChild(this.overlay);
            }
            this.overlay = null;
        }, 200);
    }
}

// Initialize global instances
window.notificationManager = new NotificationManager();
window.confirmDialog = new ConfirmationDialog();
window.loadingOverlay = new LoadingOverlay();

// Global convenience functions
window.notify = {
    success: (message, options) =>
        notificationManager.success(message, options),
    error: (message, options) => notificationManager.error(message, options),
    warning: (message, options) =>
        notificationManager.warning(message, options),
    info: (message, options) => notificationManager.info(message, options),
    loading: (message, options) =>
        notificationManager.loading(message, options),
};

// Preserve native confirm before overriding it with custom modal helper
window.nativeConfirm = window.confirm.bind(window);

window.confirm = async (message, options = {}) => {
    return await confirmDialog.show({
        message,
        ...options,
    });
};

window.confirmDelete = async (itemName = "item ini") => {
    return await confirmDialog.show({
        title: "Konfirmasi Hapus",
        message: `Apakah Anda yakin ingin menghapus ${itemName}? Tindakan ini tidak dapat dibatalkan.`,
        confirmText: "Ya, Hapus",
        cancelText: "Batal",
        icon: "fas fa-trash-alt text-red-500",
        danger: true,
    });
};

window.addEventListener("open-modal", (event) => {
    if (!event.detail) {
        return;
    }

    const {
        title,
        message,
        confirmText,
        cancelText,
        type,
        danger,
        icon,
        onConfirm,
        onCancel,
    } = event.detail;

    const dialog = new ConfirmationDialog();
    dialog.show({
        title: title || "Konfirmasi",
        message: message || "Apakah Anda yakin?",
        confirmText: confirmText || "Ya",
        cancelText: cancelText || "Batal",
        danger: type === "confirm" || danger === true,
        icon: icon || "fas fa-exclamation-triangle text-yellow-500",
        onConfirm: () => {
            if (typeof onConfirm === "function") {
                onConfirm();
            }
        },
        onCancel: () => {
            if (typeof onCancel === "function") {
                onCancel();
            }
        },
    });
});

window.showConfirmationModal = (options) => {
    const defaultOptions = {
        title: "Konfirmasi",
        message: "Apakah Anda yakin?",
        confirmText: "Ya",
        cancelText: "Batal",
        type: "confirm",
        danger: false,
        icon: "fas fa-exclamation-triangle text-yellow-500",
        onConfirm: null,
        onCancel: null,
    };

    const config = {
        ...defaultOptions,
        ...options,
    };

    if (window.confirmDialog && typeof window.confirmDialog.show === "function") {
        window.dispatchEvent(new CustomEvent("open-modal", {
            detail: config,
            bubbles: true,
            cancelable: true,
        }));
        return;
    }

    const confirmed = window.confirm(config.message);
    if (confirmed && typeof config.onConfirm === "function") {
        config.onConfirm();
    }
    if (!confirmed && typeof config.onCancel === "function") {
        config.onCancel();
    }
};

// Add CSS for animations
const style = document.createElement("style");
style.textContent = `
    @keyframes progress {
        from { width: 100%; }
        to { width: 0%; }
    }

    .notification-item {
        backdrop-filter: blur(8px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .notification-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }
`;
document.head.appendChild(style);

// Auto-hide existing alerts on page load
document.addEventListener("DOMContentLoaded", function () {
    const existingAlerts = document.querySelectorAll('[role="alert"]');
    existingAlerts.forEach((alert) => {
        if (alert.__x && alert.__x.$data) {
            setTimeout(() => {
                alert.__x.$data.show = false;
            }, 5000);
        }
    });
});
