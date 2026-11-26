# Scripts de la auditoría de seguridad (skill `security-audit` de Cloudflare)

Ayudantes para correr la skill [`security-audit`](https://github.com/cloudflare/security-audit-skill) en Windows. Con ellos se auditó el plugin `angelitozzz-cursos` (corrida 1, 2026-11-26). Son herramientas del equipo: están en `specs/`, que no se sube al servidor. No escriben nada en el repo: todo lo de la corrida va a la carpeta `OUT`, fuera del repo.

## Requisitos

- **La skill:** se instala con `pnpm dlx skills add https://github.com/cloudflare/security-audit-skill --skill security-audit --global`. Queda en `~/.agents/skills/security-audit`, con un enlace desde `~/.claude/skills/`. La versión revisada es el commit `c1c8a8c`: antes de usar otra, leer su código.
- **Node 22 o más nuevo.**

## Variables

| Variable | Qué es | Ejemplo |
| --- | --- | --- |
| `SKILL_DIR` | Carpeta de la skill | `~/.agents/skills/security-audit` |
| `OUT` | Carpeta de la corrida, **fuera del repo** | `~/security-audit-skill/angelitozzz/run-1` |
| `TARGET` | Repo auditado (ruta absoluta) | `C:/Users/<usuario>/Local Sites/angelitozzz/app/public/wp-content/themes/angelitozzz` |
| `WP_ROOT` | WordPress del Local (núcleo y WooCommerce para leer) | `C:/Users/<usuario>/Local Sites/angelitozzz/app/public` |
| `COMMIT` | Commit auditado (va en los prompts y en los informes) | `git -C "$TARGET" rev-parse --short HEAD` |
| `REPO_NOMBRE` | Nombre en los informes | `angelitozzz` |
| `ORDEN` | (opcional) Huellas separadas por coma, en orden de prioridad, para `informes.cjs` | |

## Scripts, en el orden de la skill

1. **`bloque.cjs ARCHIVO.md "Clase"`:** copia textual de un bloque de la skill (clase de ataque o sección "Core discipline", "Universal moves" o "Validation rules"). Lo usan los demás scripts.
2. **`plan.cjs`:** escribe `coverage-ledger.json` y `agents/<id>/PROMPT.md` de cada cazador de la ola 1. **La lista `H` es la del plugin**: para otro repo se reemplaza con las superficies de su reconocimiento.
3. **`plan2.cjs`:** agrega a ese ledger las unidades de una ola 2 (lista `N`, también del plugin) y los prompts de sus cazadores. Sin borrar lo anterior.
4. **`validar.cjs ledger|findings <archivo>`:** aplica las mismas reglas que exportan los validadores de la skill. Los de la skill no leen archivos en Windows: exigen `O_NOFOLLOW`. Correrlo después de cada cambio del ledger y de `findings.json`.
5. **`guardar.cjs <agente> < spec.json`:** arma el resultado de un cazador a partir de un JSON compacto (las unidades por clase, las comprobaciones y los candidatos). Se necesita porque las transcripciones de los subagentes salen vacías: el JSON que devuelve cada agente lo guarda el coordinador.
6. **`consolidar.cjs <agente> <resultado.json>`:** aplica ese resultado al ledger y junta los candidatos y las superficies sin cubrir en `OUT/_estado/`.
7. **`verif.cjs`:** escribe los prompts de la fase 3, un verificador por candidato, en `agents/v3-NN/`.
8. **`verif5.cjs`:** escribe los prompts de la fase 5, uno por registro de `findings.json` que no esté rechazado, en `agents/v5-NN/`.
9. **`informes.cjs`:** fase 6. Arma `NEEDS-VALIDATION.md`, `FINDINGS-DETAIL.md` y `_estado/hardening.txt`. `REPORT.md` lo escribe el coordinador.

Cada agente recibe un prompt corto que le pide leer su `PROMPT.md` entero y devolver solo el JSON del contrato.

## Presupuesto (perfil `standard`)

Lo que se gasta:

- 4 agentes de reconocimiento;
- los cazadores de cada ola;
- 1 crítico por ola, más 1 crítico final;
- **2 verificadores por candidato** (fases 3 y 5).

Por ejemplo, la corrida del plugin usó 36 agentes: 4 de reconocimiento, 12 cazadores en 2 olas, 3 críticos, 9 verificadores en la fase 3 y 8 en la fase 5. Si los candidatos no entran en el tope, preguntarle al usuario antes de subirlo.

## Sin sandbox

En Windows no hay sandbox del sistema operativo, así que el código auditado **no se ejecuta**. Todo se hace leyendo el código, y lo que necesita ejecutarse queda como `needs_validation`. Después se valida con sondas en el Local, con datos de prueba que se borran al terminar. El checklist (F7 → "Auditoría de seguridad completa") guarda cómo se hizo con el plugin.
