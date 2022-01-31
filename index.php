<!DOCTYPE html>
<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=crud_products;', 'root', 'password');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $statement = $pdo->prepare('SELECT * FROM products WHERE title LIKE :title ORDER BY creation_date DESC');
    $statement->bindValue(':title', "%$search%");
} else {
    $statement = $pdo->prepare('SELECT * FROM products ORDER BY creation_date DESC;');
}

$statement->execute();
$products = $statement->fetchAll(PDO::FETCH_ASSOC);

?>
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
    <main>
        <header>
            <h1>CRUD application</h1>
            <nav>
                <a href="create.php" class="btn btn-success">Create product</a>
            </nav>
        </header>
        <hr />

        <section>
            <form method="GET" action="index.php">
                <div class="input-group mb-3">
                    <label hidden for="search">Search</label>
                    <input type="text" class="form-control" id="search" name="search" placeholder="Search for a product" value="<?php if (isset($_GET) && array_key_exists('search', $_GET)) {
                                                                                                                                    echo $_GET['search'];
                                                                                                                                } ?>" aria-label="Search">
                </div>
            </form>
        </section>

        <table class="table">
            <caption>Products</caption>
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Image</th>
                    <th scope="col">Title</th>
                    <th scope="col">Price</th>
                    <th scope="col">Brief description</th>
                    <th scope="col">Creation date</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $i => $product) : ?>
                    <tr>
                        <td><?php echo $i + 1 ?></td>
                        <td><img class="img-fluid" src="<?php echo $product['image'] ?>" alt="<?php echo $product['title']; ?>" /></td>
                        <td><?php echo $product['title'] ?></td>
                        <td><?php echo $product['price'] ?></td>
                        <td><?php echo $product['description'] ?></td>
                        <td><?php echo $product['creation_date'] ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $product['id']; ?>" class="btn btn-small btn-primary">Edit</a>
                            <form method="POST" action="delete.php">
                                <input type="hidden" name="product-id" value="<?php echo $product['id']; ?>" />
                                <button type="submit" class="btn btn-small btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6">Products count:</td>
                    <td><?php echo ($i ?? -1) + 1; ?></td>
                </tr>
            </tfoot>
        </table>
    </main>

</body>

</html>