# Back Office LivresGourmands.net

Interface d'administration pour le site e-commerce LivresGourmands.net.

## Fonctionnalités

- Gestion des livres (ajout, édition, suppression)
- Gestion des catégories
- Modération des commentaires
- Suivi des ventes
- Administration des utilisateurs et des rôles
- Dashboard avec indicateurs clés

## Technologies utilisées

- Laravel 11
- MySQL
- Bootstrap 5
- Font Awesome

## Rôles et permissions

- **Éditeur** : gestion des descriptions des livres, validation des commentaires, gestion des catégories
- **Gestionnaire** : toutes les permissions de l'éditeur + gestion du stock, catalogue complet, suivi des ventes
- **Administrateur** : toutes les permissions + gestion des utilisateurs et des rôles

## Installation

1. Cloner le dépôt
```
git clone https://github.com/votre-utilisateur/livresgourmands-admin.git
cd livresgourmands-admin
```

2. Installer les dépendances
```
composer install
npm install
```

3. Configurer l'environnement
```
cp .env.example .env
php artisan key:generate
```

4. Configurer la base de données dans le fichier .env
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=livresgourmands
DB_USERNAME=root
DB_PASSWORD=
```

5. Exécuter les migrations et seeders
```
php artisan migrate --seed
```

6. Lancer le serveur
```
php artisan serve
```

## Structure de la base de données

- **Books** : titre, description, auteur, catégorie_id, niveau_expertise, stock, prix
- **Categories** : nom, description
- **Comments** : contenu, statut, user_id, book_id
- **Sales** : book_id, quantité, prix_unitaire, date_vente
- **Users** : nom, email, password
- **Roles** : nom

## API Documentation

L'API REST est disponible à `/api/v1/` et expose les ressources suivantes :

- `/api/v1/books`
- `/api/v1/categories`
- `/api/v1/comments`
- `/api/v1/sales`
- `/api/v1/users`

Voir la documentation Postman pour plus de détails.
