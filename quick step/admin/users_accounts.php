<?php
// Šis kods nodrošina lietotāju kontu pārvaldību administratora panelī. 
// Administrators var skatīt visus lietotāju kontus un dzēst tos, kas nepieciešami.

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_user = $conn->prepare("DELETE FROM `users` WHERE id = ?");
    $delete_user->execute([$delete_id]);
    header('location:users_accounts.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Accounts</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="accounts">
    <h1 class="heading">User Accounts</h1>
    <div class="box-container">
        <?php
        $select_accounts = $conn->prepare("SELECT * FROM `users`");
        $select_accounts->execute();
        if ($select_accounts->rowCount() > 0) {
            while ($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="box">
            <p> User ID: <span><?= $fetch_accounts['id']; ?></span> </p>
            <p> Username: <span><?= $fetch_accounts['name']; ?></span> </p>
            <p> Email: <span><?= $fetch_accounts['email']; ?></span> </p>
            <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('Delete this account? The user related information will also be deleted!')" class="delete-btn">Delete</a>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">No accounts available!</p>';
        }
        ?>
    </div>
</section>

<script src="../js/admin_script.js"></script>

</body>
</html>