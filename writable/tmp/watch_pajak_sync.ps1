$stateFile = Join-Path $PSScriptRoot 'pajak_sync_state.json'
$prevDone = -1
$stale = 0

while ($true) {
  Start-Sleep -Seconds 30
  if (-not (Test-Path $stateFile)) { continue }
  try {
    $s = Get-Content $stateFile -Raw | ConvertFrom-Json
  } catch { continue }

  $done = [int]$s.done
  $mode = [string]$s.mode
  $fail = @($s.failed).Count

  if ($mode -eq 'live' -and $done -ge 55) {
    Write-Output "DONE"
    exit 0
  }
  if ($fail -ge 40) {
    Write-Output "FAILED"
    exit 1
  }
  if ($done -eq $prevDone) {
    $stale++
  } else {
    $stale = 0
    $prevDone = $done
  }
  if ($stale -ge 20) {
    Write-Output "FAILED"
    exit 1
  }
}
