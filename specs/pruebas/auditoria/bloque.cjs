// Rutas: SKILL_DIR (la skill), OUT (carpeta de la corrida, fuera del repo), TARGET (repo auditado), WP_ROOT (WordPress del Local) y COMMIT. Ver LEEME.md.
// Extrae un bloque textual de un archivo de la skill. Uso: node bloque.cjs ARCHIVO.md "Nombre"
const fs = require('fs'); const path = require('path');
const [file, name] = process.argv.slice(2);
const dir = process.env.SKILL_DIR;
const lines = fs.readFileSync(path.join(dir, file), 'utf8').split(/\r?\n/);
let start = -1, kind = null;
for (let i = 0; i < lines.length; i++) {
  const l = lines[i];
  if (/^##+ /.test(l) && l.replace(/^#+ /, '').replace(/\s*\(.*$/, '').trim() === name) { start = i; kind = 'h'; break; }
  if (l.startsWith('**' + name + '**')) { start = i; kind = 'b'; break; }
}
if (start < 0) { console.error('NO ENCONTRADO: ' + file + '#' + name); process.exit(1); }
const level = kind === 'h' ? lines[start].match(/^#+/)[0].length : 0;
let end = lines.length;
for (let i = start + 1; i < lines.length; i++) {
  const l = lines[i];
  if (kind === 'h') { const m = l.match(/^(#+) /); if (m && m[1].length <= level) { end = i; break; } }
  else if (/^\*\*[A-Z]/.test(l) || /^#+ /.test(l)) { end = i; break; }
}
process.stdout.write(lines.slice(start, end).join('\n').trim() + '\n');
