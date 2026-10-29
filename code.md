# 📖 Comment fonctionne le code du Memory Game

Ce document explique **pas à pas** comment le jeu fonctionne, pour qu'une personne qui débute en PHP puisse comprendre la logique et modifier le code.

---

## 1. Le principe du jeu

- Il y a **16 cartes** posées face cachée (elles affichent `?`).
- Chaque symbole (rune, jade, saphyre…) existe en **2 exemplaires** : il y a donc **8 paires**.
- On retourne **2 cartes** à chaque tour :
  - si elles ont le même symbole → c'est une **paire**, elles restent visibles (fond vert clair avec une coche ✓) ;
  - sinon → elles restent affichées jusqu'au **clic suivant**, puis elles se recachent.
- Quand les 8 paires sont trouvées, on arrive sur la **page de fin**, qui affiche le nombre d'essais.

---

## 2. Les notions à connaître avant de lire le code

### 2.1 Une page PHP = une requête

À chaque fois que tu cliques sur un lien, le navigateur demande une page au serveur. Le serveur exécute le fichier PHP **du début à la fin**, renvoie le HTML, puis **oublie tout** : les variables PHP classiques (`$maVariable`) sont perdues.

👉 Problème : comment se souvenir des cartes retournées d'un clic à l'autre ?

### 2.2 La session (`$_SESSION`)

La **session** est une mémoire que le serveur garde **pour chaque joueur**, d'une page à l'autre.

```php
session_start();                 // obligatoire en haut de chaque page qui utilise la session
$_SESSION['nombreEssais'] = 0;   // on écrit dans la session
echo $_SESSION['nombreEssais'];  // on la relit sur une autre page
```

⚠️ `session_start()` doit être appelé **avant tout affichage** (avant le moindre HTML ou `echo`), sinon PHP peut afficher une erreur.

Tout l'état du jeu est rangé dans la session :

| Clé de session | Contenu | Exemple |
|---|---|---|
| `$_SESSION['carte']` | Le tableau des 16 cartes (mélangées) | voir plus bas |
| `$_SESSION['maxCartesSelectionner']` | Nombre de cartes retournées pendant le tour en cours (0, 1 ou 2) | `1` |
| `$_SESSION['last_clicked_card']` | L'`id` de la dernière carte cliquée | `9` |
| `$_SESSION['nombreEssais']` | Nombre de tours joués (une paire de cartes retournée = 1 essai) | `5` |

### 2.3 Les paramètres dans l'URL (`$_GET`)

Quand on clique sur une carte, le lien ressemble à :

```
click.php?carte=7
```

Tout ce qui est après le `?` arrive dans PHP dans le tableau `$_GET` :

```php
$_GET['carte']   // vaut "7"
```

C'est comme ça que `click.php` sait **quelle carte** a été cliquée.

### 2.4 La redirection (`header("Location: ...")`)

```php
header("Location: game.php");
```

Cette ligne dit au navigateur : « ne reste pas ici, va sur `game.php` ». `click.php` n'affiche donc jamais rien : il fait son travail puis renvoie le joueur vers le plateau.

On ajoute souvent `exit;` juste après pour arrêter le script tout de suite.

---

## 3. Une carte, c'est quoi ?

Chaque carte est un **tableau associatif** (un tableau avec des noms à la place des numéros) :

```php
["id" => 1, "name" => "rune", "selectioner" => false, "doublonTrouver" => false]
```

| Champ | Rôle |
|---|---|
| `id` | Numéro unique de la carte (1 à 16). Sert à savoir sur quelle carte on clique. |
| `name` | Le symbole. Deux cartes avec le même `name` forment une paire. |
| `selectioner` | `true` si la carte est **retournée en ce moment** (pendant le tour). |
| `doublonTrouver` | `true` si la carte fait partie d'une **paire déjà trouvée**. |

Une carte peut donc être dans **3 états** :

| `selectioner` | `doublonTrouver` | Ce que voit le joueur |
|---|---|---|
| `false` | `false` | Carte cachée `?` (cliquable) |
| `true` | `false` | Carte retournée : face blanche, on voit la pierre |
| `false` | `true` | Paire trouvée : vert clair avec une coche ✓, on voit la pierre (plus cliquable) |

---

## 4. Le parcours d'une partie

```
start.php  ──(START GAME)──►  game.php  ──(clic sur une carte)──►  click.php
                                 ▲                                    │
                                 └──────────── redirection ───────────┤
                                                                      │ toutes les paires trouvées
                                                                      ▼
                       start.php  ◄──(REJOUER)──  fin.php
```

- `start.php` prépare une nouvelle partie.
- `game.php` **affiche** le plateau (il ne modifie rien).
- `click.php` **modifie** l'état du jeu (il n'affiche rien).
- `fin.php` affiche le résultat.

Cette séparation est importante : **une page affiche, une autre page fait les calculs**.

---

## 5. Les fichiers un par un

### 5.1 `start.php` — préparer une nouvelle partie

1. On démarre la session :
   ```php
   session_start();
   ```
2. On écrit la liste des 16 cartes dans `$valeurCartes` (8 symboles × 2).
3. On copie la liste, on la **mélange** et on la range dans la session :
   ```php
   $carte = $valeurCartes;
   shuffle($carte);              // mélange le tableau au hasard
   $_SESSION['carte'] = $carte;
   ```
   > `shuffle()` modifie directement le tableau qu'on lui donne (il ne renvoie pas un nouveau tableau).
4. On remet tous les compteurs à zéro :
   ```php
   $_SESSION['last_clicked_card'] = null;
   $_SESSION['maxCartesSelectionner'] = 0;
   $_SESSION['nombreEssais'] = 0;
   ```
5. On affiche la page d'accueil : un aperçu de 4 cartes, les règles en 3 étapes et le bouton **START GAME**, qui mène à `game.php`.

💡 Les boutons **EXIT GAME** et **REJOUER** renvoient aussi vers `start.php` : passer par cette page relance donc toujours une partie toute neuve.

---

### 5.2 `game.php` — afficher le plateau

Ce fichier ne fait **que lire** la session et transformer chaque carte en HTML. Il se lit en 3 parties.

#### Partie 1 : calculer les stats

Avant d'afficher quoi que ce soit, on parcourt les cartes pour compter les paires trouvées :

```php
$pairesTrouver = 0;
$dernierTrouver = false;
foreach($_SESSION['carte'] as $key) {
    if($key["doublonTrouver"] == true) {
        $pairesTrouver++;
        if($key["id"] == $_SESSION['last_clicked_card']) {
            $dernierTrouver = true;   // la carte qu'on vient de cliquer a complété une paire
        }
    }
}
$pairesTrouver = $pairesTrouver / 2;                // 2 cartes trouvées = 1 paire
$pairesTotal = count($_SESSION['carte']) / 2;       // 16 / 2 = 8
$erreurs = $_SESSION['nombreEssais'] - $pairesTrouver;
```

> Une **erreur**, c'est un essai qui n'a pas donné de paire : `erreurs = essais - paires trouvées`.

Ensuite, on choisit le **message** à afficher au joueur selon la situation :

| Situation | Message |
|---|---|
| `maxCartesSelectionner == 2` (2 cartes différentes visibles) | « Raté ! Cliquez sur une carte pour continuer. » (en rouge) |
| `maxCartesSelectionner == 1` | « Trouvez la carte jumelle ! » |
| `$dernierTrouver == true` | « Bien joué, une paire de plus ! » (en vert) |
| sinon | « Retournez deux cartes pour trouver une paire. » |

#### Partie 2 : afficher les stats

Les 3 cases **Essais**, **Paires** et **Erreurs** affichent simplement les variables calculées avec `echo`.

La barre de progression est une `div` dont on règle la largeur en pourcentage directement depuis PHP :

```php
<div class="progression-remplie" style="width: <?php echo $pairesTrouver / $pairesTotal * 100; ?>%"></div>
```

Avec 3 paires sur 8, ça donne `width: 37.5%`.

#### Partie 3 : afficher les cartes

```php
foreach($_SESSION['carte'] as $key ){

    // la dernière carte cliquée joue l'animation de retournement
    $anime = "";
    if($key["id"] == $_SESSION['last_clicked_card']){
        $anime = " anime";
    }

    if($key["doublonTrouver"] == true){
        // paire trouvée : fond vert clair + coche, pas de lien
        echo "<div class='card trouver " . $key["name"] . $anime . "'><span class='gem'></span><span class='nom'>" . $key["name"] . "</span></div>";
    }
    else if($key["selectioner"] == true){
        // carte retournée : on montre la pierre
        $rate = "";
        if($_SESSION['maxCartesSelectionner'] == 2){
            $rate = " rate";   // 2 cartes différentes : bordure rouge
        }
        echo "<div class='card selected " . $key["name"] . $rate . $anime . "'><span class='gem'></span><span class='nom'>" . $key["name"] . "</span></div>";
    }
    else
        // carte cachée : on montre "?" et un lien vers click.php
        echo "<a class='card' href='click.php?carte=" . $key["id"] . "'>?</a>";
}
```

- `foreach` passe sur les 16 cartes une par une ; `$key` est la carte en cours.
- Le `.` sert à **coller des morceaux de texte** ensemble en PHP.
- Seules les cartes cachées sont des liens (`<a>`) vers `click.php?carte=ID`. Les cartes retournées sont de simples `<div>` : on ne peut donc pas recliquer dessus.
- **L'astuce des classes CSS :** PHP ne dessine rien, il ajoute juste des **classes** à la carte, et c'est `style.css` qui s'occupe de l'apparence. Par exemple, une carte rubis retournée qu'on vient de cliquer donne :
  ```html
  <div class='card selected rubis anime'>...</div>
  ```
  - `card` → forme de la carte
  - `selected` → face blanche (carte retournée)
  - `rubis` → couleur de la pierre (rouge)
  - `anime` → animation de retournement

---

### 5.3 `style.css` — l'apparence du jeu

Tout le style est rangé dans **un seul fichier**, `style.css`, partagé par les 3 pages grâce à cette ligne dans leur `<head>` :

```html
<link rel="stylesheet" href="style.css">
```

Ainsi, si on change une couleur, elle change partout.

#### Les couleurs en variables

En haut du fichier, les couleurs sont définies **une seule fois** :

```css
:root {
    --vert: #4CAF50;
    --vert-hover: #45a049;
    --vert-fonce: #275f2a;
    ...
}
```

On les réutilise ensuite avec `var(--vert)`. Pour changer la couleur principale du jeu, il suffit de modifier `--vert`.

#### Les 3 états d'une carte

| Classe | Apparence |
|---|---|
| `.card` | Dos vert avec un `?`, un cadre blanc et un reflet. Elle se soulève au survol. |
| `.selected` | Face blanche avec bordure vert foncé, la pierre et son nom. |
| `.trouver` | Fond vert clair, bordure verte et une coche ✓ en haut à droite. |
| `.rate` | S'ajoute à `.selected` quand les 2 cartes ne vont pas ensemble : bordure rouge + secousse. |

#### Les pierres précieuses

Il n'y a **aucune image** : la pierre est une `span class='gem'` dessinée en CSS. `clip-path` découpe un carré en forme de diamant, et des dégradés imitent les facettes. La couleur vient de la variable `--couleur`, différente pour chaque pierre :

```css
.rubis   { --couleur: #d81b45; }
.saphyre { --couleur: #1e5bd8; }
...
```

Comme le `name` de la carte est ajouté en classe par PHP, la carte `rubis` reçoit automatiquement la couleur rouge.

#### Les animations

| Animation | Quand |
|---|---|
| `retourner` | La dernière carte cliquée (classe `anime`) pivote comme si on la retournait. |
| `secouer` | Les 2 cartes d'un essai raté tremblent. |
| `rebondir` | La carte qui complète une paire grossit légèrement, et le rond ✓ de la page de fin aussi. |

> Comme la page se recharge à chaque clic, seule la carte `last_clicked_card` reçoit la classe `anime`. Sinon, toutes les cartes retournées rejoueraient l'animation à chaque clic.

---

### 5.4 `click.php` — le cerveau du jeu

C'est ici que se trouvent **toutes les règles**. Le fichier se lit en 4 étapes, dans l'ordre.

#### Étape 0 : vérifier qu'on a bien reçu une carte

```php
if(isset($_GET['carte']) && isset($_SESSION['carte'])) {
```

`isset()` vérifie qu'une variable existe. Si quelqu'un ouvre `click.php` sans `?carte=...`, on ne fait rien et on retourne au plateau.

#### Étape 1 : recacher les 2 cartes du tour précédent (si besoin)

```php
if($_SESSION['maxCartesSelectionner'] >= 2) {
    foreach($_SESSION['carte'] as $index => $key) {
        $_SESSION['carte'][$index]["selectioner"] = false;
    }
    $_SESSION['maxCartesSelectionner'] = 0;
}
```

S'il y a déjà 2 cartes retournées, c'est que ce n'était **pas une paire** (sinon le compteur aurait été remis à 0, voir l'étape 3). Avant de retourner la nouvelle carte, on recache les deux autres et on remet le compteur à 0.

> ⚠️ **Point important :** on écrit `$_SESSION['carte'][$index][...] = ...` et pas `$key[...] = ...`.
> Dans un `foreach`, `$key` est une **copie** de la carte : la modifier ne change pas la session. Il faut passer par la position `$index` pour modifier la vraie carte.

#### Étape 2 : retourner la carte cliquée

```php
foreach($_SESSION['carte'] as $index => $key) {
    if($key["id"] == $_GET['carte'] && $key["selectioner"] == false && $key["doublonTrouver"] == false) {
        $_SESSION['carte'][$index]["selectioner"] = true;
        $_SESSION['last_clicked_card'] = $key["id"];
        $_SESSION['maxCartesSelectionner']++;
    }
}
```

On cherche la carte qui a le bon `id`. On ne la retourne **que si** elle est encore cachée : c'est une sécurité si quelqu'un tape l'URL à la main. Ensuite on ajoute 1 au compteur avec `++`.

#### Étape 3 : 2 cartes retournées → est-ce une paire ?

```php
if($_SESSION['maxCartesSelectionner'] == 2) {
    $_SESSION['nombreEssais']++;

    // on note la position des 2 cartes retournées
    $cartesSelectionner = [];
    foreach($_SESSION['carte'] as $index => $key) {
        if($key["selectioner"] == true) {
            $cartesSelectionner[] = $index;   // [] = "ajoute à la fin du tableau"
        }
    }

    // même symbole ?
    if($_SESSION['carte'][$cartesSelectionner[0]]["name"] == $_SESSION['carte'][$cartesSelectionner[1]]["name"]) {
        foreach($cartesSelectionner as $index) {
            $_SESSION['carte'][$index]["doublonTrouver"] = true;
            $_SESSION['carte'][$index]["selectioner"] = false;
        }
        $_SESSION['maxCartesSelectionner'] = 0;
    }
}
```

- On compte un essai de plus.
- On récupère les positions des 2 cartes retournées dans `$cartesSelectionner`.
- On compare leurs `name` :
  - **Paire :** les 2 cartes passent en `doublonTrouver` et le compteur revient à 0. Le joueur peut directement jouer le tour suivant.
  - **Pas une paire :** on ne fait rien. Les 2 cartes restent visibles pour que le joueur les mémorise, et c'est l'**étape 1 du prochain clic** qui les recachera.

#### Étape 4 : la partie est-elle terminée ?

```php
$doublonsTrouver = 0;
foreach($_SESSION['carte'] as $key) {
    if($key["doublonTrouver"] == true) {
        $doublonsTrouver++;
    }
}
if($doublonsTrouver == count($_SESSION['carte'])) {
    header("Location: fin.php");
    exit;
}
```

On compte les cartes trouvées. Si ce nombre est égal au nombre total de cartes (`count()` = 16), on part sur `fin.php`.

Sinon, on arrive à la dernière ligne, qui renvoie au plateau :

```php
header("Location: game.php");
```

---

### 5.5 `fin.php` — la page de victoire

1. Si aucune partie n'existe en session, on renvoie vers `start.php`.
2. On vérifie que **toutes** les cartes sont trouvées. Sinon (par exemple si quelqu'un tape `fin.php` dans l'URL en pleine partie), on renvoie vers `game.php`.
3. On calcule les stats de fin :
   ```php
   $pairesTotal = count($_SESSION['carte']) / 2;                         // 16 / 2 = 8 paires
   $erreurs = $_SESSION['nombreEssais'] - $pairesTotal;                  // essais qui n'ont rien donné
   $precision = round($pairesTotal / $_SESSION['nombreEssais'] * 100);   // % d'essais réussis
   ```
   > Exemple : 8 paires en 10 essais → 2 erreurs et 80 % de précision. Une partie parfaite (8 essais) donne 100 %.
4. On affiche « Bravo ! » avec 3 cases : **Essais**, **Erreurs** et **Précision**.
5. Le bouton **REJOUER** renvoie vers `start.php`.

---

## 6. Exemple concret d'un tour

Supposons que la carte `id 3` soit un **saphyre**, la carte `id 11` un **saphyre** et la carte `id 5` une **émeraude**.

| Action du joueur | Ce que fait `click.php` | Compteur | Résultat à l'écran |
|---|---|---|---|
| Clic sur `id 3` | retourne la carte 3 | 1 | saphyre visible |
| Clic sur `id 5` | retourne la carte 5, essai +1, saphyre ≠ émeraude | 2 | saphyre + émeraude visibles |
| Clic sur `id 11` | compteur à 2 → recache 3 et 5, puis retourne 11 | 1 | seulement le saphyre (11) visible |
| Clic sur `id 3` | retourne 3, essai +1, saphyre = saphyre ✅ | 0 | les 2 saphyres en vert clair avec une coche ✓ |

---

## 7. Utiliser et modifier le jeu

### Lancer le jeu
1. Démarre WAMP (l'icône doit être verte).
2. Ouvre `http://localhost/test/memory_game/start.php` dans le navigateur (adapte le chemin à l'endroit où se trouve le dossier dans `www`).
3. Clique sur **START GAME**.

### Changer les symboles
Dans `start.php`, modifie les `name` du tableau `$valeurCartes`. Chaque symbole doit apparaître **exactement 2 fois**, et chaque `id` doit rester **unique**.

### Ajouter des paires
Ajoute 2 lignes dans `$valeurCartes`, avec de nouveaux `id` (17, 18…) et le même `name`. Le reste du code s'adapte tout seul, car il utilise `count($_SESSION['carte'])` au lieu d'écrire `16` en dur. Pense juste à :
- ajuster la grille dans `style.css` si besoin (dans `.game-board`) :

```css
grid-template-columns: repeat(4, 1fr);   /* 4 = nombre de cartes par ligne */
```
- donner une couleur à la nouvelle pierre dans `style.css`, par exemple pour une topaze :
```css
.topaze { --couleur: #f5b301; }
```

### Changer les couleurs
Tout se passe dans `style.css` :
- la couleur principale du jeu → les variables en haut du fichier (`--vert`, `--vert-hover`, `--vert-fonce`)
- `.card` → dos des cartes cachées
- `.selected` → carte retournée
- `.trouver` → paire trouvée
- `.rubis`, `.saphyre`… → couleur de chaque pierre

### Déboguer
Pour voir ce qu'il y a dans la session, ajoute temporairement dans `game.php` (après `session_start()`) :

```php
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
```

Si le jeu se retrouve dans un état bizarre, repasse simplement par `start.php` pour tout remettre à zéro.
