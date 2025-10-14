<?php


declare(strict_types=1);

abstract class AbstractProduct
{
    protected int $id;
    protected string $name;
    protected array $photos;
    protected int $price;
    protected string $description;
    protected int $quantity;
    protected DateTime $createdAt;
    protected DateTime $updatedAt;
    protected int $category_id;

    public function __construct(
        int $id = 0,
        string $name = "",
        array $photos = [],
        int $price = 0,
        string $description = "",
        int $quantity = 0,
        ?DateTime $createdAt = null,
        ?DateTime $updatedAt = null,
        int $category_id = 0
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->photos = $photos;
        $this->price = $price;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->createdAt = $createdAt ?? new DateTime();
        $this->updatedAt = $updatedAt ?? new DateTime();
        $this->category_id = $category_id;
    }

    // GETTERS et SETTERS communs
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
    public function getPhotosJson(): string
    {
        return json_encode($this->photos);
    }

    // Méthodes abstraites que chaque enfant doit implémenter
    abstract public function findOneById(int $id);
    abstract public function findAll(): array;
    abstract public function create();
    abstract public function update(): bool;
}


class Clothing extends AbstractProduct
{
    private string $size;
    private string $color;
    private string $type;
    private int $material_fee;

    public function __construct(
        int $id = 0,
        string $name = "",
        array $photos = [],
        int $price = 0,
        string $description = "",
        int $quantity = 0,
        DateTime $createdAt = new DateTime(),
        DateTime $updatedAt = new DateTime(),
        int $category_id = 0,
        string $size = "",
        string $color = "",
        string $type = "",
        int $material_fee = 0
    ) {
        parent::__construct($id, $name, $photos, $price, $description, $quantity, $createdAt, $updatedAt, $category_id);
        $this->size = $size;
        $this->color = $color;
        $this->type = $type;
        $this->material_fee = $material_fee;
    }

    // Getters & Setters
    public function getSize(): string
    {
        return $this->size;
    }
    public function setSize(string $size): void
    {
        $this->size = $size;
    }

    public function getColor(): string
    {
        return $this->color;
    }
    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    public function getType(): string
    {
        return $this->type;
    }
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getMaterialFee(): int
    {
        return $this->material_fee;
    }
    public function setMaterialFee(int $fee): void
    {
        $this->material_fee = $fee;
    }

    // Implémentation des méthodes abstraites
    public function findOneById(int $id)
    {
        $conn = new mysqli("localhost", "root", "", "draft_shop");
        if ($conn->connect_error) die("Erreur de connexion : " . $conn->connect_error);

        $stmt = $conn->prepare("SELECT * FROM clothing WHERE product_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        if (!$data) return false;

        $productData = parent::findOneById($id);
        if (!$productData) return false;

        return new Clothing(
            $productData->getId(),
            $productData->getName(),
            $productData->getPhotos(),
            $productData->getPrice(),
            $productData->getDescription(),
            $productData->getQuantity(),
            $productData->getCreatedAt(),
            $productData->getUpdatedAt(),
            $productData->getCategoryId(),
            $data['size'],
            $data['color'],
            $data['type'],
            $data['material_fee']
        );
    }

    public function findAll(): array
    {
        $conn = new mysqli("localhost", "root", "", "draft_shop");
        if ($conn->connect_error) die("Erreur de connexion : " . $conn->connect_error);

        $stmt = $conn->prepare("SELECT * FROM clothing");
        $stmt->execute();
        $result = $stmt->get_result();

        $clothes = [];
        while ($row = $result->fetch_assoc()) {
            $productData = parent::findOneById($row['product_id']);
            if ($productData) {
                $clothes[] = new Clothing(
                    $productData->getId(),
                    $productData->getName(),
                    $productData->getPhotos(),
                    $productData->getPrice(),
                    $productData->getDescription(),
                    $productData->getQuantity(),
                    $productData->getCreatedAt(),
                    $productData->getUpdatedAt(),
                    $productData->getCategoryId(),
                    $row['size'],
                    $row['color'],
                    $row['type'],
                    $row['material_fee']
                );
            }
        }
        return $clothes;
    }

    public function create()
    {
        $product = parent::create();
        if (!$product) return false;

        $conn = new mysqli("localhost", "root", "", "draft_shop");
        if ($conn->connect_error) die("Erreur de connexion : " . $conn->connect_error);

        $stmt = $conn->prepare("
            INSERT INTO clothing (product_id, size, color, type, material_fee)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "isssi",
            $product->getId(),
            $this->size,
            $this->color,
            $this->type,
            $this->material_fee
        );

        if ($stmt->execute()) return $this;
        return false;
    }

    public function update(): bool
    {
        $parentUpdate = parent::update();
        if (!$parentUpdate) return false;

        $conn = new mysqli("localhost", "root", "", "draft_shop");
        if ($conn->connect_error) die("Erreur de connexion : " . $conn->connect_error);

        $stmt = $conn->prepare("
            UPDATE clothing
            SET size = ?, color = ?, type = ?, material_fee = ?
            WHERE product_id = ?
        ");
        $stmt->bind_param(
            "sssii",
            $this->size,
            $this->color,
            $this->type,
            $this->material_fee,
            $this->getId()
        );

        return $stmt->execute();
    }
}
