Réponses aux questions
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