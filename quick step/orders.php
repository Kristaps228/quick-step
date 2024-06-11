<?php
// Šis kods nodrošina lietotāja pasūtījumu apskati tiešsaistes veikala mājaslapā. 
// Lietotājs var redzēt savus pasūtījumus, kas ietver piegādes adresi, maksājuma metodi, kopējo summu un pasūtījuma vienības ar attiecīgo informāciju.

include 'components/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    $user_id = '';
} else {
    $user_id = $_SESSION['user_id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">


    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="orders">
    <h1 class="heading">Your Orders</h1>
    <div class="box-container">

        <?php
        if ($user_id == '') {
            echo '<p class="empty">Please login to see your orders</p>';
        } else {
            $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
            $select_orders->execute([$user_id]);
            if ($select_orders->rowCount() > 0) {
                while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                    $select_user = $conn->prepare("SELECT name, email, phone FROM `users` WHERE id = ?");
                    $select_user->execute([$user_id]);
                    $user_info = $select_user->fetch(PDO::FETCH_ASSOC);
                    ?>

                    <div class="order-container">
                        <a href="pdf-functions.php?order_id=<?= $fetch_orders['id']; ?>" class="nav__link" target="_blank">Invoice (PDF)</a>
                        <div class="order-header">
                            <div class="order-date">
                                <h4>From: <span><?= $fetch_orders['placed_on']; ?></span></h4>
                                <h4>ID: <span><?= $fetch_orders['id']; ?></span></h4>
                            </div>
                            <h3>Status: <span class="order-status <?= $fetch_orders['payment_status'] == 'pending' ? 'pending' : 'completed'; ?>"><?= ucfirst($fetch_orders['payment_status']); ?></span></h3>
                        </div>
                        <div class="order-details">
                            <div class="delivery-address">
                                <h3>Delivery Address</h3>
                                <p><?= htmlspecialchars($user_info['name']); ?></p>
                                <p><?= htmlspecialchars($fetch_orders['address']); ?></p>
                                <p><?= htmlspecialchars($user_info['email']); ?></p>
                                <p>Phone number: <?= htmlspecialchars($user_info['phone']); ?></p>
                            </div>
                            <div class="payment-method">
                                <h3>Payment Method</h3>
                                <p><?= htmlspecialchars($fetch_orders['method']); ?></p>
                            </div>
                            <div class="total-price">
                                <h3>Total sum:</h3>
                                <p>€ <?= number_format((float)$fetch_orders['total_price'], 2, '.', ''); ?></p>
                            </div>
                        </div>
                        <div class="order-items">
                            <?php
                            $select_order_items = $conn->prepare("SELECT * FROM `order_items` WHERE order_id = ?");
                            $select_order_items->execute([$fetch_orders['id']]);
                            if ($select_order_items->rowCount() > 0) {
                                while ($fetch_items = $select_order_items->fetch(PDO::FETCH_ASSOC)) {
                                    $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                                    $select_product->execute([$fetch_items['product_id']]);
                                    $product = $select_product->fetch(PDO::FETCH_ASSOC);

                                    $original_price = $fetch_items['original_price'];
                                    $discounted_price = $fetch_items['discounted_price'];
                                    ?>
                                    <div class="order-item">
                                        <a href="quick_view.php?pid=<?= $product['id']; ?>">
                                            <img src="uploaded_img/<?= htmlspecialchars($product['image_01']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                                        </a>
                                        <div class="order-details">
                                            <p class="designer-model"><strong><?= htmlspecialchars($product['designer']); ?>: <?= htmlspecialchars($product['model']); ?></strong></p>
                                            <p class="color">Color: <?= htmlspecialchars($product['color']); ?></p>
                                            <p>Size: <?= htmlspecialchars($fetch_items['size']); ?></p>
                                            <p>Quantity: <?= htmlspecialchars($fetch_items['quantity']); ?></p>
                                            <p>€ <?= number_format((float)$discounted_price, 2, '.', ''); ?></p>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo '<p>No items found</p>';
                            }
                            ?>
                        </div>
                    </div>

                    <?php
                }
            } else {
                echo '<p class="empty">No orders placed yet!</p>';
            }
        }
        ?>

    </div>
</section>

<script src="js/script.js"></script>

</body>
</html>
