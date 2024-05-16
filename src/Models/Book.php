<?php

namespace App\Models;

class Book
{

    protected ?int $id = null;
    protected string $title;
    protected string $description;
    protected string $author;
    protected string $category;
    protected string $publishedAt;
    protected bool $isAvailable;
    protected string $lang;
    protected float $price;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                if ($value === 'true' || $value === 'false') {
                    $this->$key = $value === 'true';
                } else {
                    $this->$key = $value;
                }
            }
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    public function getPublishedAt(): string
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(string $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    public function getLang(): string
    {
        return $this->lang;
    }

    public function setLang(string $lang): void
    {
        $this->lang = $lang;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    public function getIsAvailable(): bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(bool $isAvailable): void
    {
        $this->isAvailable = $isAvailable;
    }

    public function display(int $spacing = 0): void
    {
        echo str_repeat("\t", $spacing) . "Titre: " . $this->getTitle() . PHP_EOL;
        echo str_repeat("\t", $spacing) . "Description: " . $this->getDescription() . PHP_EOL;
        echo str_repeat("\t", $spacing) . "Auteur: " . $this->getAuthor() . PHP_EOL;
        echo str_repeat("\t", $spacing) . "Catégorie: " . $this->getCategory() . PHP_EOL;
        echo str_repeat("\t", $spacing) . "Publié le: " . $this->getPublishedAt() . PHP_EOL;
        echo str_repeat("\t", $spacing) . "Langue: " . $this->getLang() . PHP_EOL;
        echo str_repeat("\t", $spacing) . "Prix: " . $this->getPrice() . PHP_EOL;
        echo str_repeat("\t", $spacing) . "Disponibilité: " . ($this->getIsAvailable() ? "Disponible" : "Non disponible") . PHP_EOL;
    }

    public function displayShortDetail(int $spacing = 0): void
    {
        echo str_repeat("\t", $spacing) . $this->getId() . ". " . $this->getTitle() . ' (' . $this->getLang() . ')' .
            " écrit par " . $this->getAuthor() . " à " . $this->getPrice() . "€ " . ($this->getIsAvailable() ? "" : " ( Non disponible )") . PHP_EOL;
    }

    public function toArray(): array
    {
        $props = get_object_vars($this);
        $data = [];
        foreach ($props as $key => $value) {
            if (is_bool($value)) {
                $data[$key] = $value ? 'true' : 'false';
            } else {
                $data[$key] = $value;
            }
        }
        return $data;
    }
}
