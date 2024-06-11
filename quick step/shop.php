<?php
// Šis kods nodrošina produktu filtrēšanas un kārtošanas funkcionalitāti tiešsaistes veikala lapā.
// Lietotājs var filtrēt produktus pēc izmēra, dizainera, krāsas, cenas un atlaides, kā arī kārtot tos pēc cenas.
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

include 'components/wishlist_cart.php';

$sort_option = isset($_GET['sort']) ? $_GET['sort'] : 'default';

// Filtra parametri
$size_filter = isset($_GET['size']) ? $_GET['size'] : '';
$designer_filter = isset($_GET['designer']) ? $_GET['designer'] : '';
$color_filter = isset($_GET['color']) ? $_GET['color'] : '';
$price_filter = isset($_GET['price']) ? $_GET['price'] : '';
$discount_filter = isset($_GET['discount']) ? $_GET['discount'] : '';

$designers_query = $conn->prepare("SELECT DISTINCT designer FROM products WHERE designer IS NOT NULL AND designer != ''");
$designers_query->execute();
$designers = $designers_query->fetchAll(PDO::FETCH_ASSOC);

$colors_query = $conn->prepare("SELECT DISTINCT color FROM products WHERE color IS NOT NULL AND color != ''");
$colors_query->execute();
$colors = $colors_query->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products">
    <h1 class="heading">Products</h1>
    <section class="filters">
        <form action="" method="get" class="filter-form">
            <div class="filter-box">
                <label for="size">Size:</label>
                <select name="size" id="size">
                    <option value="">All Sizes</option>
                    <option value="39" <?= $size_filter == '39' ? 'selected' : ''; ?>>39</option>
                    <option value="40" <?= $size_filter == '40' ? 'selected' : ''; ?>>40</option>
                    <option value="41" <?= $size_filter == '41' ? 'selected' : ''; ?>>41</option>
                    <option value="42" <?= $size_filter == '42' ? 'selected' : ''; ?>>42</option>
                    <option value="43" <?= $size_filter == '43' ? 'selected' : ''; ?>>43</option>
                    <option value="44" <?= $size_filter == '44' ? 'selected' : ''; ?>>44</option>
                    <option value="45" <?= $size_filter == '45' ? 'selected' : ''; ?>>45</option>
                    <option value="46" <?= $size_filter == '46' ? 'selected' : ''; ?>>46</option>
                    <option value="47" <?= $size_filter == '47' ? 'selected' : ''; ?>>47</option>
                    <option value="48" <?= $size_filter == '48' ? 'selected' : ''; ?>>48</option>
                </select>
            </div>
            <div class="filter-box">
                <label for="designer">Designer:</label>
                <select name="designer" id="designer">
                    <option value="">All Designers</option>
                    <?php foreach ($designers as $designer): ?>
                        <option value="<?= htmlspecialchars($designer['designer']); ?>" <?= $designer_filter == $designer['designer'] ? 'selected' : ''; ?>><?= htmlspecialchars($designer['designer']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-box">
                <label for="color">Color:</label>
                <select name="color" id="color">
                    <option value="">All Colors</option>
                    <?php foreach ($colors as $color): ?>
                        <option value="<?= htmlspecialchars($color['color']); ?>" <?= $color_filter == $color['color'] ? 'selected' : ''; ?>><?= htmlspecialchars($color['color']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-box">
                <label for="price">Price:</label>
                <select name="price" id="price">
                    <option value="">All Prices</option>
                    <option value="0-100" <?= $price_filter == '0-100' ? 'selected' : ''; ?>>0 - 100€</option>
                    <option value="100-200" <?= $price_filter == '100-200' ? 'selected' : ''; ?>>100 - 200€</option>
                    <option value="200-300" <?= $price_filter == '200-300' ? 'selected' : ''; ?>>200 - 300€</option>
                    <option value="300-400" <?= $price_filter == '300-400' ? 'selected' : ''; ?>>300 - 400€</option>
                    <option value="400-500" <?= $price_filter == '400-500' ? 'selected' : ''; ?>>400 - 500€</option>
                    <option value="500-1000" <?= $price_filter == '500-1000' ? 'selected' : ''; ?>>500 - 1000€</option>
                </select>
            </div>
            <div class="filter-box">
                <label for="discount">Discount:</label>
                <select name="discount" id="discount">
                    <option value="">All Discounts</option>
                    <option value="up_to_20" <?= $discount_filter == 'up_to_20' ? 'selected' : ''; ?>>up to 20%</option>
                    <option value="more_than_20" <?= $discount_filter == 'more_than_20' ? 'selected' : ''; ?>>more than 20% off</option>
                    <option value="more_than_30" <?= $discount_filter == 'more_than_30' ? 'selected' : ''; ?>>more than 30% off</option>
                    <option value="more_than_40" <?= $discount_filter == 'more_than_40' ? 'selected' : ''; ?>>more than 40% off</option>
                    <option value="more_than_50" <?= $discount_filter == 'more_than_50' ? 'selected' : ''; ?>>more than 50% off</option>
                    <option value="more_than_60" <?= $discount_filter == 'more_than_60' ? 'selected' : ''; ?>>more than 60% off</option>
                    <option value="more_than_70" <?= $discount_filter == 'more_than_70' ? 'selected' : ''; ?>>more than 70% off</option>
                    <option value="more_than_80" <?= $discount_filter == 'more_than_80' ? 'selected' : ''; ?>>more than 80% off</option>
                    <option value="more_than_90" <?= $discount_filter == 'more_than_90' ? 'selected' : ''; ?>>more than 90% off</option>
                </select>
            </div>
            <div class="filter-box">
                <label for="sort">Sort by:</label>
                <select name="sort" id="sort">
                    <option value="default" <?= $sort_option == 'default' ? 'selected' : ''; ?>>Default</option>
                    <option value="price_asc" <?= $sort_option == 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                    <option value="price_desc" <?= $sort_option == 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                </select>
            </div>
            <button type="submit" class="btn">Apply Filters</button>
        </form>
        <div class="selected-filters">
            <?php if ($size_filter): ?>
                <span class="filter-tag"><?= $size_filter ?> <a href="shop.php?<?= http_build_query(array_merge($_GET, ['size' => ''])) ?>">&times;</a></span>
            <?php endif; ?>
            <?php if ($designer_filter): ?>
                <span class="filter-tag"><?= $designer_filter ?> <a href="shop.php?<?= http_build_query(array_merge($_GET, ['designer' => ''])) ?>">&times;</a></span>
            <?php endif; ?>
            <?php if ($color_filter): ?>
                <span class="filter-tag"><?= $color_filter ?> <a href="shop.php?<?= http_build_query(array_merge($_GET, ['color' => ''])) ?>">&times;</a></span>
            <?php endif; ?>
            <?php if ($price_filter): ?>
                <span class="filter-tag"><?= $price_filter ?> <a href="shop.php?<?= http_build_query(array_merge($_GET, ['price' => ''])) ?>">&times;</a></span>
            <?php endif; ?>
            <?php if ($discount_filter): ?>
                <span class="filter-tag"><?= str_replace('_', ' ', $discount_filter); ?> <a href="shop.php?<?= http_build_query(array_merge($_GET, ['discount' => ''])) ?>">&times;</a></span>
            <?php endif; ?>
            <?php if ($size_filter || $designer_filter || $color_filter || $price_filter || $discount_filter): ?>
                <a href="shop.php" class="remove-all-filters">Remove all filters</a>
            <?php endif; ?>
        </div>
    </section>
    <div class="box-container">
        <?php
        $query = "SELECT *, price - (price * (discount / 100)) AS discounted_price FROM `products` WHERE 1";

        if ($size_filter) {
            $query .= " AND sizes LIKE '%$size_filter%'";
        }
        if ($designer_filter) {
            $query .= " AND designer = '$designer_filter'";
        }
        if ($color_filter) {
            $query .= " AND color = '$color_filter'";
        }
        if ($price_filter) {
            $price_range = explode('-', $price_filter);
            $query .= " AND price BETWEEN {$price_range[0]} AND {$price_range[1]}";
        }
        if ($discount_filter) {
            switch ($discount_filter) {
                case 'up_to_20':
                    $query .= " AND discount <= 20";
                    break;
                case 'more_than_20':
                    $query .= " AND discount > 20";
                    break;
                case 'more_than_30':
                    $query .= " AND discount > 30";
                    break;
                case 'more_than_40':
                    $query .= " AND discount > 40";
                    break;
                case 'more_than_50':
                    $query .= " AND discount > 50";
                    break;
                case 'more_than_60':
                    $query .= " AND discount > 60";
                    break;
                case 'more_than_70':
                    $query .= " AND discount > 70";
                    break;
                case 'more_than_80':
                    $query .= " AND discount > 80";
                    break;
                case 'more_than_90':
                    $query .= " AND discount > 90";
                    break;
            }
        }
        if ($sort_option == 'price_asc') {
            $query .= " ORDER BY discounted_price ASC";
        } elseif ($sort_option == 'price_desc') {
            $query .= " ORDER BY discounted_price DESC";
        }

        $select_products = $conn->prepare($query);
        $select_products->execute();
        if ($select_products->rowCount() > 0) {
            while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
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
                    <img src="uploaded_img/<?= htmlspecialchars($fetch_product['image_01']); ?>" alt="" class="first-image">
                    <img src="uploaded_img/<?= htmlspecialchars($fetch_product['image_02']); ?>" alt="" class="second-image">
                </div>
            </a>
            <div class="details">
                <div class="product-info">
                    <div class="designer-model">
                        <?= htmlspecialchars($fetch_product['designer']); ?> 
                        <?= htmlspecialchars($fetch_product['model']); ?>
                    </div>
                    <div class="name"><?= htmlspecialchars($fetch_product['name']); ?></div>
                    <div class="price">
                        <?php if ($fetch_product['discount'] > 0): ?>
                            <span class="original-price">€<?= number_format($fetch_product['price'], 2); ?></span>
                            <span class="discounted-price">€<?= number_format($discounted_price, 2); ?></span>
                            <span class="discount-percentage"><?= htmlspecialchars($fetch_product['discount']); ?>% off</span>
                        <?php else: ?>
                            <span class="price">€<?= number_format($fetch_product['price'], 2); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </form>
        <?php
            }
        } else {
            echo '<p class="empty">No products found!</p>';
        }
        ?>
    </div>
</section>


<script src="js/script.js"></script>

</body>
</html>
