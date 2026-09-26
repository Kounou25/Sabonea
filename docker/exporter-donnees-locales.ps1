# Exporte les données de ce PC pour les importer sur le serveur Docker (voir DEPLOIEMENT.md) :
#   - sabonea-base.sql        : la base PostgreSQL locale (pages, textes, réglages, comptes, candidatures, messages...) ;
#   - sabonea-fichiers.tar.gz : les images envoyées depuis le back-office et les documents des fournisseurs.
# Ces fichiers contiennent des données personnelles : à garder privés, et à supprimer une fois importés.
#
# Utilisation (PowerShell, depuis le dossier du projet) :
#   powershell -ExecutionPolicy Bypass -File docker\exporter-donnees-locales.ps1

param(
    [string] $Destination = (Join-Path ([Environment]::GetFolderPath('Desktop')) 'sabonea-export'),
    [string] $PgBin = 'C:\Program Files\PostgreSQL\16\bin'
)

$ErrorActionPreference = 'Stop'
$project = Split-Path -Parent $PSScriptRoot

# Connexion à la base locale : celle du .env de ce PC.
$settings = @{}
foreach ($line in Get-Content (Join-Path $project '.env')) {
    if ($line -match '^\s*([A-Z_]+)\s*=\s*(.*)$') {
        $settings[$Matches[1]] = $Matches[2].Trim().Trim('"')
    }
}

New-Item -ItemType Directory -Force -Path $Destination | Out-Null
$database = Join-Path $Destination 'sabonea-base.sql'
$files = Join-Path $Destination 'sabonea-fichiers.tar.gz'

Write-Host "Export de la base $($settings['DB_DATABASE'])..."
$env:PGPASSWORD = $settings['DB_PASSWORD']
& (Join-Path $PgBin 'pg_dump.exe') `
    --host $settings['DB_HOST'] --port $settings['DB_PORT'] --username $settings['DB_USERNAME'] --dbname $settings['DB_DATABASE'] `
    --no-owner --no-privileges --clean --if-exists --encoding UTF8 `
    --exclude-table-data cache --exclude-table-data cache_locks --exclude-table-data sessions `
    --exclude-table-data jobs --exclude-table-data job_batches --exclude-table-data failed_jobs `
    --file $database
Remove-Item Env:PGPASSWORD
if ($LASTEXITCODE -ne 0) { throw 'pg_dump a échoué.' }

Write-Host 'Export des fichiers (storage/app)...'
# Sans les fichiers temporaires ni les fiches PDF en cache : ils se recréent tout seuls.
tar -czf $files -C (Join-Path $project 'storage\app') `
    --exclude 'private/livewire-tmp' --exclude 'pdf-cache' --exclude 'pdf-thumbnails' `
    public private
if ($LASTEXITCODE -ne 0) { throw 'tar a échoué.' }

Write-Host ''
Write-Host "Terminé : $Destination"
Get-ChildItem $Destination | Format-Table Name, @{ Label = 'Taille'; Expression = { '{0:N1} Mo' -f ($_.Length / 1MB) } } -AutoSize
