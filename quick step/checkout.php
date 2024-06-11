<?php
// Šis kods nodrošina funkcionalitāti pasūtījuma veikšanai tiešsaistes veikalā. 
// Lietotājs var apskatīt savus groza priekšmetus, ievadīt piegādes informāciju un veikt pasūtījumu.

include 'components/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:user_login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Saņem lietotāja informāciju no datubāzes
$select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select_user->execute([$user_id]);
$user_data = $select_user->fetch(PDO::FETCH_ASSOC);

$name = $user_data['name'];
$lastname = $user_data['lastname'] ?? '';
$email = $user_data['email'];
$phone = $user_data['phone'] ?? '';
$address = $user_data['address'] ?? '';

// Apstrādā pasūtījuma veikšanu
if (isset($_POST['order'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_SPECIAL_CHARS);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $method = filter_var($_POST['method'], FILTER_SANITIZE_SPECIAL_CHARS);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_SPECIAL_CHARS);
    $total_price = $_POST['total_price'];

    // Atjaunina lietotāja informāciju
    $update_user = $conn->prepare("UPDATE `users` SET name = ?, lastname = ?, phone = ?, address = ? WHERE id = ?");
    $update_user->execute([$name, $lastname, $number, $address, $user_id]);

    $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $check_cart->execute([$user_id]);

    if ($check_cart->rowCount() > 0) {
        $insert_order = $conn->prepare("INSERT INTO `orders` (user_id, method, address, total_price) VALUES (?,?,?,?)");
        $insert_order->execute([$user_id, $method, $address, $total_price]);
        $order_id = $conn->lastInsertId();

        while ($cart_item = $check_cart->fetch(PDO::FETCH_ASSOC)) {
            $pid = $cart_item['product_id'];
            $select_product = $conn->prepare("SELECT price, discount FROM `products` WHERE id = ?");
            $select_product->execute([$pid]);
            $product = $select_product->fetch(PDO::FETCH_ASSOC);

            $original_price = $product['price'];
            $discount = $product['discount'];
            $discounted_price = $discount ? $original_price * (1 - $discount / 100) : $original_price;

            $insert_order_item = $conn->prepare("INSERT INTO `order_items` (order_id, product_id, quantity, price, size, original_price, discounted_price) VALUES (?,?,?,?,?,?,?)");
            $insert_order_item->execute([$order_id, $pid, $cart_item['quantity'], $original_price, $cart_item['size'], $original_price, $discounted_price]);
        }

        $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
        $delete_cart->execute([$user_id]);

        $message[] = 'Order placed successfully!';
    } else {
        $message[] = 'Your cart is empty';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">


    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
<?php include 'components/user_header.php'; ?>

<section class="checkout-orders">

    <form action="" method="POST">

        <h3>Your Orders</h3>

        <div class="display-orders">
        <?php
            $grand_total = 0;
            $cart_items = [];
            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->execute([$user_id]);
            if ($select_cart->rowCount() > 0) {
                while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                    $pid = $fetch_cart['product_id'];
                    $select_product = $conn->prepare("SELECT name, price, image_01, color, discount, designer, model FROM `products` WHERE id = ?");
                    $select_product->execute([$pid]);
                    $product = $select_product->fetch(PDO::FETCH_ASSOC);

                    $discount = $product['discount'];
                    $final_price = $discount ? $product['price'] * (1 - $discount / 100) : $product['price'];

                    $cart_items[] = $product['name'].' ('.$final_price.' x '. $fetch_cart['quantity'].') - ';
                    $total_products = implode($cart_items);
                    $grand_total += ($final_price * $fetch_cart['quantity']);
        ?>
            <div class="order-item">
                <img src="uploaded_img/<?= $product['image_01']; ?>" alt="<?= $product['name']; ?>">
                <div class="order-details">
                    <p class="designer-model"><?= htmlspecialchars($product['designer']); ?>: <?= htmlspecialchars($product['model']); ?></p>
                    <p class="name"><?= $product['name']; ?></p>
                    <p class="color">Color: <?= htmlspecialchars($product['color']); ?></p>
                    <p class="">Size: <?= $fetch_cart['size']; ?></p>
                    <p class="price"><?= '€'.number_format($final_price, 2).' x '. $fetch_cart['quantity']; ?></p>
                    <?php if ($discount): ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php
                }
            } else {
                echo '<p class="empty">Your cart is empty!</p>';
            }
        ?>
            <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
            <div class="grand-total">Grand total : <span>€<?= number_format($grand_total, 2); ?></span></div>
        </div>

        <h3>Place Your Orders</h3>

        <div class="flex">
            <div class="inputBox">
                <span>Your Name :</span>
                <input type="text" name="name" value="<?= $name; ?>" placeholder="Enter your name" class="box" maxlength="20" required>
            </div>
            <div class="inputBox">
                <span>Your Last Name :</span>
                <input type="text" name="lastname" value="<?= $lastname; ?>" placeholder="Enter your last name" class="box" maxlength="50" required>
            </div>
            <div class="inputBox">
                <span>Your Number :</span>
                <input type="text" name="number" value="<?= $phone; ?>" placeholder="Enter your number" class="box" maxlength="15" required>
            </div>
            <div class="inputBox">
                <span>Your Email :</span>
                <input type="email" name="email" value="<?= $email; ?>" placeholder="Enter your email" class="box" maxlength="50" required>
            </div>
            <div class="inputBox">
                <span>Payment Method :</span>
                <select name="method" class="box" required>
                    <option value="cash on delivery">Cash on Delivery</option>
                    <option value="credit card">Credit Card</option>
                    <option value="paypal">PayPal</option>
                </select>
            </div>
            <div class="inputBox">
                <span>Address :</span>
                <input type="text" name="address" value="<?= $address; ?>" placeholder="Enter your address" class="box" maxlength="500" required>
            </div>
        </div>

        <input type="submit" name="order" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>" value="Place Order">

    </form>

</section>

<script src="js/script.js"></script>

</body>
</html>
