// Rutas: SKILL_DIR (la skill), OUT (carpeta de la corrida, fuera del repo), TARGET (repo auditado), WP_ROOT (WordPress del Local) y COMMIT. Ver LEEME.md.
// Envoltorio para Windows: lee el archivo (escrito solo por el parent) sin O_NOFOLLOW
// y aplica exactamente las reglas exportadas por los validadores de la skill.
const fs = require('fs'), path = require('path');
const [tipo, file] = process.argv.slice(2);
const SK = process.env.SKILL_DIR;
const st = fs.lstatSync(file);
if (!st.isFile() || st.isSymbolicLink() || st.nlink !== 1 || st.size > 5 * 1024 * 1024) { console.error('FAIL: input must be a regular, non-linked file under 5 MiB'); process.exit(1); }
const data = JSON.parse(fs.readFileSync(file, 'utf8'));
let errors;
if (tipo === 'ledger') errors = require(path.join(SK, 'validate-coverage-ledger.cjs')).validateDocument(data);
else { const m = require(path.join(SK, 'validate-findings.cjs')); errors = m.validateDocument(data, JSON.parse(fs.readFileSync(path.join(SK, 'report-schema.json'), 'utf8'))); }
errors = Array.from(errors || []);
if (errors.length) { console.error(errors.join('\n')); console.error('FAIL: ' + errors.length + ' error(s)'); process.exit(1); }
console.log('PASS: ' + (Array.isArray(data) ? data.length : 0) + ' ' + tipo + ' entries valid');
