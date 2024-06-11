<?php
// Šis kods nodrošina lietotāja saskarni ar galveni, paziņojumu rādīšanu, lietotāja profilu, 
// iepirkumu grozu, vēlmju sarakstu un meklēšanas funkcionalitāti. 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'connect.php';

// Pārbauda, vai lietotājs ir pieteicies
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

// Rāda paziņojumus, ja tādi ir
if (isset($message) && is_array($message)) {
    foreach ($message as $msg) {
        echo '
        <div class="message">
            <span>' . htmlspecialchars($msg) . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}

// Pārvalda meklēšanas vēsturi
if (!isset($_SESSION['search_history'])) {
    $_SESSION['search_history'] = [];
}

if (isset($_GET['query']) && !empty($_GET['query'])) {
    array_unshift($_SESSION['search_history'], $_GET['query']);
    $_SESSION['search_history'] = array_slice($_SESSION['search_history'], 0, 5);
}

if (isset($_POST['delete_history'])) {
    $_SESSION['search_history'] = [];
}
?>

<header class="header">
   <section class="flex">
      <div class="header__wrapper">
         <nav class="letter-spacing">
            <a href="home.php" class="nav__link">Quick step</a>
         </nav>
         <a href="home.php" class="logo">Quick step</a>
         <nav>
            <a href="about_us.php" class="nav__link">About Us</a>
            <a href="contact.php" class="nav__link">Contact</a>
            <a href="shop.php" class="nav__link">Shop</a>
         </nav>
      </div>

      <div class="icons">
         <?php
            if ($user_id != '') {
               $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
               $count_wishlist_items->execute([$user_id]);
               $total_wishlist_counts = $count_wishlist_items->rowCount();

               $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
               $count_cart_items->execute([$user_id]);
               $total_cart_counts = $count_cart_items->rowCount();
            } else {
               $total_wishlist_counts = 0;
               $total_cart_counts = 0;
            }
         ?>
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="search-btn" class="fas fa-search"></div>
         <a href="wishlist.php"><i class="fas fa-heart"></i></a>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i></a>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div id="profile-panel" class="profile">
         <?php
         if ($user_id != '') {
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if ($select_profile->rowCount() > 0) {
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <div class="profile-info">
            <p class="navv__link"><i class="fas fa-user"></i> <?= htmlspecialchars($fetch_profile["name"]); ?></p>
         </div>
         <hr class="profile-divider">
         <div class="profile-actions">
            <div class="profile-center">
               <a href="update_user.php" class="navv__link"><i class="fas fa-edit"></i> Update Profile</a>
            </div>
            <div class="profile-center">
               <a href="orders.php" class="navv__link"><i class="fas fa-shopping-bag"></i> Orders</a>
            </div>
         </div>
         <hr class="profile-divider">
         <div class="profile-logout">
            <a href="components/user_logout.php" onclick="return confirm('Logout from the website?');" class="navv__link"><i class="fas fa-sign-out-alt"></i> Logout</a>
         </div>
         <a href="#" class="exit-cross" onclick="hideProfilePanel()"><i class="fas fa-times"></i></a>
         <?php
            } else {
         ?>
         <p>Please login or register first!</p>
         <div class="flex-btn">
            <a href="user_register.php" class="option-btn">Register</a>
            <a href="user_login.php" class="option-btn">Login</a>
         </div>
         <?php
            }
         } else {
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

      <div id="search-panel" class="search-panel">
         <form action="search_page.php" method="get" class="search-form">
            <input type="text" name="query" placeholder="Search for products..." required>
            <button type="submit" class="fas fa-search"></button>
         </form>
         <div class="search-history">
            <h3>RECENT SEARCHES</h3>
            <ul>
               <?php foreach ($_SESSION['search_history'] as $history): ?>
               <li><a href="search_page.php?query=<?= urlencode($history) ?>"><?= htmlspecialchars($history) ?></a></li>
               <?php endforeach; ?>
            </ul>
            <form method="post" class="delete-history-form">
               <button type="submit" name="delete_history" class="delete-history">
                  <i class="fas fa-trash"></i> Delete history
               </button>
            </form>
         </div>
      </div>
   </section>

   <script>
      function hideProfilePanel() {
         document.getElementById('profile-panel').style.display = 'none';
      }

      document.getElementById('user-btn').addEventListener('click', function() {
         var profilePanel = document.getElementById('profile-panel');
         if (profilePanel.style.display === 'block') {
            profilePanel.style.display = 'none';
         } else {
            profilePanel.style.display = 'block';
         }
      });

      document.getElementById('search-btn').addEventListener('click', function() {
         var searchPanel = document.getElementById('search-panel');
         if (searchPanel.style.display === 'block') {
            searchPanel.style.display = 'none';
         } else {
            searchPanel.style.display = 'block';
         }
      });
   </script>
</header>
