<?php

declare(strict_types=1);

require_once __DIR__ . '/../Job01/Job01.php';

class Category
{
    public function __construct(
        private int $id = 0,
        private string $name = "",
        private string $description = "",
        private DateTime $createdAt = new DateTime(),
        private DateTime $updatedAt = new DateTime()
    ) {}

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}

$category = new Category(11, "Électronique", "Appareils et gadgets high-tech");
$product = new Product(1, "iPhone 15", ["iphone15.jpg"], 1200, "Smartphone Apple", 10, new DateTime(), new DateTime(), $category->getId());
echo "<br><br><br>Job02 <br><br><br>";
var_dump($category);
echo "<br>";
var_dump($product);
