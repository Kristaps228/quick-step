<?php
// Šis kods nodrošina iepirkumu groza funkcionalitāti, ļaujot lietotājam skatīt, atjaunināt un dzēst produktus no groza, 
// kā arī dzēst visu grozu un pāriet uz pirkuma apmaksu.

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:user_login.php');
    exit();
}

if (isset($_POST['delete'])) {
    $cart_id = $_POST['cart_id'];
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
    $delete_cart_item->execute([$cart_id]);
}

if (isset($_GET['delete_all'])) {
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart_item->execute([$user_id]);
    header('location:cart.php');
    exit();
}

if (isset($_POST['update_qty'])) {
    $cart_id = $_POST['cart_id'];
    $qty = $_POST['qty'];
    $qty = filter_var($qty, FILTER_SANITIZE_STRING);
    $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
    $update_qty->execute([$qty, $cart_id]);
    $message[] = 'Cart quantity updated';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping Cart</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products shopping-cart">
   <h3 class="heading">Cart</h3>
   <div class="box-container">
   <?php
      $grand_total = 0;
      $select_cart = $conn->prepare("
          SELECT c.*, p.name, p.price, p.image_01, p.discount, p.designer, p.model
          FROM `cart` c 
          JOIN `products` p ON c.product_id = p.id
          WHERE c.user_id = ?
      ");
      $select_cart->execute([$user_id]);
      if ($select_cart->rowCount() > 0) {
          while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
              $original_price = $fetch_cart['price'];
              $discount = $fetch_cart['discount'];
              $discounted_price = $original_price - ($original_price * ($discount / 100));
              $sub_total = $discounted_price * $fetch_cart['quantity'];
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
      <a href="quick_view.php?pid=<?= $fetch_cart['product_id']; ?>">
         <img src="uploaded_img/<?= $fetch_cart['image_01']; ?>" alt="">
      </a>
      <div class="name">
         <span class="designer"><?= htmlspecialchars($fetch_cart['designer']); ?></span>
         <span class="model"><?= htmlspecialchars($fetch_cart['model']); ?></span>
         <?= htmlspecialchars($fetch_cart['name']); ?>
      </div>
      <div class="size">Size: <?= $fetch_cart['size']; ?></div>
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
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="<?= $fetch_cart['quantity']; ?>">
         <button type="submit" class="fas fa-edit" name="update_qty"></button>
      </div>
      <input type="submit" value="delete item" onclick="return confirm('delete this from cart?');" class="delete-btn" name="delete">
   </form>
   <?php
          $grand_total += $sub_total;
          }
      } else {
          echo '<div style="text-align: center;">
          <img src="images/bag-png-33933.png" alt="">
          <h2 class="empty">Your cart is empty</h2>
              <p class="empty">There\'s nothing in your cart yet. Visit the shop to find inspiration and personalized recommendations.</p>
              <a href="shop.php" class="navvv__link">View shop</a>
      </div>';
      }
   ?>
   </div>

   <div class="cart-total">
      <p>Grand total : <span>€<?= number_format($grand_total, 2); ?></span></p>
      <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('delete all from cart?');">Delete all items</a>
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">Proceed to checkout</a>
   </div>
</section>

<script src="js/script.js"></script>
</body>
</html>
