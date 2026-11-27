// Inicio · Beneficios (template-parts/pages/home/beneficios.php): los colgantes se mueven como
// cuerdas sueltas. Cada uno es un péndulo con resorte: el scroll los sacude (en todos los tamaños)
// y en escritorio con mouse se mecen al pasar por encima y se estiran arrastrando la figura; al
// soltarla rebota y gira. Sin JS o con movimiento reducido quedan quietos, como en el diseño.
// Un solo requestAnimationFrame que se detiene cuando todo está quieto o la sección no se ve.

const GRAVEDAD = 6000;      // px/s²: con cuerdas de 190 a 530 px da vaivenes de 1,1 a 1,9 s.
const FRENO_ANGULO = 0.9;   // amortiguación del vaivén (1/s).
const RESORTE_LARGO = 180;  // rigidez del estiramiento.
const FRENO_LARGO = 9;
const RESORTE_GIRO = 90;    // giro de la figura sobre sí misma.
const FRENO_GIRO = 5;
const RESORTE_CURVA = 60;   // el medio de la cuerda sigue al ángulo con retraso: así se curva.
const FRENO_CURVA = 8;
const ANGULO_MAX = 1.2;     // rad.
const ESTIRAR_MAX = 140;    // px.
const EMPUJE_SCROLL = 0.002;
const REBOTE_SCROLL = 1.0;  // rebote del largo con el scroll.
const TOPE_SCROLL = 2.5;    // velocidad máxima (rad/s) que suma el scroll.
const EMPUJE_MOUSE = 0.006;
const SACUDIDA = [1, -0.75, 0.55, -0.9, 0.8]; // cada colgante se sacude distinto con el scroll.

export function initColgantes() {
    const lista = document.querySelector('[data-colgantes]');
    if (!lista || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const seccion = lista.closest('section') ?? lista;
    const conMouse = window.matchMedia('(min-width: 1024px) and (hover: hover) and (pointer: fine)');

    const colgantes = [...lista.querySelectorAll('[data-colgante]')].map((el, i) => {
        const svg = el.querySelector('svg');
        const cuerda = el.querySelector('[data-colgante-cuerda]');
        const figura = el.querySelector('[data-colgante-figura]');
        if (!svg || !cuerda || !figura) return null;

        const ancho = Number(svg.getAttribute('width'));
        const alto = Number(svg.getAttribute('height'));
        const x = Number(el.dataset.cuerdaX);
        const largo = Number(el.dataset.cuerda);
        figura.style.transformOrigin = '0 0';
        figura.style.willChange = 'transform';

        return {
            el, svg, cuerda, figura, ancho, x, largo,
            centro: largo + (alto - largo) / 2,           // centro de la figura bajo el anclaje.
            radio: Math.max(ancho, alto - largo) / 2 + 12, // zona para agarrarla.
            sacudida: SACUDIDA[i % SACUDIDA.length],
            a: 0, va: 0, s: 0, vs: 0, g: 0, vg: 0, m: 0, vm: 0,
        };
    }).filter(Boolean);
    if (!colgantes.length) return;

    let visible = false;
    let corriendo = false;
    let anterior = 0;
    let arrastre = null; // { c, desfase, t, a, s }

    // Posición del puntero en las unidades del SVG del colgante (el SVG no se transforma).
    const local = (c, e) => {
        const r = c.svg.getBoundingClientRect();
        const escala = r.width / c.ancho || 1;
        return { lx: (e.clientX - r.left) / escala, ly: (e.clientY - r.top) / escala };
    };

    const cerca = (c, e) => {
        const { lx, ly } = local(c, e);
        const d = c.centro + c.s;
        const fx = c.x - d * Math.sin(c.a);
        const fy = d * Math.cos(c.a);
        return Math.hypot(lx - fx, ly - fy) <= c.radio;
    };

    const dibujar = (c) => {
        const l = c.largo + c.s;
        const ex = c.x - l * Math.sin(c.a);
        const ey = l * Math.cos(c.a);
        const curva = c.a + (c.m - c.a) * 2;
        const cx = c.x - (l / 2) * Math.sin(curva);
        const cy = (l / 2) * Math.cos(curva);
        c.cuerda.setAttribute('d', `M${c.x} 0Q${cx.toFixed(2)} ${cy.toFixed(2)} ${ex.toFixed(2)} ${ey.toFixed(2)}`);
        c.figura.style.transform =
            `translate(${c.x}px,0) rotate(${c.a}rad) translate(0,${c.s + c.centro}px) rotate(${c.g}rad) translate(${-c.x}px,${-c.centro}px)`;
    };

    const reposo = (c) => {
        c.a = c.va = c.s = c.vs = c.g = c.vg = c.m = c.vm = 0;
        c.cuerda.setAttribute('d', `M${c.x} 0L${c.x} ${c.largo}`);
        c.figura.style.transform = '';
    };

    const quieto = (c) =>
        Math.abs(c.a) < 0.001 && Math.abs(c.va) < 0.01 && Math.abs(c.s) < 0.2 && Math.abs(c.vs) < 1 &&
        Math.abs(c.g) < 0.002 && Math.abs(c.vg) < 0.02 && Math.abs(c.m - c.a) < 0.001 && Math.abs(c.vm) < 0.01;

    const paso = (c, dt) => {
        if (arrastre?.c !== c) {
            c.va += (-(GRAVEDAD / (c.largo + c.s)) * Math.sin(c.a) - FRENO_ANGULO * c.va) * dt;
            c.a = Math.max(-ANGULO_MAX, Math.min(ANGULO_MAX, c.a + c.va * dt));
            c.vs += (-RESORTE_LARGO * c.s - FRENO_LARGO * c.vs) * dt;
            c.s += c.vs * dt;
        }
        c.vg += (-RESORTE_GIRO * c.g - FRENO_GIRO * c.vg) * dt;
        c.g += c.vg * dt;
        c.vm += (RESORTE_CURVA * (c.a - c.m) - FRENO_CURVA * c.vm) * dt;
        c.m += c.vm * dt;
    };

    const cuadro = (t) => {
        const dt = Math.min((t - anterior) / 1000, 1 / 30);
        anterior = t;
        let activos = 0;
        for (const c of colgantes) {
            paso(c, dt);
            if (arrastre?.c === c || !quieto(c)) {
                activos++;
                dibujar(c);
            } else {
                reposo(c);
            }
        }
        corriendo = activos > 0 && (visible || arrastre !== null);
        if (corriendo) requestAnimationFrame(cuadro);
    };

    const arrancar = () => {
        if (corriendo) return;
        corriendo = true;
        anterior = performance.now();
        requestAnimationFrame(cuadro);
    };

    new IntersectionObserver(([entrada]) => {
        visible = entrada.isIntersecting;
    }).observe(seccion);

    // Scroll: sacudida lateral (cada uno hacia su lado) y un rebote en el largo.
    let ultimaY = window.scrollY;
    window.addEventListener('scroll', () => {
        const y = window.scrollY;
        const delta = Math.max(-80, Math.min(80, y - ultimaY));
        ultimaY = y;
        if (!visible || !delta) return;
        for (const c of colgantes) {
            if (arrastre?.c === c) continue;
            c.va = Math.max(-TOPE_SCROLL, Math.min(TOPE_SCROLL, c.va + delta * EMPUJE_SCROLL * c.sacudida));
            c.vs += delta * REBOTE_SCROLL;
        }
        arrancar();
    }, { passive: true });

    // Escritorio con mouse: mecer al pasar, estirar al arrastrar.
    seccion.addEventListener('pointermove', (e) => {
        if (!conMouse.matches || e.pointerType !== 'mouse') return;

        if (arrastre) {
            const { c } = arrastre;
            const { lx, ly } = local(c, e);
            const dx = lx - c.x;
            const d = Math.hypot(dx, ly);
            const a = Math.max(-ANGULO_MAX, Math.min(ANGULO_MAX, Math.atan2(-dx, ly)));
            const s = Math.max(-c.largo * 0.25, Math.min(ESTIRAR_MAX, d - arrastre.desfase - c.largo));
            const dt = Math.max((e.timeStamp - arrastre.t) / 1000, 1 / 240);
            c.va = c.va * 0.5 + ((a - c.a) / dt) * 0.5;
            c.vs = c.vs * 0.5 + ((s - c.s) / dt) * 0.5;
            c.a = a;
            c.s = s;
            arrastre.t = e.timeStamp;
            arrancar();
            return;
        }

        for (const c of colgantes) {
            const encima = cerca(c, e);
            c.el.style.cursor = encima ? 'grab' : '';
            if (encima && e.movementX) {
                const movimiento = Math.max(-30, Math.min(30, e.movementX));
                c.va = Math.max(-4, Math.min(4, c.va - movimiento * EMPUJE_MOUSE));
                arrancar();
            }
        }
    });

    seccion.addEventListener('pointerdown', (e) => {
        if (!conMouse.matches || e.pointerType !== 'mouse' || e.button !== 0) return;
        const c = colgantes.find((col) => cerca(col, e));
        if (!c) return;
        e.preventDefault();
        const { lx, ly } = local(c, e);
        arrastre = { c, desfase: Math.hypot(lx - c.x, ly) - (c.largo + c.s), t: e.timeStamp };
        seccion.setPointerCapture(e.pointerId);
        document.documentElement.style.cursor = 'grabbing';
        arrancar();
    });

    const soltar = () => {
        if (!arrastre) return;
        const { c } = arrastre;
        arrastre = null;
        document.documentElement.style.cursor = '';
        c.va = Math.max(-6, Math.min(6, c.va));
        c.vs = Math.max(-1500, Math.min(1500, c.vs));
        // Al soltar, la figura da un giro hacia el lado del vaivén (más fuerte cuanto más se estiró).
        c.vg += (Math.sign(c.va) || 1) * (6 + Math.abs(c.s) * 0.08);
        arrancar();
    };
    seccion.addEventListener('pointerup', soltar);
    seccion.addEventListener('pointercancel', soltar);
    seccion.addEventListener('lostpointercapture', soltar);
}
