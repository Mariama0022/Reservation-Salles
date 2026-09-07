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



# Partie 3 — Modèles Eloquent

## 1. Quel type de relation Eloquent avez-vous utilisé ?

Une relation un-à-plusieurs (One-to-Many) :

- `Salle` utilise `hasMany`.
- `Reservation` utilise `belongsTo`.

Une salle peut donc avoir plusieurs réservations, tandis qu'une réservation appartient à une seule salle.

## 2. Pourquoi déclarer `$fillable` ou `$guarded` ?

Ils permettent de contrôler les attributs autorisés lors de l'affectation de masse et d'éviter qu'un champ non autorisé soit modifié.

## 3. Pourquoi convertir `active` en booléen ?

Parce que `active` représente un état logique :

- `true` pour une salle active ;
- `false` pour une salle inactive.

## 4. Pourquoi convertir les dates en objets ?

Le cast permet de manipuler les dates comme des objets plutôt que comme de simples chaînes de caractères. Cela facilite leur formatage, leurs comparaisons et les calculs sur les dates.



# Partie 4 — Données initiales

## 1. Quelle différence existe entre migration et seeder ?

Une **migration** sert à créer ou modifier la structure de la base de données : créer une table, ajouter une colonne, modifier une contrainte, etc.

Un **seeder** sert à ajouter des données initiales dans les tables, par exemple les cinq salles de notre projet.

**En résumé :**

* Migration → structure de la base de données.
* Seeder → données de départ.

## 2. Pourquoi les données initiales doivent-elles être reproductibles ?

Les données initiales doivent être reproductibles afin de pouvoir exécuter le seeder plusieurs fois sans provoquer d'erreurs ni créer plusieurs fois les mêmes données.

Cela est utile lors de l'installation du projet, des tests ou du déploiement.

## 3. Comment empêcher les doublons ?

Dans notre projet, nous utilisons **`firstOrCreate()`** d'Eloquent.

Cette méthode vérifie d'abord si la salle existe déjà avec le même nom :

* si elle existe → elle n'est pas recréée ;
* si elle n'existe pas → elle est créée.

Ainsi, le seeder peut être exécuté plusieurs fois sans créer de doublons.



# Partie 5 — Validation

## 1. Pourquoi séparer la validation syntaxique des règles métier ?

La validation syntaxique vérifie que les données respectent un format attendu : type, longueur, email valide, entier, date valide, etc.

Les règles métier concernent le fonctionnement de l'application, par exemple vérifier qu'une date de fin est après une date de début.

Séparer les deux permet de garder un code plus clair et de placer chaque responsabilité dans la bonne couche.

## 2. Pourquoi créer une interface de validation ?

L'interface permet de définir un contrat commun pour tous les validateurs.

Ainsi, `SalleValidator` et `ReservationValidator` possèdent la même méthode `validate()` et retournent toutes les deux un `ValidationResult`.

Cela facilite également le remplacement ou l'ajout de nouveaux validateurs.

## 3. Pourquoi le validateur ne doit-il pas enregistrer les données ?

Le validateur doit uniquement vérifier les données.

Il ne doit pas enregistrer les données en base de données car cela mélangerait deux responsabilités différentes : la validation et la persistance.

L'enregistrement doit être réalisé par la couche métier ou le repository.

## 4. Comment retourner plusieurs erreurs en une seule fois ?

Les erreurs sont stockées dans un tableau associatif avec le nom du champ comme clé.

Par exemple :

```php
[
    'nom' => 'Le nom est obligatoire.',
    'capacite' => 'La capacité est invalide.',
    'email' => 'L\'adresse email est invalide.'
]
```

Le `ValidationResult` retourne ensuite ce tableau avec la méthode `errors()`.

Cela permet d'afficher toutes les erreurs à l'utilisateur en une seule fois.




# Partie 6 — DTO

## 1. Quelle différence existe entre DTO et modèle Eloquent ?

Un DTO (Data Transfer Object) sert à transporter des données entre les différentes couches de l'application.

Il contient uniquement les données nécessaires et correctement typées.

Un modèle Eloquent représente une donnée persistée en base de données et permet notamment d'effectuer des opérations de lecture et d'écriture.

Le DTO transporte les données tandis que le modèle Eloquent représente les données persistées.

## 2. Pourquoi le DTO ne doit-il pas appeler save() ?

Le DTO ne doit pas appeler `save()` car sa responsabilité est uniquement de transporter les données.

Il ne doit pas connaître la base de données ni gérer la persistance.

L'enregistrement doit être réalisé par le service ou la couche responsable de la persistance.

## 3. À quel moment transforme-t-on les chaînes en dates ?

Les valeurs provenant de `$_POST` sont des chaînes de caractères.

Après la validation, elles sont transformées en objets `DateTimeImmutable` avant d'être placées dans le DTO.

Le DTO reçoit donc des données déjà correctement typées.

## 4. Le DTO doit-il contenir la règle de chevauchement ?

Non.

Le DTO ne doit pas contenir la règle de chevauchement car cette règle appartient à la logique métier.

Le DTO sert uniquement à transporter les données.

La vérification du chevauchement doit être réalisée dans le service métier.