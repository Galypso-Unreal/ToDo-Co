Guide de Contribution au Projet ToDo & Co

Ce document vise à fournir des directives claires aux développeurs qui contribuent au projet ToDo & Co. Les contributions peuvent inclure des corrections de bugs, des améliorations de fonctionnalités, des tests, de la documentation, etc.

   # **Avant de Commencer**

   Avant de commencer à contribuer, assurez-vous d'avoir :

   **Compréhension de Symfony** : avoir une connaissance de base de Symfony et de ses concepts clés.

   **Compte GitHub** : créez un compte GitHub si vous n'en avez pas déjà un.

   **Installation Locale** : configurez un environnement de développement Symfony 6.4.7 sur votre machine locale.

   **Familiarisation avec Git** : avoir une connaissance de base de Git pour cloner, créer des branches, fusionner des demandes d'extraction, etc.

   # **1.Processus de Contribution**

   ## **2.1 Lire le README**

   Avant de commencer toute collaboration avec ce projet, il vous est demandé de lire le **README** du projet que vous pouvez retrouver à la racine du projet.

   ## **2.2 Choix d'une tâche**

   Avant de commencer à coder, sélectionnez une tâche à partir de la liste des problèmes ouverts sur le dépôt. Si vous voulez ajouter une tâche, merci de fournir le temps approximatif pour résoudre ce problème et rédiger le nom du problème en anglais.

   Le nom de la tâche doit être rédigé de la manière suivante : 

   Numéro de la branche - [OPERATION] description rapide de la tâche

   Exemple : [5 - \[ADD\] PHPUnit tests implementations](https://github.com/Galypso-Unreal/ToDo-Co/issues/5)

   Voici un exemple des différentes opérations :

   ADD : ajouter de nouveaux fichiers ou de nouvelles fonctionnalités.

   UPD : mettre à jour des fonctionnalités existantes ou des fichiers.

   FIX : corriger des bugs ou des erreurs dans le code.

   REFA : réorganiser ou améliorer le code sans changer son comportement externe.

   RVT : annuler un ou plusieurs commits précédents.

   RMV : supprimer des fichiers ou du code.

   ## **2.3 Création d'une branche**

   Créez une branche dédiée à votre tâche. Utilisez un nom significatif pour la branche en anglais, par exemple :

   numéro de la branche-opération-fonctionnalité

   Ex : 11-add-registeration-account-page

   Via l’interface de github, vous pouvez directement créer une branche via le bouton «development create branch»

   ## **2.4 Codage**

   Développez la fonctionnalité ou corrigez le bug selon les meilleures pratiques de codage Symfony.

   Respectez les conventions de codage de Symfony et de PSR-12. Utilisez des outils comme PHP CS Fixer pour assurer la conformité :

   composer require --dev friendsofphp/php-cs-fixer

   ./vendor/bin/php-cs-fixer fix

   Ou vous pouvez utiliser un outil externe comme par exemple : Codacy -> <https://www.codacy.com/>

   ## **2.5 Tests**

   Assurez-vous d'écrire des tests unitaires et/ou fonctionnels pour votre code. Exécutez tous les tests existants pour garantir qu'ils passent avec succès. Une couverture de 70% minimum est nécessaire sur ce projet.

   Si vous ne possédez pas phpunit vous pouvez lancer avec la commande suivante :

   ./vendor/bin/phpunit qui permettra aussi de tester le code.

   Vous devez ensuite générer une couverture du code en HTML avec le code suivante : 

   ./vendor/bin/phpunit --coverage-html tests/Coverage.

   Attention le Coverage devra être obligatoirement dans le dossier suivant : tests/Coverage

   ## **2.6 Documentation**

   Mettez à jour la documentation si nécessaire pour refléter les changements apportés.

   Dans le code, au minimum une description de la fonctionnalité est nécessaire. Vous pouvez ajouter une documentation écrite si une installation de bundle est nécessaire et des exemples de code.

   ## **2.7 Validation Locale**

   Testez votre code localement pour vous assurer qu'il fonctionne correctement et qu'il ne casse pas les fonctionnalités existantes. Vous devez effectuer une vérification manuelle du code (effectué un test utilisateur) et exécuté les tests unitaires et fonctionnels pour confirmer le fonctionnement du code.

   ## **2.8 Processus de Qualité**

   Avant de soumettre votre demande de merge, assurez-vous de respecter les normes de qualité suivantes :

   **Conventions de Codage** : suivez les conventions de codage Symfony pour assurer la cohérence du code.

   **Performance** : vérifiez que votre code n'a pas d'impact négatif sur les performances de l'application. Vous pouvez vous aider du profiler de Symfony ou d’un autre outil externe.

   **Sécurité** : assurez-vous que votre code respecte les bonnes pratiques de sécurité.

   **Fiabilité** : testez votre code dans différentes conditions pour garantir sa fiabilité et prévenir des risques de rétrogradation du code ayant pour impacte le non fonctionnement des fonctionnalités existantes.

   **Maintenabilité** : écrivez du code propre et bien organisé pour faciliter la maintenance future.
   ## **2.9 Commit**
   Le nom des commits doivent être rédigé en anglais de la manière suivante :

   Opération + [Context] + description rapide de la tâche

   Soit : ADD [user] registeration account page

   ## **2.10 Soumission de la tâche effectuée (Pull Request)**

   Une fois que votre code est prêt, soumettez une demande d'extraction sur le dépôt.

   Incluez une description claire de vos modifications.

   Mentionnez les problèmes liés à votre PR s'il y en a.

   ## **2.11 Révision et Feedback**
   
   Les mainteneurs du projet examineront votre demande. Attendez leurs commentaires et apportez des modifications si nécessaire. N’envoyé pas votre modification si le code n’a pas été vérifié par une personne tierce du projet.

   ## **2.12 Fusion**
   
   Une fois que votre PR est approuvée, elle sera fusionnée dans la branche de la version de développement du projet nommé **develop.**

   Chaque PR déclenche une pipeline CI qui exécute les tâches suivantes :

      1. Installation du php nécessaire
      2. Installation du vendor via composer install
      3. Installation de php unit
      4. Test des fonctionnalités via phpunit

   Si la pipeline échoue, veuillez relire votre code et apporter les modifications nécessaires pour faire fonctionner les tests unitaires et fonctionnels.

   ## **2.13 Mise en production**
   Pour finir, lorsque les modifications apportées fonctionnent sur la version de développement, la branche sera envoyée sur la version de production (branche **main**).
