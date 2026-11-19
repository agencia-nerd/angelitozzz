/*
 * Carrito sin recargar (specs/solucion.md → drawer-carrito), con los endpoints de WooCommerce:
 * - agregarAlCarrito(): ?wc-ajax=add_to_cart, uno por producto y en orden (las etapas son varios cursos). Con
 *   `regalo` manda angelitozzz_regalo=1 (el plugin angelitozzz-cursos marca la línea como regalo).
 * - Quitar del drawer ([data-carrito-eliminar]): ?wc-ajax=remove_from_cart. Sin JS es un enlace con la URL de Woo.
 * - "Agregar" de la sugerida ([data-carrito-agregar]).
 * - Cupón del hero (form[data-cupon-hero]): ?wc-ajax=apply_coupon y el aviso de Woo en el formulario. Sin JS el
 *   formulario va al carrito.
 * Woo responde con los fragmentos de inc/woocommerce/carrito.php (contenido del drawer y contador del header),
 * que se cambian aquí; después se avisa a wc-cart-fragments (jQuery) para que guarde el carrito nuevo.
 */

const contenido = () => document.querySelector('[data-carrito-contenido]');

function urlAjax(endpoint) {
    const base = contenido()?.dataset.wcAjax || '/?wc-ajax=%%endpoint%%';
    return base.replace('%%endpoint%%', endpoint);
}

async function enviar(endpoint, campos = {}) {
    const respuesta = await fetch(urlAjax(endpoint), {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: new URLSearchParams(campos),
    });
    if (!respuesta.ok) throw new Error(`wc-ajax ${endpoint}: ${respuesta.status}`);
    return respuesta;
}

export function aplicarFragmentos(fragmentos) {
    Object.entries(fragmentos || {}).forEach(([selector, html]) => {
        document.querySelectorAll(selector).forEach((elemento) => {
            elemento.outerHTML = html;
        });
    });
}

function avisarWoo(evento, datos) {
    if (window.jQuery && datos?.fragments) {
        window.jQuery(document.body).trigger(evento, [datos.fragments, datos.cart_hash]);
    }
}

function ocupado(activo) {
    contenido()?.setAttribute('aria-busy', activo ? 'true' : 'false');
}

async function refrescar() {
    const datos = await (await enviar('get_refreshed_fragments')).json();
    aplicarFragmentos(datos.fragments);
    return datos;
}

// Lo que ya está en el carrito: "id" y "id:regalo". Cada curso se vende de a uno: volver a agregarlo haría que
// Woo guarde un aviso de error para la página siguiente, así que se salta.
function enCarrito() {
    return new Set((contenido()?.dataset.enCarrito || '').split(',').filter(Boolean));
}

export async function agregarAlCarrito(productos, { regalo = false } = {}) {
    const presentes = enCarrito();
    const pendientes = productos.filter((id) => id && !presentes.has(regalo ? `${id}:regalo` : String(id)));

    ocupado(true);
    try {
        let ultimo = null;
        for (const id of pendientes) {
            const campos = { product_id: id, quantity: 1 };
            if (regalo) campos.angelitozzz_regalo = 1;
            const datos = await (await enviar('add_to_cart', campos)).json();
            if (datos?.fragments) ultimo = datos;
        }
        if (ultimo) {
            aplicarFragmentos(ultimo.fragments);
            avisarWoo('added_to_cart', ultimo);
        } else {
            await refrescar();
        }
    } catch (error) {
        console.error(error);
        await refrescar().catch(console.error);
    } finally {
        ocupado(false);
    }
}

async function quitar(enlace) {
    const dialogo = enlace.closest('dialog');
    ocupado(true);
    try {
        const datos = await (await enviar('remove_from_cart', { cart_item_key: enlace.dataset.carritoEliminar })).json();
        if (!datos?.fragments) throw new Error('remove_from_cart sin fragmentos');
        aplicarFragmentos(datos.fragments);
        avisarWoo('removed_from_cart', datos);
    } catch (error) {
        console.error(error);
        window.location.href = enlace.href; // Sin respuesta útil: la URL de Woo lo quita recargando.
        return;
    } finally {
        ocupado(false);
    }
    // El botón quitado ya no existe: el foco vuelve al primer elemento del drawer.
    dialogo?.querySelector('[data-carrito-eliminar], [data-carrito-contenido] a, [data-carrito-contenido] button')?.focus();
}

async function aplicarCupon(formulario) {
    const mensaje = formulario.querySelector('[data-cupon-mensaje]');
    const boton = formulario.querySelector('button[type="submit"]');
    const codigo = formulario.querySelector('[name="coupon_code"]')?.value.trim() || '';
    if (boton) boton.disabled = true;
    try {
        const html = await (await enviar('apply_coupon', { security: formulario.dataset.seguridad, coupon_code: codigo })).text();
        const avisos = document.createElement('div');
        avisos.innerHTML = html; // Avisos de Woo (wc_print_notices): solo se lee su texto.
        if (mensaje) mensaje.textContent = avisos.textContent.replace(/\s+/g, ' ').trim();
        await refrescar();
    } catch (error) {
        console.error(error);
        formulario.submit();
    } finally {
        if (boton) boton.disabled = false;
    }
}

export function initCarrito() {
    document.addEventListener('click', (evento) => {
        const eliminar = evento.target.closest('[data-carrito-eliminar]');
        if (eliminar) {
            evento.preventDefault();
            quitar(eliminar);
            return;
        }

        const agregar = evento.target.closest('[data-carrito-agregar]');
        if (agregar && !agregar.disabled) {
            agregar.disabled = true;
            agregarAlCarrito((agregar.dataset.productos || '').split(',').filter(Boolean));
        }
    });

    document.addEventListener('submit', (evento) => {
        const formulario = evento.target.closest('form[data-cupon-hero]');
        if (!formulario) return;
        evento.preventDefault();
        aplicarCupon(formulario);
    });
}
