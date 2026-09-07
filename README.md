# Réponses aux questions

# Partie 1

# 1. Quel est le rôle de Composer ?

Composer est le gestionnaire de dépendances de PHP.

Il permet de :

installer des bibliothèques PHP ;
gérer leurs versions ;
gérer les dépendances du projet ;
générer l'autoloading des classes ;
utiliser PSR-4 pour charger automatiquement les classes.
Dans ton projet, par exemple :

require_once __DIR__ . '/vendor/autoload.php';

permet de charger automatiquement les classes configurées dans Composer.

# 2. Quelle différence entre require et require-dev ?

require contient les dépendances nécessaires au fonctionnement de l'application.

Exemple :

"require": {
    "php": "^8.3"
}

require-dev contient les dépendances utilisées uniquement pendant le développement, par exemple PHPUnit :

"require-dev": {
    "phpunit/phpunit": "^11.0"
}

En production, on peut installer uniquement les dépendances nécessaires avec :

composer install --no-dev
# 3. Pourquoi versionner composer.lock ?

composer.lock contient les versions exactes des dépendances installées.

Cela permet à toute l'équipe d'avoir les mêmes versions.

Par exemple, si ton projet utilise :

PHPUnit 11.5.30

le fichier composer.lock permet de conserver précisément cette version plutôt que de laisser Composer choisir une version différente.

Donc :

composer.json  → quelles dépendances sont demandées
composer.lock  → quelles versions exactes sont installées
# 4. Pourquoi ne versionne-t-on pas vendor/ ?

Parce que vendor/ contient les bibliothèques installées par Composer.

Il peut être très volumineux et est reproductible à partir de :

composer.json
composer.lock

Une autre personne récupère le projet puis fait simplement :

composer install

et Composer recrée vendor/.

Donc :

GitHub
│
├── composer.json       ✅
├── composer.lock       ✅
└── vendor/             ❌


# Partie 2 — Eloquent

## 1. Quel rôle joue Capsule\Manager ?

`Capsule\Manager` permet de configurer et d'utiliser le composant Database d'Eloquent indépendamment de Laravel. Il permet notamment de configurer la connexion à la base de données et de démarrer Eloquent.

## 2. Pourquoi Eloquent peut-il fonctionner sans Laravel ?

Eloquent est disponible comme composant indépendant grâce au package `illuminate/database`. Laravel utilise Eloquent, mais Eloquent peut également être utilisé dans une application PHP sans le framework Laravel.

## 3. Où doit se trouver le démarrage de l'ORM ?

Le démarrage de l'ORM doit être centralisé dans `config/database.php`. Ce fichier charge les variables d'environnement, configure `Capsule\Manager` et démarre Eloquent. Les classes métier ne doivent pas gérer directement la connexion.

## 4. Quelle différence existe entre ORM et SQL écrit à la main ?

Avec SQL écrit à la main, le développeur écrit directement les requêtes SQL.

Avec un ORM, le développeur manipule des modèles et des objets PHP. L'ORM se charge de générer les requêtes SQL nécessaires pour communiquer avec la base de données.