<div id="cookie-consent-banner" class="fixed bottom-0 left-0 right-0 bg-gray-800 text-white p-4 flex items-center justify-between flex-wrap z-50 hidden">
    <p class="text-sm mb-2 sm:mb-0 mr-4">
        Ce site utilise des cookies pour vous garantir la meilleure expérience sur notre site.
        <a href="/privacy-policy" class="text-blue-400 hover:underline">En savoir plus</a>.
    </p>
    <button id="accept-cookies" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition-colors duration-300">
        Accepter
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cookieBanner = document.getElementById('cookie-consent-banner');
        const acceptButton = document.getElementById('accept-cookies');
        const consentGiven = localStorage.getItem('cookie_consent');

        if (!consentGiven) {
            cookieBanner.classList.remove('hidden');
        }

        acceptButton.addEventListener('click', function() {
            localStorage.setItem('cookie_consent', 'true');
            cookieBanner.classList.add('hidden');
        });
    });
</script>