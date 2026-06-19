# Gestion de stage intelligente — Projet Laravel

Ce zip contient un starter Laravel respectant la conception du projet :

Candidature → Sélection → Stage → Suivi → Soutenance.

## 1. Installation

Dézipper le projet, puis ouvrir un terminal dans le dossier :

```bash
composer install
copy .env.example .env
php artisan key:generate
```

Sur Linux/Mac :

```bash
cp .env.example .env
php artisan key:generate
```

## 2. Base de données

Créer une base MySQL :

```sql
CREATE DATABASE gestion_stage CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Configurer le fichier `.env` :

```env
DB_DATABASE=gestion_stage
DB_USERNAME=root
DB_PASSWORD=
```

Puis lancer :

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Accès :

```txt
http://127.0.0.1:8000
```

## 3. Comptes de test

| Rôle | Email | Mot de passe |
|---|---|---|
| Super Admin | super_admin@gmail.com | 12345678 |
| Admin Organisme | admin@example.com | 12345678 |
| Encadrant | encadrant@example.com | 12345678 |
| Étudiant | etudiant@example.com | 12345678 |

## 4. Modules inclus

- Authentification simple
- Gestion des utilisateurs
- Gestion des organismes
- Gestion des offres
- Candidatures avec upload CV et lettre
- Acceptation/refus des candidatures
- Création automatique du stage après acceptation
- Gestion des stages
- Documents de stage
- Suivis avec avancement %
- Évaluations
- Soutenances
- Dashboard selon rôle
- Logs simples des actions importantes

## 5. Remarque importante

Le dossier `vendor` n’est pas inclus, c’est normal. Il faut exécuter :

```bash
composer install
```

