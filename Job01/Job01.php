<?php

declare(strict_types=1);

class Product
{
    public function __construct(
        private int $id = 0,
        private string $name = "",
        private array $photos = [],
        private int $price = 0,
        private string $description = "",
        private int $quantity = 0,
        private DateTime $createdAt = new DateTime(),
        private DateTime $updatedAt = new DateTime(),
        private int $category_id = 0, // nouvel attribut
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
    public function setCategoryId(int $category_id): void
    {
        $this->category_id = $category_id;
    }
    public function setPhotosFromJson(string $json): void
    {
        $this->photos = json_decode($json, true) ?? []; //Pour récupérer depuis la bse de données JSON → PHP (lecture)
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
    public function getCategoryId(): int
    {
        return $this->category_id;
    }
    public function getCategory(): ?Category
    {
        $conn = new mysqli("localhost", "root", "", "draft_shop");
        if ($conn->connect_error) {
            die("Erreur de connexion : " . $conn->connect_error);
        }
        $stmt = $conn->prepare("SELECT * FROM category WHERE id = ?");
        $id = $this->category_id;
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $getCategoryById = $result->fetch_assoc();
        if (!$getCategoryById) {
            return null;
        }
        return new Category($this->category_id, $getCategoryById['name'], $getCategoryById['description'], new DateTime($getCategoryById['createdAt']), new DateTime($getCategoryById['updatedAt']));
    }
    public function getPhotosJson(): string
    {
        return json_encode($this->photos); // pour stocker en BDD PHP → JSON (stockage)
    }
    public function findOneById(int $id)
    {
        $conn = new mysqli("localhost", "root", "", "draft_shop");
        if ($conn->connect_error) {
            die("Erreur de connexion : " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT * FROM product WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $productData = $result->fetch_assoc();

        if (!$productData) {
            return false;
        }

        // On retourne une nouvelle instance de Product hydratée avec les données
        return new Product(
            $productData['id'],
            $productData['name'],
            json_decode($productData['photos'], true) ?? [],
            (int)$productData['price'],
            $productData['description'],
            (int)$productData['quantity'],
            new DateTime($productData['createdAt']),
            new DateTime($productData['updatedAt']),
            (int)$productData['category_id']
        );
    }
    public function findAll(): array
    {
        $conn = new mysqli("localhost", "root", "", "draft_shop");
        if ($conn->connect_error) {
            die("Erreur de connexion : " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT * FROM product");
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];

        while ($row = $result->fetch_assoc()) {
            $products[] = new Product(
                $row['id'],
                $row['name'],
                json_decode($row['photos'], true) ?? [],
                $row['price'],
                $row['description'],
                $row['quantity'],
                new DateTime($row['createdAt']),
                new DateTime($row['updatedAt']),
                $row['category_id']
            );
        }

        return $products;
    }
    public function create(): mixed
    {
        $conn = new mysqli("localhost", "root", "", "draft_shop");
        if ($conn->connect_error) {
            die("Erreur de connexion : " . $conn->connect_error);
        }

        $photosJson = json_encode($this->photos); // convertir le tableau de photos en JSON pour la BDD
        $stmt = $conn->prepare("
        INSERT INTO product 
        (name, photos, price, description, quantity, createdAt, updatedAt, category_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

        $createdAtStr = $this->createdAt->format('Y-m-d H:i:s');
        $updatedAtStr = $this->updatedAt->format('Y-m-d H:i:s');

        $stmt->bind_param(
            "ssissssi",
            $this->name,
            $photosJson,
            $this->price,
            $this->description,
            $this->quantity,
            $createdAtStr,
            $updatedAtStr,
            $this->category_id
        );

        if ($stmt->execute()) {
            // récupérer l'id généré par la BDD
            $this->id = $conn->insert_id;
            return $this; // retourner l'objet Product courant avec l'id rempli
        }

        return false;
    }
}
// on instancie la classe et on teste 

$produit1 = new Product(1, 'T-shirt', ['img1.jpg'], 25, 'Beau t-shirt', 10);

$produit1->setName('Taylor Swift');
$produit1->setPrice(30);

echo "Job01: <br><br><br>";
echo "Nom : " . $produit1->getName() . "<br>";
echo "id : " . $produit1->getId() . "<br>";
echo "Prix : " . $produit1->getPrice() . "<br>";
echo "description : " . $produit1->getDescription() . "<br>";
echo "Créé le : " . $produit1->getCreatedAt()->format('Y-m-d H:i:s');
