#!/usr/bin/env bash
# Export Railway PostgreSQL to a local backup file
# Usage:
#   export DATABASE_URL="postgresql://user:pass@host:port/railway"
#   ./scripts/export-railway-db.sh

set -euo pipefail

OUTPUT_FILE="${1:-backup.sql}"

if [[ -z "${DATABASE_URL:-}" ]]; then
  echo "Set DATABASE_URL to your Railway Postgres connection string first." >&2
  exit 1
fi

pg_dump "$DATABASE_URL" --no-owner --no-acl -f "$OUTPUT_FILE"
echo "Exported to $OUTPUT_FILE"
