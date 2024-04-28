<?php

// Include necessary files
require_once('tcpdf/tcpdf.php');
include 'components/connect.php';

session_start();

if(!isset($_SESSION['user_id'])){
   // Redirect or handle unauthorized access
   // For example:
   header("Location: login.php");
   exit();
}

$user_id = $_SESSION['user_id'];

// Initialize PDF object
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Invoice');
$pdf->SetSubject('Invoice');
$pdf->SetKeywords('Invoice, Quick Step');

// Set margins
$pdf->SetMargins(15, 15, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);

// Fetch user's orders from the database
$select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
$select_orders->execute([$user_id]);

while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){

    // Add a page for each order
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('helvetica', '', 10);

    // Output store name on the right side of the page
    $pdf->Cell(0, 10, 'QUICK STEP', 0, true, 'R', 0, '', 0, false, 'T', 'M');

    // Output Invoice title in the center of the page
    $pdf->Cell(0, 10, 'Invoice', 0, true, 'C', 0, '', 0, false, 'T', 'M');

    // Output Delivery Address on the left side of the page
    $pdf->Cell(0, 10, 'Delivery Address:', 0, true, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(0, 10, 'Name: ' . $fetch_orders['name'], 0, true, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(0, 10, 'Address: ' . $fetch_orders['address'], 0, true, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(0, 10, 'Email: ' . $fetch_orders['email'], 0, true, 'L', 0, '', 0, false, 'T', 'M');

    // Output Delivery Date / Invoice Date on the right side of the page
    $pdf->Cell(0, 10, 'DELIVERY DATE / INVOICE DATE: ' . date('Y-m-d'), 0, true, 'R', 0, '', 0, false, 'T', 'M');

    // Output Order ID on the left side of the page
    $pdf->Cell(0, 10, 'Order ID: ' . $fetch_orders['id'], 0, true, 'L', 0, '', 0, false, 'T', 'M');

    // Output Dear Customer Name
    $pdf->Cell(0, 10, 'Dear ' . $fetch_orders['name'], 0, true, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(0, 10, 'Thank you for shopping at QUICK STEP.', 0, true, 'L', 0, '', 0, false, 'T', 'M');

    // Output ordered products and their prices
    $pdf->Cell(0, 10, 'Order Details:', 0, true, 'L', 0, '', 0, false, 'T', 'M');

    // Output product table headers
    $pdf->Cell(100, 10, 'Product', 1, 0, 'C');
    $pdf->Cell(80, 10, 'Price X Quantity', 1, 1, 'C');

    // Retrieve product details from the order string
    $products = explode(" - ", $fetch_orders['total_products']);
    foreach ($products as $product) {
        $product_details = explode(" x ", $product);
        if (count($product_details) >= 2) { // Check if product details are complete
            $product_name = $product_details[0];
            $product_quantity = $product_details[1];
            // Retrieve the price from the product name
            $price_start = strpos($product_name, '(') + 1;
            $price_end = strpos($product_name, ')');
            $product_price = substr($product_name, $price_start, $price_end - $price_start); // Adjusted this line to only extract the price

            // Output product details
            $pdf->Cell(100, 10, $product_name, 1, 0, 'L');
            $pdf->Cell(80, 10, "$product_price X $product_quantity", 1, 1, 'C');
        }
    }

    // Calculate total sum based on the products associated with this order
    $total_sum = $fetch_orders['total_price'];

    // Output total sum
    $pdf->Cell(0, 10, 'Total Sum: ' . "$total_sum €", 0, true, 'L', 0, '', 0, false, 'T', 'M');

    // Output message at the end of the page
    $pdf->Cell(0, 10, 'Thank you for your purchase.', 0, true, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(0, 10, 'Best regards, QUICK STEP', 0, true, 'L', 0, '', 0, false, 'T', 'M');
}

// Output PDF
$pdf->Output('invoice.pdf', 'I');

?>
