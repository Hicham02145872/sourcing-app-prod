import './bootstrap';

import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.Chart = Chart;

Alpine.start();

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