// Listados con wp-query (blog, testimonios y soluciones), contra el Local y el contenido que tenga.
// - Blog: pastilla de categoría, búsqueda `q` sin recargar, noindex y canonical de la búsqueda, y lo mismo sin JS.
// - Testimonios: las pastillas (radios) filtran sin recargar; "Todos" vuelve al total; sin JS por URL.
// - Paginación / "Ver más": solo si el contenido la tiene (blog y soluciones: 12 por página); si no, lo dice.
// No necesita datos de prueba.
// Uso: node cdp-filtros.mjs   (puerto 9347)
import { abrir, sitio } from './lib/cdp.mjs';

const BUSQUEDA = 'sueño';
const c = await abrir({ puerto: 9347 });
const out = { blog: {}, testimonios: {}, paginacion: {} };

// Marca en la ventana: si sigue después de filtrar, la página no se recargó.
const marcar = () => c.ev(`window.__sinRecarga = 1; return 1;`);
const estado = (items) => c.ev(`
    const z = (n) => document.querySelector('[data-query="' + n + '"]');
    return {
        url: location.pathname + location.search,
        sinRecarga: window.__sinRecarga === 1,
        items: document.querySelectorAll(${JSON.stringify(items)}).length,
        total: z('total')?.textContent.trim() || '',
        robots: document.querySelector('meta[name="robots"]')?.content || '',
        canonical: document.querySelector('link[rel="canonical"]')?.href || '',
        paginacion: [...(z('paginacion')?.querySelectorAll('a') || [])].map((a) => a.textContent.trim()),
    };`);
const esperarCambio = () => c.ev(`await new Promise((r) => setTimeout(r, 1500)); return 1;`);
const centro = (selector) => c.ev(`
    const e = document.querySelector(${JSON.stringify(selector)});
    if (!e) return null;
    e.scrollIntoView({ block: 'center' });
    const q = e.getBoundingClientRect();
    return { x: q.x + q.width / 2, y: q.y + q.height / 2 };`);

// 1. Blog: la pastilla de una categoría lleva a su archivo, con las entradas de esa categoría.
await c.ir('/blog/');
const pastilla = await c.ev(`
    const a = [...document.querySelectorAll('[data-pastillas] a')].find((x) => x.href.includes('/categoria/'));
    return a ? { texto: a.textContent.trim(), href: a.getAttribute('href') } : null;`);
if (c.comprobar('blog: hay pastillas de categoría', !!pastilla, pastilla)) {
    const p = await centro(`[data-pastillas] a[href="${pastilla.href}"]`);
    await c.clic(p.x, p.y);
    await esperarCambio();
    out.blog.categoria = { pastilla: pastilla.texto, ...(await estado('[data-query="resultados"] [data-query-items] > li')) };
    c.comprobar('blog: la pastilla lleva al archivo de su categoría', out.blog.categoria.url === new URL(pastilla.href, sitio).pathname, out.blog.categoria);
    c.comprobar('blog: el archivo de la categoría lista entradas', out.blog.categoria.items > 0, out.blog.categoria);
    out.blog.activa = await c.ev(`return [...document.querySelectorAll('[data-pastillas] a[aria-current]')].map((a) => a.textContent.trim());`);
    c.comprobar('blog: la pastilla de la categoría queda marcada (aria-current)', out.blog.activa.includes(pastilla.texto), out.blog.activa);
}

// 2. Blog: búsqueda `q` sin recargar.
await c.ir('/blog/');
await marcar();
await c.ev(`const f = document.querySelector('form[data-query="filtros"]'); f.q.value = ${JSON.stringify(BUSQUEDA)}; f.requestSubmit(); return 1;`);
await esperarCambio();
out.blog.busqueda = await estado('[data-query="resultados"] [data-query-items] > li');
c.comprobar('blog: la búsqueda cambia la URL (?q=)', out.blog.busqueda.url.includes('q='), out.blog.busqueda);
c.comprobar('blog: la búsqueda no recarga la página', out.blog.busqueda.sinRecarga, out.blog.busqueda);
c.comprobar('blog: la búsqueda muestra resultados', out.blog.busqueda.items > 0, out.blog.busqueda);

// 3. Blog: la búsqueda cargada directo (y sin JS) da lo mismo, con noindex y canonical al blog.
const urlBusqueda = `/blog/?q=${encodeURIComponent(BUSQUEDA)}`;
await c.ir(urlBusqueda);
out.blog.directa = await estado('[data-query="resultados"] [data-query-items] > li');
c.comprobar('blog: la búsqueda lleva noindex', out.blog.directa.robots.includes('noindex'), out.blog.directa.robots);
c.comprobar('blog: el canonical de la búsqueda apunta al blog', out.blog.directa.canonical === `${sitio}/blog/`, out.blog.directa.canonical);
await c.sinJs(true);
await c.ir(urlBusqueda);
out.blog.sinJs = await estado('[data-query="resultados"] [data-query-items] > li');
await c.sinJs(false);
c.comprobar('blog: sin JS la búsqueda da los mismos resultados', out.blog.sinJs.items === out.blog.busqueda.items, { conJs: out.blog.busqueda.items, sinJs: out.blog.sinJs.items });

// 4. Testimonios: los radios filtran sin recargar; "Todos" vuelve al total.
const testimonios = '[data-query="resultados"] article';
await c.ir('/testimonios/');
out.testimonios.inicial = await estado(testimonios);
const radios = await c.ev(`return [...document.querySelectorAll('form[data-query="filtros"] input[type="radio"]')].map((r) => r.value);`);
const valor = radios.find((v) => v !== '');
if (c.comprobar('testimonios: hay pastillas de categoría', !!valor, radios)) {
    await marcar();
    const p = await centro(`form[data-query="filtros"] input[value="${valor}"] + *`) || await centro(`form[data-query="filtros"] input[value="${valor}"]`);
    await c.clic(p.x, p.y);
    await esperarCambio();
    out.testimonios.filtrado = { valor, ...(await estado(testimonios)) };
    c.comprobar('testimonios: el filtro cambia la URL', out.testimonios.filtrado.url.includes(`categoria=${valor}`), out.testimonios.filtrado);
    c.comprobar('testimonios: el filtro no recarga la página', out.testimonios.filtrado.sinRecarga, out.testimonios.filtrado);
    c.comprobar('testimonios: el filtro deja menos o igual resultados', out.testimonios.filtrado.items > 0 && out.testimonios.filtrado.items <= out.testimonios.inicial.items, out.testimonios.filtrado);

    const todos = await centro(`form[data-query="filtros"] input[value=""] + *`) || await centro(`form[data-query="filtros"] input[value=""]`);
    await c.clic(todos.x, todos.y);
    await esperarCambio();
    out.testimonios.todos = await estado(testimonios);
    c.comprobar('testimonios: "Todos" vuelve al total', out.testimonios.todos.items === out.testimonios.inicial.items, out.testimonios.todos);

    await c.sinJs(true);
    await c.ir(`/testimonios/?categoria=${encodeURIComponent(valor)}`);
    out.testimonios.sinJs = await estado(testimonios);
    await c.sinJs(false);
    c.comprobar('testimonios: sin JS el filtro por URL da lo mismo', out.testimonios.sinJs.items === out.testimonios.filtrado.items, { conJs: out.testimonios.filtrado.items, sinJs: out.testimonios.sinJs.items });
    c.comprobar('testimonios: la página filtrada lleva noindex', out.testimonios.sinJs.robots.includes('noindex'), out.testimonios.sinJs.robots);
}

// 5. Paginación / "Ver más": solo se prueba si el contenido llega a una segunda página.
for (const [nombre, ruta, items] of [['blog (categoría)', out.blog.categoria ? new URL(pastilla.href, sitio).pathname : '/blog/', '[data-query="resultados"] [data-query-items] > li'], ['soluciones', '/soluciones-de-sueno/', '[data-query-items] > li']]) {
    await c.ir(ruta);
    const antes = await estado(items);
    const enlace = await c.ev(`return !!document.querySelector('[data-query="paginacion"] a');`);
    if (!enlace) {
        out.paginacion[nombre] = `saltado: ${antes.items} resultados, cabe en una página (12 por página)`;
        continue;
    }
    await marcar();
    const p = await centro('[data-query="paginacion"] a');
    await c.clic(p.x, p.y);
    await esperarCambio();
    const despues = await estado(items);
    out.paginacion[nombre] = { antes: antes.items, despues: despues.items, url: despues.url, sinRecarga: despues.sinRecarga };
    c.comprobar(`${nombre}: la página siguiente carga sin recargar`, despues.sinRecarga && despues.url !== antes.url, out.paginacion[nombre]);
}

for (const ruta of ['/blog/', urlBusqueda, '/testimonios/']) {
    await c.ir(ruta);
    const php = await c.avisosPhp();
    c.comprobar(`${ruta}: sin avisos de PHP`, php.length === 0, php);
}

await c.terminar(out);
