# Guide de déploiement — IUFP / G_universite

Plateforme de gestion universitaire (PHP MVC) de l'Institut Universitaire de
Formation Professionnelle (IUFP), Université de Ségou.

Ce document décrit comment mettre l'application en ligne **sans accroc** sur un
hébergement mutualisé (cPanel/Plesk), un VPS, ou tout serveur Apache + PHP +
MySQL/MariaDB. L'application est aussi une **PWA** (installable, fonctionne hors ligne).

---

## 1. Prérequis

| Composant | Version recommandée |
|-----------|---------------------|
| PHP | **8.1+** (minimum 7.4) |
| Extensions PHP | `pdo_mysql`, `mbstring`, `fileinfo`, `json`, `openssl`, `curl` |
| Base de données | MySQL 5.7+ **ou** MariaDB 10.4+ |
| Serveur web | Apache avec `mod_rewrite` (et idéalement `mod_headers`, `mod_expires`, `mod_mime`) |
| Composer | pour installer les dépendances (`vendor/`) |

> ℹ️ L'extension **GD n'est pas requise** : les icônes PWA sont des fichiers
> pré-générés, et les PDF utilisent Dompdf (inclus via Composer).

---

## 2. Architecture (à connaître avant de déployer)

```
G_universite/
├── index.php              ← point d'entrée si on accède à .../G_universite/
├── public/                ← RACINE WEB RECOMMANDÉE (DocumentRoot)
│   ├── index.php          ← front controller
│   ├── .htaccess          ← routage (portable, aucun chemin en dur)
│   ├── manifest.webmanifest, sw.js, offline.html   ← PWA
│   ├── assets/            ← CSS, JS, images, icônes PWA
│   └── signature/ cv_enseignant/ contrat_enseignant/ profile/  ← uploads
├── app/
│   ├── .htaccess          ← INTERDIT l'accès web direct (sécurité)
│   ├── core/config.php    ← NE PAS MODIFIER (lit env + config.local.php)
│   ├── core/config.local.php  ← À CRÉER sur l'hébergeur (secrets, NON versionné)
│   ├── controller/ model/ views/
└── vendor/                ← dépendances Composer (NON versionné → composer install)
```

Le routage se fait via `?url=Controleur/methode` ; le `.htaccess` réécrit toutes
les URL vers le front controller. **L'URL de base (`ROOT`) est détectée
automatiquement** (schéma http/https, sous-dossier, `/public`).

---

## 3. Déploiement pas à pas

### Étape 1 — Copier les fichiers
Transférez tout le dossier `G_universite/` sur l'hébergeur (Git, FTP ou
gestionnaire de fichiers).

### Étape 2 — Configurer la racine web (DocumentRoot)
**Recommandé (le plus sûr) :** faites pointer le DocumentRoot du domaine /
sous-domaine **sur le dossier `public/`**. Ainsi `app/`, `vendor/` et les
secrets restent **hors de la racine web**.

| Scénario | Accès | À configurer |
|----------|-------|--------------|
| **A. Sous-domaine / domaine dédié** | `https://app.mon-domaine.com/` | DocumentRoot → `.../G_universite/public` |
| **B. Domaine racine** | `https://mon-domaine.com/` | DocumentRoot → `.../G_universite/public` |
| **C. Sous-dossier** (pas d'accès au DocumentRoot) | `https://mon-domaine.com/G_universite/public/` | rien de spécial : ça marche tel quel |

> Dans le scénario C, l'app fonctionne sans rien changer. Si jamais le routage
> renvoie des 404, ouvrez `public/.htaccess` et **décommentez** la ligne
> `RewriteBase` correspondant à l'URL de votre dossier `public/`.

### Étape 3 — Créer la configuration locale (secrets BDD)
Créez le fichier **`app/core/config.local.php`** (il n'est jamais versionné) :

```php
<?php
// Identifiants de la base de données de l'hébergeur
$dbHost = "localhost";          // souvent "localhost" (parfois une IP/un hôte dédié)
$dbName = "moncompte_iufp";
$dbUser = "moncompte_user";
$dbPass = "MOT_DE_PASSE_BDD";

// Optionnel : forcer l'URL de base (sinon détection automatique)
// $appUrl = "https://app.mon-domaine.com";

// Optionnel : clé de l'assistant IA (voir §6)
// $groqApiKey = "gsk_xxxxxxxx";
```

> **Alternative** (hébergeurs type plateforme) : au lieu du fichier, définissez
> les variables d'environnement **préfixées `GU_`** :
> `GU_DB_HOST`, `GU_DB_NAME`, `GU_DB_USER`, `GU_DB_PASSWORD`, `GU_APP_URL`,
> `GU_GROQ_API_KEY`, `GU_APP_DEBUG`.
> ⚠️ Le préfixe `GU_` est **obligatoire** : il évite toute collision avec les
> variables génériques (`DB_PASSWORD`…) d'autres applications du serveur.

### Étape 4 — Transférer la base de données
Le dépôt ne contient **pas** de dump complet (le dossier `sql/` ne contient que
des scripts de maintenance ponctuels). Il faut donc **exporter la base locale**
puis l'importer sur l'hébergeur.

1. **Exporter** depuis le poste local (XAMPP) — phpMyAdmin → *Exporter*, ou :
   ```bash
   mysqldump -u root db_universite > db_universite.sql
   ```
2. **Créer** une base vide sur l'hébergeur (panneau de l'hébergeur / phpMyAdmin).
3. **Importer** le fichier dans cette base :
   ```bash
   mysql -u UTILISATEUR -p NOM_BASE < db_universite.sql
   ```

> Les scripts du dossier `sql/` (backfill, normalisations, correctifs FK…) sont
> **optionnels** : ne les rejouez que si vous savez pourquoi.

### Étape 5 — Installer les dépendances
```bash
cd G_universite
composer install --no-dev --optimize-autoloader
```

### Étape 6 — Droits d'écriture sur les dossiers d'upload
Ces dossiers doivent être **accessibles en écriture** par le serveur web :
```
public/signature/   public/cv_enseignant/   public/contrat_enseignant/   public/profile/
```
(en SSH : `chmod -R 775` ; sur cPanel : 755/775 selon l'hébergeur).

### Étape 7 — Activer HTTPS
Activez un certificat SSL (gratuit chez la plupart des hébergeurs).
**HTTPS est requis pour l'installation de la PWA** (sauf en `localhost`).

---

## 4. Vérification post-déploiement

1. Ouvrir `https://VOTRE-URL/` → la **page de connexion** s'affiche.
2. Se connecter → le tableau de bord se charge, les styles sont présents.
3. Tester un upload (ex. ajouter une signature) → pas d'erreur de droits.
4. Console du navigateur (F12) : aucune erreur 404 sur les assets.
5. **PWA** : dans la barre d'adresse (Edge/Chrome), un bouton **« Installer »**
   apparaît. DevTools → *Application* → *Service Workers* doit montrer le SW
   **activated**, et *Manifest* doit lister le nom et les icônes.

---

## 5. La PWA (Progressive Web App)

- **Installable** sur mobile (Android : « Ajouter à l'écran d'accueil ») et
  bureau (Edge/Chrome : bouton *Installer*).
- **Hors ligne** : une page de repli s'affiche sans connexion ; les pages déjà
  visitées et les assets restent disponibles.
- **Mises à jour** : le service worker et le manifeste sont servis en *no-cache*
  ; une nouvelle version est prise en compte automatiquement au rechargement.
- Fichiers concernés : `public/manifest.webmanifest`, `public/sw.js`,
  `public/offline.html`, `public/assets/images/pwa/`.

> Pour forcer une mise à jour du cache après un gros changement, incrémentez la
> constante `CACHE` (`gu-cache-v1` → `gu-cache-v2`) en haut de `public/sw.js`.

---

## 6. Sécurité (important)

- ✅ `app/core/config.local.php` et `vendor/` sont **exclus de Git** (`.gitignore`).
- ✅ `app/.htaccess` interdit l'accès web direct au code source.
- ✅ Les erreurs PHP sont **masquées** par défaut (mettez `GU_APP_DEBUG=1` uniquement pour diagnostiquer, jamais en prod).
- 🔑 **À FAIRE** : la clé API **Groq** a été exposée dans l'historique Git. Elle
  doit être **régénérée** sur <https://console.groq.com/keys>, l'ancienne
  révoquée, et la nouvelle placée dans `config.local.php` (ou la variable
  `GU_GROQ_API_KEY`). L'assistant IA reste optionnel : sans clé, le reste de
  l'app fonctionne normalement.

---

## 7. Dépannage rapide

| Symptôme | Cause probable / solution |
|----------|---------------------------|
| **« Echec de connexion a la base de données »** | Identifiants BDD : vérifier `config.local.php` (hôte/nom/user/mot de passe). |
| **Page blanche / erreur 500** | Activer temporairement `GU_APP_DEBUG=1`, consulter le log d'erreurs Apache/PHP. Souvent : `vendor/` absent (→ `composer install`) ou `mod_rewrite` désactivé. |
| **Liens en 404 après connexion** | `mod_rewrite` non actif, ou décommenter `RewriteBase` dans `public/.htaccess`. |
| **CSS/JS non chargés (page « nue »)** | Mauvaise URL de base : définir `$appUrl` dans `config.local.php`. |
| **Une page marche en local mais pas en ligne** | Sensibilité à la **casse** (Linux) : vérifier que le nom de fichier correspond exactement (majuscules/minuscules) à la référence. |
| **PWA non installable** | HTTPS manquant, ou `manifest.webmanifest` non servi (vérifier le type `application/manifest+json`). |
| **Upload de signature en échec** | Droits d'écriture sur `public/signature/` (et dossiers d'upload). |

---

## 8. Checklist finale

- [ ] DocumentRoot pointé sur `/public` (recommandé)
- [ ] `app/core/config.local.php` créé avec les identifiants BDD
- [ ] Base de données importée
- [ ] `composer install` exécuté (`vendor/` présent)
- [ ] Dossiers d'upload accessibles en écriture
- [ ] HTTPS actif
- [ ] Connexion + tableau de bord OK
- [ ] Service worker *activated* + bouton *Installer* présent
- [ ] 🔑 Clé Groq régénérée
