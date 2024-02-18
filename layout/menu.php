<?php
$file = $_SERVER['SCRIPT_NAME'];
?>
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link <?= $file=='/index.php'?'active':'' ?>" href="/index.php">
            Főoldal
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $file=='/about.php'?'active':'' ?>" href="/about.php">
            Rólunk
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $file=='/cart.php'?'active':'' ?>" href="/cart.php">
            Kosár
        </a>
    </li>
</ul>