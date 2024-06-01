# Projet Symfony 6.4.7 - README

## Prérequis technologiques

PHP : Version minimale 8.2
MySQL : Version 5.7.44
Composer : Version 2.0 ou supérieure
Symfony CLI : Optionnel, pour faciliter certaines commandes

## Mise en place de l'environnement

### Installation de Composer

Si Composer n'est pas installé sur votre machine, vous pouvez l'installer en suivant les instructions [ici](https://getcomposer.org/download/).

### Installation de Symfony CLI (Optionnel)

Pour installer Symfony CLI, vous pouvez suivre les instructions [ici](https://symfony.com/download).

### Cloner le projet

Clonez le projet depuis le dépôt Git :

`git clone https://github.com/Galypso-Unreal/ToDo-Co.git`

## Configuration de l'environnement

Créez un fichier .env.local en copiant le fichier .env fourni :

`cp .env .env.local`

Modifiez le fichier .env.local pour y inclure vos paramètres spécifiques, notamment la connexion à la base de données et le secret token :

`DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"`
`APP_SECRET="votre_secret_token"`

## Installation des dépendances

Installez les dépendances PHP du projet avec Composer :

`composer install`

## Mise en place de la base de données

Créez la base de données et exécutez les migrations :

`php bin/console doctrine:database:create`
`php bin/console doctrine:migrations:migrate`

## Installation des fixtures (données de test)

Pour utiliser des fixtures pour peupler la base de données avec des données de test, exécutez la commande suivante :

`php bin/console doctrine:fixtures:load`

## Tests avec PHPUnit

Si vous voulez exécuter tests unitaires, assurez-vous d'avoir PHPUnit installé et exécutez les tests avec :

`vendor/bin/phpunit`

## Contribuer au projet

Pour contribuer au projet, veuillez suivre les étapes dans le fichier "Comment contribuer au développement du projet ?" à la racine du dépot git

## Documentation

Pour plus d'informations sur la façon d'utiliser Symfony, consultez la documentation officielle que vous pouvez retrouver [ici](https://symfony.com/doc/current/index.html).

## Autres configurations locales

Serveur de développement : Vous pouvez lancer un serveur de développement local en utilisant la commande :

`symfony serve`

Variables d'environnement supplémentaires : Ajoutez toute autre configuration nécessaire dans votre fichier .env.local.

Merci de contribuer et de faire partie de notre projet !
