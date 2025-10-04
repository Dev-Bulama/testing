(function () {
    const doc = document;

    function toggleModal(id, show) {
        const modal = doc.getElementById(id);
        if (!modal) {
            return;
        }
        if (show) {
            modal.removeAttribute('hidden');
            const focusable = modal.querySelector('input, select, textarea, button');
            if (focusable) {
                focusable.focus();
            }
        } else {
            modal.setAttribute('hidden', '');
        }
    }

    doc.addEventListener('click', function (event) {
        const toggle = event.target.closest('[data-gaenity-toggle]');
        if (toggle) {
            event.preventDefault();
            toggleModal(toggle.getAttribute('data-gaenity-toggle'), true);
            return;
        }

        const close = event.target.closest('[data-gaenity-close]');
        if (close) {
            event.preventDefault();
            toggleModal(close.getAttribute('data-gaenity-close'), false);
        }
    });

    doc.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            doc.querySelectorAll('.gaenity-modal:not([hidden])').forEach(function (modal) {
                modal.setAttribute('hidden', '');
            });
        }
    });

    const autoHideDelay = (window.gaeinityCommunity && window.gaeinityCommunity.autoHideDelay) || 6000;
    doc.querySelectorAll('[data-gaenity-auto-hide]').forEach(function (notice) {
        setTimeout(function () {
            notice.style.display = 'none';
        }, autoHideDelay);
    });
})();
