(function () {
    const doc = document;

    function toggleModal(id, show) {
        const modal = doc.getElementById(id);
        if (!modal) {
            return;
        }
        if (show) {
            modal.removeAttribute('hidden');
            doc.body.classList.add('gaenity-modal-open');
            const focusable = modal.querySelector('input, select, textarea, button');
            if (focusable) {
                focusable.focus();
            }
        } else {
            modal.setAttribute('hidden', '');
            if (!doc.querySelector('.gaenity-modal:not([hidden])')) {
                doc.body.classList.remove('gaenity-modal-open');
            }
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
            return;
        }

        const modal = event.target.closest('.gaenity-modal');
        if (modal && event.target === modal) {
            toggleModal(modal.id, false);
        }
    });

    doc.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            doc.querySelectorAll('.gaenity-modal:not([hidden])').forEach(function (modal) {
                modal.setAttribute('hidden', '');
            });
            doc.body.classList.remove('gaenity-modal-open');
        }
    });

    const autoHideDelay = (window.gaeinityCommunity && window.gaeinityCommunity.autoHideDelay) || 6000;
    doc.querySelectorAll('[data-gaenity-auto-hide]').forEach(function (notice) {
        setTimeout(function () {
            notice.style.display = 'none';
        }, autoHideDelay);
    });
})();
