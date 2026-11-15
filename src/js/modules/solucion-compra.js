/*
 * Compra en las landings de soluciones (maquetación F4):
 * - Selector de edad (template-parts/modals/selecciona-edad.php): recalcula el total y el ahorro al
 *   marcar o desmarcar (la zona tiene aria-live) y activa "Agregar al carrito" con al menos una etapa.
 *   Total: suma de los precios, o el total de la escala de descuento si coincide la cantidad.
 * - "Agregar al carrito" ([data-solucion-agregar], en el selector y en cada modal de etapa): cierra su
 *   modal y abre el drawer del carrito. En la F5 (wp-woocommerce) antes agrega los cursos al carrito.
 */
import { openModal, closeModal } from './modal.js';

const dinero = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' });

function iniciarSelector(raiz) {
    let descuentos = {};
    try {
        descuentos = JSON.parse(raiz.dataset.descuentos || '{}');
    } catch {
        descuentos = {};
    }

    const casillas = [...raiz.querySelectorAll('input[type="checkbox"][data-precio]')];
    const total = raiz.querySelector('[data-solucion-total]');
    const ahorro = raiz.querySelector('[data-solucion-ahorro]');
    const boton = raiz.querySelector('[data-solucion-agregar]');

    const recalcular = () => {
        const marcadas = casillas.filter((casilla) => casilla.checked);
        const suma = marcadas.reduce((acumulado, casilla) => acumulado + Number(casilla.dataset.precio || 0), 0);
        const especial = Number(descuentos[marcadas.length] || 0);
        const pagar = especial > 0 && especial < suma ? especial : suma;
        const ahorra = suma - pagar;

        if (total) total.textContent = dinero.format(pagar);
        if (ahorro) {
            ahorro.hidden = ahorra <= 0;
            ahorro.textContent = ahorra > 0 ? `Estás ahorrando ${dinero.format(ahorra)}` : '';
        }
        if (boton) boton.disabled = marcadas.length === 0;
    };

    casillas.forEach((casilla) => casilla.addEventListener('change', recalcular));
    recalcular();
}

export function initSolucionCompra() {
    document.querySelectorAll('[data-solucion-edades]').forEach(iniciarSelector);

    document.addEventListener('click', (evento) => {
        const boton = evento.target.closest('[data-solucion-agregar]');
        if (!boton || boton.disabled) return;

        // woo: agregar al carrito (F5). Se cambia un modal por otro, sin apilarlos.
        const carrito = document.getElementById('carrito');
        closeModal(boton.closest('dialog'));
        if (carrito) openModal(carrito, document.querySelector('[data-modal-open="carrito"]'));
    });
}
