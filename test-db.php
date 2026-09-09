<?php

$s = microtime(true);
for ($i = 0; $i < 5; $i++) {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=vendor_automation', 'root', '');
}
echo 'Time: '.(microtime(true) - $s);
