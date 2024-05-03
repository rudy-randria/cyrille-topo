Dans une application PHP typique, les dossiers "controllers", "models" et "views" suivent généralement le modèle MVC (Modèle-Vue-Contrôleur) qui est un motif de conception largement utilisé pour organiser le code.

1. **Controllers (Contrôleurs)**:
   - Les contrôleurs sont responsables de la logique de l'application et de la gestion des interactions entre le modèle et la vue. 
   - Ils reçoivent les entrées de l'utilisateur, traitent les données à l'aide des modèles, puis passent les résultats aux vues appropriées pour l'affichage.
   - En règle générale, les contrôleurs reçoivent les requêtes HTTP, déterminent les actions à entreprendre en fonction de ces requêtes et invoquent les méthodes appropriées des modèles.

2. **Models (Modèles)**:
   - Les modèles représentent la logique métier de l'application. Ils sont responsables de la manipulation des données de l'application, de leur validation, de leur stockage et de leur récupération à partir de la base de données, le cas échéant.
   - Les modèles sont souvent associés à une table de la base de données dans le cadre d'une application qui utilise une base de données relationnelle. Ils encapsulent généralement les opérations de lecture, d'écriture, de mise à jour et de suppression (CRUD) des données.
   - Les modèles sont généralement appelés par les contrôleurs pour effectuer des opérations sur les données en réponse aux actions de l'utilisateur.

3. **Views (Vues)**:
   - Les vues sont responsables de l'affichage des données à l'utilisateur. Elles représentent l'interface utilisateur de l'application.
   - Les vues sont généralement des fichiers HTML avec des balises ou des directives de modèle spécifiques au framework utilisé (comme PHP pour du code dynamique).
   - Les vues reçoivent généralement des données du contrôleur et les affichent de manière appropriée pour l'utilisateur final.
   - Elles contiennent souvent du code PHP pour récupérer et afficher les données fournies par le contrôleur, ainsi que pour gérer la logique d'affichage conditionnelle ou itérative.

En résumé, dans une application PHP suivant l'architecture MVC, les contrôleurs traitent les entrées utilisateur, les modèles manipulent les données de l'application et les vues affichent ces données à l'utilisateur final. Cela permet une séparation claire des préoccupations et facilite la maintenance et l'évolutivité de l'application.