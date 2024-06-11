<?php
// Šis kods nodrošina funkcionalitāti, lai pievienotu produktus vēlmju sarakstam un iepirkumu grozam. 
// Tas pārbauda, vai lietotājs ir pieteicies, un apstrādā produkta pievienošanu attiecīgajām tabulām.

if (isset($_POST['add_to_wishlist'])) {

    if ($user_id == '') {
        header('location:user_login.php');
        exit;
    } else {

        $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_STRING);
        $size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_STRING);
        if (empty($size)) {
            $size = 'default'; 
        }

        if ($product_id) {
            $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE product_id = ? AND user_id = ? AND size = ?");
            $check_wishlist_numbers->execute([$product_id, $user_id, $size]);

            $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE product_id = ? AND user_id = ? AND size = ?");
            $check_cart_numbers->execute([$product_id, $user_id, $size]);

            if ($check_wishlist_numbers->rowCount() > 0) {
                $message[] = 'Already added to wishlist!';
            } elseif ($check_cart_numbers->rowCount() > 0) {
                $message[] = 'Already added to cart!';
            } else {
                $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, product_id, size) VALUES(?,?,?)");
                $insert_wishlist->execute([$user_id, $product_id, $size]);
                $message[] = 'Added to wishlist!';
            }
        } else {
            $message[] = 'Product ID missing!';
        }
    }
}

if (isset($_POST['add_to_cart'])) {

    if ($user_id == '') {
        header('location:user_login.php');
        exit;
    } else {

        $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_STRING);
        $qty = filter_input(INPUT_POST, 'qty', FILTER_SANITIZE_STRING);
        $size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_STRING);
        if (empty($size)) {
            $size = 'default'; 
        }

        if (empty($qty)) {
            $qty = 1; 
        }

        if ($product_id) {
            $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE product_id = ? AND user_id = ? AND size = ?");
            $check_cart_numbers->execute([$product_id, $user_id, $size]);

            if ($check_cart_numbers->rowCount() > 0) {
                $message[] = 'Already added to cart!';
            } else {

                $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE product_id = ? AND user_id = ? AND size = ?");
                $check_wishlist_numbers->execute([$product_id, $user_id, $size]);

                if ($check_wishlist_numbers->rowCount() > 0) {
                    $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE product_id = ? AND user_id = ? AND size = ?");
                    $delete_wishlist->execute([$product_id, $user_id, $size]);
                }

                $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, product_id, quantity, size) VALUES(?,?,?,?)");
                $insert_cart->execute([$user_id, $product_id, $qty, $size]);
                $message[] = 'Added to cart!';
            }
        } else {
            $message[] = 'Product ID missing!';
        }
    }
}
?>
