# Routes de l'API

## Routes d'Authentification

- `POST /login`: Connecte un utilisateur.
- `POST /register`: Enregistre un nouvel utilisateur.

## Routes Utilisateur

- `GET /user`: Récupère les détails de l'utilisateur authentifié.
- `PATCH /user`: Met à jour les détails de l'utilisateur authentifié.
- `DELETE /users/{user}`: Supprime un utilisateur spécifique.
- `GET /user/groups`: Récupère tous les groupes d'un utilisateur spécifique.
- `POST /user/groups`: Crée un nouveau groupe pour l'utilisateur authentifié.
- `PATCH /user/groups/{groupe}/leave`: L'utilisateur authentifié quitte un groupe spécifique.

## Routes Stock

- `GET /user/stocks`: Récupère tous les stocks d'un utilisateur spécifique.
- `POST /user/stocks`: Crée un nouveau stock pour un utilisateur spécifique.
- `GET /user/stocks/{stock}`: Récupère un stock spécifique d'un utilisateur spécifique.
- `PUT /users/stocks/{stock}`: Met à jour un stock spécifique d'un utilisateur spécifique.
- `DELETE /user/stocks/{stock}`: Supprime un stock spécifique d'un utilisateur spécifique.

## Routes Produit

- `POST /user/stocks/{stock}/produits`: Ajoute un produit à un stock spécifique d'un utilisateur spécifique.
- `GET /user/stocks/{stock}/produits`: Récupère tous les produits d'un stock spécifique d'un utilisateur spécifique.
- `DELETE /user/stocks/{stock}/produits/{product}`: Supprime un produit d'un stock spécifique d'un utilisateur spécifique.
- `PATCH /user/stocks/{stock}/produits/{product}/quantite`: Met à jour la quantité d'un produit spécifique dans un stock spécifique d'un utilisateur spécifique.

## Routes Groupe

- `GET /groups/{groupe}`: Récupère un groupe spécifique.
- `PATCH /groups/{groupe}`: Met à jour un groupe spécifique.
- `DELETE /groups/{groupe}`: Supprime un groupe spécifique.
- `POST /groups/{groupe}/add`: Associe un utilisateur à un groupe spécifique.
- `PATCH /groups/{groupe}/users/{user}`: Dissocie un utilisateur d'un groupe spécifique.
- `DELETE /groups/{groupe}/stocks/{stock}`: Supprime un stock d'un groupe spécifique.

## Routes Stock de Groupe

- `GET /groups/{groupe}/stocks`: Récupère tous les stocks d'un groupe spécifique.
- `POST /groups/{groupe}/stocks`: Ajoute un stock à un groupe spécifique.
- `GET /groups/{groupe}/stocks/{stock}`: Récupère un stock spécifique d'un groupe spécifique.
- `GET /groups/{groupe}/stocks/{stock}/produits`: Récupère tous les produits d'un stock spécifique d'un groupe spécifique.
- `DELETE /groups/{groupe}/stocks/{stock}/produits/{product}`: Supprime un produit d'un stock spécifique d'un groupe spécifique.
- `PATCH /groups/{groupe}/stocks/{stock}/produits/{product}`: Met à jour un produit spécifique dans un stock spécifique d'un groupe spécifique.
- `PATCH /groups/{groupe}/stocks/{stock}/produits/{product}/quantite`: Met à jour la quantité d'un produit spécifique dans un stock spécifique d'un groupe spécifique.

Veuillez noter que toutes les routes à l'intérieur du groupe de middleware `auth:sanctum` nécessitent que l'utilisateur soit authentifié.

## Installation

1. Clonez le dépôt : `git clone https://github.com/LZonca/stocker-api.git`
2. Accédez au répertoire du projet : `cd repertoire-du-projet`
3. Installez les dépendances : `composer install`
4. Copiez le fichier `.env.example` en `.env` et configurez les paramètres de la base de données.

## Configuration

1. Configurez les paramètres de la base de données dans le fichier `.env`.
2. Assurez-vous que la base de données est correctement configurée.
3. Configurer un serveur SMTP pour les emails de confirmation.

## Utilisation

1. Lancez l'application : `php artisan serve`

## Tests

Pour tester l'api:
    - Utilisez /login pour obtenir un token
    - Utilisez le token dans le header bearer token
    - Utilisez les routes de l'api

Les tests d'intégration ne fonctionnent plus à cause d'une contrainte MySQL incompatible avec sqlite.
