/*
 * "Ver más". Dos modos, ambos dentro de un [data-ver-mas-root]:
 *
 * 1. Por lotes (página Preguntas frecuentes): el servidor imprime los grupos ocultos
 *    ([data-ver-mas-grupo][hidden]); cada clic muestra el siguiente y lleva el foco a su primera
 *    pregunta. Al mostrar el último grupo, el botón desaparece.
 *
 * 2. Mejora progresiva (landings de soluciones): el servidor imprime todo visible, con los ítems de
 *    más marcados [data-ver-mas-extra] y el botón con `hidden`. Sin JS se ve la lista completa; con
 *    JS los extras se ocultan y el botón aparece. Con data-ver-mas-menos="Ver menos" el botón
 *    alterna; sin él, desaparece después de mostrar el resto.
 */

function initLotes(boton, raiz) {
    boton.addEventListener('click', () => {
        const grupo = raiz.querySelector('[data-ver-mas-grupo][hidden]');
        if (!grupo) {
            boton.hidden = true;
            return;
        }

        grupo.hidden = false;
        grupo.querySelector('summary')?.focus();

        const siguiente = raiz.querySelector('[data-ver-mas-grupo][hidden]');
        if (siguiente) {
            boton.setAttribute('aria-controls', siguiente.id);
        } else {
            boton.hidden = true;
        }
    });
}

function initExtras(boton, raiz) {
    const extras = [...raiz.querySelectorAll('[data-ver-mas-extra]')];
    if (!extras.length) return;

    const texto = boton.querySelector('[data-ver-mas-texto]');
    const textoMas = texto?.textContent ?? '';
    const textoMenos = boton.dataset.verMasMenos ?? '';

    const mostrar = (abierto) => {
        for (const extra of extras) extra.hidden = !abierto;
        boton.setAttribute('aria-expanded', String(abierto));
        if (texto) texto.textContent = abierto && textoMenos ? textoMenos : textoMas;
    };

    mostrar(false);
    boton.hidden = false;

    boton.addEventListener('click', () => {
        const abrir = boton.getAttribute('aria-expanded') !== 'true';
        mostrar(abrir);

        if (abrir) {
            // El foco pasa al primer ítem nuevo (su <summary> si es una pregunta).
            const destino = extras[0].querySelector('summary') ?? extras[0];
            if (!destino.matches('summary')) destino.setAttribute('tabindex', '-1');
            destino.focus();
            if (!textoMenos) boton.hidden = true;
        }
    });
}

export function initVerMas() {
    for (const boton of document.querySelectorAll('[data-ver-mas]')) {
        const raiz = boton.closest('[data-ver-mas-root]');
        if (!raiz) continue;

        if (raiz.querySelector('[data-ver-mas-extra]')) {
            initExtras(boton, raiz);
        } else {
            initLotes(boton, raiz);
        }
    }
}
