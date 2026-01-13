import { useEffect } from 'react';

export default function Sidebar() {
    useEffect(() => {
        // Smooth and Slow Scroll Implementation
        // Easing function (easeInOutQuad)
        const ease = (t, b, c, d) => {
            t /= d / 2;
            if (t < 1) return c / 2 * t * t + b;
            t--;
            return -c / 2 * (t * (t - 2) - 1) + b;
        };

        const handleScrollLink = (e) => {
            e.preventDefault();
            const targetId = e.currentTarget.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                const targetPosition = targetElement.getBoundingClientRect().top + window.scrollY - 40; // Slight offset
                const startPosition = window.scrollY;
                const distance = targetPosition - startPosition;
                const duration = 1500; // 1500ms = 1.5 seconds (Slow and Smooth)
                let start = null;

                function animation(currentTime) {
                    if (start === null) start = currentTime;
                    const timeElapsed = currentTime - start;
                    const run = ease(timeElapsed, startPosition, distance, duration);
                    window.scrollTo(0, run);
                    if (timeElapsed < duration) requestAnimationFrame(animation);
                }

                requestAnimationFrame(animation);
            }
        };

        const links = document.querySelectorAll('.sidebar a');
        links.forEach(link => link.addEventListener('click', handleScrollLink));

        // Active Menu Highlight on Scroll
        const handleScrollSpy = () => {
            let current = '';
            const sections = document.querySelectorAll('#intro, #partie-1, #partie-2, #partie-3, #partie-4, #conclusion');
            const navLinks = document.querySelectorAll('.sidebar a');

            sections.forEach(section => {
                const sectionTop = section.getBoundingClientRect().top;
                // Trigger when section is in the upper third of the viewport
                if (sectionTop < window.innerHeight / 3) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(a => {
                a.classList.remove('active');
                if (current && a.getAttribute('href') === '#' + current) {
                    a.classList.add('active');
                }
            });
        };

        window.addEventListener('scroll', handleScrollSpy);

        return () => {
            links.forEach(link => link.removeEventListener('click', handleScrollLink));
            window.removeEventListener('scroll', handleScrollSpy);
        };
    }, []);

    return (
        <nav className="sidebar">
            <h3>Sommaire</h3>
            <ul>
                <li><a href="#intro">Introduction</a></li>
                <li><a href="#partie-1">Partie 1 : Le Routage</a></li>
                <li><a href="#partie-2">Partie 2 : Middleware</a></li>
                <li><a href="#partie-3">Partie 3 : Scénarios</a></li>
                <li><a href="#partie-4">Partie 4 : Bonnes pratiques</a></li>
                <li><a href="#conclusion">Conclusion</a></li>
            </ul>
        </nav>
    );
}
