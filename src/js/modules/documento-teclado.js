/*
 * Checkout · "Número de documento" (woocommerce/checkout/form-billing.php): al cambiar "Tipo de documento",
 * cambia el teclado del celular. DNI y RUC abren el numérico; CE y Pasaporte, el normal en mayúsculas.
 * Solo es el teclado: el formato lo valida el plugin angelitozzz-cursos en el servidor.
 */
const NUMERICOS = ['dni', 'ruc'];
const CON_LETRAS = ['ce', 'pasaporte'];

export function initDocumentoTeclado() {
    const tipo = document.querySelector('[data-documento-tipo]');
    const numero = document.querySelector('[data-documento-numero]');
    if (!tipo || !numero) return;

    const actualizar = () => {
        numero.inputMode = NUMERICOS.includes(tipo.value) ? 'numeric' : 'text';
        if (CON_LETRAS.includes(tipo.value)) {
            numero.setAttribute('autocapitalize', 'characters');
        } else {
            numero.removeAttribute('autocapitalize');
        }
    };

    actualizar();
    tipo.addEventListener('change', actualizar);
}
