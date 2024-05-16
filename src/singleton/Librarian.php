<?php

namespace App\Singleton;

use App\Builder\BookBuilder;
use App\Models\Book;

class Librarian
{
    private static ?Librarian $instance = null;
    private array $books = [];
    private array $jsonBooks = [];

    // à l'instantiation de la classe, on charge les livres depuis le fichier json
    private function __construct()
    {
        // Load books from json file
        if (is_dir(__DIR__ . '/../../data') && file_exists(__DIR__ . '/../../data/books.json')) {
            $books = json_decode(file_get_contents(__DIR__ . '/../../data/books.json'), true);
        } else {
            $books = [];
        }

        $this->jsonBooks = $books;

        foreach ($books as $book) {
            $this->books[] = new Book($book);
        }
    }

    // On recupère l'instance de la classe, si elle n'existe pas on l'instancie
    public static function getInstance(): Librarian
    {
        if (self::$instance === null) {
            self::$instance = new Librarian();
        }

        return self::$instance;
    }

    // fonction pour remonter les livres
    public function getBooks(): array
    {
        return $this->books;
    }

    // fonction pour afficher les livres
    public function displayBooks($books, int $space = 0): void
    {
        // On affiche un détail court de chaque livre
        foreach ($books as $book) {
            $book->displayShortDetail($space);
        }
    }

    // fonction qui lance la recherche des livres
    // intercation avec l'utilisateur pour choisir le critère de recherche
    public function userSearchBooks(): array
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
                $result = $this->searchBooksByValue('title');
                break;
            case '2':
                $result = $this->searchBooksByValue('author');
                break;
            case '3':
                $result = $this->searchBooksByValue('description');
                break;
            case '4':
                echo "Choisissez une catégorie : \n";
                echo "  1. Fiction\n";
                echo "  2. Histoire\n";
                echo "Votre choix : ";
                $choice = trim(fgets(STDIN));
                // vu qu'on a que 2 catégories, on peut se permettre de faire un ternaire
                // mais dans le cas ou on a plusieurs catégories le code ne sera pas correct
                $result = $this->searchBooksByValue('category', $choice === '1' ? 'Fiction' : 'Histoire');
                break;
            case '5':
                echo "Choisissez la disponibilité : \n";
                echo "  1. Disponible\n";
                echo "  2. Non disponible\n";
                echo "Votre choix : ";
                $choice = trim(fgets(STDIN));
                $result = $this->searchBooksByValue('isAvailable', ($choice === '1' ? 'true' : 'false'));
                break;
            default:
                echo "Choix invalide. Veuillez choisir un nombre entre 1 et 4.\n";
                return $this->userSearchBooks();
        }

        echo '( ' . count($result) . " livres trouvés. )" . PHP_EOL;
        $this->displayBooks($result, 1);

        return $result;
    }

    // Selon un champ et une valeur, on recherche les livres avec une methode de recherche binnaire
    public function searchBooksByValue($field, $value = null): array
    {
        $orderedBooks = $this->jsonBooks;
        // d'abord on trie les livres par le champ donné
        usort($orderedBooks, function ($a, $b) use ($field) {
            return strcmp(strtolower($a[$field]), strtolower($b[$field]));
        });


        // Si la valeur n'est pas donnée, on demande à l'utilisateur de la saisir
        if ($value === null) {
            echo "Votre recherche : ";
            $value = trim(fgets(STDIN));
        }

        $result = [];

        $left = 0;
        $right = count($orderedBooks) - 1;
        while ($left <= $right) {
            // On recupère l'indice du milieu
            $mid = floor(($left + $right) / 2);
            // Si la valeur est trouvée, on recupère les livres qui ont la même valeur
            if (stripos($orderedBooks[$mid][$field], $value) !== false) {
                $result[] = new Book($orderedBooks[$mid]);
                $i = $mid - 1;

                // Vu qu'on veut remonter une liste des livres
                // On parcourt les livres à gauche et à droite du livre trouvé
                // pour s'assurer qu'on a tous les livres qui répondent à la recherche
                while ($i >= 0 && stripos($orderedBooks[$i][$field], $value) !== false) {
                    $result[] = new Book($orderedBooks[$i]);
                    $i--;
                }
                $i = $mid + 1;
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

    /**
     * fonction pour le tri des livres
     * On demande à l'utilisateur de choisir le critère de tri
     * On demande si on veut trier les livres dans l'ordre croissant ou décroissant
     * On trie les livres, puis on les sauvegarde et on les affiche
     */
    public function sortBooks(): void
    {
        // les livres doivent pouvoir être triés dans l’ordre croissant ou décroissant 
        // en utilisant un tri fusion sur n’importe quelle colonne (une colonne à la fois), donc le nom, 
        // la description et s’il est disponible en stock ou non.
        $done = false;
        $sortProperties = [];

        while ($done == false) {
            if (empty($sortProperties)) {
                echo "Sur quel critère voulez-vous trier les livres ?" . PHP_EOL;
            } else {
                echo "Voulez-vous ajouter un autre critère de tri ?" . PHP_EOL;
            }

            if (!array_key_exists('title', $sortProperties)) {
                echo "1. Titre" . PHP_EOL;
            }
            if (!array_key_exists('author', $sortProperties)) {
                echo "2. Auteur" . PHP_EOL;
            }
            if (!array_key_exists('description', $sortProperties)) {
                echo "3. Description" . PHP_EOL;
            }
            if (!array_key_exists('category', $sortProperties)) {
                echo "4. Catégorie" . PHP_EOL;
            }
            if (!array_key_exists('isAvailable', $sortProperties)) {
                echo "5. Disponibilité" . PHP_EOL;
            }

            // Si on a déjà un critère de tri, on propose de trier les livres
            if (!empty($sortProperties)) {
                echo "-------------------" . PHP_EOL;
                echo "6. Trier les livres" . PHP_EOL;
            }

            echo "Votre choix : ";
            $choice = trim(fgets(STDIN));
            echo "\033[2J\033[;H";

            switch ($choice) {
                case '1':
                    if (array_key_exists('title', $sortProperties))
                        echo "Le titre est déjà choisi.\n";
                    else
                        $sortProperties['title'] = 'title';
                    break;
                case '2':
                    if (array_key_exists('author', $sortProperties))
                        echo "L'auteur est déjà choisi.\n";
                    else
                        $sortProperties['author'] = 'author';
                    break;
                case '3':
                    if (array_key_exists('description', $sortProperties))
                        echo "La description est déjà choisie.\n";
                    else
                        $sortProperties['description'] = 'description';
                    break;
                case '4':
                    if (array_key_exists('category', $sortProperties))
                        echo "La catégorie est déjà choisie.\n";
                    else
                        $sortProperties['category'] = 'category';
                    break;
                case '5':
                    if (array_key_exists('isAvailable', $sortProperties))
                        echo "La disponibilité est déjà choisie.\n";
                    else
                        $sortProperties['isAvailable'] = 'isAvailable';
                    break;
                case '6':
                    $done = true;
                    break;
                default:
                    echo "Choix invalide. Veuillez choisir un nombre entre 1 et 6.\n";
                    break;
            }
        }

        $asc = false;
        echo "Voulez-vous trier les livres dans l'ordre croissant ? (oui/non) : ";
        $choice = trim(fgets(STDIN));
        if ($choice === 'oui') {
            $asc = true;
        }

        // On trie les livres
        $sortedBooksJSON = $this->jsonBooks;
        usort($sortedBooksJSON, function ($a, $b) use ($sortProperties, $asc) {
            foreach ($sortProperties as $sortProperty) {
                if ($a[$sortProperty] === $b[$sortProperty]) {
                    continue;
                }

                if ($asc) {
                    return strcmp(strtolower($a[$sortProperty]), strtolower($b[$sortProperty]));
                }

                return strcmp(strtolower($b[$sortProperty]), strtolower($a[$sortProperty]));
            }

            return 0;
        });
        $this->jsonBooks = $sortedBooksJSON;

        // On sauvegarde les livres triés
        (new BookBuilder())->saveBooks($sortedBooksJSON);

        // On affiche les livres triés, pour ça il faut les instancier
        $this->books = [];
        foreach ($sortedBooksJSON as $book) {
            $this->books[] = new Book($book);
        }
        $this->displayBooks($this->books);
        displayBooksOptions($this->books);
    }
}
