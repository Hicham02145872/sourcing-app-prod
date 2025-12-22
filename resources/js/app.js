import './bootstrap';
import Chart from 'chart.js/auto';

window.Chart = Chart;



// En Livewire 3, Alpine est déjà disponible globalement ou via Livewire.
// On attend que Livewire soit prêt pour s'assurer que Alpine est disponible.
document.addEventListener('livewire:init', () => {
    // Si vous avez besoin d'accéder à Alpine :
    // window.Alpine = Livewire.Alpine;
});

// --- Global Loading Spinner Logic ---
let activeRequests = 0;
let loadingTimeout = null;
const LOADING_DELAY = 2000; // 2 seconds

function showGlobalSpinner(message = 'Processing your request...') {
    if (loadingTimeout === null) {
        loadingTimeout = setTimeout(() => {
            window.dispatchEvent(new CustomEvent('loading-start', { detail: { message: message } }));
        }, LOADING_DELAY);
    }
}

function hideGlobalSpinner() {
    if (activeRequests === 0) {
        clearTimeout(loadingTimeout);
        loadingTimeout = null;
        window.dispatchEvent(new CustomEvent('loading-stop'));
    }
}

// Intercept Fetch API
const originalFetch = window.fetch;
window.fetch = async function (...args) {
    console.log('Fetch Interceptor: Request started', args[0]);
    activeRequests++;
    showGlobalSpinner('Loading data...');

    // Add Accept header for JSON
    let options = args[1] || {};
    options.headers = options.headers || {};
    options.headers['Accept'] = 'application/json';
    args[1] = options;

    try {
        return await originalFetch(...args);
    } finally {
        activeRequests--;
        hideGlobalSpinner();
    }
};

// Intercept XMLHttpRequest
const originalXhrOpen = XMLHttpRequest.prototype.open;
XMLHttpRequest.prototype.open = function (method, url, async, user, password) {
    this._url = url; // Store URL for potential debugging
    originalXhrOpen.apply(this, arguments);
};

const originalXhrSend = XMLHttpRequest.prototype.send;
XMLHttpRequest.prototype.send = function (...args) {
    console.log('XHR Interceptor: Request started', this._url);
    activeRequests++;
    showGlobalSpinner('Loading data...');

    this.addEventListener('loadend', () => {
        activeRequests--;
        hideGlobalSpinner();
    });

    originalXhrSend.apply(this, args);
};
// --- End Global Loading Spinner Logic ---