/*
 * Checkout: resalta la tarjeta del paso en que va la persona (specs/checkout.md, 2026-11-27). Las tarjetas llevan
 * data-paso ("cuenta", "datos", "pago"); la activa recibe data-activo y el estilo sale de src/css/checkout-pasos.css.
 *
 * - Al cargar, la activa es la primera tarjeta ("cuenta" sin sesión, "datos" con sesión).
 * - Cualquier foco dentro de una tarjeta la activa: escribir en un campo, un select, los campos de Openpay o
 *   "Regístrate", que lleva el foco a #tus-datos. Al salir de todas, sigue activa la última.
 * - WooCommerce reemplaza #payment por AJAX al actualizar el resumen (updated_checkout): se vuelve a aplicar.
 * Solo es aspecto: no cambia el formulario ni el envío.
 */
export function initCheckoutPasos() {
    const tarjetas = () => [...document.querySelectorAll('[data-paso]')];
    let activa = tarjetas()[0]?.dataset.paso;
    if (!activa) return;

    const aplicar = () => {
        tarjetas().forEach((tarjeta) => tarjeta.toggleAttribute('data-activo', tarjeta.dataset.paso === activa));
    };

    document.documentElement.classList.add('checkout-pasos');
    aplicar();

    document.addEventListener('focusin', (evento) => {
        const tarjeta = evento.target instanceof Element ? evento.target.closest('[data-paso]') : null;
        if (!tarjeta || tarjeta.dataset.paso === activa) return;
        activa = tarjeta.dataset.paso;
        aplicar();
    });

    // updated_checkout es un evento de jQuery (checkout.js de Woo): no llega a addEventListener.
    window.jQuery?.(document.body).on('updated_checkout', aplicar);
}
