#!/usr/bin/env node
/*
 * Entorno de pruebas del proyecto en esta PC (Windows + Local): lo que vive FUERA de app/public/
 * y que All-in-One WP Migration no lleva de una PC a otra.
 *
 *   node specs/pruebas/entorno/instalar.mjs             revisa y dice qué falta (no cambia nada)
 *   node specs/pruebas/entorno/instalar.mjs --instalar  crea o actualiza lo que falte
 *
 * Todo lo saca de esta PC: el usuario de Windows, la carpeta del sitio (desde la ubicación de este
 * archivo) y, del sites.json de Local, la versión de PHP y de MySQL y el puerto. Nada va escrito a mano.
 *
 * Qué revisa (y con --instalar, arma):
 *   1. ~/Local Sites/angelitozzz/wp-tests/php-cli.ini (desde la plantilla php-cli.ini)
 *   2. ~/bin/php, php.bat, composer, composer.bat (desde las plantillas) y composer.phar (SHA-256 verificado)
 *   3. ~/bin en el PATH (solo avisa: no cambia el PATH)
 *   4. wp-tests/suite-src: suite de pruebas de WordPress en el tag de la versión del sitio
 *   5. wp-tests/wordpress: copia del núcleo sin wp-content ni wp-config.php
 *   6. base local_tests en el MySQL de Local (el sitio tiene que estar encendido)
 *   7. regla de Nginx de los PDF privados en conf/nginx/site.conf.hbs (y que responda 403)
 *   8. plugin angelitozzz-cursos: vendor/ y un phpunit.xml viejo con rutas de otra PC
 *
 * Nunca borra: lo que reemplaza lo deja al lado con el sufijo .viejo-<fecha>.
 * Se puede correr las veces que haga falta. Se corre desde cualquier carpeta.
 */
import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import net from 'node:net';
import crypto from 'node:crypto';
import { spawnSync } from 'node:child_process';

const INSTALAR = process.argv.includes('--instalar');

if (process.platform !== 'win32') {
	console.error('Este script es para Windows con Local.');
	process.exit(1);
}

// --- Carpetas, calculadas desde este archivo -------------------------------------------------
const plantillas = import.meta.dirname; // specs/pruebas/entorno
const tema = path.resolve(plantillas, '../../..');
const publico = path.resolve(tema, '../../..'); // app/public
const sitio = path.resolve(publico, '../..'); // ~/Local Sites/angelitozzz
const plugin = path.join(publico, 'wp-content/plugins/angelitozzz-cursos');
const casa = os.homedir();
const bin = path.join(casa, 'bin');
const wpTests = path.join(sitio, 'wp-tests');
const ini = path.join(wpTests, 'php-cli.ini');
const suite = path.join(wpTests, 'suite-src');
const nucleo = path.join(wpTests, 'wordpress');
const servicios = path.join(process.env.APPDATA ?? '', 'Local/lightning-services');
const fecha = new Date().toISOString().slice(0, 10);

const barras = (ruta) => ruta.replaceAll('\\', '/');
const invertidas = (ruta) => ruta.replaceAll('/', '\\');
const posix = (ruta) => barras(ruta).replace(/^([A-Za-z]):/, (_, letra) => '/' + letra.toLowerCase());

// --- Resultado -------------------------------------------------------------------------------
let pendientes = 0;
const ok = (texto) => console.log(`  OK     ${texto}`);
const aviso = (texto) => console.log(`  AVISO  ${texto}`);
const falta = (texto) => {
	pendientes++;
	console.log(`  FALTA  ${texto}`);
};
const hecho = (texto) => console.log(`  HECHO  ${texto}`);
const titulo = (texto) => console.log(`\n${texto}`);

/** Guarda una copia del archivo o carpeta al lado (nunca borra). */
function apartar(ruta) {
	let destino = `${ruta}.viejo-${fecha}`;
	for (let n = 2; fs.existsSync(destino); n++) destino = `${ruta}.viejo-${fecha}-${n}`;
	fs.renameSync(ruta, destino);
	return destino;
}

function correr(programa, argumentos, opciones = {}) {
	return spawnSync(programa, argumentos, { encoding: 'utf8', ...opciones });
}

// --- 0. Sitio en Local -----------------------------------------------------------------------
titulo(`Sitio: ${sitio}`);
const archivoSitios = path.join(process.env.APPDATA ?? '', 'Local/sites.json');
if (!fs.existsSync(archivoSitios)) {
	console.error(`  No encuentro ${archivoSitios}. ¿Está Local instalado y el sitio creado?`);
	process.exit(1);
}
const normalizar = (ruta) => barras(ruta.startsWith('~') ? casa + ruta.slice(1) : ruta).toLowerCase().replace(/\/+$/, '');
const sitios = Object.values(JSON.parse(fs.readFileSync(archivoSitios, 'utf8')));
const datosSitio = sitios.find((s) => normalizar(s.path ?? '') === normalizar(sitio)) ?? sitios.find((s) => s.name === 'angelitozzz');
if (!datosSitio) {
	console.error('  Local no tiene un sitio en esta carpeta. Importa el sitio en Local primero.');
	process.exit(1);
}
const versionPhp = datosSitio.services?.php?.version;
const versionMysql = datosSitio.services?.mysql?.version;
const puerto = datosSitio.services?.mysql?.ports?.MYSQL?.[0];
const dominio = datosSitio.domain;
console.log(`  Local: PHP ${versionPhp}, MySQL ${versionMysql} en el puerto ${puerto}, ${dominio}`);

/** Carpeta de lightning-services de un servicio y versión (la de número de build más alto). */
function carpetaServicio(nombre, version) {
	if (!fs.existsSync(servicios)) return null;
	const candidatas = fs
		.readdirSync(servicios)
		.filter((d) => d === `${nombre}-${version}` || d.startsWith(`${nombre}-${version}+`))
		.sort((a, b) => a.localeCompare(b, 'en', { numeric: true }));
	return candidatas.length ? path.join(servicios, candidatas.at(-1)) : null;
}
const carpetaPhp = carpetaServicio('php', versionPhp);
const php = carpetaPhp && path.join(carpetaPhp, 'bin/win64/php.exe');
if (!php || !fs.existsSync(php)) {
	console.error(`  No encuentro el PHP ${versionPhp} de Local en ${servicios}. Enciende el sitio una vez en Local.`);
	process.exit(1);
}
const carpetaMysql = carpetaServicio('mysql', versionMysql);
const mysql = carpetaMysql && path.join(carpetaMysql, 'bin/win64/bin/mysql.exe');

// --- 1. php-cli.ini --------------------------------------------------------------------------
titulo('1. PHP de línea de comandos (wp-tests/php-cli.ini)');
const extDir = barras(path.join(carpetaPhp, 'bin/win64/ext'));
const iniEsperado =
	'; Generado por specs/pruebas/entorno/instalar.mjs del tema: no editar a mano, volver a correr el script.\n' +
	fs
		.readFileSync(path.join(plantillas, 'php-cli.ini'), 'utf8')
		.split(/\r?\n/)
		.filter((l) => !/Copiarlo|^; por las de la PC/.test(l))
		.join('\n')
		.replace(/^extension_dir = .*$/m, `extension_dir = "${extDir}"`);
if (fs.existsSync(ini) && fs.readFileSync(ini, 'utf8').includes(`extension_dir = "${extDir}"`)) {
	ok(`${ini} usa el PHP ${versionPhp} de esta PC`);
} else if (!INSTALAR) {
	falta(fs.existsSync(ini) ? `${ini} apunta a otro PHP o a otra PC` : `${ini} no existe`);
} else {
	fs.mkdirSync(wpTests, { recursive: true });
	if (fs.existsSync(ini)) aviso(`el anterior quedó en ${apartar(ini)}`);
	fs.writeFileSync(ini, iniEsperado);
	hecho(`${ini}`);
}

// --- 2. Lanzadores en ~/bin ------------------------------------------------------------------
titulo('2. Lanzadores en ~/bin');
const phar = path.join(bin, 'composer.phar');
/** Reemplaza en una plantilla <USUARIO>, la carpeta del sitio y la versión de PHP por las de esta PC. */
function desdePlantilla(nombre) {
	const sitioPlantilla = 'C:/Users/<USUARIO>/Local Sites/angelitozzz';
	const reemplazos = [
		// Primero el php.exe completo: sale de %APPDATA% (Local), no de la carpeta personal.
		[
			/[^"]*lightning-services[\\/]php-[^"\\/]+[\\/]bin[\\/]win64[\\/]php\.exe/g,
			(ruta) => (ruta.startsWith('/') ? posix(php) : ruta.includes('\\') ? invertidas(php) : barras(php)),
		],
		[sitioPlantilla, barras(sitio)],
		[invertidas(sitioPlantilla), invertidas(sitio)],
		['/c/Users/<USUARIO>', posix(casa)],
		['C:/Users/<USUARIO>', barras(casa)],
		['C:\\Users\\<USUARIO>', invertidas(casa)],
	];
	let texto = fs.readFileSync(path.join(plantillas, nombre), 'utf8');
	for (const [buscar, poner] of reemplazos) texto = texto.replaceAll(buscar, poner);
	const lineas = texto.split(/\r?\n/).filter((l) => !/Copiarlo/.test(l));
	return lineas.join(nombre.endsWith('.bat') ? '\r\n' : '\n'); // los .bat con CRLF o cmd los lee mal
}
const lanzadores = ['php', 'php.bat', 'composer', 'composer.bat'];
for (const nombre of lanzadores) {
	const destino = path.join(bin, nombre);
	const esperado = desdePlantilla(nombre);
	// Vale si llama al php.exe y al ini de esta PC (los comentarios pueden ser otros).
	const phpEnFormato = nombre.endsWith('.bat') ? invertidas(php) : posix(php);
	const actual = fs.existsSync(destino) ? fs.readFileSync(destino, 'utf8') : null;
	const vale =
		actual !== null &&
		actual.toLowerCase().includes(phpEnFormato.toLowerCase()) &&
		actual.toLowerCase().includes((nombre.endsWith('.bat') ? invertidas(ini) : barras(ini)).toLowerCase()) &&
		(!nombre.endsWith('.bat') || actual.includes('\r\n'));
	if (vale) {
		ok(`~/bin/${nombre}`);
	} else if (!INSTALAR) {
		falta(actual === null ? `~/bin/${nombre} no existe` : `~/bin/${nombre} apunta a otro PHP, a otra PC o no tiene CRLF`);
	} else {
		fs.mkdirSync(bin, { recursive: true });
		if (actual !== null) aviso(`el anterior quedó en ${apartar(destino)}`);
		fs.writeFileSync(destino, esperado);
		hecho(`~/bin/${nombre}`);
	}
}
if (fs.existsSync(phar)) {
	ok('~/bin/composer.phar');
} else if (!INSTALAR) {
	falta('~/bin/composer.phar no existe');
} else {
	const base = 'https://getcomposer.org/download/latest-2.x/composer.phar';
	const contenido = Buffer.from(await (await fetch(base)).arrayBuffer());
	const sha = (await (await fetch(`${base}.sha256sum`)).text()).trim().split(/\s+/)[0];
	const calculado = crypto.createHash('sha256').update(contenido).digest('hex');
	if (sha && calculado === sha) {
		fs.mkdirSync(bin, { recursive: true });
		fs.writeFileSync(phar, contenido);
		hecho(`~/bin/composer.phar (SHA-256 ${sha.slice(0, 12)}… verificado)`);
	} else {
		falta('composer.phar descargado no coincide con su SHA-256: no se guardó');
	}
}

// --- 3. PATH ---------------------------------------------------------------------------------
titulo('3. ~/bin en el PATH');
const enPath = (process.env.PATH ?? '').split(';').some((p) => normalizar(p) === normalizar(bin));
if (enPath) {
	ok('~/bin está en el PATH');
} else {
	falta('~/bin no está en el PATH. Agrégalo una vez (PowerShell) y abre otra terminal:');
	console.log(`         [Environment]::SetEnvironmentVariable('Path', "$([Environment]::GetEnvironmentVariable('Path','User'));${bin}", 'User')`);
}

// --- 4. Suite de pruebas de WordPress --------------------------------------------------------
titulo('4. Suite de pruebas de WordPress (wp-tests/suite-src)');
const versionWp = fs.readFileSync(path.join(publico, 'wp-includes/version.php'), 'utf8').match(/\$wp_version = '([^']+)'/)?.[1];
const git = (...args) => correr('git', ['-C', suite, ...args]);
const tagSuite = fs.existsSync(suite) ? git('describe', '--tags').stdout.trim() : null;
const suiteCompleta = fs.existsSync(path.join(suite, 'tests/phpunit/includes/functions.php'));
if (tagSuite === versionWp && suiteCompleta) {
	ok(`suite en el tag ${versionWp}, igual que el sitio`);
} else if (!INSTALAR) {
	falta(tagSuite ? `la suite está en ${tagSuite} y el sitio en ${versionWp}` : 'la suite no existe');
} else if (!tagSuite) {
	fs.mkdirSync(wpTests, { recursive: true });
	const r = correr(
		'git',
		['clone', '--depth', '1', '--branch', versionWp, '--filter=blob:none', '--sparse', 'https://github.com/WordPress/wordpress-develop.git', suite],
		{ stdio: 'inherit' },
	);
	if (r.status === 0 && git('sparse-checkout', 'set', 'tests/phpunit/includes', 'tests/phpunit/data').status === 0) {
		hecho(`suite en el tag ${versionWp}`);
	} else {
		falta('no se pudo clonar la suite (¿hay internet y git?)');
	}
} else {
	const r1 = git('fetch', '--depth', '1', 'origin', 'tag', versionWp);
	const r2 = git('-c', 'advice.detachedHead=false', 'checkout', versionWp);
	if (r1.status === 0 && r2.status === 0) hecho(`suite cambiada de ${tagSuite} a ${versionWp}`);
	else falta(`no se pudo cambiar la suite a ${versionWp}: ${r1.stderr || r2.stderr}`);
}
if (fs.existsSync(path.join(suite, 'wp-tests-config.php'))) {
	aviso('wp-tests/suite-src/wp-tests-config.php ya no se usa (la config está en el plugin: tests/wp-tests-config.php)');
}

// --- 5. Núcleo de pruebas --------------------------------------------------------------------
titulo('5. Núcleo de pruebas (wp-tests/wordpress)');
const versionNucleo = fs.existsSync(path.join(nucleo, 'wp-includes/version.php'))
	? fs.readFileSync(path.join(nucleo, 'wp-includes/version.php'), 'utf8').match(/\$wp_version = '([^']+)'/)?.[1]
	: null;
if (versionNucleo === versionWp) {
	ok(`núcleo ${versionWp}, igual que el sitio`);
} else if (!INSTALAR) {
	falta(versionNucleo ? `el núcleo es ${versionNucleo} y el sitio ${versionWp}` : 'el núcleo no existe');
} else {
	if (fs.existsSync(nucleo)) aviso(`el anterior quedó en ${apartar(nucleo)}`);
	const fuera = new Set(['wp-content', 'wp-config.php']);
	fs.cpSync(publico, nucleo, {
		recursive: true,
		filter: (origen) => {
			const relativa = path.relative(publico, origen);
			const primera = relativa.split(path.sep)[0];
			return !fuera.has(primera) && !/^zz-.*\.php$/.test(primera);
		},
	});
	hecho(`núcleo ${versionWp} copiado sin wp-content ni wp-config.php`);
}

// --- 6. Base local_tests ---------------------------------------------------------------------
titulo('6. Base de pruebas local_tests');
const mysqlEncendido = await new Promise((resolver) => {
	const s = net.connect({ host: '127.0.0.1', port: puerto, timeout: 1500 });
	s.on('connect', () => (s.destroy(), resolver(true)));
	s.on('error', () => resolver(false));
	s.on('timeout', () => (s.destroy(), resolver(false)));
});
if (!mysqlEncendido) {
	falta(`MySQL no responde en 127.0.0.1:${puerto}: enciende el sitio en Local y vuelve a correr el script`);
} else if (!mysql || !fs.existsSync(mysql)) {
	falta(`no encuentro mysql.exe de MySQL ${versionMysql} en ${servicios}`);
} else {
	const sql = (consulta) =>
		correr(mysql, ['-h', '127.0.0.1', '-P', String(puerto), '-u', 'root', '-N', '-e', consulta], { env: { ...process.env, MYSQL_PWD: 'root' } });
	const existe = sql("SHOW DATABASES LIKE 'local_tests'").stdout.trim() === 'local_tests';
	if (existe) ok('local_tests existe');
	else if (!INSTALAR) falta('la base local_tests no existe');
	else if (sql('CREATE DATABASE IF NOT EXISTS local_tests').status === 0) hecho('base local_tests creada');
	else falta('no se pudo crear local_tests');
}

// --- 7. Nginx: PDF privados ------------------------------------------------------------------
titulo('7. Nginx: carpeta privada de los PDF');
const conf = path.join(sitio, 'conf/nginx/site.conf.hbs');
const regla = fs
	.readFileSync(path.join(plantillas, 'nginx-angelitozzz-privado.conf'), 'utf8')
	.split(/\r?\n/)
	.filter((l) => l.trim() && !l.trim().startsWith('#'))
	.map((l) => '    ' + l)
	.join('\n');
const textoConf = fs.existsSync(conf) ? fs.readFileSync(conf, 'utf8') : null;
let reiniciar = false;
if (textoConf === null) {
	falta(`no existe ${conf}`);
} else if (textoConf.includes('angelitozzz-privado')) {
	ok('la regla está en site.conf.hbs');
} else if (!INSTALAR) {
	falta('falta la regla deny de angelitozzz-privado en site.conf.hbs');
} else {
	const ancla = /^([ \t]*include includes\/restrictions\.conf;[^\n]*\n)/m;
	if (ancla.test(textoConf)) {
		fs.copyFileSync(conf, `${conf}.viejo-${fecha}`);
		const bloque = `\n    #\n    # Angelitozzz Cursos: los PDF de materiales solo se entregan por /cursos/descargar/<id>/\n    #\n${regla}\n`;
		fs.writeFileSync(conf, textoConf.replace(ancla, `$1${bloque}`));
		hecho('regla agregada a site.conf.hbs (copia en .viejo). Reinicia el sitio en Local');
		reiniciar = true;
	} else {
		falta('no encontré dónde ponerla: pega entorno/nginx-angelitozzz-privado.conf a mano dentro del bloque server');
	}
}
if (!reiniciar && dominio) {
	try {
		const r = await fetch(`http://${dominio}/wp-content/uploads/angelitozzz-privado/prueba.pdf`, { signal: AbortSignal.timeout(3000) });
		if (r.status === 403) ok(`http://${dominio}/wp-content/uploads/angelitozzz-privado/ responde 403`);
		else falta(`la carpeta privada responde ${r.status}, no 403: reinicia el sitio en Local para que tome la regla`);
	} catch {
		aviso(`no pude abrir http://${dominio}/ para comprobar el 403 (¿sitio apagado?)`);
	}
}

// --- 8. Plugin angelitozzz-cursos ------------------------------------------------------------
titulo('8. Plugin angelitozzz-cursos');
if (!fs.existsSync(plugin)) {
	falta(`no existe ${plugin}: clónalo desde GitHub (agencia-nerd/angelitozzz-cursos)`);
} else {
	if (!fs.existsSync(path.join(plugin, '.git'))) {
		falta('el plugin no tiene .git (All-in-One no lo lleva): clónalo de GitHub en esa carpeta en vez de usar la copia importada');
	} else {
		ok('el plugin es un repo de git');
	}
	const xml = path.join(plugin, 'phpunit.xml');
	if (fs.existsSync(xml)) {
		const rutas = [...fs.readFileSync(xml, 'utf8').matchAll(/value="([A-Za-z]:[^"]+)"/g)].map((m) => m[1]);
		const rotas = rutas.filter((r) => !fs.existsSync(r));
		if (!rotas.length) aviso('hay un phpunit.xml local: ya no hace falta (las rutas se calculan solas)');
		else if (!INSTALAR) falta(`phpunit.xml apunta a rutas de otra PC: ${rotas[0]}`);
		else hecho(`phpunit.xml con rutas de otra PC apartado en ${apartar(xml)}`);
	}
	if (fs.existsSync(path.join(plugin, 'vendor/autoload.php'))) {
		ok('vendor/ instalado');
	} else if (!INSTALAR) {
		falta('falta vendor/ (composer install)');
	} else if (fs.existsSync(phar) && fs.existsSync(ini)) {
		const r = correr(php, ['-c', ini, phar, 'install'], { cwd: plugin, stdio: 'inherit' });
		if (r.status === 0) hecho('composer install');
		else falta('composer install falló');
	} else {
		falta('falta vendor/ y no hay composer.phar o php-cli.ini para instalarlo');
	}
}

// --- Resumen ---------------------------------------------------------------------------------
console.log('');
if (pendientes === 0) {
	console.log('Todo en orden. Desde la carpeta del plugin: composer test y composer lint.');
} else if (!INSTALAR) {
	console.log(`${pendientes} pendiente(s). Para armarlos: node specs/pruebas/entorno/instalar.mjs --instalar`);
	process.exitCode = 1;
} else {
	console.log(`${pendientes} pendiente(s) que el script no pudo resolver: revísalos arriba.`);
	process.exitCode = 1;
}
