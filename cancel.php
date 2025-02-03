<?php
require 'config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $pdo->prepare("UPDATE orders SET status = 'canceled' WHERE token = ?");
    $stmt->execute([$token]);

    echo "Оплата была отменена!";
}
?>
