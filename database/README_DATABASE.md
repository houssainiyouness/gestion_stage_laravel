# Base de données

Créer la base :

```sql
CREATE DATABASE gestion_stage CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Ensuite utiliser les migrations :

```bash
php artisan migrate --seed
```
