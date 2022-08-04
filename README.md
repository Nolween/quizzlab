
# Installation

* Aller dans le dossier
  * `cd quizzlab`

## Back

* Installation des librairies PHP (Laravel,...)
* `composer install`

* Configuration du fichier \quizzlab\.env
  * APP_..., DB_..., MAIL_..., 

* Création d'une base de données Mysql (ex: quizzlab)

* Création de la structure de la DB (Migration)
  * `php artisan migrate:refresh --seed`

## Front

* Installation des librairies
  * `npm install`

# Environnement de dévelopement

* Lancement du serveur php
  * `php artisan serve`

* Lancement du compilateur JS Vite
  * `npm run dev`

* Accès au projet: http://127.0.0.1:8000/
* Accès à Telescope pour le profiler du back: http://127.0.0.1:8000/telescope/requests

# Environnement de production