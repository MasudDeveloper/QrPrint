$sourceFile = Join-Path $PSScriptRoot "PrintShebaAgent.cs"
$outputExe = Join-Path $PSScriptRoot "PrintShebaAgent.exe"
$publicDownloadDir = Join-Path $PSScriptRoot "..\public\downloads"
$publicExe = Join-Path $publicDownloadDir "PrintShebaAgent.exe"

Write-Host "Building PrintSheba Windows Native Agent (.EXE)..." -ForegroundColor Cyan

if (-not (Test-Path $sourceFile)) {
    Write-Error "Source file not found: $sourceFile"
    exit 1
}

$csharpCode = Get-Content -Path $sourceFile -Raw -Encoding UTF8

$iconFile = Join-Path $PSScriptRoot "app_icon.ico"
$provider = New-Object Microsoft.CSharp.CSharpCodeProvider
$parameters = New-Object System.CodeDom.Compiler.CompilerParameters
$parameters.GenerateExecutable = $true
$parameters.OutputAssembly = $outputExe

if (Test-Path $iconFile) {
    $parameters.CompilerOptions = "/target:winexe /platform:anycpu /optimize /win32icon:`"$iconFile`""
} else {
    $parameters.CompilerOptions = "/target:winexe /platform:anycpu /optimize"
}

$parameters.ReferencedAssemblies.Add("System.dll") | Out-Null
$parameters.ReferencedAssemblies.Add("System.Core.dll") | Out-Null
$parameters.ReferencedAssemblies.Add("Microsoft.CSharp.dll") | Out-Null
$parameters.ReferencedAssemblies.Add("System.Drawing.dll") | Out-Null
$parameters.ReferencedAssemblies.Add("System.Windows.Forms.dll") | Out-Null
$parameters.ReferencedAssemblies.Add("System.Web.Extensions.dll") | Out-Null

$results = $provider.CompileAssemblyFromSource($parameters, $csharpCode)

if ($results.Errors.Count -eq 0) {
    Write-Host "SUCCESS: PrintShebaAgent.exe compiled successfully!" -ForegroundColor Green
    $fileInfo = Get-Item $outputExe
    Write-Host "File Size: $([math]::Round($fileInfo.Length / 1KB, 2)) KB" -ForegroundColor Green

    if (-not (Test-Path $publicDownloadDir)) {
        New-Item -ItemType Directory -Path $publicDownloadDir -Force | Out-Null
    }
    Copy-Item -Path $outputExe -Destination $publicExe -Force
    Write-Host "Public Web Download Available: $publicExe" -ForegroundColor Green

    $defaultConfig = Join-Path $PSScriptRoot "config.json"
    if (-not (Test-Path $defaultConfig)) {
        $json = '{"server_url":"http://localhost:8000","api_token":"shop_tok_demo12345","bw_printer":"","color_printer":"","poll_interval_sec":3,"auto_start":false,"minimize_to_tray":true}'
        Set-Content -Path $defaultConfig -Value $json -Encoding UTF8
        Write-Host "Created default config.json" -ForegroundColor DarkGray
    }

    Write-Host "PrintShebaAgent.exe is ready!" -ForegroundColor Cyan
} else {
    Write-Host "Compilation failed with errors:" -ForegroundColor Red
    for ($i = 0; $i -lt $results.Errors.Count; $i++) {
        $e = $results.Errors[$i]
        Write-Host ($e.ToString()) -ForegroundColor Red
    }
    exit 1
}
