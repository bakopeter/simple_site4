<?php
session_start();
include_once "layout/header.php"
?>

<?php
$products = array();
require_once "data/products.php";

if (!isset($_GET['pid'])) { die('No pid!'); }

$pid = $_GET['pid'];
$product = null;

foreach ($products as $prod) {
    if ($prod['id'] == $pid) {
        $product = $prod;
    }
}

if ($product == null) { die('No product!'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'add_to_cart') {
        if (!isset($_SESSION['cart'][$pid])) {
            $_SESSION['cart'][$pid] = $_POST['quantity'];
        } else {
            $_SESSION['cart'][$pid] += $_POST['quantity'];
        }
    echo '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            A termék kosárba került!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
}
?>

<h2 class="position-relative pr-10 mt-2">
    <?= $product['name'] ?>&nbsp;
    <span class="position-absolute ml-100 badge rounded-pill bg-warning">
            <?= $_SESSION['cart'][$pid]?? 0 ?>
    </span>
</h2>
<hr>
<form class="row g-3" action="product.php?pid=<?= $product['id'] ?>" method="post">
    <input type="hidden" name="action" value="add_to_cart">
    <div class="row mt-4">
        <div class="col">
            <input type="number" class="form-control" name="quantity" value="1" min="1"
                   max="500" aria-label="Mennyiség" required>
        </div>
        <div class="col">
            <input type="submit" class="form-control btn btn-primary" value="Kosárba">
        </div>
</form>
<h5 class="mt-3"><?= $product['price'] ?> Ft</h5>
<hr>
<p style="text-align: justify"><?= $product['description'] ?></p>

<?php
include_once "layout/footer.php"
?>
