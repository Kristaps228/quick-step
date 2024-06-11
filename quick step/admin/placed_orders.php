<?php
// Šis kods nodrošina administratora saskarni pasūtījumu pārvaldībai. 
// Tas ļauj atjaunināt maksājuma statusu un dzēst pasūtījumus, kā arī parāda visus veiktos pasūtījumus.

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

if (isset($_POST['update_payment'])) {
    $order_id = $_POST['order_id'];
    $payment_status = $_POST['payment_status'];
    $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);
    $update_payment = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
    $update_payment->execute([$payment_status, $order_id]);
    $message[] = 'Payment status updated!';
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
    $delete_order->execute([$delete_id]);
    header('location:placed_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placed Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="orders">
    <h1 class="heading">Placed Orders</h1>
    <div class="box-container">
        <?php
        $select_orders = $conn->prepare("
            SELECT 
                orders.*, 
                users.name, 
                users.lastname, 
                users.phone,
                (SELECT COUNT(*) FROM order_items WHERE order_items.order_id = orders.id) AS total_products,
                (SELECT GROUP_CONCAT(products.image_01 SEPARATOR ',') 
                 FROM order_items 
                 JOIN products ON order_items.product_id = products.id 
                 WHERE order_items.order_id = orders.id) AS product_images,
                (SELECT GROUP_CONCAT(products.id SEPARATOR ',') 
                 FROM order_items 
                 JOIN products ON order_items.product_id = products.id 
                 WHERE order_items.order_id = orders.id) AS product_ids
            FROM orders
            JOIN users ON orders.user_id = users.id
        ");
        $select_orders->execute();
        if ($select_orders->rowCount() > 0) {
            while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="box">
            <p> Placed on: <span><?= $fetch_orders['placed_on']; ?></span> </p>
            <p> Name: <span><?= $fetch_orders['name']; ?></span> </p>
            <p> Last Name: <span><?= $fetch_orders['lastname']; ?></span> </p>
            <p> Phone: <span><?= $fetch_orders['phone']; ?></span> </p>
            <p> Address: <span><?= $fetch_orders['address']; ?></span> </p>
            <?php
            $product_images = explode(',', $fetch_orders['product_images']);
            $product_ids = explode(',', $fetch_orders['product_ids']);
            foreach ($product_images as $index => $image) {
            ?>
            <a href="../quick_view.php?pid=<?= $product_ids[$index]; ?>">
                <img src="../uploaded_img/<?= $image; ?>" alt="Product">
            </a>
            <?php } ?>
            <p> Total products: <span><?= $fetch_orders['total_products']; ?></span> </p>
            <p> Total price: <span>€<?= $fetch_orders['total_price']; ?></span> </p>
            <p> Payment method: <span><?= $fetch_orders['method']; ?></span> </p>
            <form action="" method="post">
                <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                <select name="payment_status" class="select">
                    <option selected disabled><?= $fetch_orders['payment_status']; ?></option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                </select>
                <div class="flex-btn">
                    <input type="submit" value="Update" class="option-btn" name="update_payment">
                    <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn"
                       onclick="return confirm('Delete this order?');">Delete</a>
                </div>
            </form>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">No orders placed yet!</p>';
        }
        ?>
    </div>
</section>

</body>
</html>
