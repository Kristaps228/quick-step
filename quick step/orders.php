<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <!-- добавленные стили -->
   
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="orders">

   <h1 class="heading">your orders</h1>

   <div class="box-container">

   <?php
      if($user_id == ''){
         echo '<p class="empty">please login to see your orders</p>';
      }else{
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
         $select_orders->execute([$user_id]);
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   
   <div class="box">
   <a href="pdf-functions.php"  class="nav__link" target="_blank">Invoice (PDF)</a>
      <div class="box-delivery">
         <div class="box-date">
         <h4>From: <span><?= $fetch_orders['placed_on']; ?></span></h4>
         <h4>ID: <span><?= $fetch_orders['id']; ?></span></h4>
         </div>
      <h3>Delivery Address</h3>
      <p><span><?= $fetch_orders['name']; ?></span></p>
      <p><span><?= $fetch_orders['address']; ?></span></p>
      <p><span><?= $fetch_orders['email']; ?></span></p>
      <p>Phone number: <span><?= $fetch_orders['number']; ?></span></p>
      <h3>Payment Method</h3>
      <p><span><?= $fetch_orders['method']; ?></span></p>
      </div>
   <div class="box-items">
      <p>Status: <span style="color:<?php if($fetch_orders['payment_status'] == 'pending'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['payment_status']; ?></span> </p>
      
      <!-- Таблица с изображениями и названиями продуктов -->
      <table>
         <tr>
            <th>Product</th>
            <th></th>
         </tr>
         <?php
            // Получение списка продуктов из заказа
            $product_list = explode(" - ", $fetch_orders['total_products']);

            // Отображение изображений и названий для каждого продукта
            foreach($product_list as $product_info) {
               // Разбивка строки на элементы (название продукта, цена, количество)
               $product_data = explode(" (", $product_info);
               $product_name = $product_data[0];
               $product_image = '';
               // Получение изображения продукта из базы данных
               $select_product_image = $conn->prepare("SELECT image_01, id FROM products WHERE name = ?");
               $select_product_image->execute([$product_name]);
               if($select_product_image->rowCount() > 0) {
                  $fetch_product_image = $select_product_image->fetch(PDO::FETCH_ASSOC);
                  $product_image = $fetch_product_image['image_01'];
               }
   
               echo '<tr>';
               echo '<td><a href="quick_view.php?pid=' . $fetch_product_image['id'] . '"><img src="uploaded_img/' . $product_image . '" alt="' . $product_name . '"></a></td>';
               echo '<td>' . $product_name . '</td>';
               echo '</tr>';
            }
         ?>
      </table>
      
      <h3>Total sum: <span>€ <?= $fetch_orders['total_price']; ?></span></h3>

   </div>
   
   </div>

   <?php
      }
      }else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
      }
   ?>

   </div>
   

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
