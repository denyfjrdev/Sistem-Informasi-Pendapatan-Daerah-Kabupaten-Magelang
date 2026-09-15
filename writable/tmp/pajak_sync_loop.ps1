$ErrorActionPreference = 'Continue'
$root = Split-Path (Split-Path $PSScriptRoot -Parent) -Parent
Set-Location $root
$envLine = Select-String -Path "$root\.env" -Pattern '^KEY_AUTH='
$token = $envLine.Line.Substring(9)
$headers = @{ Token = $token; 'Content-Type' = 'application/json' }
$log = "$PSScriptRoot\pajak_sync_loop.log"

function Write-Log($msg) {
  $line = "$(Get-Date -Format 'yyyy-MM-dd HH:mm:ss') $msg"
  Add-Content -Path $log -Value $line
}

Write-Log 'loop start'
while ($true) {
  try {
    $r = Invoke-WebRequest -Uri 'http://localhost/api/sync/tick' -Headers $headers -TimeoutSec 180 -UseBasicParsing
    $j = $r.Content | ConvertFrom-Json
    if ($j.busy) {
      Write-Log 'busy'
      Start-Sleep -Seconds 2
      continue
    }
    $job = $j.job
    $jenis = $job.type
    $bulan = $job.bulan
    $kec = $job.kd_kecamatan
    Write-Log ("mode=$($j.mode) remaining=$($j.remaining) done=$($j.done) job=$jenis $bulan $kec ok=$($j.hasil.ok)")
    if ($j.mode -eq 'live') {
      Write-Log 'full queue finished'
      exit 0
    }
    Start-Sleep -Seconds 1
  } catch {
    Write-Log ("error " + $_.Exception.Message)
    Start-Sleep -Seconds 10
  }
}
