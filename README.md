# 🎮 Memory Game

Un jeu de mémoire interactif et amusant développé avec PHP. Trouvez les paires de cartes en testant votre mémoire !

![Memory Game](https://img.shields.io/badge/PHP-7.4+-blue?style=flat-square) ![Status](https://img.shields.io/badge/Status-Active-success?style=flat-square) ![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

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

### Prérequis
- **PHP** 7.4 ou supérieur
- **Serveur web** (Apache, WAMP, etc.)
- **cURL** activé (optionnel)

### Étapes

1. **Clonez le repository**
```bash
git clone https://github.com/[votre-utilisateur]/memory_game.git
cd memory_game
```

2. **Placez les fichiers sur votre serveur**
```bash
# Copiez le dossier dans le répertoire web de votre serveur
cp -r memory_game /var/www/html/
# ou pour WAMP
# C:\wamp64\www\memory_game\
```

3. **Lancez le jeu**
Accédez à : `http://localhost/memory_game/start.php`

## 💻 Utilisation

1. Cliquez sur le bouton **"START GAME"** pour commencer une nouvelle partie
2. **Cliquez sur les cartes** pour les retourner et voir leur contenu
3. Vous pouvez sélectionner jusqu'à **2 cartes par tour**
4. Les cartes sélectionnées s'affichent en couleur plus foncée
5. Cliquez sur **"EXIT GAME"** pour retourner à l'accueil

## 🛠️ Architecture Technique

### Structure des fichiers

```
memory_game/
├── start.php       # Page d'accueil - Initialisation du jeu
├── game.php        # Affichage du plateau de jeu
├── click.php       # Gestion des clics et logique de jeu
├── fin.php         # Page de fin (à développer)
└── README.md       # Documentation
```

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

## 🔮 Améliorations Futures

- [ ] Système de scoring et classement
- [ ] Détection automatique des paires
- [ ] Limite de temps par partie
- [ ] Niveaux de difficulté (grille 3x3, 5x5, etc.)
- [ ] Design amélioré avec animations CSS
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

## 📝 Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 👨‍💻 Auteur

Développé avec ❤️ par Killian DUMOULIN

- GitHub: [Jillian-973](https://github.com/Jillian-973)
- Portfolio: [Jillian](https://jillian-973.github.io/Dev_Portfolio/)
---

**Amusez-vous bien ! 🎮**
