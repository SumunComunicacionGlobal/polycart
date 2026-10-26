document.addEventListener('DOMContentLoaded', function () {
    // Registrar el plugin ScrollTrigger
    gsap.registerPlugin(ScrollTrigger, SplitText);

    // Animar elementos con la clase .animate al entrar en el viewport
    // gsap.utils.toArray('.wp-block-cover__inner-container > * > *, .is-layout-flow > * > *, .wp-block-separator, #colophon, .grid-productos > *').forEach(function (element) {
    //     gsap.from(element, {
    //         scrollTrigger: {
    //             trigger: element, // Elemento que activa la animación
    //             start: 'top 80%', // Inicia cuando el top del elemento está al 80% del viewport
    //             end: 'bottom 20%', // Termina cuando el bottom del elemento está al 20% del viewport
    //             toggleActions: 'play none none none', // Reproducir solo una vez
    //         },
    //         opacity: 0,
    //         y: 50,
    //         duration: 0.3,
    //     });
    // });

    // Animar tarjetas home
    gsap.matchMedia().add(
        '(min-width: 782px) and (prefers-reduced-motion: no-preference)',
        function () {
            document.querySelectorAll('.w-dyn-list').forEach(function (list) {
                const cards = list.querySelectorAll(
                    ':scope > .is-style-card-product-category'
                );

                cards.forEach(function (card) {
                    gsap.to(card, {
                        scale: 0.8,
                        ease: 'none',
                        scrollTrigger: {
                            trigger: card,
                            start: function () {
                                return 'top ' + parseFloat(getComputedStyle(card).top) + 'px';
                            },
                            end: 'bottom top',
                            scrub: 1,
                            invalidateOnRefresh: true,
                        },
                    });
                });
            });
        }
    );

    gsap.matchMedia().add(
        '(prefers-reduced-motion: no-preference)',
        function () {
            const splits = gsap.utils.toArray('p.has-heading-3-font-size').map(function (text) {
                return SplitText.create(text, {
                    type: 'words,chars',
                    tag: 'span',
                    charsClass: 'scroll-text-char',
                    autoSplit: true,
                    aria: text.querySelector('a, button') ? 'none' : 'auto',
                    onSplit: function (split) {
                        gsap.set(split.chars, { opacity: 0.2 });

                        return gsap.to(split.chars, {
                            opacity: 1,
                            duration: 0.2,
                            stagger: 0.2,
                            ease: 'none',
                            scrollTrigger: {
                                trigger: text,
                                start: 'clamp(10% bottom)',
                                end: 'clamp(bottom 50%)',
                                scrub: 0.8,
                                invalidateOnRefresh: true,
                            },
                        });
                    },
                });
            });

            return function () {
                splits.forEach(function (split) {
                    split.revert();
                });
            };
        }
    );

});