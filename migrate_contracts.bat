@echo off
echo ========================================
echo Migration du systeme de gestion des contrats
echo ========================================
echo.

echo Etape 1/3: Creation de la table contracts...
php artisan migrate --path=database/migrations/2025_12_15_000001_create_contracts_table.php
if %errorlevel% neq 0 (
    echo ERREUR lors de la creation de la table contracts
    pause
    exit /b 1
)
echo [OK] Table contracts creee avec succes
echo.

echo Etape 2/3: Creation de la table contract_histories...
php artisan migrate --path=database/migrations/2025_12_15_000002_create_contract_histories_table.php
if %errorlevel% neq 0 (
    echo ERREUR lors de la creation de la table contract_histories
    pause
    exit /b 1
)
echo [OK] Table contract_histories creee avec succes
echo.

echo Etape 3/3: Migration des donnees depuis users vers contracts...
php artisan migrate --path=database/migrations/2025_12_15_000003_migrate_contracts_from_users.php
if %errorlevel% neq 0 (
    echo ERREUR lors de la migration des donnees
    pause
    exit /b 1
)
echo [OK] Donnees migrees avec succes
echo.

echo ========================================
echo Migration terminee avec succes !
echo ========================================
echo.
echo Vous pouvez maintenant acceder au systeme de contrats sur : /contracts
echo.
pause
