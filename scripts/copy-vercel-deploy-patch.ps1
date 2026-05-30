# Copies CSS/asset-path fix files into deploy-patch/ for upload to GitHub (repo root).
$root = Split-Path (Split-Path $PSScriptRoot -Parent) -Parent
$site = Split-Path $PSScriptRoot -Parent
$out = Join-Path $site 'deploy-patch'
$files = @(
    'api\index.php',
    'vercel.json',
    'includes\environment.php',
    'includes\head.php',
    'includes\vercel-php-router.php'
)
if (Test-Path $out) { Remove-Item $out -Recurse -Force }
foreach ($rel in $files) {
    $src = Join-Path $site $rel
  if (-not (Test-Path $src)) { Write-Error "Missing $src"; exit 1 }
    $dest = Join-Path $out $rel
    New-Item -ItemType Directory -Force -Path (Split-Path $dest) | Out-Null
    Copy-Item $src $dest -Force
}
Write-Host "Patch ready: $out"
Write-Host "Upload these files to https://github.com/fastranking856-max/allenhouseghaziabad then Redeploy on Vercel (clear cache)."
