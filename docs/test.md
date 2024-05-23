PHPUnit est un framework de test unitaire pour le langage PHP qui permet d'écrire des tests automatisés pour vérifier que le code fonctionne comme prévu.

Si vous n'avez pas encore installé Composer, vous pouvez le télécharger depuis getcomposer.org.

pour installer PHPUnit, vous pouvez exécuter la commande suivante dans votre terminal :
composer require --dev phpunit/phpunit

Installation de PHPUnit via Composer :
Exécutez la commande suivante à la racine du votre projet :
composer require --dev phpunit/phpunit

Création de la structure des tests :
Pour organiser vos tests, vous pouvez créer un dossier tests/ à la racine de votre projet. Ce dossier contiendra tous vos fichiers de test unitaire.



Initialiser un projet avec Composer

    Créez un fichier composer.json à la racine de votre projet, ou exécutez la commande suivante pour initialiser un nouveau fichier:

    composer init

Pour chaque fonction ou classe que vous souhaitez tester, créez un fichier de test correspondant dans le répertoire tests/.

Par exemple, si vous avez des fonctions spécifiques à la carte dans maps/functions.php que vous souhaitez tester, vous pouvez créer un fichier MapFunctionsTest.php dans le répertoire tests/.

pour lancer le test:
php vendor/phpunit/phpunit/phpunit tests/MapFunctionsTest.php