/*
 * Compra en las landings de soluciones:
 * - Selector de edad (template-parts/modals/selecciona-edad.php): recalcula el total y el ahorro al
 *   marcar o desmarcar (la zona tiene aria-live) y activa "Agregar al carrito" con al menos una etapa.
 *   Total: suma de los precios, o el total de la escala de descuento si coincide la cantidad (el carrito aplica
 *   el mismo descuento: inc/woocommerce/descuentos.php).
 * - "Agregar al carrito" ([data-solucion-agregar]): en el hero y la galería (data-productos, y data-regalo en
 *   "Regalar este curso"), en cada modal de etapa (su producto) y en el selector (las casillas marcadas, cuyo
 *   value es el ID del producto). Cierra su modal, abre el drawer y agrega los cursos (carrito.js).
 * - "Regalar este curso" con etapas ([data-regalo-etapas]): abre el mismo selector en modo regalo. Las etapas
 *   marcadas van al carrito como regalo y el total no lleva el descuento por cantidad (no aplica a regalos).
 *   Cualquier otro botón que abra el selector lo deja en modo compra.
 */
import { openModal, closeModal } from './modal.js';
import { agregarAlCarrito } from './carrito.js';

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
        // El descuento por cantidad no aplica a los regalos (inc/woocommerce/descuentos.php).
        const especial = raiz.dataset.regalo === '1' ? 0 : Number(descuentos[marcadas.length] || 0);
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

    // Modo regalo o compra según el botón que abrió el selector.
    const dialogo = raiz.closest('dialog');
    document.addEventListener('click', (evento) => {
        const abre = evento.target.closest('[data-modal-open]');
        if (!abre || !dialogo || abre.getAttribute('commandfor') !== dialogo.id) return;
        const regalo = abre.dataset.regaloEtapas === '1';
        raiz.dataset.regalo = regalo ? '1' : '';
        if (boton) {
            if (regalo) boton.dataset.regalo = '1';
            else delete boton.dataset.regalo;
        }
        recalcular();
    });
}

function productosDe(boton) {
    const selector = boton.closest('[data-solucion-edades]');
    if (selector) {
        return [...selector.querySelectorAll('input[type="checkbox"]:checked')].map((casilla) => casilla.value);
    }
    return (boton.dataset.productos || '').split(',').filter(Boolean);
}

export function initSolucionCompra() {
    document.querySelectorAll('[data-solucion-edades]').forEach(iniciarSelector);

    document.addEventListener('click', (evento) => {
        const boton = evento.target.closest('[data-solucion-agregar]');
        if (!boton || boton.disabled) return;

        const productos = productosDe(boton);
        // Se cambia un modal por otro, sin apilarlos. En el hero y la galería el botón ya abre el drawer
        // (data-modal-open): openModal no lo abre dos veces.
        const carrito = document.getElementById('carrito');
        closeModal(boton.closest('dialog'));
        if (carrito) openModal(carrito, document.querySelector('[data-modal-open="carrito"]'));
        if (productos.length) agregarAlCarrito(productos, { regalo: boton.dataset.regalo === '1' });
    });
}
