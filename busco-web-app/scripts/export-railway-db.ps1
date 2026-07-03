# Export Railway PostgreSQL to a local backup file
# Usage (PowerShell):
#   $env:DATABASE_URL = "postgresql://user:pass@host:port/railway"
#   .\scripts\export-railway-db.ps1

param(
    [string]$OutputFile = "backup.sql"
)

if (-not $env:DATABASE_URL) {
    Write-Error "Set DATABASE_URL to your Railway Postgres connection string first."
    exit 1
}

pg_dump $env:DATABASE_URL --no-owner --no-acl -f $OutputFile
Write-Host "Exported to $OutputFile"
