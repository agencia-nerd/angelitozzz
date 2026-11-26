// Rutas: SKILL_DIR (la skill), OUT (carpeta de la corrida, fuera del repo), TARGET (repo auditado), WP_ROOT (WordPress del Local) y COMMIT. Ver LEEME.md.
const fs = require('fs'), path = require('path'), cp = require('child_process');
const SK = process.env.SKILL_DIR, OUT = process.env.OUT;
const rd = (f) => fs.readFileSync(path.join(SK, f), 'utf8');
const bloque = (f, n) => cp.execFileSync('node', [path.join(__dirname, 'bloque.cjs'), f, n], { env: { ...process.env } }).toString();
const enc = (s) => Buffer.from(s.normalize('NFC'), 'utf8').reduce((a, b) => a + (/[A-Za-z0-9\-._~]/.test(String.fromCharCode(b)) ? String.fromCharCode(b) : '%' + b.toString(16).toUpperCase().padStart(2, '0')), '');
const A = 'ATTACK-CLASSES.md', W = 'WEB-PROTOCOL-AND-AUTH.md', D = 'DATA-ISOLATION-AND-LIFECYCLE.md', R = 'RESOURCE-EXHAUSTION-AND-AVAILABILITY.md', C = 'CLIENT-SIDE.md';
const COMP = [W, D, R, C];
const ALLOWED = { [W]: ['Ordinary CSRF','Session fixation and invalidation','Password reset and broader recovery','Account linking and identity collision'], [D]: ['Enumeration and aggregate oracles','Stale authorization and derived copy use','Blob and signed-reference overreach','Missing tenant or owner enforcement','Export and backup scope expansion','Soft-delete and tombstone bypass','Migration default and ownership confusion'], [R]: ['Unbounded buffering and cardinality','File descriptor, handle, and temporary-resource leaks','Quota-accounting scope and reset gaps'], [C]: ['DOM-based XSS'] };
const ledger = JSON.parse(fs.readFileSync(path.join(OUT, 'coverage-ledger.json'), 'utf8'));
// unidades nuevas: [aid, surface, boundary, subsystem, paths, ordinary|null, [companion blocks], reason]
// EJEMPLO DEL PLUGIN (ola 2 de la corrida 1): unidades que propuso el crítico. Para otro repo, reemplazar con las suyas.
const N = [
 ['h11-cuentas-paralelas','WooCommerce checkout account creation (classic and Store API) via wc_create_new_customer','includes/cuentas-formularios.php#angelitozzz_cursos_limite_cuenta_checkout','includes/cuentas',['includes/cuentas-acceso.php','includes/cuentas-formularios.php','includes/checkout.php'],null,[D+'#Enumeration and aggregate oracles',R+'#Quota-accounting scope and reset gaps']],
 ['h11-cuentas-paralelas','WooCommerce My Account form handlers on wp_loaded and core wp-login.php lostpassword|rp|register','includes/cuentas-formularios.php#angelitozzz_cursos_logica_recuperar limits vs lostpassword_post/validate_password_reset','includes/cuentas',['includes/cuentas-formularios.php','includes/cuentas-acceso.php','includes/cuentas.php'],null,[R+'#Quota-accounting scope and reset gaps',W+'#Password reset and broader recovery']],
 ['h11-cuentas-paralelas','WordPress core REST /wp/v2/users/me and application passwords reachable by student sessions','includes/cuentas-acceso.php#angelitozzz_cursos_cerrar_admin','includes/cuentas-acceso',['includes/cuentas-acceso.php','includes/rest.php','includes/archivos-entrega.php'],A+'#Access control',[]],
 ['h12-producto-curso','Product-to-course mapping lifecycle outside the builder (duplicate, wc/v3 REST meta, quick edit, trash/untrash/draft, migration 2)','includes/woocommerce.php#angelitozzz_cursos_curso_de_producto','includes/woocommerce',['includes/woocommerce.php','includes/constructor.php','includes/admin.php','includes/esquema.php','includes/desinstalar.php'],A+'#Business logic',[D+'#Soft-delete and tombstone bypass',D+'#Migration default and ownership confusion']],
];
const nuevos = [];
for (const [aid, sur, bou, sub, paths, ord, comps] of N) {
  const list = (ord ? [[ord, null]] : []).concat(comps.map(c => [c, c]));
  for (const [ac, comp] of list) {
    const f = ac.slice(0, ac.indexOf('#'));
    const refs = { surface: sur, boundary: bou, subsystem: sub, attack_class: ac };
    const id = [sur, bou, sub, ac].map(enc).join('::');
    if (ledger.some(u => u.coverage_id === id)) continue;
    const sel = comp ? [f + '#Core discipline', ac, f + '#Universal moves', f + '#Validation rules'] : [];
    const excl = COMP.filter(cf => cf !== f).map(cf => ({ block: cf, reason: "companion domain not crossed by this unit's boundary" }));
    const u = { coverage_id: id, canonical_refs: refs, surface: sur, boundary: bou, subsystem: sub, attack_class: ac, starting_paths: paths, ordinary_attack_class_block: comp ? null : ac, selected_companion_blocks: sel, excluded_blocks: excl, prior_status: 'none', attempts: [], wave: 2, status: 'in_progress', agent_id: aid, reviewed_paths: [], local_checks: [], result_fingerprints: [], unresolved: [], _origin: 'post-wave critic wave 1 / hunter uncovered' };
    ledger.push(u); nuevos.push(u);
  }
}
// fuera de alcance: builder del tema
const sur='Theme templates and JS rendering /administrador builder screens from angelitozzz/v1 JSON', bou='theme output escaping of plugin-provided manager data', sub='theme (outside TARGET plugin repo)', ac=C+'#DOM-based XSS';
const oid=[sur,bou,sub,ac].map(enc).join('::');
if (!ledger.some(u=>u.coverage_id===oid)) ledger.push({ coverage_id: oid, canonical_refs:{surface:sur,boundary:bou,subsystem:sub,attack_class:ac}, surface:sur, boundary:bou, subsystem:sub, attack_class:ac, starting_paths:['includes/constructor.php','includes/admin.php'], ordinary_attack_class_block:null, selected_companion_blocks:[C+'#Core discipline',ac,C+'#Universal moves',C+'#Validation rules'], excluded_blocks:[], prior_status:'none', attempts:[], wave:2, status:'out_of_scope', agent_id:null, reviewed_paths:[], local_checks:[], result_fingerprints:[], unresolved:['Theme repository is outside this run target; audit it in the theme run.'] });
ledger.sort((a, b) => a.coverage_id < b.coverage_id ? -1 : 1);
fs.writeFileSync(path.join(OUT, 'coverage-ledger.json'), JSON.stringify(ledger, null, 2));
const H = [...new Set(nuevos.map(u => u.agent_id))].map(a => [a]);
// Prompts
const hunt = rd('HUNTING.md');
const fence = (titulo) => { const i = hunt.indexOf(titulo); const a = hunt.indexOf('```text\n', i) + 8; return hunt.slice(a, hunt.indexOf('\n```', a)); };
const metodo = fence('#### Core hunting method'), promo = fence('#### Promotion procedure'), gate = fence('#### Core validation rules');
const ri = hunt.indexOf('## Structured hunter result'), contrato = hunt.slice(ri, hunt.indexOf('## Parent consolidation'));
const schema = require(path.join(SK, 'report-schema.json'));
const arq = fs.readFileSync(path.join(OUT, 'architecture.md'), 'utf8');
for (const [aid] of H) {
  const mine = ledger.filter(u => u.agent_id === aid && u.status === "in_progress");
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
