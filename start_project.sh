#!/bin/bash

# Variables
REPO_URL="https://github.com/madydmk/POC_ForestHill-2FA.git"
PROJECT_DIR="POC_ForestHill-2FA"

# Vérification des prérequis
echo "Vérification des prérequis..."

# Vérifie si PHP est installé
if ! command -v php &> /dev/null
then
    echo "PHP n'est pas installé. Veuillez installer PHP 7.4 ou supérieur."
    exit
fi

# Vérifie si Composer est installé
if ! command -v composer &> /dev/null
then
    echo "Composer n'est pas installé. Veuillez installer Composer."
    exit
fi

# Vérifie si Symfony CLI est installé
if ! command -v symfony &> /dev/null
then
    echo "Symfony CLI n'est pas installé. Veuillez installer Symfony CLI."
    exit
fi

# Clone le repository
echo "Clonage du repository..."
if [ -d "$PROJECT_DIR" ]; then
    echo "Le dossier $PROJECT_DIR existe déjà. Suppression du dossier existant..."
    rm -rf "$PROJECT_DIR"
fi
git clone "$REPO_URL"
cd "$PROJECT_DIR" || exit

# Installation des dépendances
echo "Installation des dépendances..."
composer install

# Lancement du projet
echo "Lancement du serveur Symfony..."
symfony server:start

# Alternative pour lancer le serveur PHP intégré
# echo "Lancement du serveur PHP intégré..."
# php -S 127.0.0.1:8000 -t public

echo "Le projet est maintenant en cours d'exécution."
