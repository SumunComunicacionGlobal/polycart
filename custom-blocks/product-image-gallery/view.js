(function ($) {
    $(function () {
        if (typeof $.fn.slick !== 'function') return;

        $('.product-image-gallery--carousel').each(function () {
            const gallery = this;
            const slides = $(gallery).find('.product-image-gallery__slides');
            const main = gallery.querySelector('.product-image-gallery__main');
            const thumbnails = gallery.querySelector('.product-image-gallery__thumbnails');
            const buttons = Array.from(thumbnails.querySelectorAll('button'));
            const previous = gallery.querySelector('[data-gallery-prev]');
            const next = gallery.querySelector('[data-gallery-next]');
            const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

            if (slides.hasClass('slick-initialized')) return;

            const status = document.createElement('span');
            status.className = 'screen-reader-text';
            status.setAttribute('role', 'status');
            status.setAttribute('aria-live', 'polite');
            gallery.appendChild(status);
            main.tabIndex = 0;

            function select(index, announce) {
                buttons.forEach(function (button, buttonIndex) {
                    button.setAttribute('aria-current', String(buttonIndex === index));
                });

                const active = buttons[index];
                const trackRect = thumbnails.getBoundingClientRect();
                const activeRect = active.getBoundingClientRect();
                const top = activeRect.top - trackRect.top + thumbnails.scrollTop;
                let scrollTop = thumbnails.scrollTop;

                if (top < scrollTop) {
                    scrollTop = top;
                } else if (top + activeRect.height > scrollTop + thumbnails.clientHeight) {
                    scrollTop = top + activeRect.height - thumbnails.clientHeight;
                }

                if (scrollTop !== thumbnails.scrollTop) {
                    thumbnails.scrollTo({ top: scrollTop, behavior: reducedMotion.matches ? 'auto' : 'smooth' });
                }

                if (announce) status.textContent = active.getAttribute('aria-label');
            }

            function updateArrows(index) {
                previous.disabled = 0 === index;
                next.disabled = index === buttons.length - 1;
            }

            slides.on('init', function () {
                select(0, false);
                updateArrows(0);
            });
            slides.on('afterChange', function (event, slick, index) {
                select(index, true);
                updateArrows(index);
            });
            slides.slick({
                arrows: false,
                dots: false,
                infinite: false,
                slidesToShow: 1,
                slidesToScroll: 1,
                speed: reducedMotion.matches ? 0 : 250,
                accessibility: true,
                adaptiveHeight: false,
                rows: 0,
            });

            previous.addEventListener('click', function () { slides.slick('slickPrev'); });
            next.addEventListener('click', function () { slides.slick('slickNext'); });

            reducedMotion.addEventListener('change', function () {
                slides.slick('slickSetOption', 'speed', reducedMotion.matches ? 0 : 250);
            });

            buttons.forEach(function (button, index) {
                button.addEventListener('click', function () {
                    slides.slick('slickGoTo', index);
                });

                button.addEventListener('keydown', function (event) {
                    let next;
                    if (event.key === 'ArrowDown') next = Math.min(index + 1, buttons.length - 1);
                    if (event.key === 'ArrowUp') next = Math.max(index - 1, 0);
                    if (event.key === 'Home') next = 0;
                    if (event.key === 'End') next = buttons.length - 1;
                    if (next === undefined) return;
                    event.preventDefault();
                    buttons[next].focus({ preventScroll: true });
                    slides.slick('slickGoTo', next);
                });
            });

            main.addEventListener('keydown', function (event) {
                if (event.target !== main) return;
                if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
                    event.preventDefault();
                    slides.slick(event.key === 'ArrowRight' ? 'slickNext' : 'slickPrev');
                }
            });
        });
    });
}(jQuery));