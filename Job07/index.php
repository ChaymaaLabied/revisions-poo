<?php

declare(strict_types=1);

// Inclure la classe Product
require_once __DIR__ . '/../Job06/index.php';
echo " <br><br><br> Job07 <br><br><br>";
var_dump($product->findOneById(1));
var_dump($product->findOneById(100));
