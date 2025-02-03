<?php
require 'config.php';

$stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
$orders = $stmt->fetchAll();

foreach ($orders as $order) {
    echo "<p>{$order['name']} - {$order['sum']} {$order['currency']} - {$order['status']}</p>";
}
?>
