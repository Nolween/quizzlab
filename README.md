# Installation

- Aller dans le dossier
    - `cd quizzlab`

## Back Elasticsearch

1. Télécharger elasticsearch:

[Download Elasticsearch](https://www.elastic.co/fr/downloads/elasticsearch)

1. Créer un dossier dans le disque qui rendra son contenu facile d’accès → C:/elasticsearch
2. Dézipper le contenu du fichier téléchargé dans ce dossier
3. Dans le terminal, se rendre dans le dossier `cd /elasticsearch/bin` et exécuter `elasticsearch`
4. Lors du premier lancement d’elasticsearch, une ligne indiquant le mot de passe généré devrait apparaitre:

   Password for the elastic user (reset with `bin/elasticsearch-reset-password -u elastic`):
   MOTDEPASSE

5. Pour le local, ce message peut apparaitre dans le
   terminal : `o.e.x.s.t.n.SecurityNetty4HttpServerTransport] [NOMDUPC] received plaintext http traffic on an https channel, closing connection Netty4HttpChannel`

   Cela veut dire que le SSL est activé, il faut le désactiver dans `/elasticsearch/config/elasticsearch.yml` >>

   **xpack.security.http.ssl:**

   **enabled: false**

6. Une fois cela réglé et le serveur lancé via la commande `elasticsearch`, pour se connecter à la BDD >> username:
   elastic - password: le mot de passe donné au premier lancement

## Back PHP

- Installation des librairies PHP (Laravel,...)
- `composer install`

- Configuration du fichier \quizzlab\.env

    - APP_..., DB_..., ELASTIC_..., MAIL\_...,

- Création d'une base de données Mysql (ex: quizzlab)

- Création de la structure de la DB avec jeu de données(Migration)
    - `php artisan migrate:refresh --seed`

- Création d'un lien concernant le storage public et le public
    - `php artisan storage:link`

## Front

- Installation des librairies
    - `npm install`

# Environnement de dévelopement

- Lancement du serveur Elasticsearch
    - Se rendre dans le dossier `cd /elasticsearch/bin` et exécuter `elasticsearch`

- Lancement du serveur php
    - `php artisan serve`

- Lancement du serveur websocket
    - `php artisan websockets:serve`

- Lancement du compilateur JS Vite
    - `npm run dev`

- Accès au projet: http://127.0.0.1:8000/
- Accès à Telescope pour le profiler du back: http://127.0.0.1:8000/telescope/requests
- Accès au serveur et voir les appels: http://127.0.0.1:8000/laravel-websockets

# Maintenance du projet

- Lancement du PHP CS Fixer, pour formater le code
    - `./vendor/bin/pint`

- Lancement de l'analyze statique du code avec écriture dans un fichier
    - `./vendor/bin/phpstan analyse --generate-baseline`
- Lancement de l'analyze statique dans un fichier particulier
    - `./vendor/bin/phpstan analyse path/to/your/file.php`
- Lancement des test unitaires sous PEST PHP
    - `./vendor/bin/pest`
- Lancement d'un fichier de test unitaire particulier
    - `./vendor/bin/pest path/to/your/file.php`
- Lancement d'un test unitaire précis particulier
    - `./vendor/bin/pest --filter name_of_test`
