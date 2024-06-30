# Hackathon Final Destination

## Fonctionnalités

1. **Gestion des Utilisateurs**

    - Création et authentification des comptes utilisateurs.
    - Gestion des rôles et des permissions.

2. **Suivi des Patients**

    - Enregistrement des patients et de leurs informations personnelles.
    - Suivi post-intervention avec un chatbot interactif.
    - Collecte des données sur la santé mentale et physique des patients.

3. **Système de Chatbot**

    - Chatbot intégré pour recueillir des informations post-opératoires.
    - Enregistrement et analyse des messages des patients.

4. **Gestion des Symptômes**

    - Ajout, modification et suppression des symptômes.
    - Association des symptômes aux patients.
    - Visualisation des symptômes les plus fréquents.

5. **Statistiques et Analyses**

    - Génération de statistiques globales et détaillées.
    - Visualisation des données sous forme de graphiques interactifs.
    - Analyse des données et prédictions basées sur l’IA.

6. **Fonctionnalités Additionnelles**

    - Système d'upload des ordonnances.
    - Ajout de commentaires liés aux ordonnances.
    - Passage de commandes de médicaments.
    - Analyse des images pour détecter les blessures ou autres pathologies.

7. **Intégration IA**
    - Utilisation de Mistral pour l'analyse locale des données.
    - Traitement des réponses en temps réel.

## Installation et Initialisation du Projet

### Installation de Mistral

1. **Télécharger Ollama :**

Depuis repo github : https://github.com/ollama/ollama

2. **Recuperer l'image de mistral :**

```bash
ollama pull mistral
```

3. **Facultatif - Lancer mistral en terminal :**

```bash
ollama run mistral
```

### Cloner le Dépôt

Pour commencer, clonez le dépôt depuis GitHub :

```bash
git clone git@github.com:romain0201/hackathon-final-destination.git
cd hackathon-final-destination
```

### Configuration de l'Environnement

Créez un fichier `.env.local` à la racine du projet et ajoutez les variables d'environnement suivantes :

```bash
APP_ENV=dev
APP_SECRET=XXXXXXXX
DATABASE_URL="example://db_user:db_password@db_host:db_port/db_name"
TWILIO_SID=ACXXXXXXXX
TWILIO_AUTH_TOKEN=XXXXXXXX
TWILIO_PHONE_NUMBER=+1234567890
```

### Démarrage du Serveur

Lancez le serveur Symfony avec Docker Compose :

```bash
docker compose build --no-cache
docker compose up -d
```

### Installation des Dépendances

Installez les dépendances du projet avec Composer :

```bash
docker compose exec php composer install
npm install
npm run dev
```

### Création de la Base de Données

Créez la base de données et exécutez les migrations :

```bash
docker compose exec php bin/console doctrine:database:create
docker compose exec php bin/console doctrine:migrations:migrate
```

### Installation des Données de Test

Installez les données de test avec les fixtures :

```bash
docker compose exec php bin/console doctrine:fixtures:load
```

### Génération des Statistiques

Générez les statistiques avec les commandes suivantes :

```bash
docker compose exec php bin/console GenerateStatistics
```

### Accès à l'Application

Ouvrez l'application dans votre navigateur à l'adresse `https://localhost`.

### Arrêt du Serveur

Arrêtez le serveur Symfony avec Docker Compose :

```bash
docker compose down --remove-orphans
```

## Membres de l'équipe

-   Romain Lethumier (Pseudo GitHub: `romain0201`)
-   Samy Amallah (Pseudo GitHub: `Choetsu`, Name GitHub: `Koetsu`)
-   Chemlal Morade (Pseudo GitHub: `mchemlal`)
-   Dahbi Ossama (Pseudo GitHub: `Ossama9`)
