# Knowlity - Plateforme Éducative Innovante (Web)

## Description

Knowlity est une plateforme éducative développée avec **Symfony** pour une interface utilisateur web moderne, centrée sur l’apprentissage interactif via des quiz, cours, blogs, et événements, avec des fonctionnalités avancées d’IA et de sécurité.

- **Objectif** : Offrir une solution complète pour l’apprentissage en ligne, avec des outils pour les étudiants, enseignants, et administrateurs, en intégrant des technologies modernes comme l’IA et des interfaces web intuitives.
- **Problème qu’il résout** : Faciliter la création et la gestion de contenu éducatif, améliorer l’engagement des étudiants grâce à des quiz interactifs et des événements, et assurer une expérience utilisateur sécurisée et accessible via le web.
- **Ses principales fonctionnalités** :
  - **Gestion des Utilisateurs** : Inscription via Google, réinitialisation de mot de passe, verrouillage après 3 tentatives de connexion échouées.
  - **Gestion des Cours** : Création, partage sur Facebook, génération de descriptions par IA, gestion des catégories, chapitres, paiements via API Konnect.
  - **Gestion des Quiz et Évaluations** : Création automatisée via API Gemini, correction automatique/manuelle, musique de fond, text-to-speech, génération de PDF.
  - **Gestion de Blog** : Création, likes, commentaires.
  - **Gestion des Événements** : Création avec API Map, descriptions générées par IA, QR codes pour inscription, chatbot.

## Table des Matières

- [Installation](#installation)
- [Utilisation](#utilisation)
- [Contribution](#contribution)
- [Licence](#licence)

## Installation

1. Clonez le repository :
   ```bash
   git clone https://github.com/ahmed-kasdaoui/knowlity-web.git
   cd knowlity-web
   ```

2. Installez les dépendances :
   ```bash
   composer install
   ```

3. Configurez les variables d’environnement :
   * Créez un fichier `.env` à la racine du projet.
   * Ajoutez les clés API pour Google, Konnect, Gemini, Hugging Face, et autres :
     ```
     GOOGLE_CLIENT_ID=votre_google_client_id
     GOOGLE_CLIENT_SECRET=votre_google_client_secret
     KONNECT_API_KEY=votre_konnect_api_key
     GEMINI_API_KEY=votre_gemini_api_key
     HUGGING_FACE_API_KEY=votre_hugging_face_api_key
     DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/knowlity_db
     ```

4. Configurez la base de données :
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. Lancez l’application :
   ```bash
   symfony server:start
   ```

6. Accédez à l’application :
   * Ouvrez votre navigateur et allez à `http://localhost:8000`.

## Utilisation

### Prérequis
- **PHP 8.1+** et **Composer** pour le backend Symfony
- **MySQL** pour la base de données
- **Node.js** (optionnel, pour les assets front-end si Webpack Encore est utilisé)
- Clés API pour [Google](https://developers.google.com), [Konnect](https://konnect.api.url), [Gemini](https://gemini.api.url), [Hugging Face](https://huggingface.co)

### Installation de PHP
1. Installez PHP 8.1+ :
   * Sur Ubuntu :
     ```bash
     sudo apt update
     sudo apt install php8.1 php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl
     ```
   * Vérifiez :
     ```bash
     php -v
     ```

2. Installez Composer :
   ```bash
   curl -sS https://getcomposer.org/installer | php
   sudo mv composer.phar /usr/local/bin/composer
   ```

3. (Optionnel) Installez Node.js pour les assets front-end :
   ```bash
   sudo apt install nodejs npm
   npm install
   ```

### Instructions d’utilisation

#### Gestion des Utilisateurs
1. **Inscription** :
   * Accédez à `/signup` pour vous inscrire via un formulaire ou utilisez *Connexion Google*.
   * Recevez un email de confirmation.
2. **Connexion** :
   * Connectez-vous via `/login`.
   * Après 3 tentatives échouées, le compte est bloqué 1 minute.
3. **Réinitialisation de Mot de Passe** :
   * Demandez une réinitialisation via `/reset-password`.
   * Recevez un lien par email pour réinitialiser.

#### Gestion des Cours
1. **Enseignant** :
   * Ajoutez un cours via `/teacher/courses/add`, incluant catégories et chapitres.
   * Utilisez une IA pour générer une description.
   * Partagez le cours sur Facebook via l’API Graph.
   * Ajoutez un *watermark* (logo Knowlity) sur les images des cours.
2. **Administrateur** :
   * Gérez les catégories et matières via `/admin/categories`.
   * Redimensionnez les icônes des catégories.
3. **Étudiant** :
   * Consultez les cours via `/courses`.
   * Ajoutez des cours en favoris.
   * Payez via l’API Konnect.
4. **Planification** :
   * Générez un planning étudiant avec l’API Hugging Face via `/student/planning`.

#### Gestion des Quiz et Évaluations
1. **Création** :
   * Créez des quiz/évaluations via `/teacher/quiz/add` automatiquement avec l’API Gemini.
   * Randomisez les questions et réponses pour éviter la triche.
2. **Passation** :
   * Lancez un quiz via `/student/quiz/{id}`.
   * Écoutez une *musique douce* pendant la session (via HTML5 audio).
   * Utilisez *text-to-speech* pour lire les questions.
   * Détection des *mots inappropriés* dans les réponses des évaluations.
3. **Correction** :
   * Correction automatique des quiz.
   * Correction manuelle des évaluations via `/teacher/evaluation/correct`.
4. **Résultats** :
   * Affichez les résultats avec *emojis* (🎉 pour >90%, 😕 pour <50%) via `/student/quiz/results`.
   * Générez un PDF avec scores et réponses.
5. **Statistiques** :
   * Consultez les taux de réussite et questions échouées via `/teacher/statistics`.

#### Gestion de Blog
1. **Utilisateur** :
   * Créez, modifiez, supprimez des articles via `/blog`.
   * Ajoutez des *likes*, *dislikes*, et commentaires.
2. **Administrateur** :
   * Modérez les articles et commentaires via `/admin/blog`.

#### Gestion des Événements
1. **Création** :
   * Ajoutez un événement via `/events/add`.
   * Générez une description via API IA.
   * Intégrez une carte avec *API Map*.
2. **Inscription** :
   * Inscrivez-vous avec un *QR code* généré.
   * Exportez les détails en PDF via `/events/{id}/export`.
3. **Interaction** :
   * Utilisez un *chatbot* pour des questions sur l’événement via `/events/{id}/chat`.

## Contribution

Nous remercions tous ceux qui ont contribué à ce projet !

### Contributeurs
- Utilisateur1 - Gestion des utilisateurs et cours
- Utilisateur2 - Quiz, évaluations et IA
- Utilisateur3 - Blog et événements

### Comment contribuer ?
1. Fork le projet :
   * Cliquez sur **Fork** sur la page GitHub du projet.
2. Clonez votre fork :
   ```bash
   git clone https://github.com/votre-utilisateur/knowlity-web.git
   cd knowlity-web
   ```
3. Créez une nouvelle branche :
   ```bash
   git checkout -b feature/votre-fonctionnalite
   ```
4. Effectuez vos modifications :
   ```bash
   git add .
   git commit -m "Ajout de votre fonctionnalité"
   ```
5. Poussez vers votre fork :
   ```bash
   git push origin feature/votre-fonctionnalite
   ```
6. Soumettez une pull request :
   * Créez une pull request depuis votre branche sur le repository original.

## Licence

Ce projet est sous la licence **MIT**. Pour plus de détails, consultez le fichier [LICENSE](./LICENSE).