

class SessionManager {
    constructor() {
        this.sessionTimeout = 120000; 
        this.checkInterval = 30000; 
        this.lastActivity = Date.now();
        this.checkTimer = null;
        this.isPageVisible = true;
        this.pageHiddenTime = null;
        
        this.init();
    }
    
    init() {
        
        this.startSessionCheck();
        
        
        this.trackUserActivity();
        
        
        this.handlePageVisibility();
        
        
        this.handleWindowFocus();
    }
    
    startSessionCheck() {
        this.checkTimer = setInterval(() => {
            this.checkSession();
        }, this.checkInterval);
    }
    
    async checkSession() {
        try {
            const response = await fetch('session_check.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            });
            
            const data = await response.json();
            
            if (data.expired) {
                this.handleSessionExpired(data.redirect_url);
            }
        } catch (error) {
            console.error('Session check failed:', error);
        }
    }
    
    trackUserActivity() {
        const events = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
        
        events.forEach(event => {
            document.addEventListener(event, () => {
                this.lastActivity = Date.now();
            }, true);
        });
    }
    
    handlePageVisibility() {
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.isPageVisible = false;
                this.pageHiddenTime = Date.now();
            } else {
                this.isPageVisible = true;
                
                // If page was hidden for more than 2 minutes, check session immediately
                if (this.pageHiddenTime && (Date.now() - this.pageHiddenTime) > this.sessionTimeout) {
                    this.checkSession();
                }
                
                this.pageHiddenTime = null;
            }
        });
    }
    
    handleWindowFocus() {
        window.addEventListener('focus', () => {
            // Check session when window regains focus
            this.checkSession();
        });
        
        window.addEventListener('blur', () => {
            // Optional: Pause session checking when window loses focus
            // this.pauseSessionCheck();
        });
    }
    
    handleSessionExpired(redirectUrl) {
        // Clear the interval
        if (this.checkTimer) {
            clearInterval(this.checkTimer);
        }
        
        // Show alert to user
        this.showSessionExpiredModal(redirectUrl);
    }
    
    showSessionExpiredModal(redirectUrl) {
        // Create modal backdrop
        const backdrop = document.createElement('div');
        backdrop.className = 'session-expired-backdrop';
        backdrop.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 10000;
            display: flex;
            justify-content: center;
            align-items: center;
        `;
        
        // Create modal
        const modal = document.createElement('div');
        modal.className = 'session-expired-modal';
        modal.style.cssText = `
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 400px;
            width: 90%;
            font-family: 'Poppins', sans-serif;
        `;
        
        modal.innerHTML = `
            <div style="color: #dc3545; font-size: 48px; margin-bottom: 20px;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 style="color: #333; margin-bottom: 15px; font-weight: 600;">Session Expired</h3>
            <p style="color: #666; margin-bottom: 25px; line-height: 1.5;">
                Your session has expired due to inactivity. Please log in again to continue.
            </p>
            <button onclick="window.location.href='${redirectUrl}'" 
                    style="
                        background: #2BC652;
                        color: white;
                        border: none;
                        padding: 12px 30px;
                        border-radius: 25px;
                        font-size: 16px;
                        font-weight: 600;
                        cursor: pointer;
                        transition: all 0.3s ease;
                    "
                    onmouseover="this.style.background='#229944'"
                    onmouseout="this.style.background='#2BC652'">
                Login Again
            </button>
        `;
        
        backdrop.appendChild(modal);
        document.body.appendChild(backdrop);
        
        // Prevent scrolling
        document.body.style.overflow = 'hidden';
        
        // Auto-redirect after 10 seconds
        setTimeout(() => {
            window.location.href = redirectUrl;
        }, 10000);
    }
    
    // Method to manually logout
    async logout(redirectUrl) {
        try {
            await fetch('logout.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            });
        } catch (error) {
            console.error('Logout request failed:', error);
        } finally {
            window.location.href = redirectUrl;
        }
    }
    
    // Method to extend session (if needed)
    extendSession() {
        this.lastActivity = Date.now();
        this.checkSession();
    }
}

// Initialize session manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.sessionManager = new SessionManager();
});

// Also handle logout buttons
document.addEventListener('DOMContentLoaded', () => {
    // Find all logout buttons and add click handlers
    const logoutButtons = document.querySelectorAll('[href*="logout"], [onclick*="logout"], .logout-btn');
    
    logoutButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            
            let redirectUrl = 'index.php';
            
            // Determine redirect URL based on current page or button attributes
            if (window.location.pathname.includes('admin')) {
                redirectUrl = 'admin-login.php';
            } else if (window.location.pathname.includes('cargo')) {
                redirectUrl = 'cargo-owner-login.php';
            } else if (window.location.pathname.includes('transporter')) {
                redirectUrl = 'transporter-login.php';
            }
            
            // Get redirect URL from button if specified
            const buttonRedirect = button.getAttribute('data-redirect');
            if (buttonRedirect) {
                redirectUrl = buttonRedirect;
            }
            
            window.sessionManager.logout(redirectUrl);
        });
    });
});
