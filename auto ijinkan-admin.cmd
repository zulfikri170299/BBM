@echo off
setlocal

set "SCRIPT_PATH=%~dp0auto ijinkan.md"

if not exist "%SCRIPT_PATH%" (
    echo File tidak ditemukan:
    echo %SCRIPT_PATH%
    pause
    exit /b 1
)

powershell -NoProfile -ExecutionPolicy Bypass -Command ^
    "Start-Process PowerShell -Verb RunAs -ArgumentList '-NoProfile','-ExecutionPolicy','Bypass','-Command','& { $code = Get-Content -Raw -LiteralPath ''%SCRIPT_PATH%''; & ([ScriptBlock]::Create($code)) }'"

if errorlevel 1 (
    echo Gagal meminta hak administrator atau PowerShell tidak dapat dijalankan.
    pause
    exit /b 1
)

exit /b 0
