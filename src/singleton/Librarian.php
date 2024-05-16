<?php

namespace App\Singleton;

use App\Builder\BookBuilder;
use App\Models\Book;

class Librarian
{
    private static ?Librarian $instance = null;
    private array $books = [];
    private array $jsonBooks = [];

    private function __construct()
    {
        // Load books from json file
        if (is_dir(__DIR__ . '/../../data')) {
            $books = json_decode(file_get_contents(__DIR__ . '/../../data/books.json'), true);
        } else {
            $books = [];
        }

        $this->jsonBooks = $books;

        foreach ($books as $book) {
            $this->books[] = new Book($book);
        }
    }

    public static function getInstance(): Librarian
    {
        if (self::$instance === null) {
            self::$instance = new Librarian();
        }

        return self::$instance;
    }

    public function getBooks(): array
    {
        return $this->books;
    }

    public function displayBooks($books, int $space = 0): void
    {
        // sort books by id
        usort($books, function ($a, $b) {
            return $a->getId() - $b->getId();
        });
        // display books
        foreach ($books as $book) {
            $book->displayShortDetail($space);
        }
    }

    public function searchBook(): array
    {
        echo "Sur quel critère voulez-vous effectuer la recherche ?" . PHP_EOL;
        echo "1. Titre" . PHP_EOL;
        echo "2. Auteur" . PHP_EOL;
        echo "3. Description" . PHP_EOL;
        echo "4. Catégorie" . PHP_EOL;
        echo "5. Disponibilité" . PHP_EOL;
        echo "Votre choix : ";
        $choice = trim(fgets(STDIN));
        echo "\033[2J\033[;H";
        
        switch ($choice) {
            case '1':
                $result = $this->searchBooks('title');
                break;
            case '2':
                $result = $this->searchBooks('author');
                break;
            case '3':
                $result = $this->searchBooks('description');
                break;
            case '4':
                echo "Choisissez une catégorie : \n";
                echo "  1. Fiction\n";
                echo "  2. Histoire\n";
                echo "Votre choix : ";
                $choice = trim(fgets(STDIN));
                $result = $this->searchBooks('category', $choice === '1' ? 'Fiction' : 'Histoire');
                break;
            case '5':
                echo "Choisissez la disponibilité : \n";
                echo "  1. Disponible\n";
                echo "  2. Non disponible\n";
                echo "Votre choix : ";
                $choice = trim(fgets(STDIN));
                $result = $this->searchBooks('isAvailable', ($choice === '1' ? 'true' : 'false'));
                break;
            default:
                echo "Choix invalide. Veuillez choisir un nombre entre 1 et 4.\n";
                return $this->searchBook();
        }

        echo '( ' . count($result) . " livres trouvés. )" . PHP_EOL;
        $this->displayBooks($result, 1);

        return $result;
    }

    public function searchBooks($field, $value = null): array
    {
        $orderedBooks = $this->jsonBooks;
        // Sort books by title
        usort($orderedBooks, function ($a, $b) {
            return strcmp(strtolower($a['title']), strtolower($b['title']));
        });


        // If the value is not provided, ask the user to enter it
        if($value === null){
            echo "Votre recherche : ";
            $value = trim(fgets(STDIN));
        }

        $result = [];

        $left = 0;
        $right = count($orderedBooks) - 1;
        while ($left <= $right) {
            // Find the middle value
            $mid = floor(($left + $right) / 2);
            // Check if the value is in the middle
            if (stripos($orderedBooks[$mid][$field], $value) !== false) {
                $result[] = new Book($orderedBooks[$mid]);
                $i = $mid - 1;
                // Check the left side of the middle value
                while ($i >= 0 && stripos($orderedBooks[$i][$field], $value) !== false) {
                    $result[] = new Book($orderedBooks[$i]);
                    $i--;
                }
                $i = $mid + 1;
                // Check the right side of the middle value
                while ($i < count($orderedBooks) && stripos($orderedBooks[$i][$field], $value) !== false) {
                    $result[] = new Book($orderedBooks[$i]);
                    $i++;
                }
                break;
            } elseif (strcmp(strtolower($orderedBooks[$mid][$field]), strtolower($value)) < 0) {
                $left = $mid + 1;
            } else {
                $right = $mid - 1;
            }
        }

        return $result;
    }

    public function sortBooks(): void
    {

    }
}
