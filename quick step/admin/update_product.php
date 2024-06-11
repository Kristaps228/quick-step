<?php
// Šis kods nodrošina administratora saskarni produktu atjaunināšanai. 
// Administrators var atjaunināt produkta informāciju, ieskaitot nosaukumu, cenu, aprakstu, atlaidi, dizaineru, modeli, materiālus, krāsu un attēlus.

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
   exit;
}

if(isset($_POST['update'])){

   $pid = $_POST['pid'];
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
   $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);
   $discount = filter_var($_POST['discount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
   $designer = filter_var($_POST['designer'], FILTER_SANITIZE_STRING);
   $model = filter_var($_POST['model'], FILTER_SANITIZE_STRING);
   $outer_material = filter_var($_POST['outer_material'], FILTER_SANITIZE_STRING);
   $inner_material = filter_var($_POST['inner_material'], FILTER_SANITIZE_STRING);
   $outsole = filter_var($_POST['outsole'], FILTER_SANITIZE_STRING);
   $color = filter_var($_POST['color'], FILTER_SANITIZE_STRING);
   $article_number = filter_var($_POST['article_number'], FILTER_SANITIZE_STRING);

   $update_product = $conn->prepare("UPDATE `products` SET name = ?, price = ?, details = ?, discount = ?, designer = ?, model = ?, outer_material = ?, inner_material = ?, outsole = ?, color = ?, article_number = ? WHERE id = ?");
   
   if ($update_product->execute([$name, $price, $details, $discount, $designer, $model, $outer_material, $inner_material, $outsole, $color, $article_number, $pid])) {
      $message[] = 'Product updated successfully!';
   } else {
      $errorInfo = $update_product->errorInfo();
      $message[] = 'Failed to update product: ' . $errorInfo[2];
   }

   $old_image_01 = $_POST['old_image_01'];
   $image_01 = filter_var($_FILES['image_01']['name'], FILTER_SANITIZE_STRING);
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = '../uploaded_img/'.$image_01;

   if(!empty($image_01)){
      if($image_size_01 > 2000000){
         $message[] = 'Image size is too large!';
      }else{
         $update_image_01 = $conn->prepare("UPDATE `products` SET image_01 = ? WHERE id = ?");
         if ($update_image_01->execute([$image_01, $pid])) {
            move_uploaded_file($image_tmp_name_01, $image_folder_01);

            if(file_exists('../uploaded_img/'.$old_image_01)){
               unlink('../uploaded_img/'.$old_image_01);
            }

            $message[] = 'Image 01 updated successfully!';
         } else {
            $errorInfo = $update_image_01->errorInfo();
            $message[] = 'Failed to update Image 01: ' . $errorInfo[2];
         }
      }
   }

   $old_image_02 = $_POST['old_image_02'];
   $image_02 = filter_var($_FILES['image_02']['name'], FILTER_SANITIZE_STRING);
   $image_size_02 = $_FILES['image_02']['size'];
   $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
   $image_folder_02 = '../uploaded_img/'.$image_02;

   if(!empty($image_02)){
      if($image_size_02 > 2000000){
         $message[] = 'Image size is too large!';
      }else{
         $update_image_02 = $conn->prepare("UPDATE `products` SET image_02 = ? WHERE id = ?");
         if ($update_image_02->execute([$image_02, $pid])) {
            move_uploaded_file($image_tmp_name_02, $image_folder_02);

            if(file_exists('../uploaded_img/'.$old_image_02)){
               unlink('../uploaded_img/'.$old_image_02);
            }

            $message[] = 'Image 02 updated successfully!';
         } else {
            $errorInfo = $update_image_02->errorInfo();
            $message[] = 'Failed to update Image 02: ' . $errorInfo[2];
         }
      }
   }

   $old_image_03 = $_POST['old_image_03'];
   $image_03 = filter_var($_FILES['image_03']['name'], FILTER_SANITIZE_STRING);
   $image_size_03 = $_FILES['image_03']['size'];
   $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
   $image_folder_03 = '../uploaded_img/'.$image_03;

   if(!empty($image_03)){
      if($image_size_03 > 2000000){
         $message[] = 'Image size is too large!';
      }else{
         $update_image_03 = $conn->prepare("UPDATE `products` SET image_03 = ? WHERE id = ?");
         if ($update_image_03->execute([$image_03, $pid])) {
            move_uploaded_file($image_tmp_name_03, $image_folder_03);

            if(file_exists('../uploaded_img/'.$old_image_03)){
               unlink('../uploaded_img/'.$old_image_03);
            }

            $message[] = 'Image 03 updated successfully!';
         } else {
            $errorInfo = $update_image_03->errorInfo();
            $message[] = 'Failed to update Image 03: ' . $errorInfo[2];
         }
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Product</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="update-product">

   <h1 class="heading">Update Product</h1>

   <?php
      $update_id = $_GET['update'];
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $select_products->execute([$update_id]);
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
            $discounted_price = $fetch_products['price'] - ($fetch_products['price'] * $fetch_products['discount'] / 100);
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="old_image_01" value="<?= $fetch_products['image_01']; ?>">
      <input type="hidden" name="old_image_02" value="<?= $fetch_products['image_02']; ?>">
      <input type="hidden" name="old_image_03" value="<?= $fetch_products['image_03']; ?>">
      <div class="image-container">
         <div class="main-image">
            <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
         </div>
         <div class="sub-image">
            <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
            <img src="../uploaded_img/<?= $fetch_products['image_02']; ?>" alt="">
            <img src="../uploaded_img/<?= $fetch_products['image_03']; ?>" alt="">
         </div>
      </div>
      <span>Update Name</span>
      <input type="text" name="name" required class="box" maxlength="100" placeholder="Enter product name" value="<?= $fetch_products['name']; ?>">
      <span>Update Price</span>
      <input type="number" name="price" required class="box" min="0" max="9999999999" placeholder="Enter product price" onkeypress="if(this.value.length == 10) return false;" value="<?= $fetch_products['price']; ?>">
      <span>Update Details</span>
      <textarea name="details" class="box" required cols="30" rows="10"><?= $fetch_products['details']; ?></textarea>
      <span>Update Discount</span>
      <input type="number" name="discount" class="box" min="0" max="100" placeholder="Enter discount percentage" value="<?= $fetch_products['discount']; ?>">
      <span>Update Designer</span>
      <input type="text" name="designer" required class="box" maxlength="255" placeholder="Enter designer" value="<?= $fetch_products['designer']; ?>">
      <span>Update Model</span>
      <input type="text" name="model" required class="box" maxlength="50" placeholder="Enter product model" value="<?= $fetch_products['model']; ?>">
      <span>Update Outer Material</span>
      <input type="text" name="outer_material" required class="box" maxlength="50" placeholder="Enter outer material" value="<?= $fetch_products['outer_material']; ?>">
      <span>Update Inner Material</span>
      <input type="text" name="inner_material" required class="box" maxlength="50" placeholder="Enter inner material" value="<?= $fetch_products['inner_material']; ?>">
      <span>Update Outsole</span>
      <input type="text" name="outsole" required class="box" maxlength="50" placeholder="Enter outsole" value="<?= $fetch_products['outsole']; ?>">
      <span>Update Color</span>
      <input type="text" name="color" required class="box" maxlength="30" placeholder="Enter color" value="<?= $fetch_products['color']; ?>">
      <span>Update Article Number</span>
      <input type="text" name="article_number" required class="box" maxlength="30" placeholder="Enter article number" value="<?= $fetch_products['article_number']; ?>">
      <span>Update Image 01</span>
      <input type="file" name="image_01" accept="image/jpg, image.jpeg, image.png, image.webp" class="box">
      <span>Update Image 02</span>
      <input type="file" name="image_02" accept="image/jpg, image.jpeg, image.png, image.webp" class="box">
      <span>Update Image 03</span>
      <input type="file" name="image_03" accept="image/jpg, image.jpeg, image.png, image.webp" class="box">
      <div class="flex-btn">
         <input type="submit" name="update" class="btn" value="Update">
         <a href="products.php" class="option-btn">Go Back</a>
      </div>
   </form>
   
   <?php
         }
      }else{
         echo '<p class="empty">No product found!</p>';
      }
   ?>

</section>

<script src="../js/admin_script.js"></script>
   
</body>
</html>

