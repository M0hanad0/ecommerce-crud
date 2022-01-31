<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=crud_products;', 'root', 'password');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$product_id = $_POST['product-id'];

$statement = $pdo->prepare('DELETE FROM products WHERE id=:id');
$statement->bindValue(":id", $product_id);
$statement->execute();

header("Location: ./index.php");
die();
