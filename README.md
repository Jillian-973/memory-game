# 🎮 Memory Game

Un jeu de mémoire interactif et amusant développé avec PHP. Trouvez les paires de cartes en testant votre mémoire !

![Memory Game](https://img.shields.io/badge/PHP-7.4+-blue?style=flat-square) ![Docker](https://img.shields.io/badge/Docker-ready-2496ED?style=flat-square&logo=docker&logoColor=white) ![Status](https://img.shields.io/badge/Status-Active-success?style=flat-square)

## 📋 Description

**Memory Game** est un jeu de plateau web classique où les joueurs doivent mémoriser et retrouver des paires de cartes identiques. Chaque session est entièrement gérée en PHP avec les sessions natives du serveur.

Le jeu propose :
- **16 cartes** organisées en grille 4x4
- **8 paires** de symboles précieux (rune, jade, saphyre, rubis, émeraude, amaryllis, quartz, cristal)
- **Mélange aléatoire** des cartes à chaque nouvelle partie
- **Système de sélection** : 2 cartes maximum par tour

## 🎯 Fonctionnalités

✨ **Gameplay fluide**
- Sélectionnez les cartes en cliquant dessus
- Visualisez instantanément votre choix
- Limite de 2 cartes par sélection

🎨 **Interface intuitive**
- Design épuré et responsive
- Navigation simple entre les pages
- Feedback visuel des cartes sélectionnées

🔄 **Gestion de session**
- État du jeu sauvegardé pendant la session
- Historique des clics
- Réinitialisation facile

## 🚀 Installation

Deux façons de lancer le jeu : avec **Docker** (le plus simple, rien à installer à part Docker) ou avec un **serveur web classique** (WAMP, Apache…).

### Option 1 : avec Docker 🐳

#### Prérequis
- **[Docker Desktop](https://www.docker.com/products/docker-desktop/)** installé et **lancé** (sous Windows, les commandes `docker` ne fonctionnent que si Docker Desktop est ouvert)

#### Étapes

1. **Clonez le repository**
```bash
git clone https://github.com/Jillian-973/memory-game.git
cd memory-game
```

2. **Construisez l'image et lancez le conteneur**
```bash
docker compose up -d --build
```

3. **Jouez**
Accédez à : `http://localhost:8080`

#### Commandes utiles

| Commande | Rôle |
|----------|------|
| `docker compose up -d --build` | Construire l'image et lancer le jeu (à refaire après chaque modification du code) |
| `docker compose down` | Arrêter le jeu |
| `docker ps` | Voir le conteneur et son état (`healthy` = le jeu répond) |
| `docker logs memory-game` | Afficher les logs d'Apache |
| `docker exec -it memory-game sh` | Ouvrir un terminal dans le conteneur |

L'image et le conteneur `memory-game` sont aussi visibles dans **Docker Desktop**, onglets *Images* et *Containers* : un clic sur le port `8080:80` ouvre le jeu dans le navigateur.

> 💡 Le jeu utilise le port **8080** pour ne pas entrer en conflit avec WAMP, qui occupe déjà le port 80. Si le port 8080 est déjà pris sur votre machine, changez `"8080:80"` en `"8081:80"` dans `docker-compose.yml`.

### Option 2 : avec un serveur web classique

#### Prérequis
- **PHP** 7.4 ou supérieur
- **Serveur web** (Apache, WAMP, etc.)
- **cURL** activé (optionnel)

#### Étapes

1. **Clonez le repository**
```bash
git clone https://github.com/Jillian-973/memory-game.git
cd memory-game
```

2. **Placez les fichiers sur votre serveur**
```bash
# Copiez le dossier dans le répertoire web de votre serveur
cp -r memory-game /var/www/html/
# ou pour WAMP
# C:\wamp64\www\memory-game\
```

3. **Lancez le jeu**
Accédez à : `http://localhost/memory-game/start.php`

## 💻 Utilisation

1. Cliquez sur le bouton **"START GAME"** pour commencer une nouvelle partie
2. **Cliquez sur les cartes** pour les retourner et voir leur contenu
3. Vous pouvez sélectionner jusqu'à **2 cartes par tour**
4. Si les 2 cartes forment une paire, elles restent visibles (vert clair avec une coche), sinon elles se recachent au clic suivant
5. Trouvez les 8 paires pour arriver sur la page de fin avec votre nombre d'essais
6. Cliquez sur **"EXIT GAME"** pour retourner à l'accueil

## 🛠️ Architecture Technique

### Structure des fichiers

```
memory-game/
├── start.php       # Page d'accueil - Initialisation du jeu
├── game.php        # Affichage du plateau de jeu
├── click.php       # Gestion des clics et logique de jeu
├── fin.php         # Page de fin - Affichage du nombre d'essais
├── style.css       # Style commun aux 3 pages (DA verte, cartes, animations)
├── Dockerfile          # Recette de l'image Docker (PHP 8.3 + Apache)
├── docker-compose.yml  # Lancement du conteneur en une commande (port 8080)
├── docker/
│   └── entrypoint.sh   # Script exécuté au démarrage du conteneur
├── .dockerignore       # Fichiers exclus de l'image Docker
├── .gitattributes      # Force les fins de ligne Linux sur les scripts .sh
└── README.md           # Documentation
```

### Image Docker

- **Base** : image officielle `php:8.3-apache`
- **Page d'accueil** : `start.php` (configurée via `DirectoryIndex` dans Apache)
- **Entrypoint** : prépare le dossier des sessions PHP et permet de changer le port d'Apache avec la variable `APP_PORT` (80 par défaut), puis démarre Apache
- **Healthcheck** : Docker vérifie toutes les 30 secondes que `start.php` répond

### Flux d'application

```
start.php (Initialize Session & Shuffle Cards)
    ↓
game.php (Display Game Board)
    ↓
click.php (Handle Card Clicks)
    ↓
game.php (Update Display)
```

### Gestion des sessions PHP

- **`$_SESSION['carte']`** : Tableau des cartes avec leurs propriétés
- **`$_SESSION['last_clicked_card']`** : ID de la dernière carte cliquée
- **`$_SESSION['maxCartesSelectionner']`** : Compteur de cartes sélectionnées
- **`$_SESSION['nombreEssais']`** : Nombre d'essais (paires de cartes retournées)

### Structure de données - Carte

```php
[
    "id" => 1,                    // Identifiant unique
    "name" => "rune",             // Nom du symbole
    "selectioner" => false,       // État sélectionné
    "doublonTrouver" => false     // Paire trouvée
]
```

## 🎨 Technologies Utilisées

- **Backend** : PHP 7.4+
- **Frontend** : HTML5, CSS3
- **Gestion d'état** : Sessions PHP
- **Serveur** : Apache/WAMP
- **Conteneurisation** : Docker, Docker Compose

## 🔮 Améliorations Futures

- [ ] Système de scoring et classement
- [x] Détection automatique des paires
- [ ] Limite de temps par partie
- [ ] Niveaux de difficulté (grille 3x3, 5x5, etc.)
- [x] Design amélioré avec animations CSS
- [ ] Persistance des données (base de données)
- [ ] Mode multijoueur
- [ ] Stockage des high-scores

## 📊 Statistiques du Projet

| Métrique | Valeur |
|----------|--------|
| Nombre de cartes | 16 |
| Nombre de paires | 8 |
| Fichiers PHP | 4 |
| Lignes de code | ~120 |

## 🤝 Contribution

Les contributions sont les bienvenues ! Pour proposer des améliorations

## 👨‍💻 Auteur

Développé avec ❤️ par Killian DUMOULIN

- GitHub: [Jillian-973](https://github.com/Jillian-973)
- Portfolio: [Jillian](https://jillian-973.github.io/Dev_Portfolio/)
---

**Amusez-vous bien ! 🎮**
