// Rutas: SKILL_DIR (la skill), OUT (carpeta de la corrida, fuera del repo), TARGET (repo auditado), WP_ROOT (WordPress del Local) y COMMIT. Ver LEEME.md.
// Genera prompts de verificadores de fase 3. Uso: node verif.cjs  (usa candidatos.json)
const fs = require('fs'), path = require('path'), cp = require('child_process');
const SK = process.env.SKILL_DIR, OUT = process.env.OUT;
const bloque = (f, n) => cp.execFileSync('node', [path.join(__dirname, 'bloque.cjs'), f, n], { env: { ...process.env } }).toString();
const val = fs.readFileSync(path.join(SK, 'VALIDATION-AND-REPORTING.md'), 'utf8');
const f1 = (t) => { const i = val.indexOf(t); const a = val.indexOf('```text\n', i) + 8; return val.slice(a, val.indexOf('\n```', a)); };
const vp = f1('#### Candidate-verifier prompt'), promo = f1('Copy this promotion procedure verbatim');
const schema = require(path.join(SK, 'report-schema.json'));
const arq = fs.readFileSync(path.join(OUT, 'architecture.md'), 'utf8');
const ledger = JSON.parse(fs.readFileSync(path.join(OUT, 'coverage-ledger.json'), 'utf8'));
const cands = JSON.parse(fs.readFileSync(path.join(OUT, '_estado', 'candidatos.json'), 'utf8'));
const vistos = new Set(); let n = 0; const ids = [];
for (const c of cands) {
  if (vistos.has(c.fingerprint)) continue; vistos.add(c.fingerprint); n++;
  const vid = 'v3-' + String(n).padStart(2, '0');
  const units = ledger.filter(u => (u.result_fingerprints || []).includes(c.fingerprint));
  const comps = [...new Set(units.flatMap(u => u.selected_companion_blocks.filter(b => /Validation rules$/.test(b))))];
  const blocks = comps.map(b => '### ' + b + '\n\n' + bloque(b.split('#')[0], 'Validation rules')).join('\n\n');
  const { _hunter, proposed_verdict, ...rec } = c;
  const dir = path.join(OUT, 'agents', vid); fs.mkdirSync(path.join(dir, 'scratch'), { recursive: true }); fs.mkdirSync(path.join(dir, 'artifacts'), { recursive: true });
  const p = `# Candidate verifier ${vid}

TARGET (read-only): \`${process.env.TARGET}\` (commit ${process.env.COMMIT || "HEAD"}). You may also read installed WordPress core (\`${process.env.WP_ROOT}/wp-includes\`, \`wp-login.php\`, \`wp-admin\`) and WooCommerce (\`.../wp-content/plugins/woocommerce\`) read-only when the claim depends on them. All record paths stay relative to TARGET.

Execution boundary: there is NO OS-enforced sandbox on this host. Do not execute target code, PHP, tests or composer; no network. Scratch: \`${dir.split(String.fromCharCode(92)).join("/")}/scratch/\`; promotion allowlist is EMPTY, so no file can be evidence. A claim that needs runtime proof stays needs_validation with the blocker "no OS-enforced sandbox on host; target execution prohibited".

## Verifier instructions (verbatim)

\`\`\`text
${vp}
\`\`\`

\`\`\`text
${promo}
\`\`\`

## Candidate (proposed verdict: ${proposed_verdict})

\`\`\`json
${JSON.stringify(rec, null, 2)}
\`\`\`

## Linked coverage-unit checks

\`\`\`json
${JSON.stringify(units.map(u => ({ coverage_id: u.coverage_id, attack_class: u.attack_class, checks: u.local_checks })), null, 2)}
\`\`\`

## Architecture facts

${arq}

## Relevant companion validation blocks (verbatim)

${blocks || '(none: ordinary attack class only)'}

## Prior records with the same fingerprint

None (first run).

## report-schema.json branches (verbatim)

confirmed:
\`\`\`json
${JSON.stringify(schema.items.oneOf[0], null, 2)}
\`\`\`

needs_validation:
\`\`\`json
${JSON.stringify(schema.items.oneOf[1], null, 2)}
\`\`\`

rejected:
\`\`\`json
${JSON.stringify(schema.items.oneOf[2], null, 2)}
\`\`\`
`;
  fs.writeFileSync(path.join(dir, 'PROMPT.md'), p);
  ids.push(vid + ' ' + c.fingerprint);
}
console.log(ids.join('\n'));
