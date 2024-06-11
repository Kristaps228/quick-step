<?php
// Šis kods pārvalda lietotāja vēlēšanās sarakstu, ļaujot pievienot, dzēst un skatīt vēlēšanās saraksta vienumus.

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:user_login.php');
    exit;
}

include 'components/wishlist_cart.php';

if (isset($_POST['delete'])) {
    $wishlist_id = $_POST['wishlist_id'];
    $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist` WHERE id = ? AND user_id = ?");
    $delete_wishlist_item->execute([$wishlist_id, $user_id]);
}

if (isset($_GET['delete_all'])) {
    $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
    $delete_wishlist_item->execute([$user_id]);
    header('location:wishlist.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>wishlist</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
<?php include 'components/user_header.php'; ?>

<section class="products">
    <h3 class="heading">your wishlist</h3>
    <div class="box-container">
    <?php
    $grand_total = 0;
    $select_wishlist = $conn->prepare("
        SELECT w.*, p.name, p.price, p.image_01, p.sizes, p.discount, p.model, p.designer
        FROM `wishlist` w 
        JOIN `products` p ON w.product_id = p.id
        WHERE w.user_id = ?
    ");
    $select_wishlist->execute([$user_id]);
    if ($select_wishlist->rowCount() > 0) {
        while ($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)) {
            $original_price = $fetch_wishlist['price'];
            $discount = $fetch_wishlist['discount'];
            $discounted_price = $original_price - ($original_price * ($discount / 100));
            $grand_total += $discounted_price;
    ?>
    <form action="" method="post" class="box">
        <input type="hidden" name="product_id" value="<?= $fetch_wishlist['product_id']; ?>">
        <input type="hidden" name="wishlist_id" value="<?= $fetch_wishlist['id']; ?>">
        <input type="hidden" name="name" value="<?= $fetch_wishlist['name']; ?>">
        <input type="hidden" name="price" value="<?= $fetch_wishlist['price']; ?>">
        <input type="hidden" name="image" value="<?= $fetch_wishlist['image_01']; ?>">
        <a href="quick_view.php?pid=<?= $fetch_wishlist['product_id']; ?>">
            <img src="uploaded_img/<?= $fetch_wishlist['image_01']; ?>" alt="">
        </a>
        <div class="name">
            <span class="designer"><?= htmlspecialchars($fetch_wishlist['designer']); ?></span>
            <span class="model"><?= htmlspecialchars($fetch_wishlist['model']); ?></span>
            <?= htmlspecialchars($fetch_wishlist['name']); ?>
        </div>
        <div class="flex">
            <div class="price">
                <?php if ($discount > 0): ?>
                    <span class="original-price">€<?= number_format($original_price, 2); ?></span>
                    <span class="discounted-price">€<?= number_format($discounted_price, 2); ?></span>
                    <span class="discount-percentage">(<?= $discount; ?>% off)</span>
                <?php else: ?>
                    €<?= number_format($original_price, 2); ?>
                <?php endif; ?>
            </div>
            <select name="size" class="size">
                <option value="" disabled selected>Choose size</option>
                <?php
                $sizes = explode(',', $fetch_wishlist['sizes']);
                foreach ($sizes as $size) {
                    echo '<option value="' . $size . '">' . $size . '</option>';
                }
                ?>
            </select>
            <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
        </div>
        <input type="submit" value="add to cart" class="btn" name="add_to_cart">
        <input type="submit" value="delete item" onclick="return confirm('delete this from wishlist?');" class="delete-btn" name="delete">
    </form>
    <?php
        }
    } else {
        echo '<div style="text-align: center;">
        <img src="images/bag-png-33933.png" alt="">
        <h2 class="empty">your wishlist is empty</h2>
            <p class="empty">There is nothing in your Wishlist yet. Visit the shop to find inspiration and personalized recommendations.</p>
            <a href="shop.php" class="navvv__link">View shop</a>
        </div>';
    }
    ?>
    </div>
</section>


<script src="js/script.js"></script>

</body>
</html>
