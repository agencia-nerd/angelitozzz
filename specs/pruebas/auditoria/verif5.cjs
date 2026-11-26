// Rutas: SKILL_DIR (la skill), OUT (carpeta de la corrida, fuera del repo), TARGET (repo auditado), WP_ROOT (WordPress del Local) y COMMIT. Ver LEEME.md.
// Genera prompts de fase 5 desde findings.json (solo confirmed y needs_validation).
const fs = require('fs'), path = require('path');
const SK = process.env.SKILL_DIR, OUT = process.env.OUT;
const val = fs.readFileSync(path.join(SK, 'VALIDATION-AND-REPORTING.md'), 'utf8');
const i = val.indexOf('### Phase 5'), j = val.indexOf('### Phase 6');
const fase5 = val.slice(i, j);
const schema = require(path.join(SK, 'report-schema.json'));
const F = JSON.parse(fs.readFileSync(path.join(OUT, 'findings.json'), 'utf8'));
let n = 0; const out = [];
for (const r of F) {
  if (r.verdict === 'rejected') continue; n++;
  const vid = 'v5-' + String(n).padStart(2, '0');
  const dir = path.join(OUT, 'agents', vid); fs.mkdirSync(path.join(dir, 'scratch'), { recursive: true }); fs.mkdirSync(path.join(dir, 'artifacts'), { recursive: true });
  fs.writeFileSync(path.join(dir, 'PROMPT.md'), `# Phase 5 record verifier ${vid}

You are a fresh research verifier. You did not hunt or validate this record. Check the structured record below against current source; do not trust its wording.

TARGET (read-only): \`${process.env.TARGET}\` (commit ${process.env.COMMIT || "HEAD"}). You may read WordPress core (\`${process.env.WP_ROOT}/wp-includes\`, \`wp-login.php\`) and WooCommerce (\`.../wp-content/plugins/woocommerce\`) read-only. No OS-enforced sandbox exists: never execute target code, PHP, tests or composer; no network; no writes outside \`${dir.split(String.fromCharCode(92)).join('/')}/scratch/\`.

## Phase 5 instructions (verbatim from the skill)

${fase5}

## Record

\`\`\`json
${JSON.stringify(r, null, 2)}
\`\`\`

## Schema branches (verbatim)

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

Return exactly one JSON object and no prose: {"decision":"verified","fingerprint":"..."} or {"decision":"replace","reason":"...","record":{...}}.
`);
  out.push(vid + ' ' + r.fingerprint);
}
console.log(out.join('\n'));
