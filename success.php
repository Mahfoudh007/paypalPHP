<?php
require 'config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $pdo->prepare("UPDATE orders SET status = 'paid' WHERE token = ?");
    $stmt->execute([$token]);

    require 'mail.php'; // Отправка email

    echo "Оплата успешно завершена!";
}
?>
