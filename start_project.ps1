# Variables
$repoUrl = "https://github.com/madydmk/POC_ForestHill-2FA.git"
$projectDir = "POC_ForestHill-2FA"
$phpInstallerUrl = "https://windows.php.net/downloads/releases/php-7.4.28-Win32-vc15-x64.zip"
$phpZipPath = "$env:TEMP\php.zip"
$phpDir = "C:\php"

# Vérification des prérequis
Write-Output "Vérification des prérequis..."

# Vérifie si PHP est installé
if (-not (Get-Command php -ErrorAction SilentlyContinue)) {
    Write-Output "PHP n'est pas installé. Installation de PHP 7.4..."

    # Télécharge PHP
    Invoke-WebRequest -Uri $phpInstallerUrl -OutFile $phpZipPath

    # Décompresse PHP
    Expand-Archive -Path $phpZipPath -DestinationPath $phpDir -Force

    # Ajoute PHP au PATH
    $env:Path += ";$phpDir"

    # Vérifie si l'ajout au PATH est nécessaire
    if (-not (Get-Command php -ErrorAction SilentlyContinue)) {
        $userEnv = [System.Environment]::GetEnvironmentVariable('Path', 'User')
        [System.Environment]::SetEnvironmentVariable('Path', "$userEnv;$phpDir", 'User')
        Write-Output "PHP a été installé. Veuillez redémarrer PowerShell pour que les modifications prennent effet."
        exit
    }
}

# Vérifie si Composer est installé
if (-not (Get-Command composer -ErrorAction SilentlyContinue)) {
    Write-Output "Composer n'est pas installé. Installation de Composer..."

    # Télécharge et installe Composer
    $composerInstallerUrl = "https://getcomposer.org/installer"
    $composerInstallerPath = "$env:TEMP\composer-setup.php"
    Invoke-WebRequest -Uri $composerInstallerUrl -OutFile $composerInstallerPath
    php $composerInstallerPath --install-dir=$phpDir --filename=composer

    # Ajoute Composer au PATH
    $env:Path += ";$phpDir"
}

# Vérifie si Symfony CLI est installé
if (-not (Get-Command symfony -ErrorAction SilentlyContinue)) {
    Write-Output "Symfony CLI n'est pas installé. Veuillez installer Symfony CLI."
    exit
}

# Clone le repository
Write-Output "Clonage du repository..."
if (Test-Path $projectDir) {
    Write-Output "Le dossier $projectDir existe déjà. Suppression du dossier existant..."
    Remove-Item -Recurse -Force $projectDir
}
git clone $repoUrl
Set-Location $projectDir

# Installation des dépendances
Write-Output "Installation des dépendances..."
composer install

# Lancement du projet
Write-Output "Lancement du serveur Symfony..."
Start-Process "symfony" "server:start"

# Alternative pour lancer le serveur PHP intégré
# Write-Output "Lancement du serveur PHP intégré..."
# Start-Process "php" "-S 127.0.0.1:8000 -t public"

Write-Output "Le projet est maintenant en cours d'exécution."
