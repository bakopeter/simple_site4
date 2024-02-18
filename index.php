<?php
session_start();
include_once "layout/header.php";
?>
<h4>Termékek</h4>

<?php
$products = array();
require_once "data/products.php";
?>
<div class="d-flex flex-wrap justify-content-between mt-1">
    <?php foreach ($products as $product): ?>
        <div class="card mt-3 btn-outline-primary" style="width: 10rem;">
            <div class="card-head text-center">
                <h6 class="card-title mt-3 mb-0">
                    <?= $product['name']?>
                </h6>
            </div>
            <hr>
            <div class="card-body text-center pt-0 mt-0">
                <a href="product.php?pid=<?= $product['id']?>" class="btn d-flex mt-0">
                    Részletek...
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php
include_once "layout/footer.php";