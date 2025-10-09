<?php

declare(strict_types=1);

// Inclure la classe Product
require_once __DIR__ . '/../Job01/Job01.php';

// Connexion à la BDD
$conn = new mysqli("localhost", "root", "", "draft_shop");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Récupérer le produit avec id = 7
$stmt = $conn->prepare("SELECT * FROM product WHERE id = ?");
$id = 7;
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$productData = $result->fetch_assoc();
var_dump($productData);

// Hydrater l'objet Product avec le resultat de $productData issu de la BBD

$product = new Product(
    $productData['id'],
    $productData['name'],
    json_decode($productData['photos'], true),
    $productData['price'],
    $productData['description'],
    $productData['quantity'],
    new DateTime($productData['createdAt']),
    new DateTime($productData['updatedAt']),
    $productData['category_id']
);

// Tester l'objet
var_dump($product);
