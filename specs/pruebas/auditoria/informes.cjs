// Rutas: SKILL_DIR (la skill), OUT (carpeta de la corrida, fuera del repo), TARGET (repo auditado), WP_ROOT (WordPress del Local) y COMMIT. Ver LEEME.md.
// Fase 6: arma NEEDS-VALIDATION.md, FINDINGS-DETAIL.md y _estado/hardening.txt desde findings.json y el ledger.
// ORDEN (opcional): huellas separadas por coma, en el orden de prioridad; si falta, el de findings.json.
const fs = require('fs');
process.chdir(process.env.OUT);
const F = JSON.parse(fs.readFileSync('findings.json', 'utf8'));
const titulo = (process.env.REPO_NOMBRE || 'repo') + ' @ ' + (process.env.COMMIT || 'HEAD');
const bt = String.fromCharCode(96); // acento grave, para el Markdown
const cod = (t) => bt + t + bt;

const pendientes = F.filter((r) => r.verdict === 'needs_validation');
const orden = process.env.ORDEN ? process.env.ORDEN.split(',') : pendientes.map((r) => r.fingerprint);

let s = '# NEEDS VALIDATION: ' + titulo + '\n\nLeads ordered by the parent\'s triage priority (not a severity). None is a confirmed vulnerability: each lead keeps its exact blocker and a bounded local or owner-observed plan. Run local plans only on a disposable local copy, never against production.\n';
orden.forEach((fp, i) => {
	const r = pendientes.find((x) => x.fingerprint === fp);
	if (!r) return;
	s += '\n## ' + (i + 1) + '. ' + r.title + '\n\n- **Fingerprint:** ' + cod(r.fingerprint) + '\n\n' + r.description + '\n\n**Claimed root cause:** ' + r.claimed_root_cause + '\n\n**Trace:**\n\n'
		+ r.trace.map((t) => '- ' + cod(t.kind) + ' ' + cod(t.file + ':' + t.line) + ' (' + t.scope + '): ' + t.description).join('\n')
		+ '\n\n**Evidence:**\n\n' + r.evidence.map((e) => '- ' + cod(e.file + ':' + e.line) + ': ' + e.description).join('\n')
		+ '\n\n**Blockers:**\n\n' + r.blockers.map((b) => '- ' + b).join('\n')
		+ '\n\n**Validation plan:**\n\n' + (r.validation_plan.local ? '- Local: ' + r.validation_plan.local + '\n' : '') + (r.validation_plan.deployment ? '- Owner-observed: ' + r.validation_plan.deployment + '\n' : '');
});
const rechazados = F.filter((r) => r.verdict === 'rejected');
if (rechazados.length) {
	s += '\n## Rejected during validation\n\n' + rechazados.map((r) => '- ' + cod(r.fingerprint) + ': ' + r.reason).join('\n') + '\n';
}
fs.writeFileSync('NEEDS-VALIDATION.md', s);

const confirmados = F.filter((r) => r.verdict === 'confirmed');
let d = '# FINDINGS DETAIL: ' + titulo + '\n\n';
if (!confirmados.length) {
	d += 'No ' + cod('confirmed') + ' records. Every surviving lead is in ' + cod('NEEDS-VALIDATION.md') + ' with its trace, blocker and plan.\n';
} else {
	for (const r of confirmados) {
		d += '## ' + r.title + '\n\n- **Fingerprint:** ' + cod(r.fingerprint) + '\n\n' + r.description + '\n\n```json\n' + JSON.stringify(r, null, 2) + '\n```\n\n';
	}
}
fs.writeFileSync('FINDINGS-DETAIL.md', d);

const L = JSON.parse(fs.readFileSync('coverage-ledger.json', 'utf8'));
const h = [];
for (const u of L) for (const x of (u._hardening || [])) if (!h.includes(x)) h.push(x);
fs.mkdirSync('_estado', { recursive: true });
fs.writeFileSync('_estado/hardening.txt', h.join('\n'));
console.log('informes listos; refuerzos:', h.length);
