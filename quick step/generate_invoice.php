<?php
require_once 'vendor/autoload.php'; // Include the Composer autoloader

use Dompdf\Dompdf;

// Function to generate PDF invoice
function generateInvoicePDF($order_id) {
    // Fetch order details from the database based on order_id
    // Connect to your MySQL database
    $conn = new mysqli('localhost', 'root', '', 'shop_db');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch order details from the database
    $sql = "SELECT * FROM orders WHERE id = $order_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Initialize Dompdf
        $dompdf = new Dompdf();

        // Start building HTML content for the PDF
        $html = '<h1>Invoice</h1>';
        
        // Loop through order details and append to HTML content
        while($row = $result->fetch_assoc()) {
            $html .= '<p>Order ID: ' . $row['id'] . '</p>';
            $html .= '<p>User Name: ' . $row['name'] . '</p>';
            // Add more details as needed
        }

        // Load HTML content into Dompdf
        $dompdf->loadHtml($html);

        // Render PDF
        $dompdf->render();

        // Output PDF as a file (you can also output as a download)
        $dompdf->stream('invoice_'.$order_id.'.pdf', array('Attachment' => 0));
    } else {
        echo "No orders found";
    }
    $conn->close();
}

// Check if order ID is provided via GET request
if(isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    generateInvoicePDF($order_id);
}
?>

<!-- HTML with a download button -->
<!DOCTYPE html>
<html>
<head>
    <title>Generate Invoice</title>
</head>
<body>
    <!-- Button to generate PDF invoice -->
    <a href="?order_id=6" target="_blank">Download Invoice PDF</a>
</body>
</html>
