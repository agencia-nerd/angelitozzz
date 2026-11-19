/*
 * "Pagar ahora" del checkout (woocommerce/checkout/form-checkout.php): el JS de Woo cambia el texto del botón con
 * $('#place_order').text(...) al elegir la pasarela y después de cada actualización del resumen, y eso borra el
 * <span> y la flecha del diseño (puede pasar antes de que cargue este módulo). Cuando falta, se vuelven a poner
 * con el texto que dejó Woo. La flecha es la misma del componente boton.php.
 *
 * Token de Openpay: su openpay.js borra el token del intento anterior al hacer clic en "form.checkout button:submit",
 * y pide uno nuevo en checkout_place_order si no hay. Nuestro botón vive fuera del <form> (atributo form=), así que
 * ese clic no lo ve y, tras un rechazo, se reenviaba el token ya usado (Openpay responde 1003). Aquí se hace la
 * misma limpieza al hacer clic en nuestro botón. Solo quita los campos ocultos del token; nunca toca la tarjeta.
 * Ese mismo clic de openpay.js revisaba el nombre del titular (CardsErrorHandler) antes de pedir el token: también
 * se repite aquí, con su misma regla (letras, tildes, ñ y espacios), y sin nombre válido no se envía el pago.
 */
const NOMBRE_VALIDO = /^[A-ZÁÉÍÓÚÑ ]+$/i;
const AVISO_NOMBRE = 'Escribe el nombre del titular tal como aparece en la tarjeta (solo letras y espacios).';

// true si se puede seguir con el pago; si no, deja el aviso junto al campo y lo enfoca.
function validarTitular() {
    document.getElementById('angelitozzz-aviso-titular')?.remove();
    const pasarela = document.querySelector('input[name="payment_method"]:checked');
    const campo = document.getElementById('openpay-holder-name');
    const tarjeta = document.getElementById('openpay_selected_card');
    // Solo con Openpay y con tarjeta nueva (una tarjeta guardada no pide nombre), como openpay.js.
    if (!campo || pasarela?.value !== 'wc_openpay_gateway' || (tarjeta && tarjeta.value !== 'new')) return true;

    const nombre = campo.value.trim();
    if (nombre && NOMBRE_VALIDO.test(nombre)) {
        campo.removeAttribute('aria-invalid');
        campo.removeAttribute('aria-describedby');
        return true;
    }
    const aviso = document.createElement('p');
    aviso.id = 'angelitozzz-aviso-titular';
    aviso.className = 'mt-1 text-caption text-acento'; // Como el error de un campo del sitio.
    aviso.setAttribute('role', 'alert');
    aviso.textContent = AVISO_NOMBRE;
    campo.insertAdjacentElement('afterend', aviso);
    campo.setAttribute('aria-invalid', 'true');
    campo.setAttribute('aria-describedby', aviso.id);
    campo.focus();
    return false;
}
const FLECHA = '<svg class="size-[18px] shrink-0" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M3.75 9h10.5M9 3.75 14.25 9 9 14.25" /></svg>';

export function initCheckoutBoton() {
    const boton = document.getElementById('place_order');
    if (!boton) return;

    const reponer = () => {
        if (boton.querySelector('svg')) return;
        const texto = document.createElement('span');
        texto.textContent = boton.textContent.trim();
        boton.replaceChildren(texto);
        boton.insertAdjacentHTML('beforeend', FLECHA);
    };

    reponer();
    new MutationObserver(reponer).observe(boton, { childList: true });

    // Antes del submit (y de checkout_place_order): sin token viejo, Openpay tokeniza la tarjeta escrita ahora.
    boton.addEventListener('click', (evento) => {
        const form = boton.form;
        if (!form || !form.classList.contains('checkout')) return;
        if (!validarTitular()) {
            evento.preventDefault(); // Sin submit: ni token ni pedido.
            return;
        }
        form.querySelectorAll('[name="openpay_token"], [name="openpay_tokenized_card"]').forEach((campo) => campo.remove());
    });
}
