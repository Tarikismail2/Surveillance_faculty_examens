# Étape 1 : Installation des dépendances PHP avec Composer
composer install

# Étape 2 : Installation des dépendances JavaScript avec NPM
npm install

# Étape 3 : Création du fichier "main.js"
# Créer un fichier nommé main.js dans le dossier /resources/js

# Étape 4 : Compilation des fichiers avec NPM
npm run build

# Étape 5 : Configuration de l'environnement
# Copier le fichier .env.example 
cp .env.example .env

# Étape 6 : Démarrage de XAMPP
# Démarrer le serveur XAMPP via le panneau de contrôle (Apache et MySQL)

# Étape 7 : Génération de la clé d'application Laravel
php artisan key:generate

# Étape 8 : Création de la base de données
# Aller dans phpMyAdmin et créer une nouvelle base de données avec le nom "surveillance_ucd"

# Étape 9 : Exécution des migrations Laravel
php artisan migrate

# Étape 10 : Insertion des données de base dans la base de données
# Insérer les données de base concernant les salles, enseignants, et départements à partir du fichier data.txt
# Copier les commandes SQL du fichier data.txt et les exécuter dans la section SQL de phpMyAdmin

# Étape 11 : Démarrage de l'application Laravel
php artisan serve