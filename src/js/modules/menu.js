// Menú móvil del header: un <button aria-expanded> abre y cierra el panel (header.php).
export function initMenu() {
    const boton = document.querySelector('[data-menu-toggle]');
    const panel = document.querySelector('[data-menu-panel]');
    if (!boton || !panel) return;

    const abrir = (abierto) => {
        boton.setAttribute('aria-expanded', String(abierto));
        panel.hidden = !abierto;
    };

    boton.addEventListener('click', () => abrir(boton.getAttribute('aria-expanded') !== 'true'));

    // Esc cierra y devuelve el foco al botón.
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && boton.getAttribute('aria-expanded') === 'true') {
            abrir(false);
            boton.focus();
        }
    });

    // Al pasar a escritorio el panel se cierra (el menú se ve en la barra).
    window.matchMedia('(min-width: 1024px)').addEventListener('change', (e) => {
        if (e.matches) abrir(false);
    });
}
