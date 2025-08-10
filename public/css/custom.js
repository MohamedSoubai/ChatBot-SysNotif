// public/js/custom.js

document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const toggleBtn = document.getElementById('sidebar-toggle');
    const openBtn = document.getElementById('sidebar-open');

    // Fonction pour basculer l'état de la sidebar (open/collapsed)
    function toggleSidebar() {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('collapsed');
    }

    // Clic sur le bouton “hamburger” dans la topbar
    if (openBtn) {
        openBtn.addEventListener('click', () => {
            toggleSidebar();
        });
    }

    // Clic sur le bouton de la sidebar (croix ⇄ hamburger)
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            toggleSidebar();
        });
    }

    // Fermer la sidebar si on clique en dehors (en mode responsive)
    document.addEventListener('click', function (e) {
        if (window.innerWidth < 1200) {
            if (!sidebar.contains(e.target) && !openBtn.contains(e.target)) {
                if (!sidebar.classList.contains('collapsed')) {
                    toggleSidebar();
                }
            }
        }
    });

    // Exemple d’effet de surbrillance sur le lien actif
    const navLinks = document.querySelectorAll('.sidebar-nav .nav-link');
    navLinks.forEach(link => {
        link.addEventListener('mouseenter', () => {
            link.style.background = 'rgba(255,255,255,0.15)';
        });
        link.addEventListener('mouseleave', () => {
            if (!link.classList.contains('active')) {
                link.style.background = 'transparent';
            }
        });
    });
});
