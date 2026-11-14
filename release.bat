@echo off
REM ──────────────────────────────────────────────────────────────────────────
REM  Uhalisia wa Tanzania Portal — Release Package Builder
REM
REM  Creates a deployment-ready ZIP file containing all application code
REM  and vendor dependencies. No Composer or Node.js needed on production.
REM
REM  Usage:  release.bat [version]
REM  Example: release.bat 1.0.0
REM ──────────────────────────────────────────────────────────────────────────

setlocal enabledelayedexpansion

set VERSION=%1
if "%VERSION%"=="" (
    echo Usage: release.bat [version]
    echo Example: release.bat 1.0.0
    exit /b 1
)

set RELEASE_NAME=uhalisia-portal-v%VERSION%
set RELEASE_DIR=%TEMP%\%RELEASE_NAME%
set RELEASE_ZIP=%CD%\%RELEASE_NAME%.zip

echo.
echo ============================================================
echo   Uhalisia wa Tanzania Portal — Release Builder v%VERSION%
echo ============================================================
echo.

REM Clean previous build
if exist "%RELEASE_DIR%" (
    echo [1/6] Cleaning previous build...
    rmdir /s /q "%RELEASE_DIR%"
)

REM Create release directory
echo [2/6] Creating release directory...
mkdir "%RELEASE_DIR%"

REM Copy application files (excluding dev-only files)
echo [3/6] Copying application files...

REM Copy directories
for %%D in (app bootstrap config database docs public resources routes storage) do (
    xcopy /E /I /Q "%%D" "%RELEASE_DIR%\%%D" >nul 2>&1
)

REM Copy vendor (pre-built dependencies)
echo [4/6] Copying vendor dependencies (this may take a moment)...
xcopy /E /I /Q "vendor" "%RELEASE_DIR%\vendor" >nul 2>&1

REM Copy brand assets
if exist "brand" (
    xcopy /E /I /Q "brand" "%RELEASE_DIR%\brand" >nul 2>&1
)

REM Copy root files
for %%F in (.env.example .htaccess artisan composer.json composer.lock index.php) do (
    if exist "%%F" copy /Y "%%F" "%RELEASE_DIR%\%%F" >nul 2>&1
)

REM Ensure required storage directories exist
echo [5/6] Preparing storage directories...
mkdir "%RELEASE_DIR%\storage\app\documents" 2>nul
mkdir "%RELEASE_DIR%\storage\app\backups" 2>nul
mkdir "%RELEASE_DIR%\storage\framework\cache\data" 2>nul
mkdir "%RELEASE_DIR%\storage\framework\sessions" 2>nul
mkdir "%RELEASE_DIR%\storage\framework\views" 2>nul
mkdir "%RELEASE_DIR%\storage\logs" 2>nul

REM Create .gitkeep files in empty directories
echo. > "%RELEASE_DIR%\storage\app\documents\.gitkeep"
echo. > "%RELEASE_DIR%\storage\app\backups\.gitkeep"
echo. > "%RELEASE_DIR%\storage\framework\cache\data\.gitkeep"
echo. > "%RELEASE_DIR%\storage\framework\sessions\.gitkeep"
echo. > "%RELEASE_DIR%\storage\framework\views\.gitkeep"
echo. > "%RELEASE_DIR%\storage\logs\.gitkeep"

REM Remove dev-only files from release
if exist "%RELEASE_DIR%\storage\logs\laravel.log" del "%RELEASE_DIR%\storage\logs\laravel.log"
if exist "%RELEASE_DIR%\.env" del "%RELEASE_DIR%\.env"

REM Create the ZIP
echo [6/6] Creating ZIP archive...
if exist "%RELEASE_ZIP%" del "%RELEASE_ZIP%"
powershell -NoProfile -Command "Compress-Archive -Path '%RELEASE_DIR%\*' -DestinationPath '%RELEASE_ZIP%' -Force"

REM Cleanup
rmdir /s /q "%RELEASE_DIR%"

echo.
echo ============================================================
echo   Release package created successfully!
echo   File: %RELEASE_ZIP%
echo ============================================================
echo.
echo Next steps:
echo   1. Upload ZIP to production server
echo   2. Extract to web directory
echo   3. Copy .env.example to .env and configure
echo   4. See docs/DEPLOY_CPANEL.md for full instructions
echo.

endlocal
