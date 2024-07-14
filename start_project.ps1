# Variables
$repoUrl = "https://github.com/madydmk/POC_ForestHill-2FA.git"
$projectDir = "POC_ForestHill-2FA"
$phpInstallerUrl = "https://windows.php.net/downloads/releases/php-7.4.28-Win32-vc15-x64.zip"
$phpZipPath = "$env:TEMP\php.zip"
$phpDir = "C:\php"

# V�rification des pr�requis
Write-Output "V�rification des pr�requis..."

# V�rifie si PHP est install�
if (-not (Get-Command php -ErrorAction SilentlyContinue)) {
    Write-Output "PHP n'est pas install�. Installation de PHP 7.4..."

    # T�l�charge PHP
    Invoke-WebRequest -Uri $phpInstallerUrl -OutFile $phpZipPath

    # D�compresse PHP
    Expand-Archive -Path $phpZipPath -DestinationPath $phpDir -Force

    # Ajoute PHP au PATH
    $env:Path += ";$phpDir"

    # V�rifie si l'ajout au PATH est n�cessaire
    if (-not (Get-Command php -ErrorAction SilentlyContinue)) {
        $userEnv = [System.Environment]::GetEnvironmentVariable('Path', 'User')
        [System.Environment]::SetEnvironmentVariable('Path', "$userEnv;$phpDir", 'User')
        Write-Output "PHP a �t� install�. Veuillez red�marrer PowerShell pour que les modifications prennent effet."
        exit
    }
}

# V�rifie si Composer est install�
if (-not (Get-Command composer -ErrorAction SilentlyContinue)) {
    Write-Output "Composer n'est pas install�. Installation de Composer..."

    # T�l�charge et installe Composer
    $composerInstallerUrl = "https://getcomposer.org/installer"
    $composerInstallerPath = "$env:TEMP\composer-setup.php"
    Invoke-WebRequest -Uri $composerInstallerUrl -OutFile $composerInstallerPath
    php $composerInstallerPath --install-dir=$phpDir --filename=composer

    # Ajoute Composer au PATH
    $env:Path += ";$phpDir"
}

# V�rifie si Symfony CLI est install�
if (-not (Get-Command symfony -ErrorAction SilentlyContinue)) {
    Write-Output "Symfony CLI n'est pas install�. Veuillez installer Symfony CLI."
    exit
}

# Clone le repository
Write-Output "Clonage du repository..."
if (Test-Path $projectDir) {
    Write-Output "Le dossier $projectDir existe d�j�. Suppression du dossier existant..."
    Remove-Item -Recurse -Force $projectDir
}
git clone $repoUrl
Set-Location $projectDir

# Installation des d�pendances
Write-Output "Installation des d�pendances..."
composer install

# Lancement du projet
Write-Output "Lancement du serveur Symfony..."
Start-Process "symfony" "server:start"

# Alternative pour lancer le serveur PHP int�gr�
# Write-Output "Lancement du serveur PHP int�gr�..."
# Start-Process "php" "-S 127.0.0.1:8000 -t public"

Write-Output "Le projet est maintenant en cours d'ex�cution."
