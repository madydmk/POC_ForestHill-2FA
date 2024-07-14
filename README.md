# Projet 2FA Symfony
Contexte: Partiel de M2 CTO &amp; TechLead

## Description
Ce projet est une preuve de concept (POC) pour un système d'authentification sécurisé utilisant Symfony. Il inclut des fonctionnalités pour l'inscription des utilisateurs, la connexion et l'authentification à deux facteurs (2FA). Le projet est conçu pour démontrer des mesures de sécurité améliorées et garantir que les utilisateurs ne peuvent accéder à certaines parties du site qu'après avoir passé un processus de 2FA sécurisé.

## Fonctionnalités
- Inscription des utilisateurs
- Connexion des utilisateurs avec chiffrement des mots de passe
- Configuration et confirmation de l'authentification à deux facteurs (2FA)
- Accès sécurisé aux zones protégées du site
- Design responsive

## Pré-requis
- PHP 8.2 ou supérieur
- Composer

## Installation

### Automatisée: avec le script fourni
Récupérer un des scripts "start_project" et le lancer dans PowerShell pour le .ps1 et dans un cmd linux ou Git Bash pour le .sh.
Celui-ci clonera le repo GIT, installera les dépendances et lancera le serveur.

### Étape 2 : Manuellement
Utilisez Composer pour installer les dépendances nécessaires. Ouvrez un terminal, naviguez vers le répertoire du projet et exécutez les commandes suivantes :

## Installation

1. Clonez le repository :
```bash
git https://github.com/madydmk/POC_ForestHill-2FA.git 
cd POC_ForestHill-2FA
```
2. Installez les dépendances  :
```bash
composer install
```
3. Mise à jour de la base de données existante
```bash
php bin/console d:s:u -f
```
## Lancement du projet
```bash
symfony server:start
```
Ou
```bash
php -S 127.0.0.1:8000 -t public
```
Accédez à l'URL du serveur (par défaut http://localhost:8000).