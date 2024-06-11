<?php
// Šis kods nodrošina mājaslapas saskarni ar jaunākajiem produktiem. 
// Tajā ir iekļauta arī lietotāja pieteikšanās pārbaude un dinamiska produktu parādīšana, izmantojot Swiper.js karuseļu slīdni.

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<div class="home-bg">
   <section class="home">
      <div class="intro-title">
         <a href="shop.php" class="intro__title">NEW ARRIVALS</a>
      </div>
      <div class="swiper-pagination"></div>
   </section>

   <section class="home-products">
      <h1 class="heading">Latest Products</h1>
      <div class="swiper products-slider">
         <div class="swiper-wrapper">
            <?php
               $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
               $select_products->execute();
               if($select_products->rowCount() > 0){
                  while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
                     $discounted_price = $fetch_product['price'] - ($fetch_product['price'] * ($fetch_product['discount'] / 100));
            ?>
            <form action="" method="post" class="swiper-slide slide">
               <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
               <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
               <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
               <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
               <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>">
                  <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
               </a>
               <div class="product-info">
                  <div class="designer-model">
                     <?= htmlspecialchars($fetch_product['designer']); ?> 
                     <?= htmlspecialchars($fetch_product['model']); ?>
                  </div>
                  <div class="name"><?= $fetch_product['name']; ?></div>
                  <div class="price-info">
                     <?php if ($fetch_product['discount'] > 0): ?>
                        <div class="price">
                           <span class="original-price">€<?= htmlspecialchars($fetch_product['price']); ?></span>
                           <span class="discounted-price">€<?= number_format($discounted_price, 2); ?></span>
                           <span class="discount">(-<?= htmlspecialchars($fetch_product['discount']); ?>%)</span>
                        </div>
                     <?php else: ?>
                        <div class="price"><span>€</span><?= $fetch_product['price']; ?><span></span></div>
                     <?php endif; ?>
                  </div>
               </div>
            </form>
            <?php
                  }
               }else{
                  echo '<p class="empty">No products added yet!</p>';
               }
            ?>
         </div>
         <div class="swiper-pagination"></div>
      </div>
   </section>
</div>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="js/script.js"></script>

<script>
var swiper = new Swiper(".products-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      550: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
      },
   },
});
</script>

</body>
</html>
