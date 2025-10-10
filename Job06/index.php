<?php

declare(strict_types=1);

// Inclure la classe Product
require_once __DIR__ . '/../Job05/index.php';

echo " <br><br><br> Job06 <br><br><br>";
$category1 = new Category(1);

// var_dump($category->getproductsOfACategory());
var_dump($category1->getProducts());
