# Mediatekformation partie back-office

## Presentation
Ce site, développé avec Symfony 5.4, permet d'accéder aux vidéos d'auto-formation proposées par une chaîne de médiathèques et qui sont aussi accessibles sur YouTube.<br> 
Sur ce repo, seule la partie back-office à été codée. Pour retrouver le repo et le readme de la partie front: https://github.com/CNED-SLAM/mediatekformation <br>

Pour accéder à la partie back office, il faut emprunter la route /admin après le /public <br>
Vous tomberez sur cette page : (l'authentification est  gérée avec keycloaok sur une machine virtuelle en ligne)

![Screenshot 2023-12-11 123516](https://github.com/Yuvem10/mediatekformation/assets/116307236/9ac918d1-b28b-46d0-ac95-8b1afb60eca9)

Ce readme se déroule une fois dans la partie admin. <br>

## Les différentes pages
Voici les pages correspondant aux différents cas d’utilisation.

## Gérer les formations
Cette page répertorie toutes les formations du site <br>
La partie du haut contient le menu permettant d'accéder aux 3 pages principales (Formations, Playlists, Catégories), ainsi que des filtres (les memes présent dans la partie front office pour trier les formations).
C'est à dire :<br>
La partie centrale contient un tableau composé de 5 colonnes :<br>
•	La 1ère colonne ("formation") contient le titre de chaque formation.<br>
•	La 2ème colonne ("playlist") contient le nom de la playlist dans laquelle chaque formation se trouve.<br>
•	La 3ème colonne ("catégories") contient la ou les catégories concernées par chaque formation (langage…).<br>
•	La 4ème colonne ("date") contient la date de parution de chaque formation.<br>
•	La 5ème contient le bouton modifier<br>
•	La 6ème contient le bouton supprimer<br>
Au niveau des colonnes "formation", "playlist" et "date", 2 boutons permettent de trier les lignes en ordre croissant ("<") ou décroissant (">").<br>
Au niveau des colonnes "formation" et "playlist", il est possible de filtrer les lignes en tapant un texte : seuls les lignes qui contiennent ce texte sont affichées. Si la zone est vide, le fait de cliquer sur "filtrer" permet de retrouver la liste complète.<br> 
Au niveau de la catégorie, la sélection d'une catégorie dans le combo permet d'afficher uniquement les formations qui ont cette catégorie. Le fait de sélectionner la ligne vide du combo permet d'afficher à nouveau toutes les formations.<br>
Par défaut la liste est triée sur la date par ordre décroissant (la formation la plus récente en premier).<br>
Au centre se regroupe toutes les formations avec pour chacune la possibilité de la modifier (en ouvrant un formulaire de modification) ou la supprimer du répertoire.<br>
Il y a également plus haut un bouton ajouter, permettant l'ajout d'un formation.<br>
![Screenshot 2023-12-11 123526](https://github.com/Yuvem10/mediatekformation/assets/116307236/3eb9b3bb-aaac-47d3-ab6f-a7a1e0edddd5)<br>

### Formulaire de modification d'une formation
Ce formulaire contient tous les attributs de la formation choisie qui sont pré-remplis dans ce formulaire. <br>
Tel que : le nom , la description, la date, la pylist correspondante, l'url de la vidéo et la catégorie. <br>
Tous ces attributs sont modifiable il est possible de tout changer et ensuite de soummettre le formulaire pour appliquer ces changements. <br>
Vous pouvez choisir plusieurs catégories mais une seule playlist. <br>
Il n'est cependant pas possible de saisir une url qui ne contient pas de vidéo, et de choisir une date postérieure à ajourd'hui. <br>
![Screenshot 2023-12-11 123541](https://github.com/Yuvem10/mediatekformation/assets/116307236/cbf7deda-a572-43ce-8001-d3e212b9592c)<br>


###  Formulaire d'ajout d'une formation
Ce formulaire est identique au précédent, il est juste vide cette fois. <br>
Il faut remplir les champs vides pour ajouter une formation qui sera visible dans le répertoire. <br>
Les champs obligatoires sont : le nom, la date, la playlist et l'url. <br>
Il n'est cependant pas possible de saisir une url qui ne contient pas de vidéo, et de choisir une date postérieure à ajourd'hui. <br>
![Screenshot 2023-12-11 123552](https://github.com/Yuvem10/mediatekformation/assets/116307236/53b107d1-da0c-41b5-bdc7-ea7404d7b932)<br>

## Gérer les playlists
Cette page présente les playlists.<br>
La partie centrale contient un tableau composé de 5 colonnes :<br>
•	La 1ère colonne ("playlist") contient le nom de chaque playlist.<br>
•	La 2ème colonne contient le nombre de formations dans la playlist<br>
• La 3ème colonne ("catégories") contient la ou les catégories concernées par chaque playlist (langage…). <br>
• La 4ème colonne contient le bouton modifier <br>
• La 5ème colonne contient le bouton supprimer<br>
Au niveau de la colonne "playlist", 2 boutons permettent de trier les lignes en ordre croissant ("<") ou décroissant (">"). Il est aussi possible de filtrer les lignes en tapant un texte : seuls les lignes qui contiennent ce texte sont affichées. Si la zone est vide, le fait de cliquer sur "filtrer" permet de retrouver la liste complète.<br> 
Au niveau de la catégorie, la sélection d'une catégorie dans le combo permet d'afficher uniquement les playlists qui ont cette catégorie. Le fait de sélectionner la ligne vide du combo permet d'afficher à nouveau toutes les playlists.<br>
Par défaut la liste est triée sur le nom de la playlist.<br>
![Screenshot 2023-12-11 123602](https://github.com/Yuvem10/mediatekformation/assets/116307236/d91a729e-bf1e-4ce0-9769-cd4465e59be3)<br>

### Modification d'une playlist
Ce formulaire contient tous les attributs de la playlist choisie qui sont pré-remplis dans ce formulaire. <br>
Tel que : le nom et la description<br>
Tous ces attributs sont modifiable il est possible de tout changer et ensuite de soummettre le formulaire pour appliquer ces changements. <br>
Il y a aussi la description générale de la playlist avec le même affichage qu'en front office. C'est à dire avec la liste des formations, le nombre et les infos écrites de la playlist. <br>
![Screenshot 2023-12-11 123612](https://github.com/Yuvem10/mediatekformation/assets/116307236/e9c5e34a-a584-4000-98db-d889100f20da)

### Ajout d'une playlist
L'ajout est tout simple c'est un formulaire contenant uniquement le nom et la description. <br>
Le  reste se gère automatiquement dans la partie formation. Et pour les catégorie l'application récupère les  catégories des formations que la plylist contient<br>
![Screenshot 2023-12-11 123621](https://github.com/Yuvem10/mediatekformation/assets/116307236/ac206ea7-b6a9-4730-a036-4c664ca38036)

## Gérer les catégories
La page des catégories est  plus simple. <br>
La page contient la liste de toutes les catégories avec pour chacune d'elle un bouton supprimer qui les enlevent de la liste. <br>
Elle dispose d'un petit formulaire à même la page pour ajouter le nom de la catégorie. <br>
Ce dernier s'ajoute automatiquement dans la liste des catégories. <br>
![Screenshot 2023-12-11 123632](https://github.com/Yuvem10/mediatekformation/assets/116307236/a9d96e96-bd90-4fee-bc96-b7bbec215dd4)

### Installer et utiliser l'application en local
Pour utiliser l'application en local vous devrez suivre les étapes suivantes: <br>

- Vérifier que Composer, Git et Wamserver (ou équivalent) sont installés sur l'ordinateur.<br>
- Télécharger le code et le dézipper dans www de Wampserver (ou dossier équivalent) puis renommer le dossier en "mediatekformation".<br>
- Ouvrir une fenêtre de commandes en mode admin, se positionner dans le dossier du projet et taper "composer install" pour reconstituer le dossier vendor.<br>
- Récupérer le fichier mediatekformation.sql en racine du projet et l'utiliser pour créer la BDD MySQL "mediatekformation" en root sans pwd (si vous voulez mettre un login/pwd d'accès, il faut le préciser dans le fichier ".env" en racine du projet).<br>
- De préférence, ouvrir l'application dans un IDE professionnel. L'adresse pour la lancer est : http://localhost/mediatekformation/public/index.php<br>
  
### Tester l'application en ligne
Le site: <br>
https://mediatekformation.shop <br>
La partie admin : <br>
https://mediatekformation.shop/mediatekformation/public/admin
