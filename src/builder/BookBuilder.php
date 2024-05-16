<?php

namespace App\Builder;

use App\Models\Book;
use App\Singleton\Librarian;


class BookBuilder
{
    function createBook()
    {
        echo "Création d'un livre...\n";

        $book = new Book();

        $this->choseTitle($book);

        $this->choseDescription($book);

        $this->choseAuthor($book);

        $this->chosePublishedAt($book);

        $this->chosePrice($book);

        $this->choseCategory($book);

        $this->choseLanguage($book);

        $this->askIsAvailable($book);

        // clear console
        echo "\033[2J\033[;H";
        echo "Livre créé avec succès !\n";
        $book->display(1);

        // save book to json file
        $this->saveBookToDB($book);
    }

    public function modifyBook($book): void
    {
        // clear console
        echo "\033[2J\033[;H";

        echo "Que voulez-vous modifier ?" . PHP_EOL;
        echo "1. Titre" . PHP_EOL;
        echo "2. Description" . PHP_EOL;
        echo "3. Auteur" . PHP_EOL;
        echo "4. Catégorie" . PHP_EOL;
        echo "5. Date de publication" . PHP_EOL;
        echo "6. Langue" . PHP_EOL;
        echo "7. Prix" . PHP_EOL;
        echo "8. Disponibilité" . PHP_EOL;
        echo "- - - - - - - - -" . PHP_EOL;
        echo "9. Sauvegarder" . PHP_EOL;
        echo "0. Annuler" . PHP_EOL;
        echo "Votre choix : ";

        $choice = trim(fgets(STDIN));
        switch ($choice) {
            case '1':
                echo "\033[2J\033[;H";
                echo "Titre actuel : " . $book->getTitle() . PHP_EOL;
                echo "Nouveau ";
                (new BookBuilder())->choseTitle($book);
                echo "Modification effectuée avec succès !\n";
                $this->modifyBook($book);
                break;
            case '2':
                echo "\033[2J\033[;H";
                echo "Description actuelle : " . $book->getDescription() . PHP_EOL;
                echo "Nouvelle ";
                (new BookBuilder())->choseDescription($book);
                echo "Modification effectuée avec succès !\n";
                $this->modifyBook($book);
                break;
            case '3':
                echo "\033[2J\033[;H";
                echo "Auteur actuel : " . $book->getAuthor() . PHP_EOL;
                echo "Nouveau ";
                (new BookBuilder())->choseAuthor($book);
                echo "Modification effectuée avec succès !\n";
                $this->modifyBook($book);
                break;
            case '4':
                echo "\033[2J\033[;H";
                echo "Catégorie actuelle : " . $book->getCategory() . PHP_EOL;
                echo "Nouvelle ";
                (new BookBuilder())->choseCategory($book);
                echo "Modification effectuée avec succès !\n";
                $this->modifyBook($book);
                break;
            case '5':
                echo "\033[2J\033[;H";
                echo "Date de publication actuelle : " . $book->getPublishedAt() . PHP_EOL;
                echo "Nouvelle ";
                (new BookBuilder())->chosePublishedAt($book);
                echo "Modification effectuée avec succès !\n";
                $this->modifyBook($book);
                break;
            case '6':
                echo "\033[2J\033[;H";
                echo "Langue actuelle : " . $book->getLang() . PHP_EOL;
                echo "Nouveau ";
                (new BookBuilder())->choseLanguage($book);
                echo "Modification effectuée avec succès !\n";
                $this->modifyBook($book);
                break;
            case '7':
                echo "\033[2J\033[;H";
                echo "Prix actuel : " . $book->getPrice() . PHP_EOL;
                echo "Nouveau ";
                (new BookBuilder())->chosePrice($book);
                echo "Modification effectuée avec succès !\n";
                $this->modifyBook($book);
                break;
            case '8':
                echo "\033[2J\033[;H";
                echo "Disponibilité actuelle : " . ($book->getIsAvailable() ? "Disponible" : "Non disponible") . PHP_EOL;
                (new BookBuilder())->askIsAvailable($book);
                echo "Modification effectuée avec succès !\n";
                $this->modifyBook($book);
                break;
            case '9':
                $this->saveBookToDB($book);
                echo PHP_EOL . "Livre sauvegardé avec succès !\n";
                break;
            case '0':
                echo "Quitter...\n";
                break;
            default:
                echo "Choix invalide. Veuillez choisir un nombre entre 1 et 9.\n";
                $this->modifyBook($book);
                break;
        }
    }

    function choseTitle(&$book)
    {
        echo "Titre : ";
        $title = trim(fgets(STDIN));
        $book->setTitle($title);
    }

    function choseDescription(&$book)
    {
        echo "Description : ";
        $description = trim(fgets(STDIN));
        $book->setDescription($description);
    }

    function choseAuthor(&$book)
    {
        echo "Auteur : ";
        $author = trim(fgets(STDIN));
        $book->setAuthor($author);
    }

    function chosePublishedAt(&$book)
    {
        echo "Date de publication : ";
        $publishedAt = trim(fgets(STDIN));
        $book->setPublishedAt($publishedAt);
    }

    function chosePrice(&$book)
    {
        echo "Prix : ";
        $price = trim(fgets(STDIN));
        $book->setPrice((float) $price);
    }

    function choseCategory(&$book)
    {
        $choice = null;
        echo "Catégorie : \n";
        while ($choice === null) {
            echo "  1. Fiction\n";
            echo "  2. Histoire\n";
            echo "Votre choix : ";

            $choice = trim(fgets(STDIN));
            switch ($choice) {
                case '1':
                    $book->setCategory('Fiction');
                    break;
                case '2':
                    $book->setCategory('Histoire');
                    break;
                default:
                    // clear console
                    echo "Votre choix est invalide. Veuillez choisir un nombre entre 1 et 2.\n";
                    // relancer la selection de la catégorie 
                    $this->choseCategory($book);
                    break;
            }
        }
    }

    function choseLanguage(&$book)
    {
        $choice = null;
        echo "Langue : \n";
        while ($choice === null) {
            echo "  1. Français\n";
            echo "  2. Anglais\n";
            echo "Votre choix : ";

            $choice = trim(fgets(STDIN));
            switch ($choice) {
                case '1':
                    $book->setLang('Fr_fr');
                    break;
                case '2':
                    $book->setLang('Br_en');
                    break;
                default:
                    // clear console
                    echo "Votre choix est invalide. Veuillez choisir un nombre entre 1 et 2.\n";
                    // relancer la selection de la langue 
                    $this->choseLanguage($book);
                    break;
            }
        }
    }

    function askIsAvailable(&$book)
    {
        $choice = null;
        echo "Il y a des exemplaires disponibles ? (oui/non) : ";

        while ($choice === null) {
            $choice = trim(fgets(STDIN));
            switch ($choice) {
                case 'oui':
                    $book->setIsAvailable(true);
                    break;
                case 'non':
                    $book->setIsAvailable(false);
                    break;
                default:
                    // clear console
                    echo "Votre choix est invalide. Veuillez choisir entre oui et non.\n";
                    // relancer la selection de la disponibilité 
                    $this->askIsAvailable($book);
                    break;
            }
        }
    }


    function saveBookToDB($book)
    {
        // create data directory if not exists
        if (!is_dir(__DIR__ . '/../../data')) {
            mkdir(__DIR__ . '/../../data');
        }

        // create books.json file if not exists
        if (!file_exists(__DIR__ . '/../../data/books.json')) {
            file_put_contents(__DIR__ . '/../../data/books.json', '[]');
        }

        $books = json_decode(file_get_contents(__DIR__ . '/../../data/books.json'), true);

        if ($book->getId() !== null) {
            foreach ($books as $b) {
                if ($b->getId() === $book->getId()) {
                    $books[] = $book->toArray();
                } else {
                    $books[] = $b->toArray();
                }
            }
        }else{
            if (count($books) > 0) {
                $book->setId($books[count($books) - 1]['id'] + 1);
            } else {
                // first book
                $book->setId(1);
            }
        }

        // get all books from file
        $books[] = $book->toArray();
        file_put_contents(__DIR__ . '/../../data/books.json', json_encode($books, JSON_PRETTY_PRINT));
    }

    public function removeBook($book): void
    {
        $books = json_decode(file_get_contents(__DIR__ . '/../../data/books.json'), true);
        $newBooks = [];
        foreach ($books as $b) {
            if ($b['id'] !== $book->getId()) {
                $newBooks[] = $b->toArray();
            }
        }

        file_put_contents(__DIR__ . '/../../data/books.json', json_encode($newBooks, JSON_PRETTY_PRINT));

        echo "Livre supprimé avec succès !\n";
    }
}
