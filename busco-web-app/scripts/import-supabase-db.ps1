# Import backup into Supabase PostgreSQL
# Usage (PowerShell):
#   $env:DATABASE_URL = "postgresql://postgres:pass@db.xxx.supabase.co:5432/postgres?sslmode=require"
#   .\scripts\import-supabase-db.ps1 -InputFile backup.sql

param(
    [Parameter(Mandatory = $true)]
    [string]$InputFile
)

if (-not $env:DATABASE_URL) {
    Write-Error "Set DATABASE_URL to your Supabase connection string first."
    exit 1
}

if (-not (Test-Path $InputFile)) {
    Write-Error "File not found: $InputFile"
    exit 1
}

psql $env:DATABASE_URL -f $InputFile
Write-Host "Import complete from $InputFile"
