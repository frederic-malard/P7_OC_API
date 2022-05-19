# projet pour openclassrooms.com
API restful, niveau 3 sur le modèle de richardson, créée avec API Platform et Symfony 4

## SITE EN LIGNE

le site web est en ligne, à l'adresse https://api.frederic-malard.com

## INSTALLATION

### cloner le projet
1.1. Sur github, lorsque vous consultez le projet, en haut à droite, veuillez cliquer sur le bouton vert « clone or download » et copier le texte indiqué.\
1.2. Dans un terminal, placez vous dans le dossier souhaité, et clonez le projet avec la commande « git clone [ce que vous avez copié à l'étape 1.1] »

### Installer les fichiers manquants
2.1. si vous n'avez pas composer, installez le, en suivant les instructions sur getcomposer.org/download\
2.2. utilisez le en version 1, pour éviter les problèmes de compatiblité : composer self-update --1 (pour revenir à la version à laquelle vous étiez : composer self-update --rollback)\
2.3. Lancez la commande « composer install » pour installer toutes les dépendances

### Base de données
3.1. importez la base de données (fichier SQL présent dans le repository) via votre SGBD (par exemple, via phpmyadmin)\
3.2. modifiez le fichier ".env" avec vos propres informations de base de données et de mail
