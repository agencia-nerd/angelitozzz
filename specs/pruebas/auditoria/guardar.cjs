// Rutas: SKILL_DIR (la skill), OUT (carpeta de la corrida, fuera del repo), TARGET (repo auditado), WP_ROOT (WordPress del Local) y COMMIT. Ver LEEME.md.
// Arma un resultado compacto de cazador: node guardar.cjs <aid> < spec.json
// spec: {units:[{cls:"<attack_class ref>", d:"covered|candidate|blocked", checks:[[paths[], invariant, result]], fps:[], unr:[]}], candidates:[], hardening:[], uncovered:[]}
const fs = require('fs'), path = require('path');
const aid = process.argv[2]; const spec = JSON.parse(fs.readFileSync(0, 'utf8'));
const ledger = JSON.parse(fs.readFileSync(path.join(process.env.OUT, 'coverage-ledger.json'), 'utf8'));
const units = spec.units.map(u => { const L = ledger.find(x => x.agent_id === aid && x.attack_class === u.cls); if (!L) throw new Error('sin unidad ' + u.cls);
  const checks = u.checks.map(([p, inv, res]) => ({ agent_id: aid, reviewed_paths: p, invariant: inv, method: 'source', result: res, artifact: null }));
  return { coverage_id: L.coverage_id, disposition: u.d, reviewed_paths: [...new Set(checks.flatMap(c => c.reviewed_paths))], checks, candidate_fingerprints: u.fps || [], unresolved: u.unr || [] }; });
fs.mkdirSync(path.join(process.env.OUT, '_estado', 'resultados'), { recursive: true });
fs.writeFileSync(path.join(process.env.OUT, '_estado', 'resultados', aid + '.json'), JSON.stringify({ units, candidates: spec.candidates || [], hardening: spec.hardening || [], uncovered: spec.uncovered || [] }, null, 2));
