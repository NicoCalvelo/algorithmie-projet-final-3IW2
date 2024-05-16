<?php

use App\Builder\BookBuilder;
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
        echo "5. Quitter\n";
        echo "Votre choix : ";

        $choice = trim(fgets(STDIN));
        // clear the console
        echo "\033[2J\033[;H";
        switch ($choice) {
            case '1':
                // Creation d'un livre
                $bookBuilder = new BookBuilder();
                $bookBuilder->createBook();
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
                Librarian::getInstance()->sortBooks();
            case '5':
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
    echo "4. Retourner à la liste des livres\n";
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
        case '4':
            // Return to the list of books
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