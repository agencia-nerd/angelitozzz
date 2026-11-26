// Rutas: SKILL_DIR (la skill), OUT (carpeta de la corrida, fuera del repo), TARGET (repo auditado), WP_ROOT (WordPress del Local) y COMMIT. Ver LEEME.md.
// Genera coverage-ledger.json y los prompts de los cazadores (ola 1).
const fs = require('fs'), path = require('path'), cp = require('child_process');
const SK = process.env.SKILL_DIR, OUT = process.env.OUT;
const rd = (f) => fs.readFileSync(path.join(SK, f), 'utf8');
const bloque = (f, n) => cp.execFileSync('node', [path.join(__dirname, 'bloque.cjs'), f, n], { env: { ...process.env } }).toString();
const enc = (s) => Buffer.from(s.normalize('NFC'), 'utf8').reduce((a, b) => a + (/[A-Za-z0-9\-._~]/.test(String.fromCharCode(b)) ? String.fromCharCode(b) : '%' + b.toString(16).toUpperCase().padStart(2, '0')), '');
const A = 'ATTACK-CLASSES.md', W = 'WEB-PROTOCOL-AND-AUTH.md', D = 'DATA-ISOLATION-AND-LIFECYCLE.md', R = 'RESOURCE-EXHAUSTION-AND-AVAILABILITY.md', C = 'CLIENT-SIDE.md';
const NUC = ['Core discipline', 'Universal moves', 'Validation rules'];
const COMP = [W, D, R, C];
// Cazadores: id, superficie, frontera, subsistema, rutas, clases [archivo, clase]
// EJEMPLO DEL PLUGIN (corrida 1): cazadores de angelitozzz-cursos. Para otro repo, reemplazar esta lista con las
// superficies de su reconocimiento: [id, superficie, frontera, subsistema, rutas, [[archivo, clase], ...]].
const H = [
 ['h01-cuentas', 'includes/cuentas.php#admin-post angelitozzz_iniciar_sesion|crear_cuenta|recuperar|nueva_contrasena|perfil_datos|perfil_contrasena and GET angelitozzz_confirmar_correo', 'includes/cuentas.php#angelitozzz_cursos_atender_formulario', 'includes/cuentas', ['includes/cuentas.php','includes/cuentas-formularios.php','includes/cuentas-acceso.php'],
   [[A,'Access control'],[A,'Business logic'],[W,'Ordinary CSRF'],[W,'Session fixation and invalidation'],[W,'Password reset and broader recovery'],[W,'Account linking and identity collision']]],
 ['h02-regalos', 'includes/regalos.php#admin-post angelitozzz_canjear_regalo, cookie angelitozzz_canje, coupon validity filters, gift coupon creation and voiding', 'includes/regalos.php#angelitozzz_cursos_regalo_canjear', 'includes/regalos', ['includes/regalos.php','includes/woocommerce.php','includes/inscripciones.php'],
   [[A,'Business logic'],[A,'Access control'],[A,'Cryptography and secrets'],[D,'Enumeration and aggregate oracles']]],
 ['h03-pedidos', 'includes/woocommerce.php#woocommerce_order_status_processing|completed|refunded|cancelled, woocommerce_order_refunded, cart item angelitozzz_regalo, retry action', 'includes/woocommerce.php#angelitozzz_cursos_pedido_pagado', 'includes/woocommerce', ['includes/woocommerce.php','includes/inscripciones.php','includes/vencimiento.php'],
   [[A,'Business logic'],[A,'Chained vulnerabilities and trust boundaries'],[D,'Stale authorization and derived copy use']]],
 ['h04-checkout', 'includes/checkout.php#WooCommerce checkout fields and validation, renewal add-to-cart, Openpay failed-order hook', 'includes/checkout.php#woocommerce_after_checkout_validation', 'includes/checkout', ['includes/checkout.php','includes/cuentas-acceso.php','includes/renovacion.php','includes/pagos.php'],
   [[A,'Business logic'],[A,'Access control'],[A,'Injection']]],
 ['h05-entrega', 'includes/archivos-entrega.php#GET /cursos/archivo/<id>/?l&e&f&d and query var angelitozzz_archivo', 'includes/archivos-entrega.php#angelitozzz_cursos_archivo_permitido', 'includes/archivos-entrega', ['includes/archivos-entrega.php','includes/archivos.php','includes/acceso.php','includes/descargas.php'],
   [[A,'Access control'],[A,'Cryptography and secrets'],[A,'Resource and file handling'],[D,'Blob and signed-reference overreach']]],
 ['h06-lecciones', 'includes/acceso.php#template_redirect lesson/course gate, REST /lecciones/{id}/completar and /lecciones/{id}/video, admin-post completion', 'includes/acceso.php#angelitozzz_cursos_puede_ver', 'includes/acceso', ['includes/acceso.php','includes/avance.php','includes/rest.php','includes/inscripciones.php','includes/vencimiento.php','includes/video.php'],
   [[A,'Access control'],[A,'Business logic'],[D,'Missing tenant or owner enforcement']]],
 ['h07-subidas', 'includes/archivos-subida.php#REST /archivos/subidas (init, PUT chunk, fin, DELETE), /archivos/{id}/portada, /cursos/{id}/portada', 'includes/archivos.php#angelitozzz_cursos_archivo_validar_tipo', 'includes/archivos-subida', ['includes/archivos-subida.php','includes/archivos.php','includes/descargas.php','includes/constructor.php'],
   [[A,'Resource and file handling'],[A,'Injection'],[R,'Unbounded buffering and cardinality'],[R,'File descriptor, handle, and temporary-resource leaks']]],
 ['h08-constructor', 'includes/rest-constructor.php#REST angelitozzz/v1 builder and admin routes, /administrador front end', 'includes/rest-admin.php#angelitozzz_cursos_rest_puede_gestionar', 'includes/constructor', ['includes/rest-constructor.php','includes/constructor.php','includes/rest-admin.php','includes/admin.php','includes/capacidades.php','includes/tipos.php'],
   [[A,'Access control'],[A,'Injection'],[A,'Business logic']]],
 ['h09-datos', 'includes/api.php#theme-facing getters, lesson HTML, privacy exporter/eraser, uninstall, delete_user, wp-admin Archivos privados screen and JS', 'includes/api.php#angelitozzz_cursos_* public functions', 'includes/api', ['includes/api.php','includes/tipos.php','includes/video.php','includes/privacidad.php','includes/desinstalar.php','uninstall.php','includes/admin-archivos.php','assets/js/archivos-admin.js','assets/js/subida.js'],
   [[A,'Injection'],[A,'Feature abuse and data leakage'],[D,'Export and backup scope expansion'],[C,'DOM-based XSS']]],
 ['h10-general', 'angelitozzz-cursos.php#plugin bootstrap, migrations, capabilities, whole includes/ tree', 'includes/capacidades.php#angelitozzz_cursos_puede_gestionar', 'plugin', ['angelitozzz-cursos.php','includes/esquema.php','includes/capacidades.php','includes/utilidades.php','includes/compatibilidad.php','includes'],
   [[A,'Obvious things'],[A,'Wildcard']]],
];
const ALLOWED = { [W]: ['Ordinary CSRF','Session fixation and invalidation','Password reset and broader recovery','Account linking and identity collision'], [D]: ['Enumeration and aggregate oracles','Stale authorization and derived copy use','Blob and signed-reference overreach','Missing tenant or owner enforcement','Export and backup scope expansion'], [R]: ['Unbounded buffering and cardinality','File descriptor, handle, and temporary-resource leaks'], [C]: ['DOM-based XSS'] };
const ledger = [];
for (const [aid, sur, bou, sub, paths, clases] of H) {
  for (const [f, cl] of clases) {
    const comp = f !== A;
    const acRef = f + '#' + cl;
    const refs = { surface: sur, boundary: bou, subsystem: sub, attack_class: acRef };
    const id = [refs.surface, refs.boundary, refs.subsystem, refs.attack_class].map(enc).join('::');
    const sel = comp ? [f + '#Core discipline', acRef, f + '#Universal moves', f + '#Validation rules'] : [];
    const excl = [];
    for (const cf of COMP) if (cf !== f) excl.push({ block: cf, reason: 'companion domain not crossed by this unit\'s boundary; assigned to its own units where applicable (see architecture.md section 8)' });
    ledger.push({ coverage_id: id, canonical_refs: refs, surface: sur, boundary: bou, subsystem: sub, attack_class: acRef, starting_paths: paths,
      ordinary_attack_class_block: comp ? null : acRef, selected_companion_blocks: sel, excluded_blocks: excl,
      prior_status: 'none', attempts: [], wave: 1, status: 'in_progress', agent_id: aid, reviewed_paths: [], local_checks: [], result_fingerprints: [], unresolved: [],
      _priority_note: 'wave 1: every seeded unit assigned; priority order lowest-trust surfaces first (accounts, gifts, orders, checkout, file delivery, lessons) then manager-only surfaces' });
  }
}
ledger.sort((a, b) => a.coverage_id < b.coverage_id ? -1 : 1);
fs.writeFileSync(path.join(OUT, 'coverage-ledger.json'), JSON.stringify(ledger, null, 2));
// Prompts
const hunt = rd('HUNTING.md');
const fence = (titulo) => { const i = hunt.indexOf(titulo); const a = hunt.indexOf('```text\n', i) + 8; return hunt.slice(a, hunt.indexOf('\n```', a)); };
const metodo = fence('#### Core hunting method'), promo = fence('#### Promotion procedure'), gate = fence('#### Core validation rules');
const ri = hunt.indexOf('## Structured hunter result'), contrato = hunt.slice(ri, hunt.indexOf('## Parent consolidation'));
const schema = require(path.join(SK, 'report-schema.json'));
const arq = fs.readFileSync(path.join(OUT, 'architecture.md'), 'utf8');
for (const [aid, , , , , clases] of H) {
  const mine = ledger.filter(u => u.agent_id === aid);
  const peers = ledger.filter(u => u.agent_id !== aid).map(u => u.coverage_id);
  const bl = []; const seen = new Set();
  for (const u of mine) for (const b of (u.ordinary_attack_class_block ? [u.ordinary_attack_class_block] : []).concat(u.selected_companion_blocks)) if (!seen.has(b)) { seen.add(b); const [f, n] = [b.slice(0, b.indexOf('#')), b.slice(b.indexOf('#') + 1)]; bl.push('### ' + b + '\n\n' + bloque(f, n)); }
  const exclOrd = ['Injection','Access control','Resource and file handling','Cryptography and secrets','Business logic','Feature abuse and data leakage','Chained vulnerabilities and trust boundaries','Wildcard','Obvious things'].filter(c => !seen.has(A + '#' + c)).map(c => '- ' + A + '#' + c + ': assigned to peer units or not crossed by this boundary');
  const exclComp = []; for (const cf of COMP) for (const c of ALLOWED[cf]) if (!seen.has(cf + '#' + c)) exclComp.push('- ' + cf + '#' + c + ': owned by a peer unit or not crossed by this boundary');
  exclComp.push('- MEMORY-SAFETY-AND-BINARY.md, AI-AND-LLM.md, SUPPLY-CHAIN-AND-RELEASE.md, CLOUD-AND-DEPLOYMENT.md, PROTOCOLS-RPC-AND-MESSAGING.md, DESKTOP-MOBILE-AND-LOCAL-IPC.md: not selected (architecture.md section 8)');
  const dir = path.join(OUT, 'agents', aid); fs.mkdirSync(path.join(dir, 'scratch'), { recursive: true }); fs.mkdirSync(path.join(dir, 'artifacts'), { recursive: true });
  const p = [
`# Hunter prompt for ${aid}

Your goal is to find source-grounded security invariant failures in your assigned coverage units of the WordPress code at TARGET = \`${process.env.TARGET}\` (commit ${process.env.COMMIT || "HEAD"}; ignore vendor/, node_modules/ and dist/; read tests only to see what is tested). You must return exactly one JSON object matching the structured-result contract at the end of this prompt.`,
'## 2. architecture.md (verbatim)\n\n' + arq,
'## 3. Assigned coverage units (from coverage-ledger.json)\n\n```json\n' + JSON.stringify(mine.map(u => ({ coverage_id: u.coverage_id, subsystem: u.subsystem, boundary: u.boundary, surface: u.surface, attack_class: u.attack_class, starting_paths: u.starting_paths, ordinary_attack_class_block: u.ordinary_attack_class_block, selected_companion_blocks: u.selected_companion_blocks })), null, 2) + '\n```',
'## 4. Selected blocks (verbatim)\n\n' + bl.join('\n\n'),
'## 5. Excluded blocks\n\n' + exclOrd.concat(exclComp).join('\n'),
'## 6. Core hunting method\n\n```text\n' + metodo + '\n```\n\n```text\n' + promo + '\n```',
'## 7. Core validation rules\n\n```text\n' + gate + '\n```',
'## 8. Exclusions and peer-owned units\n\nNo carried prior confirmed records (first run). Do not duplicate these peer-owned coverage IDs; report out-of-scope boundaries under `uncovered`:\n\n' + peers.map(x => '- ' + x).join('\n'),
`## 9. Paths, ID, limits, and result contract

- Agent ID: \`${aid}\` (use it as agent_id in every check).
- Scratch: \`${dir.replace(/\\/g,'/')}/scratch/\`. Artifacts: parent-owned, you never write them.
- Promotion allowlist: EMPTY (0 files, 0 bytes). No OS-enforced sandbox exists on this host, so you must NOT execute target code, tests, PHP, or composer. Every check is \`method: "source"\` with \`artifact: null\`. A claim that needs runtime proof is \`needs_validation\` with the blocker "no OS-enforced sandbox on host; target execution prohibited".
- Use repository-relative paths (relative to TARGET) everywhere.

` + contrato + '\n### report-schema.json branches (verbatim): confirmed\n\n```json\n' + JSON.stringify(schema.items.oneOf[0], null, 2) + '\n```\n\n### needs_validation\n\n```json\n' + JSON.stringify(schema.items.oneOf[1], null, 2) + '\n```\n'];
  fs.writeFileSync(path.join(dir, 'PROMPT.md'), p.join('\n\n'));
  console.log(aid, mine.length, 'units', fs.statSync(path.join(dir, 'PROMPT.md')).size, 'bytes');
}
