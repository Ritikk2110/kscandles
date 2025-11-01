<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/config.php';
session_start();
if (!isset($_SESSION['user_id'])) { http_response_code(403); echo json_encode(['error'=>'login']); exit; }
$total = cartTotal($conn);
$amount = intval($total * 100);

$key_id = RAZORPAY_KEY_ID; $key_secret = RAZORPAY_KEY_SECRET;
$ch = curl_init();
$data = json_encode(["amount"=>$amount, "currency"=>"INR", "payment_capture"=>1]);
curl_setopt($ch, CURLOPT_URL, "https://api.razorpay.com/v1/orders");
curl_setopt($ch, CURLOPT_USERPWD, $key_id . ":" . $key_secret);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
$response = curl_exec($ch);
curl_close($ch);
header('Content-Type: application/json');
echo $response;
