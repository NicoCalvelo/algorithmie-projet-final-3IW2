# Projet Final Algorithmie

L'structure est la suivante

- index.php -> exécute les fonctions qui permettent à l'utilisateur d'effectuer différentes actions.
- /singeltons/Librarian -> exécute des actions spécifiques à une librairie telles que trier et rechercher des livres.
- /singeltons/archive -> permet de gérer l'historique des actions réalisées dans le logiciel.
- /models/books -> Objet du livre.
- /builder/bookBuilder -> est en charge de la gestion du livre. Création, modification et suppression.

- Dossier /core -> Nous ne l'avons pas utilisé dans le projet, ce sont juste ce que nous avons vu en classe.


Points à considérer :
  - Les catégories et la langue des livres sont fixes. À certains endroits du code,
    nous nous permettons de faire de mauvaises pratiques à cause de cela. Une version
    plus complète devrait permettre d'ajouter des catégories et des langues et donc
    proposer les différentes options.
  - On ajoute des commentaires dans le code pour comprendre.
