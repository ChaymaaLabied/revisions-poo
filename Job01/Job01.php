<?php

declare(strict_types=1);

class ClasseProduct
{
    public function __construct(
        private int $id = 0,
        private string $name = "",
        private array $photos = [],
        private int $price = 0,
        private string $description = "",
        private int $quantity = 0,
        private DateTime $createdAt = new DateTime(),
        private DateTime $updatedAt = new DateTime()
    ) {}

    //SETTERS 
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setPhotos(array $photos): void
    {
        $this->photos = $photos;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function setUpdatedAt(DateTime $date): void
    {
        $this->updatedAt = $date;
    }

    //GETTERS
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhotos(): array
    {
        return $this->photos;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }
}
// on instancie la classe et on teste 

$produit1 = new ClasseProduct(1, 'T-shirt', ['img1.jpg'], 25, 'Beau t-shirt', 10);

$produit1->setName('Taylor Swift');
$produit1->setPrice(30);

echo "Nom : " . $produit1->getName() . "<br>";
echo "id : " . $produit1->getId() . "<br>";
echo "Prix : " . $produit1->getPrice() . "<br>";
echo "description : " . $produit1->getDescription() . "<br>";
echo "Créé le : " . $produit1->getCreatedAt()->format('Y-m-d H:i:s');
