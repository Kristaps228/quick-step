<?php
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>


<header class="header">

   <section class="flex">
      <div class="header__wrapper">
         <nav class="letter-spacing">
            <a href="home.php" class="nav__link">Quick step</a>
         </nav>
      <a href="home.php" class="logo">Quick step</a>
      <nav >
         <a href="#contact" class="nav__link">contact</a>
         <a href="shop.php" class="nav__link">shop</a>
         

      </nav>

      </div>

      <div class="icons">
         <?php
            $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
            $count_wishlist_items->execute([$user_id]);
            $total_wishlist_counts = $count_wishlist_items->rowCount();

            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_counts = $count_cart_items->rowCount();
         ?>
         <div id="menu-btn" class="fas fa-bars"></div>
         <a href="search_page.php"><i class="fas fa-search"></i></a>
         <a href="wishlist.php"><i class="fas fa-heart"></i></a>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i></a>
         <div id="user-btn" class="fas fa-user"></div>
      </div>


      <div class="profile">
    <?php
    $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
    $select_profile->execute([$user_id]);
    if($select_profile->rowCount() > 0){
        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
    ?>
    <div class="profile-info">
        <p class="navv__link"><i class="fas fa-user"></i> <?= $fetch_profile["name"]; ?></p>
    </div>
            
    <hr class="profile-divider">

    <div class="profile-actions">
        <a href="update_user.php" class="navv__link"><i class="fas fa-edit"></i> Update Profile</a>
        <a href="orders.php" class="navv__link"><i class="fas fa-shopping-bag"></i> Orders</a>
    </div>

    <hr class="profile-divider">
    <div class="profile-logout">
        <a href="components/user_logout.php" onclick="return confirm('Logout from the website?');" class="navv__link"><i ></i> Logout</a>
      </div>
    <a href="home.php" class="exit-cross"><i class="fas fa-times"></i></a>

    <?php
    }else{
    ?>
    <p>Please login or register first!</p>
    <div class="flex-btn">
        <a href="user_register.php" class="option-btn">Register</a>
        <a href="user_login.php" class="option-btn">Login</a>
    </div>
    <?php
    }
    ?>
</div>


   </section>
   <script>
    document.querySelector('a[href="#contact"]').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('contact').scrollIntoView({ behavior: 'smooth' });
    });
</script>
</header>