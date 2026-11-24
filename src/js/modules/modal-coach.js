// Modal del coach (template-parts/modals/modal-coach.php): al enviarse el formulario "coach",
// el mismo <dialog> pasa a la pantalla de gracias con el nombre escrito. Al cerrarse, vuelve al formulario.
const ANCHO_FORMULARIO = '600px';
const ANCHO_GRACIAS = '560px';

export function initModalCoach() {
    const dialog = document.getElementById('modal-coach');
    const form = dialog?.querySelector('form[data-form="coach"]');
    const pasoForm = dialog?.querySelector('[data-coach-paso="formulario"]');
    const pasoGracias = dialog?.querySelector('[data-coach-paso="gracias"]');
    if (!dialog || !form || !pasoForm || !pasoGracias) return;

    const tituloModal = dialog.querySelector('.modal__title');
    const tituloOriginal = tituloModal?.textContent ?? '';
    const tituloVisible = pasoGracias.querySelector('[data-coach-titulo]');
    const mensaje = pasoGracias.querySelector('[data-coach-mensaje]');
    const whatsapp = pasoGracias.querySelector('[data-coach-whatsapp]');
    const numero = (pasoGracias.dataset.whatsapp || '').replace(/\D/g, '');

    // form.js limpia el formulario antes de avisar el éxito: el nombre se guarda al enviar.
    let nombre = '';
    form.addEventListener('submit', () => {
        nombre = (form.elements.namedItem('nombre')?.value || '').trim();
    }, true);

    const conNombre = (texto) => (texto || '').replaceAll('{nombre}', nombre);

    dialog.addEventListener('wp-form:success', () => {
        const titulo = conNombre(pasoGracias.dataset.titulo);
        const texto = conNombre(pasoGracias.dataset.mensaje);

        if (tituloModal) tituloModal.textContent = titulo; // Nombre accesible del modal.
        if (tituloVisible) tituloVisible.textContent = titulo;
        if (mensaje) mensaje.textContent = texto;

        // target, rel y el aviso de pestaña nueva ya vienen del helper de enlaces (PHP): aquí solo el mensaje.
        if (whatsapp?.hasAttribute('href') && numero) {
            whatsapp.href = `https://wa.me/${numero}?text=${encodeURIComponent(texto)}`;
        }

        dialog.dataset.estado = 'gracias';
        dialog.style.setProperty('--modal-width-exact', ANCHO_GRACIAS);
        pasoForm.hidden = true;
        pasoGracias.hidden = false;
        (numero ? whatsapp : dialog.querySelector('.modal__close'))?.focus();
    });

    // Al cerrar, el modal vuelve al formulario para un próximo envío.
    dialog.addEventListener('modal:close', () => {
        delete dialog.dataset.estado;
        dialog.style.setProperty('--modal-width-exact', ANCHO_FORMULARIO);
        if (tituloModal) tituloModal.textContent = tituloOriginal;
        pasoForm.hidden = false;
        pasoGracias.hidden = true;
    });
}
