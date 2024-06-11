<?php

require_once('../tcpdf/tcpdf.php');
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
   exit;
}

// Создаем новый PDF-документ
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Dashboard Statistics Invoice');

// Убираем заголовок и футер
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

$total_pendings = 0.0;
$select_pendings = $conn->prepare("SELECT total_price FROM `orders` WHERE payment_status = ?");
$select_pendings->execute(['pending']);
if($select_pendings->rowCount() > 0){
   while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
      $total_pendings += (float)$fetch_pendings['total_price'];
   }
}

$total_completes = 0.0;
$select_completes = $conn->prepare("SELECT total_price FROM `orders` WHERE payment_status = ?");
$select_completes->execute(['completed']);
if($select_completes->rowCount() > 0){
   while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
      $total_completes += (float)$fetch_completes['total_price'];
   }
}

$select_orders = $conn->prepare("SELECT id FROM `orders`");
$select_orders->execute();
$number_of_orders = $select_orders->rowCount();

$select_products = $conn->prepare("SELECT id FROM `products`");
$select_products->execute();
$number_of_products = $select_products->rowCount();

$select_users = $conn->prepare("SELECT id FROM `users`");
$select_users->execute();
$number_of_users = $select_users->rowCount();

$select_admins = $conn->prepare("SELECT id FROM `admins`");
$select_admins->execute();
$number_of_admins = $select_admins->rowCount();

$html = "
<style>
    h1 {
        text-align: center;
        color: #4CAF50;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 10px;
        border: 1px solid #ddd;
    }
    th {
        background-color: #4CAF50;
        color: white;
        text-align: left;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
</style>
<h1>Dashboard Statistics</h1>
<table>
    <tr>
        <th>Total Pendings</th>
        <td>€".number_format($total_pendings, 2)."</td>
    </tr>
    <tr>
        <th>Completed Orders</th>
        <td>€".number_format($total_completes, 2)."</td>
    </tr>
    <tr>
        <th>Orders Placed</th>
        <td>$number_of_orders</td>
    </tr>
    <tr>
        <th>Products Added</th>
        <td>$number_of_products</td>
    </tr>
    <tr>
        <th>Normal Users</th>
        <td>$number_of_users</td>
    </tr>
    <tr>
        <th>Admin Users</th>
        <td>$number_of_admins</td>
    </tr>
</table>
";

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output('dashboard_statistics_invoice.pdf', 'I');
?>
