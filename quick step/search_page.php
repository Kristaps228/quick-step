<?php
// Šis kods nodrošina meklēšanas rezultātu parādīšanu lietotājam. Lietotājs var meklēt produktus pēc nosaukuma, raksta numura, dizainera, modeļa vai krāsas.

include 'components/connect.php';

if (session_status() === PHP_SESSION_NONE) {
   session_start();
}

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
}

include 'components/wishlist_cart.php';

$query = isset($_GET['query']) ? $_GET['query'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Search Results</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products">
    <h1 class="heading">Search Results</h1>
    <div class="box-container">
        <?php
        if($query) {
            $search_query = "%$query%";
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE name LIKE ? OR article_number LIKE ? OR designer LIKE ? OR model LIKE ? OR color LIKE ?");
            $select_products->execute([$search_query, $search_query, $search_query, $search_query, $search_query]);
            if($select_products->rowCount() > 0){
                while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
                    $discounted_price = $fetch_product['price'] - ($fetch_product['price'] * ($fetch_product['discount'] / 100));
        ?>
        <form action="" method="post" class="box">
            <input type="hidden" name="product_id" value="<?= $fetch_product['id']; ?>">
            <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
            <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
            <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
            <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
            <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>">
                <div class="product-img-container">
                    <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="" class="first-image">
                    <img src="uploaded_img/<?= $fetch_product['image_02']; ?>" alt="" class="second-image">
                </div>
            </a>
            <div class="details">
                <div class="designer-model">
                    Designer: <?= htmlspecialchars($fetch_product['designer']); ?><br>
                    Model: <?= htmlspecialchars($fetch_product['model']); ?>
                </div>
                <div class="name"><?= htmlspecialchars($fetch_product['name']); ?></div>
                <div class="price">
                    <?php if ($fetch_product['discount'] > 0): ?>
                        <span class="original-price">€<?= htmlspecialchars($fetch_product['price']); ?></span>
                        <span class="discounted-price">€<?= number_format($discounted_price, 2); ?></span>
                        <span class="discount">(<?= htmlspecialchars($fetch_product['discount']); ?>% off)</span>
                    <?php else: ?>
                        €<?= htmlspecialchars($fetch_product['price']); ?>
                    <?php endif; ?>
                </div>
            </div>
        </form>
        <?php
                }
            } else {
                echo '<p class="empty">No products found!</p>';
            }
        }
        ?>
    </div>
</section>

<script src="js/script.js"></script>

</body>
</html>
