<?php
// Šis kods nodrošina rēķina ģenerēšanu PDF formātā lietotājam, kurš veicis pirkumu. 
// Rēķinā ir iekļauta informācija par lietotāju, piegādes adresi, pasūtījuma datumu un detalizēta informācija par pasūtītajām precēm un to cenām.

require_once('tcpdf/tcpdf.php');
include 'components/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id === 0) {
    die('Invalid Order ID');
}

$select_order = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? AND id = ?");
$select_order->execute([$user_id, $order_id]);

if ($select_order->rowCount() == 0) {
    die('Order not found or you do not have permission to view this order');
}

$fetch_order = $select_order->fetch(PDO::FETCH_ASSOC);

$select_user = $conn->prepare("SELECT name, email FROM `users` WHERE id = ?");
$select_user->execute([$user_id]);
$user_info = $select_user->fetch(PDO::FETCH_ASSOC);


$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Invoice');
$pdf->SetSubject('Invoice');
$pdf->SetKeywords('Invoice, Quick Step');


$pdf->SetMargins(15, 15, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);

$pdf->AddPage();

$pdf->SetFont('helvetica', '', 12);

$pdf->Cell(0, 10, 'QUICK STEP', 0, true, 'R', 0, '', 0, false, 'T', 'M');

$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Invoice', 0, true, 'C', 0, '', 0, false, 'T', 'M');
$pdf->SetFont('helvetica', '', 12);


$pdf->Ln(10);
$pdf->Cell(0, 10, 'Delivery Address:', 0, true, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(0, 10, 'Name: ' . $user_info['name'], 0, true, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(0, 10, 'Address: ' . $fetch_order['address'], 0, true, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(0, 10, 'Email: ' . $user_info['email'], 0, true, 'L', 0, '', 0, false, 'T', 'M');


$pdf->Ln(10);
$pdf->Cell(0, 10, 'DELIVERY DATE / INVOICE DATE: ' . $fetch_order['placed_on'], 0, true, 'R', 0, '', 0, false, 'T', 'M');

$pdf->Ln(10);
$pdf->Cell(0, 10, 'Order ID: ' . $fetch_order['id'], 0, true, 'L', 0, '', 0, false, 'T', 'M');

$pdf->Ln(10);
$pdf->Cell(0, 10, 'Dear ' . $user_info['name'], 0, true, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(0, 10, 'Thank you for shopping at QUICK STEP.', 0, true, 'L', 0, '', 0, false, 'T', 'M');


$pdf->Cell(0, 10, 'Order Details:', 0, true, 'L', 0, '', 0, false, 'T', 'M');


$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(90, 10, 'Product', 1, 0, 'C');
$pdf->Cell(40, 10, 'Size', 1, 0, 'C');
$pdf->Cell(60, 10, 'Price x Quantity', 1, 1, 'C');
$pdf->SetFont('helvetica', '', 12);


$select_order_items = $conn->prepare("SELECT * FROM `order_items` WHERE order_id = ?");
$select_order_items->execute([$order_id]);
$total_sum = 0.0;
while ($item = $select_order_items->fetch(PDO::FETCH_ASSOC)) {
    $select_product = $conn->prepare("SELECT name, designer, model FROM `products` WHERE id = ?");
    $select_product->execute([$item['product_id']]);
    $product = $select_product->fetch(PDO::FETCH_ASSOC);

    $product_name = $product['designer'] . ' ' . $product['model'] . ' - ' . $product['name'];
    $original_price = $item['original_price'];
    $discounted_price = $item['discounted_price'];
    $item_total = $discounted_price * $item['quantity'];
    $total_sum += $item_total;

    $pdf->Cell(90, 10, $product_name, 1, 0, 'L');
    $pdf->Cell(40, 10, $item['size'], 1, 0, 'C');
    $pdf->Cell(60, 10, '€' . number_format($discounted_price, 2) . ' x ' . $item['quantity'], 1, 1, 'C');
}


$pdf->Ln(10);
$pdf->Cell(0, 10, 'Total Sum: ' . "€" . number_format($total_sum, 2), 0, true, 'L', 0, '', 0, false, 'T', 'M');


$pdf->Ln(10);
$pdf->Cell(0, 10, 'Thank you for your purchase.', 0, true, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(0, 10, 'Best regards, QUICK STEP', 0, true, 'L', 0, '', 0, false, 'T', 'M');


$pdf->Output('invoice.pdf', 'I');

?>
