/*
 * Pestañas accesibles ([data-pestanas] con role="tablist"): cuenta (mi perfil) y lección.
 * Cada role="tab" controla un panel (aria-controls). Clic, flechas izquierda/derecha, Inicio y Fin
 * cambian de pestaña; la activa lleva aria-selected="true" y tabindex 0, los paneles inactivos `hidden`.
 * Sin JS se ven todos los paneles (el servidor no oculta ninguno). Al cargar, si la URL trae
 * #<id-del-panel>, abre esa pestaña.
 */

export function initPestanas() {
    for (const lista of document.querySelectorAll('[data-pestanas][role="tablist"]')) {
        const tabs = [...lista.querySelectorAll('[role="tab"]')];
        if (!tabs.length) continue;

        const panel = (tab) => document.getElementById(tab.getAttribute('aria-controls'));

        const activar = (elegida, foco = false) => {
            for (const tab of tabs) {
                const activa = tab === elegida;
                tab.setAttribute('aria-selected', activa ? 'true' : 'false');
                tab.tabIndex = activa ? 0 : -1;
                const p = panel(tab);
                if (p) p.hidden = !activa;
            }
            if (foco) elegida.focus();
        };

        const inicial = tabs.find((tab) => location.hash && tab.getAttribute('aria-controls') === location.hash.slice(1))
            ?? tabs.find((tab) => tab.getAttribute('aria-selected') === 'true')
            ?? tabs[0];
        activar(inicial);

        lista.addEventListener('click', (evento) => {
            const tab = evento.target.closest('[role="tab"]');
            if (tab && tabs.includes(tab)) activar(tab);
        });

        lista.addEventListener('keydown', (evento) => {
            const actual = tabs.indexOf(document.activeElement);
            if (actual < 0) return;
            const destinos = {
                ArrowRight: (actual + 1) % tabs.length,
                ArrowLeft: (actual - 1 + tabs.length) % tabs.length,
                Home: 0,
                End: tabs.length - 1,
            };
            if (!(evento.key in destinos)) return;
            evento.preventDefault();
            activar(tabs[destinos[evento.key]], true);
        });
    }
}
