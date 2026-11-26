// Rutas: SKILL_DIR (la skill), OUT (carpeta de la corrida, fuera del repo), TARGET (repo auditado), WP_ROOT (WordPress del Local) y COMMIT. Ver LEEME.md.
// Aplica el resultado JSON de un cazador al ledger. Uso: node consolidar.cjs <agent_id> <resultado.json>
const fs = require('fs'), path = require('path');
const [aid, file] = process.argv.slice(2);
const OUT = process.env.OUT;
fs.mkdirSync(path.join(OUT, '_estado'), { recursive: true });
const L = path.join(OUT, 'coverage-ledger.json');
const ledger = JSON.parse(fs.readFileSync(L, 'utf8'));
const r = JSON.parse(fs.readFileSync(file, 'utf8'));
const mine = ledger.filter(u => u.agent_id === aid && u.status === 'in_progress');
const ids = new Set(mine.map(u => u.coverage_id));
const vistos = new Set(); const problemas = [];
for (const res of r.units || []) {
  if (!ids.has(res.coverage_id)) { problemas.push('ID ajeno o repetido: ' + res.coverage_id.slice(0, 80)); continue; }
  if (vistos.has(res.coverage_id)) { problemas.push('duplicado'); continue; }
  vistos.add(res.coverage_id);
  const u = ledger.find(x => x.coverage_id === res.coverage_id);
  const checks = (res.checks || []).map(c => ({ ...c, agent_id: aid, artifact: null, method: 'source' }));
  const union = [...new Set(checks.flatMap(c => c.reviewed_paths || []))];
  u.status = res.disposition; u.reviewed_paths = union; u.local_checks = checks;
  u.result_fingerprints = res.disposition === 'candidate' ? (res.candidate_fingerprints || []) : [];
  u.unresolved = res.unresolved || [];
  if (res.disposition === 'covered') u.unresolved = [];
  u._hardening = r.hardening || [];
}
for (const u of mine) if (!vistos.has(u.coverage_id)) { u.status = 'planned'; u.agent_id = null; problemas.push('unidad sin resultado -> planned: ' + u.attack_class); }
ledger.sort((a, b) => a.coverage_id < b.coverage_id ? -1 : 1);
fs.writeFileSync(L, JSON.stringify(ledger, null, 2));
const cands = path.join(OUT, '_estado', 'candidatos.json');
const all = fs.existsSync(cands) ? JSON.parse(fs.readFileSync(cands, 'utf8')) : [];
for (const c of r.candidates || []) all.push({ ...c, _hunter: aid });
fs.writeFileSync(cands, JSON.stringify(all, null, 2));
const unc = path.join(OUT, '_estado', 'uncovered.json');
const u2 = fs.existsSync(unc) ? JSON.parse(fs.readFileSync(unc, 'utf8')) : [];
for (const x of r.uncovered || []) u2.push({ ...x, _hunter: aid });
fs.writeFileSync(unc, JSON.stringify(u2, null, 2));
console.log(aid, (r.units || []).map(x => x.disposition).join(','), '| candidatos:', (r.candidates || []).map(c => c.fingerprint + ' [' + c.proposed_verdict + (c.severity ? ' ' + JSON.stringify(c.severity.overall || c.severity) : '') + ']').join('; ') || 'ninguno', '| uncovered:', (r.uncovered || []).length, problemas.length ? '\nPROBLEMAS: ' + problemas.join('; ') : '');
