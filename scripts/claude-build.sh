#!/usr/bin/env bash
# Opens Claude Code in this project with the kickoff prompt loaded.
# Claude Code reads CLAUDE.md on its own; the prompt tells it where to start.
set -euo pipefail
cd "$(dirname "$0")/.."
command -v claude >/dev/null || { echo "Claude Code is not installed. See https://docs.claude.com/en/docs/claude-code/overview"; exit 1; }
PHASE="${1:-}"
PROMPT="$(cat docs/KICKOFF_PROMPT.md)"
[ -n "$PHASE" ] && PROMPT="${PROMPT}

Start at phase ${PHASE} of docs/BUILD_BRIEF.md. Check which earlier phases are already done before you begin."
exec claude "$PROMPT"
