Ceci est le repository de mon projet Health North.

Health North est une entreprise médicale qui requiert une application Web et une application mobile qui communiqueront toutes les deux avec une API afin d'interagir avec une BDD.

Le service informatique externe permettra aux patients de prendre et de consulter leurs réservations et services connexes, d'ajouter des alarmes de prise de médicament dans leur application mobile, de se créer un compte et de consulter leur dossier médical.

Techno utilisées : Draw.io, DbDiagram.io, PHP, Html, CSS, Doctrine ORM, Framework Symfony, XAMPP, Vscode, Git, Github, Bootstrap, Postman, Figma, MySQL, Nelmio bundle, JWT authentication, Fixture Bundle
Ressources documentaires principales : Studi.com, OpenClassroom, Stack Overflow

Pour tester l'API : 

- Créez un dossier à l'emplacement de votre choix, ouvrez un terminal en admin et rendez vous dans ce dossier.
- Exécutez la commande : git clone https://github.com/Paul-prog44/NorthHealthAPI.git
- Se rendre à la racine du projet et tapez : composer install
- Renseignez le fichier .env en mettant le login et mdp de votre base de donnée.
- Créez la Bdd en exécutant la commande php bin/console doctrine:database:create 
- Demandez a Doctrine de créer les tables et les relations grâce à : php bin/console doctrine:schema:update --force
- Remplissez la BDD avec des valeurs de test en exécutant la commande : php bin/console doctrine:fixtures:load
- Generez les clés publiques et privées à l'aide de : php bin/console lexik:jwt:generate-keypair (Open SSL requis)

- Vous pouvez ensuite lancer le serveur symfony à la commande symfony server:start depuis le dossier health_north_api
- Rendez-vous dans postman pour vous connecter, faites une requête POST à cette adresse : https://127.0.0.1:8000/api/login_check en mettant :
{
    "username": "admin@healthnorthapi.com",
    "password": "password"
}
dans le corps de la requête, vous obtiendrez un token d'une durée de vie de 10h que vous devrez passer dans vos requêtes vers l'API.

La documentation de l'API sera directement accessible dans votre navigateur à cette adresse : https://127.0.0.1:8000/api/doc
