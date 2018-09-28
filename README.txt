Etape 1 : Créer un utilisateur dans mysql avec les identifiants .env

    CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';

Etape 2 : Accorder les droits à l'utilisateur

    GRANT ALL PRIVILEGES ON * . * TO 'newuser'@'localhost';
    FLUSH PRIVILEGES;

Etape 3 : Charger les dépendances

    composer install

Etape 4 : Créer la base de données

    php bin/console doctrine:database:create

Etape 5 : Effectuer les migrations

    php bin/console make:migration

Etape 6 : Appliquer les migrations

    php bin/console doctrine:migrations:migrate

Etape 7 : Lancer l'application

    php bin/console server:start