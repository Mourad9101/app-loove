# Evergem : Une application de rencontre symbolique et respectueuse

Evergem est une application de rencontre web innovante conçue pour offrir une alternative respectueuse et transparente aux plateformes de rencontre traditionnelles. Le cœur du projet repose sur un système de pierres précieuses, où chaque gemme représente une intention claire, permettant aux utilisateurs de se connecter sur la base d'aspirations communes.

Ce projet a été développé dans le cadre de ma formation à l'école **CODA**.

## ✨ Le Concept

L'objectif d'Evergem est de remplacer l'incertitude et les jeux de devinettes par un langage symbolique et clair. Chaque utilisateur choisit une pierre précieuse qui représente son intention de recherche principale :

-   💎 **Rubis** : Pour une relation d'un soir.
-   💚 **Émeraude** : Pour une relation sérieuse et durable.
-   💙 **Saphir** : Pour une amitié sincère.
-   💜 **Améthyste** : Pour une connexion spirituelle ou intellectuelle.

Cette approche vise à encourager des interactions plus honnêtes et à aligner les attentes dès le départ.

## 🚀 Fonctionnalités Principales

-   **Système de Matching Avancé** : Un algorithme qui suggère des profils en se basant sur l'intention (pierre précieuse), la géolocalisation et la compatibilité des profils.
-   **Profils Utilisateurs Détaillés** : Chaque utilisateur dispose d'un profil personnalisable avec photos, biographie, centres d'intérêt, etc.
-   **Onboarding Guidé** : Un processus d'inscription en plusieurs étapes pour aider les utilisateurs à compléter leur profil de manière intuitive.
-   **Géolocalisation** : Affiche les utilisateurs à proximité, favorisant les rencontres locales.
-   **Messagerie Instantanée** : Un système de chat en temps réel pour permettre aux "matchs" de communiquer en toute sécurité.
-   **Fonctionnalités Premium** : Accès à des filtres de recherche avancés et à des "likes" illimités pour les utilisateurs abonnés.
-   **Dashboard Administrateur** : Un panneau de gestion pour superviser les utilisateurs, les signalements et les statistiques de l'application.

## 💻 Technologies Utilisées

| Catégorie          | Technologie                                                                                                  |
| ------------------ | ------------------------------------------------------------------------------------------------------------ |
| **Backend**        | PHP 8.x, Architecture MVC personnalisée, [Composer](https://getcomposer.org/) pour la gestion des dépendances. |
| **Frontend**       | JavaScript (Vanilla, Modules ES6), HTML5, CSS3, [Bootstrap](https://getbootstrap.com/)                         |
| **Base de données**  | MySQL                                                                                                        |
| **Services Tiers** | [Pusher](https://pusher.com/) pour les notifications, [Stripe](https://stripe.com/) pour les paiements.        |
| **Environnement**  | MAMP / XAMPP                                                                                                 |

## 📂 Structure du Projet

Le projet suit une architecture MVC (Modèle-Vue-Contrôleur) pour une séparation claire des responsabilités.

```
Evergem/
├── app/                # Cœur de l'application (MVC)
│   ├── Controllers/    # Logique de traitement des requêtes
│   ├── Core/           # Classes de base (Router, Database, Controller...)
│   ├── Models/         # Modèles d'interaction avec la base de données
│   ├── Services/       # Logique métier externe (notifications, etc.)
│   └── Views/          # Fichiers de présentation (HTML, PHP)
├── config/             # Fichiers de configuration (ex: base de données)
├── database/           # Schéma SQL, migrations et seeds
├── public/             # Fichiers accessibles publiquement (assets)
│   ├── css/            # Feuilles de style
│   ├── js/             # Scripts JavaScript
│   └── uploads/        # Images uploadées par les utilisateurs
├── vendor/             # Dépendances gérées par Composer
├── .env.example        # Fichier d'exemple pour les variables d'environnement
├── composer.json       # Déclaration des dépendances PHP
└── index.php           # Point d'entrée unique de l'application
```

## 🛠️ Instructions d'Installation Locale

Pour lancer Evergem sur votre machine locale, suivez ces étapes :

1.  **Prérequis** :
    -   Un environnement de développement local (MAMP, XAMPP, WAMP, etc.).
    -   [Composer](https://getcomposer.org/download/) installé.
    -   Une base de données MySQL fonctionnelle.

2.  **Cloner le dépôt** :
    ```bash
    git clone [URL_DU_DEPOT] Evergem3
    cd Evergem3
    ```

3.  **Installer les dépendances PHP** :
    ```bash
    composer install
    ```

4.  **Configurer l'environnement** :
    -   Copiez le fichier `.env.example` et renommez-le en `.env`.
    -   Modifiez le fichier `.env` avec vos propres informations :
        ```ini
        DB_HOST=localhost
        DB_NAME=evergem
        DB_USER=root
        DB_PASS=root
        
        # Clés pour les services tiers (Pusher, Stripe, Google Auth)
        PUSHER_APP_ID=...
        PUSHER_APP_KEY=...
        PUSHER_APP_SECRET=...
        # etc.
        ```

5.  **Base de données** :
    -   Connectez-vous à votre client MySQL (ex: phpMyAdmin).
    -   Créez une nouvelle base de données nommée `evergem` (ou le nom que vous avez mis dans le `.env`).
    -   Importez le fichier `database/schema.sql` dans cette base de données pour créer toutes les tables nécessaires.

6.  **Lancer le serveur** :
    -   Pointez votre serveur local (Apache via MAMP/XAMPP) vers le répertoire `public/` du projet ou accédez directement à `http://localhost/chemin/vers/Evergem3/`.

Vous devriez maintenant pouvoir accéder à l'application Evergem !

## 👤 Auteur

Ce projet a été réalisé par **Mourad9101** dans le cadre de la formation Développeur Web et Web Mobile à l'école **CODA**. 