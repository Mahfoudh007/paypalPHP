<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.yourmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'your@email.com';
$mail->Password = 'yourpassword';
$mail->SMTPSecure = 'ssl';
$mail->Port = 465;

$mail->setFrom('your@email.com', 'Your Shop');
$mail->addAddress($email);
$mail->Subject = 'Оплата заказа';
$mail->Body = "Ваш заказ оплачен!\nСпасибо за покупку.";

$mail->send();
?>
