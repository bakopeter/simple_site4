<?php
session_start();
require_once "layout/header.php";

$products = array();
require_once "data/products.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'empty_cart') {
    $_SESSION['cart'] = array();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == "empty_cart_row") {
    if (isset($_GET['pid'])) {
        $pid = $_GET['pid'];
        unset($_SESSION['cart'][$pid]);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'send_order')
{
    $order = array();
    foreach ($_SESSION['cart'] as $key => $quantity) {
        foreach ($products as $product) {
            if ($key == $product['id']) {
                $order[] = [
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => $quantity
                ];
            }
        }
    }

    $content = json_encode([
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'comment' => $_POST['comment'],
        'products' => $order,
        'sumTotal' => $_POST['sum_total']
    ]);

    $fw = fopen('orders/order_'.date('y-m-d-h-i-sa').'_'.$_COOKIE['PHPSESSID'].'.json', 'w');
    fwrite($fw, $content);
    fclose($fw);

    $_SESSION['cart'] = array();

    echo '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Rendelés sikeresen elküldve!
            <button type="button" class="btn-close" data-bs-dismis="alert" aria-label="Bezár"></button>
        </div>
    ';
}

$sum = 0;
$sumTotal = 0;
?>

<h4>Kosár</h4>

<?php if (isset($_SESSION['cart']) && (count($_SESSION['cart']) > 0)): ?>

    <ul class="list-group list-group-flush align-text-bottom">
    <?php foreach ($_SESSION['cart'] as $id => $quantity): ?>
        <?php $sum = $products[$id-1]['price'] * $quantity; $sumTotal += $sum; ?>
        <li class="list-group-item">
            <form action="/cart.php?pid=<?= $id?>" class="d-flex" method="post">
                <input type="submit" class="btn btn-outline-warning btn-sm" value="X">
                <input type="hidden" name="action" value="empty_cart_row">
                <span class="me-auto p-2"><?= $products[$id-1]["name"] ?></span>
                <span class="p-2"><?= $products[$id-1]["price"] ?> Ft</span>
                <span class="p-2" style="width: 70px; text-align: right"><?= $quantity ?> db</span>
                <span class="p-2" style="width: 80px; text-align: right"><?= $sum ?> Ft</span>
            </form>
        </li>
    <?php endforeach; ?>
        <li class="list-group-item d-flex">
            <span class="me-auto p-2">Fizetendő</span>
            <span class="p-2" style="width: 80px; text-align: right"><?= $sumTotal ?> Ft</span>
        </li>
    </ul>
    <div class="d-flex justify-content-end mt-4">
        <form action="/cart.php" method="post">
            <input type="hidden" name="action" value="empty_cart">
            <input type="submit" value="Kosár ürítése" class="btn btn-outline-warning">
        </form>
    </div>
<?php else: ?>
    <div class="alert alert-warning" role="alert">A kosár üres!</div>
<?php endif; ?>

<h4>Számlázási adatok</h4>
    <form action="/cart.php" method="post">
        <input type="hidden" name="action" value="send_order"/>
        <input type="hidden" name="sum_total" value=<?= $sumTotal ?>>
        <div class="form-floating mb-3">
            <input class="form-control" id="name" name="name" type="text" placeholder="Név"
                   value="<?= $_POST['name'] ?? "" ?>" required />
            <label for="name">Név</label>
        </div>
        <div class="form-floating mb-3">
            <input class="form-control" id="email" name="email" type="email" placeholder="Email cím"
                   value="<?= $_POST['email'] ?? "" ?>" required />
            <label for="email">Email cím</label>
        </div>
        <div class="form-floating mb-3">
            <textarea class="form-control" name="comment" id="comment" placeholder="Megjegyzés"
                      style="height: 10rem;" ><?= $_POST[''] ?? "" ?></textarea>
            <label for="comment">Megjegyzés</label>
        </div>
        <div class="mb-3">
            <label class="form-label d-block">Elfogadom az ÁSZF-et</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" id="accept" type="checkbox" name="accept" required />
                <label class="form-check-label" for="accept">Igen</label>
            </div>
        </div>
        <div class="d-flex justify-content-end">
            <input class="btn btn-outline-primary" id="submitButton" type="submit"
                   value="Rendelés leadása" />
        </div>
    </form>
    <br>
<?php
require_once "layout/footer.php";
?>