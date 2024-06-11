<?php
// Šis kods nodrošina administratora saskarni produktu pievienošanai un pārvaldībai. 
// Administratoriem ir iespēja pievienot jaunus produktus, atjaunināt to informāciju un dzēst produktus. 
// Produkta pievienošana ietver informāciju par nosaukumu, cenu, atlaidi, dizaineru, modeli, materiāliem, krāsu un attēliem.

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit;
}

if (isset($_POST['add_product'])) {
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

    $image_01 = filter_var($_FILES['image_01']['name'], FILTER_SANITIZE_STRING);
    $image_size_01 = $_FILES['image_01']['size'];
    $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
    $image_folder_01 = '../uploaded_img/' . $image_01;

    $image_02 = filter_var($_FILES['image_02']['name'], FILTER_SANITIZE_STRING);
    $image_size_02 = $_FILES['image_02']['size'];
    $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
    $image_folder_02 = '../uploaded_img/' . $image_02;

    $image_03 = filter_var($_FILES['image_03']['name'], FILTER_SANITIZE_STRING);
    $image_size_03 = $_FILES['image_03']['size'];
    $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
    $image_folder_03 = '../uploaded_img/' . $image_03;

    $sizes = '39,40,41,42,43,44,45,46,47,48';

    $insert_products = $conn->prepare("INSERT INTO `products` (name, details, price, discount, designer, model, outer_material, inner_material, outsole, color, image_01, image_02, image_03, sizes, article_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insert_products->execute([$name, $details, $price, $discount, $designer, $model, $outer_material, $inner_material, $outsole, $color, $image_01, $image_02, $image_03, $sizes, $article_number]);

    if ($insert_products) {
        $upload_errors = false;
        if ($image_size_01 > 2000000 || $image_size_02 > 2000000 || $image_size_03 > 2000000) {
            $message[] = 'Image size is too large!';
            $upload_errors = true;
        }

        if (!$upload_errors) {
            if (move_uploaded_file($image_tmp_name_01, $image_folder_01) && move_uploaded_file($image_tmp_name_02, $image_folder_02) && move_uploaded_file($image_tmp_name_03, $image_folder_03)) {
                $message[] = 'New product added!';
            } else {
                $message[] = 'Failed to upload images!';
            }
        }
    } else {
        $message[] = 'Failed to add product!';
    }
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    $check_order_items = $conn->prepare("SELECT * FROM `order_items` WHERE product_id = ?");
    $check_order_items->execute([$delete_id]);

    if ($check_order_items->rowCount() > 0) {
        $message[] = 'Cannot delete this product as it is used in orders!';
    } else {
        $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
        $delete_product_image->execute([$delete_id]);
        $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);

        if ($fetch_delete_image) {
            if (file_exists('../uploaded_img/' . $fetch_delete_image['image_01'])) {
                unlink('../uploaded_img/' . $fetch_delete_image['image_01']);
            }
            if (file_exists('../uploaded_img/' . $fetch_delete_image['image_02'])) {
                unlink('../uploaded_img/' . $fetch_delete_image['image_02']);
            }
            if (file_exists('../uploaded_img/' . $fetch_delete_image['image_03'])) {
                unlink('../uploaded_img/' . $fetch_delete_image['image_03']);
            }
        }

        $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
        $delete_product->execute([$delete_id]);

        $message[] = 'Product successfully deleted!';
    }

    header('location:products.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="add-products">
   <h1 class="heading">Add Product</h1>
   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <span>Product Name (required)</span>
            <input type="text" class="box" required maxlength="100" placeholder="Enter product name" name="name">
         </div>
         <div class="inputBox">
            <span>Product Price (required)</span>
            <input type="number" min="0" class="box" required max="9999999999" placeholder="Enter product price" onkeypress="if(this.value.length == 10) return false;" name="price">
         </div>
         <div class="inputBox">
            <span>Product Discount</span>
            <input type="number" step="0.01" min="0" max="100" class="box" placeholder="Enter discount percentage" name="discount">
         </div>
         <div class="inputBox">
            <span>Designer (required)</span>
            <input type="text" class="box" required maxlength="255" placeholder="Enter designer" name="designer">
         </div>
         <div class="inputBox">
            <span>Model (required)</span>
            <input type="text" class="box" required maxlength="50" placeholder="Enter model" name="model">
         </div>
         <div class="inputBox">
            <span>Outer Material (required)</span>
            <input type="text" class="box" required maxlength="50" placeholder="Enter outer material" name="outer_material">
         </div>
         <div class="inputBox">
            <span>Inner Material (required)</span>
            <input type="text" class="box" required maxlength="50" placeholder="Enter inner material" name="inner_material">
         </div>
         <div class="inputBox">
            <span>Outsole (required)</span>
            <input type="text" class="box" required maxlength="50" placeholder="Enter outsole" name="outsole">
         </div>
         <div class="inputBox">
            <span>Color (required)</span>
            <input type="text" class="box" required maxlength="30" placeholder="Enter color" name="color">
         </div>
         <div class="inputBox">
            <span>Image 01 (required)</span>
            <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
         </div>
         <div class="inputBox">
            <span>Image 02 (required)</span>
            <input type="file" name="image_02" accept="image/jpg, image.jpeg, image.png, image.webp" class="box" required>
         </div>
         <div class="inputBox">
            <span>Image 03 (required)</span>
            <input type="file" name="image_03" accept="image/jpg, image.jpeg, image.png, image.webp" class="box" required>
         </div>
         <div class="inputBox">
            <span>Product Description (required)</span>
            <textarea name="details" placeholder="Enter product description" class="box" required maxlength="500" cols="30" rows="10"></textarea>
         </div>
         <div class="inputBox">
            <span>Article Number (required)</span>
            <input type="text" class="box" required maxlength="30" placeholder="Enter article number" name="article_number">
         </div>
      </div>
      <input type="submit" value="Add Product" class="btn" name="add_product">
   </form>
</section>

<section class="show-products">
   <h1 class="heading">Added Products</h1>
   <div class="box-container">
      <?php
         $select_products = $conn->prepare("SELECT * FROM `products`");
         $select_products->execute();
         if ($select_products->rowCount() > 0) {
            while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
               $discounted_price = $fetch_products['price'] - ($fetch_products['price'] * $fetch_products['discount'] / 100);
               $discount_percentage = round($fetch_products['discount'], 2);
      ?>
      <div class="box">
         <img src="../uploaded_img/<?= htmlspecialchars($fetch_products['image_01']); ?>" alt="">
         <img src="../uploaded_img/<?= htmlspecialchars($fetch_products['image_02']); ?>" alt="">
         <img src="../uploaded_img/<?= htmlspecialchars($fetch_products['image_03']); ?>" alt="">
         <div class="designer-model"><strong><?= htmlspecialchars($fetch_products['designer']); ?> <?= htmlspecialchars($fetch_products['model']); ?></strong></div>
         <div class="name"><?= htmlspecialchars($fetch_products['name']); ?></div>
         <?php if ($fetch_products['discount'] > 0): ?>
            <div class="price">
               <s>€<span><?= htmlspecialchars($fetch_products['price']); ?></span></s> 
               €<span><?= htmlspecialchars($discounted_price); ?></span>
               <span class="discount-percentage">(<?= $discount_percentage; ?>% off)</span>
            </div>
         <?php else: ?>
            <div class="price">€<span><?= htmlspecialchars($fetch_products['price']); ?></span></div>
         <?php endif; ?>
         <div class="details"><span><?= htmlspecialchars($fetch_products['details']); ?></span></div>
         <div class="sizes"><span>Available sizes: <?= htmlspecialchars($fetch_products['sizes']); ?></span></div>
         <div class="article-number"><span>Article Number: <?= htmlspecialchars($fetch_products['article_number']); ?></span></div>
         <div class="flex-btn">
            <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Update</a>
            <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?');">Delete</a>
         </div>
      </div>
      <?php
            }
         } else {
            echo '<p class="empty">No products added yet!</p>';
         }
      ?>
   </div>
</section>

<script src="../js/admin_script.js"></script>
</body>
</html>
