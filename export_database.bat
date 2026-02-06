@echo off
REM Script untuk export database MySQL
REM Pastikan MySQL sudah terinstall dan ada di PATH

echo ========================================
echo Export Database untuk InfinityFree
echo ========================================
echo.

REM Set variables
set DB_NAME=blogapp_laravel
set DB_USER=root
set DB_PASS=
set OUTPUT_FILE=database_export_%date:~-4,4%%date:~-10,2%%date:~-7,2%_%time:~0,2%%time:~3,2%%time:~6,2%.sql
set OUTPUT_FILE=%OUTPUT_FILE: =0%

echo Database: %DB_NAME%
echo Output file: %OUTPUT_FILE%
echo.

REM Export database
echo Exporting database...
mysqldump -u %DB_USER% %DB_NAME% > %OUTPUT_FILE%

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo SUCCESS! Database exported to:
    echo %OUTPUT_FILE%
    echo ========================================
    echo.
    echo Silakan upload file ini ke phpMyAdmin di InfinityFree
    echo.
) else (
    echo.
    echo ========================================
    echo ERROR! Export failed.
    echo ========================================
    echo.
    echo Pastikan:
    echo 1. MySQL sudah terinstall
    echo 2. Database '%DB_NAME%' ada
    echo 3. Username dan password benar
    echo.
    echo Atau export manual via phpMyAdmin:
    echo 1. Buka http://localhost/phpmyadmin
    echo 2. Pilih database '%DB_NAME%'
    echo 3. Klik tab 'Export'
    echo 4. Klik 'Go'
    echo.
)

pause
