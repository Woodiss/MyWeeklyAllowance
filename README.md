# Test Unitaire TDD - Projet MyWeeklyAllowance

## Contexte du projet

Vous allez concevoir un module de gestion d’argent de poche pour adolescents, selon la méthode TDD (Test Driven Development).
L’application **MyWeeklyAllowance** permet aux parents de gérer un “porte-monnaie virtuel” pour leurs ados.

Chaque adolescent a un compte d’argent de poche, et chaque parent peut :

- créer un compte pour un ado,
- déposer de l’argent,
- enregistrer des dépenses,
- fixer une allocation hebdomadaire automatique.

## Organisation

- Phase 1 – Rédaction des tests unitaires (RED)
- Phase 2 – Implémentation du code (BLUE)
- Phase 3 – Refactoring (GREEN)
- Phase 4 – Vérification de la couverture

---

## 🚀 Installation et Démarrage

Ce projet est conteneurisé avec **Docker**. Vous n'avez pas besoin d'installer PHP ou MySQL localement.

### Prérequis

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) installé et lancé.

### Étapes d'installation

1. **Cloner le projet** (si ce n'est pas déjà fait)

   ```bash
   git clone https://github.com/Woodiss/MyWeeklyAllowance
   cd MyWeeklyAllowance
   ```

2. **Configurer l'environnement**
   Copiez le fichier d'exemple pour créer votre configuration locale :

   ```bash
   cp .env.example .env
   ```

   Vous pouvez modifier les ports ou les identifiants dans le fichier `.env` si nécessaire.

3. **Lancer les conteneurs**
   Construisez et démarrez l'application :
   ```bash
   docker compose up -d --build
   ```
4. **Installer les dépendances PHP**
   Exécutez `composer install` à l'intérieur du conteneur PHP pour télécharger les librairies nécessaires (dont PHPUnit) :

   ```bash
   docker exec myweeklyallowance_php composer install
   ```

5. **Initialiser la base de données 💾**

Accédez à http://localhost:8081 (PhpMyAdmin). Connectez-vous avec l'utilisateur root et le mot de passe défini dans .env. Sélectionnez la base de données myweeklyallowance et utilisez l'onglet "Importer" pour charger le fichier SQL du projet qui se trouve dans le dossier `Database/myweeklyallowanceDatabase.sql`.

### 🌍 Accès à l'application

Une fois les conteneurs démarrés :

- **Application Web** : [http://localhost:8080](http://localhost:8080) (ou le port défini dans `APP_PORT`)
- **PhpMyAdmin** (Gestion BDD) : [http://localhost:8081](http://localhost:8081) (ou le port défini dans `PMA_PORT`)

### 🛠 Stack Technique

Le projet met en place l'architecture suivante via Docker Compose :

- **PHP 8.2 + Apache** : Serveur web avec Xdebug activé pour le développement et la couverture de code.
- **MySQL 8.0** : Base de données relationnelle.
- **PhpMyAdmin** : Interface web pour gérer la base de données.

### ✅ Lancer les Tests

Pour exécuter la suite de tests PHPUnit avec le rapport de couverture :

```bash
docker exec myweeklyallowance_php ./vendor/bin/phpunit --coverage-text
```

> **Note** : Le nom du conteneur `myweeklyallowance_php` dépend de la variable `PROJECT_NAME` dans votre `.env`. Si vous l'avez changé, adaptez la commande.

### Commandes utiles

- **Arrêter les conteneurs** : `docker compose down`
- **Voir les logs PHP** : `docker compose logs -f php`
- **Accéder au terminal du conteneur PHP** : `docker exec -it myweeklyallowance_php bash`

Auteur
| Nom | Prénom | Github |
| --- | --- | --- |
| Descarpentries | Stéphane | [Woodiss](https://github.com/Woodiss) |
| Allard | Adrien | [The-Leyn](https://github.com/The-Leyn) |
