<?php
$creation_date = date('Y-m-d H:i:s');

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=crud_products;', 'root', 'password');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$errors = [];

$title = '';
$price = '';
$description = '';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $title = $_POST['product-title'];
    $description = $_POST['product-description'];
    $price = $_POST['product-price'];

    if ($title === "") {
        array_push($errors, "Product title is required");
    }

    if ($price === "") {
        array_push($errors, "Product price is required");
    } else if (intval($price) <= 0) {
        array_push($errors, "Product price can't be zero or negative");
    }

    $img_dir = "./imgs";
    if (!(file_exists($img_dir) && is_dir($img_dir))) {
        echo "Creating $img_dir directory";
        mkdir($img_dir);
    }

    if (empty($errors)) {
        $image = $_FILES['product-image'];
        $imagePath = '';

        if (file_exists($image['tmp_name']) && is_uploaded_file($image['tmp_name'])) {
            $imagePath = "$img_dir/" . round(microtime(true)) . "-{$image['name']}";
            move_uploaded_file($image['tmp_name'], $imagePath);
        }

        $statement = $pdo->prepare("INSERT INTO products (title, description, image, price, creation_date)
               VALUES (:title, :description, :image, :price, :creation_date)");
        $statement->bindValue(":title", $title);
        $statement->bindValue(":image", $imagePath);
        $statement->bindValue(":description", $description);
        $statement->bindValue(":price", $price);
        $statement->bindValue(":creation_date", $creation_date);

        $statement->execute();
        header("Location: ./index.php");
        die();
    }
}

?>
<!DOCTYPE html>
<html class="no-js" lang="en">

<?php require_once 'partials/head.php' ?>

<body>
    <!--[if lt IE 8]>
            <p class="browserupgrade">
            You are using an <strong>outdated</strong> browser. Please
            <a href="http://browsehappy.com/">upgrade your browser</a> to improve
            your experience.
            </p>
        <![endif]-->
    <header>
        <h1>Create a new product</h1>
    </header>
    <hr />

    <?php if (!empty($errors)) : ?>
        <section class="alert alert-danger">
            <header>
                <h2>Errors:</h2>
            </header>
            <?php foreach ($errors as $error) : ?>
                <div><?php echo $error; ?></div>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
    <main>
        <form action="create.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="product-image" class="form-label">Product Image</label> <input type="file" id="product-image" name="product-image" class="form-control" accept="image/*" />
            </div>
            <div class="mb-3">
                <label for="product-title" class="form-label">Product title</label> <input type="text" id="product-title" name="product-title" class="form-control" placeholder="Title" value="<?php echo $title; ?>" autofocus maxlength="512" required>
            </div>
            <div class="mb-3">
                <label for="product-description" class="form-label">Product Description</label> <textarea id="product-description" name="product-description" class="form-control" placeholder="Description"><?php echo $description; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="product-price" class="form-label">Product Price</label> <input type="number" step="0.01" id="product-price" name="product-price" class="form-control" min="0.01" max="9999999999.99" value="<?php echo $price; ?>" required>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </main>
</body>

</html>