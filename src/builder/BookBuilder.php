<?php

namespace App\Builder;

use App\Models\Book;
use App\Singleton\Archive;

/**
 * Cette classe gère la persistance des livres
 * 
 * Seulement 3 methodes sont accessibles depuis l'extérieur de la classe :
 *   - createBook() : pour créer un livre
 *   - modifyBook($book) : pour modifier un livre
 *   - removeBook($book) : pour supprimer un livre
 * 
 * Les autres méthodes sont privées ( ce sont de setter et la methode de sauvegarde )
 */
class BookBuilder
{
    // fonction pour créer un livre, il l'affiche après la création
    public function createBook()
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

        // ajout ligne dans l'historique
        Archive::getInstance()->addHistory("Création du livre : " . $book->getTitle());

        // save book to json file
        $this->saveBookToDB($book);
    }

    // fonction pour modifier un livre
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
        echo "\033[2J\033[;H";
        switch ($choice) {
            case '1':
                echo "Titre actuel : " . $book->getTitle() . PHP_EOL;
                echo "Nouveau ";
                $this->choseTitle($book);
                echo "Modification effectuée avec succès !\n";
                $this->modifyBook($book);
                break;
            case '2':
                echo "Description actuelle : " . $book->getDescription() . PHP_EOL;
                echo "Nouvelle ";
                $this->choseDescription($book);
                echo "Modification effectuée avec succès !\n";
                $this->modifyBook($book);
                break;
            case '3':
                echo "Auteur actuel : " . $book->getAuthor() . PHP_EOL;
                echo "Nouveau ";
                $this->choseAuthor($book);
                echo "Modification effectuée avec succès !\n";
                $this->modifyBook($book);
                break;
            case '4':
                echo "Catégorie actuelle : " . $book->getCategory() . PHP_EOL;
                echo "Nouvelle ";
                $this->choseCategory($book);
                echo "Modification effectuée avec succès !\n";
                $this->modifyBook($book);
                break;
            case '5':
                echo "Date de publication actuelle : " . $book->getPublishedAt() . PHP_EOL;
                echo "Nouvelle ";
                $this->chosePublishedAt($book);
                echo "Modification effectuée avec succès !\n";
                $this->modifyBook($book);
                break;
            case '6':
                echo "Langue actuelle : " . $book->getLang() . PHP_EOL;
                echo "Nouveau ";
                $this->choseLanguage($book);
                echo "Modification effectuée avec succès !\n";
                $this->modifyBook($book);
                break;
            case '7':
                echo "Prix actuel : " . $book->getPrice() . PHP_EOL;
                echo "Nouveau ";
                $this->chosePrice($book);
                echo "Modification effectuée avec succès !\n";
                $this->modifyBook($book);
                break;
            case '8':
                echo "Disponibilité actuelle : " . ($book->getIsAvailable() ? "Disponible" : "Non disponible") . PHP_EOL;
                $this->askIsAvailable($book);
                echo "Modification effectuée avec succès !\n";
                $this->modifyBook($book);
                break;
            case '9':
                $this->saveBookToDB($book);
                echo PHP_EOL . "Livre sauvegardé avec succès !\n";
                // ajout ligne dans l'historique
                Archive::getInstance()->addHistory("Modification du livre : " . $book->getTitle());
                // On relance le script
                userInteract();
                break;
            case '0':
                echo "Quitter...\n";
                userInteract();
                break;
            default:
                echo "Choix invalide. Veuillez choisir un nombre entre 1 et 9.\n";
                break;
        }
    }

    // fonction pour choisir le titre du livre
    private function choseTitle(&$book)
    {
        echo "Titre : ";
        $title = trim(fgets(STDIN));
        $book->setTitle($title);
    }

    // fonction pour choisir la description du livre
    private function choseDescription(&$book)
    {
        echo "Description : ";
        $description = trim(fgets(STDIN));
        $book->setDescription($description);
    }

    // fonction pour choisir l'auteur du livre
    private function choseAuthor(&$book)
    {
        echo "Auteur : ";
        $author = trim(fgets(STDIN));
        $book->setAuthor($author);
    }

    // fonction pour choisir la date de publication du livre
    private function chosePublishedAt(&$book)
    {
        echo "Date de publication : ";
        $publishedAt = trim(fgets(STDIN));
        $book->setPublishedAt($publishedAt);
    }

    // fonction pour choisir le prix du livre
    private function chosePrice(&$book)
    {
        echo "Prix : ";
        $price = trim(fgets(STDIN));
        $book->setPrice((float) $price);
    }

    // fonction pour choisir la catégorie du livre
    private function choseCategory(&$book)
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

    // fonction pour choisir la langue du livre
    private function choseLanguage(&$book)
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

    // fonction pour choisir la disponibilité du livre
    private function askIsAvailable(&$book)
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

    // fonction pour sauvegarder le livre dans le fichier json
    private function saveBookToDB($book)
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
        } else {
            if (count($books) > 0) {
                $book->setId($books[count($books) - 1]['id'] + 1);
            } else {
                // first book
                $book->setId(1);
            }
        }

        // get all books from file
        $books[] = $book->toArray();
        $this->saveBooks($books);
    }

    // fonction pour supprimer un livre
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

        // ajout ligne dans l'historique
        Archive::getInstance()->addHistory("Suppression du livre : " . $book->getTitle());
    }

    // fonction pour sauvegarder les livres dans le fichier json
    // utilise pour la fonction de trie des livres
    public function saveBooks($books)
    {
        file_put_contents(__DIR__ . '/../../data/books.json', json_encode($books, JSON_PRETTY_PRINT));
    }
}
