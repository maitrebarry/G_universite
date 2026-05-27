# Hebergement G_UNIVERSITE

La configuration principale se fait dans `app/core/config.php`.

## 1. URL de l'application

Configurez uniquement la variable `$appUrl`.

Exemples:

```php
$appUrl = "https://monsite.com/G_universite/public";
```

ou, si le domaine pointe directement vers le dossier `public`:

```php
$appUrl = "https://monsite.com";
```

En local XAMPP:

```php
$appUrl = "http://localhost:8080/G_universite/public";
```

Vous pouvez aussi laisser vide:

```php
$appUrl = "";
```

Dans ce cas, l'application tente de detecter automatiquement l'URL.

## 2. Base de donnees

Dans le meme fichier:

```php
define("DB_NAME", "db_universite");
define("DBHOST", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");
```

## 3. Chemins JS et uploads

Les fichiers JavaScript utilisent maintenant `window.APP_ROOT` et `window.APP_ROUTE`.
Ils ne doivent plus contenir d'URL locale comme `http://localhost/G_universite/public`.

Les uploads utilisent `PUBLIC_PATH`, calcule automatiquement vers le dossier `public`.

## 4. Dossier public

Idealement, le domaine ou sous-domaine doit pointer vers:

```text
G_universite/public
```

Si l'hebergeur ne permet pas cela, utilisez une URL avec `/public` dans `$appUrl`.
