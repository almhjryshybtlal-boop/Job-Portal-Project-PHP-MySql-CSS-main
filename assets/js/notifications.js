/**
 * نظام الإشعارات والتنبيهات
 * Notifications System
 * Version: 1.0
 */

class NotificationSystem {
    constructor() {
        this.container = null;
        this.init();
    }

    // تهيئة النظام
    init() {
        // إنشاء حاوية الإشعارات
        this.container = document.createElement('div');
        this.container.className = 'notification-container';
        document.body.appendChild(this.container);

        // التحقق من وجود رسائل من PHP Session
        this.checkSessionMessages();
    }

    // التحقق من رسائل PHP Session
    checkSessionMessages() {
        // البحث عن عنصر البيانات المخفي
        const sessionData = document.getElementById('session-notification-data');
        if (sessionData) {
            try {
                const data = JSON.parse(sessionData.textContent);
                if (data.message) {
                    this.show(data.message, data.type || 'info');
                }
                // حذف العنصر بعد القراءة
                sessionData.remove();
            } catch (e) {
                console.error('Error parsing session notification:', e);
            }
        }
    }

    // عرض إشعار
    show(message, type = 'info', duration = 5000) {
        const notification = this.createNotification(message, type);
        this.container.appendChild(notification);

        // تفعيل الصوت (اختياري)
        this.playSound(type);

        // إزالة تلقائية بعد المدة المحددة
        const progressBar = notification.querySelector('.notification-progress');
        if (progressBar && duration > 0) {
            progressBar.style.animation = `progress ${duration}ms linear forwards`;
        }

        if (duration > 0) {
            setTimeout(() => {
                this.hide(notification);
            }, duration);
        }

        return notification;
    }

    // إنشاء عنصر الإشعار
    createNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;

        const icon = this.getIcon(type);
        const title = this.getTitle(type);

        notification.innerHTML = `
            <div class="notification-icon">
                <i class="${icon}"></i>
            </div>
            <div class="notification-content">
                <div class="notification-title">${title}</div>
                <div class="notification-message">${message}</div>
            </div>
            <button class="notification-close" onclick="this.closest('.notification').remove()">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="notification-progress"></div>
        `;

        return notification;
    }

    // إخفاء الإشعار
    hide(notification) {
        notification.classList.add('hiding');
        setTimeout(() => {
            notification.remove();
        }, 400);
    }

    // الحصول على الأيقونة حسب النوع
    getIcon(type) {
        const icons = {
            'success': 'fa-solid fa-circle-check',
            'error': 'fa-solid fa-circle-xmark',
            'warning': 'fa-solid fa-triangle-exclamation',
            'info': 'fa-solid fa-circle-info'
        };
        return icons[type] || icons.info;
    }

    // الحصول على العنوان حسب النوع
    getTitle(type) {
        const titles = {
            'success': 'نجح! ✓',
            'error': 'خطأ! ✗',
            'warning': 'تحذير! ⚠',
            'info': 'معلومة ℹ'
        };
        return titles[type] || titles.info;
    }

    // تشغيل صوت (اختياري)
    playSound(type) {
        // يمكن إضافة أصوات هنا إذا أردت
        // const audio = new Audio(`/sounds/${type}.mp3`);
        // audio.play().catch(() => {});
    }

    // دوال مساعدة سريعة
    success(message, duration) {
        return this.show(message, 'success', duration);
    }

    error(message, duration) {
        return this.show(message, 'error', duration);
    }

    warning(message, duration) {
        return this.show(message, 'warning', duration);
    }

    info(message, duration) {
        return this.show(message, 'info', duration);
    }

    // حذف جميع الإشعارات
    clearAll() {
        const notifications = this.container.querySelectorAll('.notification');
        notifications.forEach(notification => {
            this.hide(notification);
        });
    }
}

// تهيئة النظام عند تحميل الصفحة
let notificationSystem;
document.addEventListener('DOMContentLoaded', function() {
    notificationSystem = new NotificationSystem();
    
    // جعل النظام متاحاً عالمياً
    window.notify = {
        show: (msg, type, duration) => notificationSystem.show(msg, type, duration),
        success: (msg, duration) => notificationSystem.success(msg, duration),
        error: (msg, duration) => notificationSystem.error(msg, duration),
        warning: (msg, duration) => notificationSystem.warning(msg, duration),
        info: (msg, duration) => notificationSystem.info(msg, duration),
        clear: () => notificationSystem.clearAll()
    };
});

// دوال للاستخدام من JavaScript مباشرة
function showNotification(message, type = 'info', duration = 5000) {
    if (window.notify) {
        return window.notify.show(message, type, duration);
    }
}

function showSuccess(message, duration = 5000) {
    if (window.notify) {
        return window.notify.success(message, duration);
    }
}

function showError(message, duration = 5000) {
    if (window.notify) {
        return window.notify.error(message, duration);
    }
}

function showWarning(message, duration = 5000) {
    if (window.notify) {
        return window.notify.warning(message, duration);
    }
}

function showInfo(message, duration = 5000) {
    if (window.notify) {
        return window.notify.info(message, duration);
    }
}
