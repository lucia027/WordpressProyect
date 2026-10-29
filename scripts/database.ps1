param(
    [Parameter(Mandatory = $true)]
    [ValidateSet('export', 'import')]
    [string]$Action
)

$ErrorActionPreference = 'Stop'
Set-Location (Split-Path $PSScriptRoot -Parent)
$utf8 = New-Object System.Text.UTF8Encoding($false)
$OutputEncoding = $utf8
[Console]::OutputEncoding = $utf8

function Export-Database([string]$Destination) {
    # Stdin conserva las comillas; el comentario final absorbe el CRLF de Windows.
    $command = 'exec mariadb-dump --user="$MARIADB_USER" --password="$MARIADB_PASSWORD" --single-transaction --skip-comments --skip-dump-date --no-tablespaces --default-character-set=utf8mb4 "$MARIADB_DATABASE" # fin'
    $sql = $command | & docker compose exec -T db sh
    if ($LASTEXITCODE -ne 0) { throw 'No se pudo exportar la base de datos.' }
    [System.IO.File]::WriteAllText($Destination, (($sql -join "`n") + "`n"), $utf8)
}

$root = (Get-Location).Path
$shared = Join-Path $root 'database/wordpress.sql'

if ($Action -eq 'export') {
    Export-Database $shared
    Write-Host 'Exportacion creada: database/wordpress.sql. Revisala antes de subirla a Git.'
} else {
    if (-not (Test-Path $shared)) { throw 'Falta database/wordpress.sql. Pide a tu companero que exporte y suba su copia.' }
    Write-Warning 'Esto SUSTITUYE la base de datos local por la copia compartida. No mezcla cambios.'
    if ((Read-Host 'Escribe IMPORTAR para continuar') -cne 'IMPORTAR') {
        Write-Host 'Importacion cancelada.'
        exit 0
    }
    New-Item -ItemType Directory -Force -Path 'backups' | Out-Null
    $backup = Join-Path $root ('backups/before-import-' + (Get-Date -Format 'yyyyMMdd-HHmmss-fff') + '.sql')
    Export-Database $backup
    & docker compose cp $shared 'db:/tmp/wordpress-import.sql'
    if ($LASTEXITCODE -ne 0) { throw 'No se pudo copiar el SQL al contenedor.' }
    try {
        $command = 'exec mariadb --user="$MARIADB_USER" --password="$MARIADB_PASSWORD" --default-character-set=utf8mb4 "$MARIADB_DATABASE" < /tmp/wordpress-import.sql # fin'
        $command | & docker compose exec -T db sh
        if ($LASTEXITCODE -ne 0) { throw "Importacion fallida. Copia anterior: $backup" }
    } finally {
        & docker compose exec -T db rm -f /tmp/wordpress-import.sql
    }
    Write-Host "Importacion completada. Copia anterior: $backup"
}
