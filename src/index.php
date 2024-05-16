<?php

use App\Builder\BookBuilder;
use App\Singleton\Archive;
use App\Singleton\Librarian;

require_once __DIR__ . "/../vendor/autoload.php";

function userInteract()
{
    $choice = null;
    while ($choice === null) {
        echo "1. Créer un livre\n";
        echo "2. Afficher tous les livres\n";
        echo "3. Rechercher un livre\n";
        echo "4. Trier les livres\n";
        echo "5. Afficher l'historique\n";
        echo "- - - - - - -\n";
        echo "0. Quitter\n";
        echo "Votre choix : ";

        $choice = trim(fgets(STDIN));
        // clear the console
        echo "\033[2J\033[;H";
        switch ($choice) {
            case '1':
                // Creation d'un livre
                $bookBuilder = new BookBuilder();
                $bookBuilder->createBook();
                echo "- - - - - - -" . PHP_EOL;
                echo "Appuyez sur Entrée pour revenir au menu principal...";
                fgets(STDIN);
                echo "\033[2J\033[;H";
                userInteract();
                break;
            case '2':
                // Affichage de tous les livres
                echo "Affichage de tous les livres...\n\n";
                $books = Librarian::getInstance()->getBooks();
                Librarian::getInstance()->displayBooks($books);
                displayBooksOptions($books);
                break;
            case '3':
                // Recherche d'un livre
                $books = Librarian::getInstance()->userSearchBooks();
                displayBooksOptions($books);
                break;
            case '4':
                // Trier les livres
                $books = Librarian::getInstance()->sortBooks();
                displayBooksOptions($books);
                break;
            case '5':
                // Afficher l'historique
                $history = Archive::getInstance()->getHistory();
                if (count($history) > 0) {
                    echo "Historique des actions : \n";
                    foreach ($history as $action) {
                        echo "  - " . $action . "\n";
                    }
                } else {
                    echo "Aucune action n'a été enregistrée dans l'historique.\n";
                }
                echo "- - - - - - -" . PHP_EOL;
                echo "Appuyez sur Entrée pour revenir au menu principal...";
                fgets(STDIN);
                echo "\033[2J\033[;H";
                userInteract();
                break;
            case '0':
                echo "Au revoir !\n";
                exit(0);
            default:
                echo "Choix invalide. Veuillez choisir un nombre entre 1 et 4.\n";
                // relance le script 
                userInteract();
                break;
        }
    }
}

function displayBooksOptions($unindexedBooks)
{
    if (empty($unindexedBooks)) {
        echo "Aucun livre trouvé.\n";
        return;
    }
    $books = [];
    foreach ($unindexedBooks as $book) {
        $books[$book->getId()] = $book;
    }

    echo PHP_EOL . "Sélectionnez un livre pour plus des options ( 0 pour quitter ) : ";
    $choice = trim(fgets(STDIN));
    if ($choice === '0') {
        echo "\033[2J\033[;H";
        return userInteract();
    }

    if (isset($books[$choice])) {
        displayBookOptions($books[$choice], $unindexedBooks);
    } else {
        echo "Livre non trouvé.\n";
        displayBooksOptions($unindexedBooks);
    }
}

function displayBookOptions($book, $unindexedBooks)
{
    echo PHP_EOL . "Que voulez-vous faire avec le livre " . $book->getTitle() . " ?" . PHP_EOL;
    echo "1. Afficher les détails" . PHP_EOL;
    echo "2. Modifier le livre" . PHP_EOL;
    echo "3. Supprimer le livre" . PHP_EOL;
    echo "- - - - - - -" . PHP_EOL;
    echo "9. Retourner à la liste des livres\n";
    echo "0. Quitter\n";
    echo "Votre choix : ";

    $action = trim(fgets(STDIN));

    switch ($action) {
        case '1':
            // Display the book details
            echo "\033[2J\033[;H";
            $book->display(1);
            break;
        case '2':
            // Modify the book
            (new BookBuilder())->modifyBook($book);
            displayBookOptions($book, $unindexedBooks);
            break;
        case '3':
            // Delete the book
            (new BookBuilder())->removeBook($book);
            userInteract();
            break;
        case '9':
            // Return to the list of books
            echo "\033[2J\033[;H";
            Librarian::getInstance()->displayBooks($unindexedBooks);
            displayBooksOptions($unindexedBooks);
            break;
        case '0':
            // Quit the program
            exit(0);
            break;
        default:
            //clear console
            echo "\033[2J\033[;H";
            echo "Choix invalide. Veuillez choisir un nombre entre 1 et 3.\n";
            displayBookOptions($book, $unindexedBooks);
            break;
    }
}


echo "\033[2J\033[;H";
userInteract();
