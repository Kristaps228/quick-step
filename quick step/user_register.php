<?php
// Šis kods nodrošina lietotāja reģistrācijas funkcionalitāti.
// Lietotājs var ievadīt savu vārdu, uzvārdu, e-pastu un paroli, lai izveidotu jaunu kontu.

include 'components/connect.php';

session_start();

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $lastname = $_POST['lastname'];
    $lastname = filter_var($lastname, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $password = filter_var($password, FILTER_SANITIZE_STRING);
    $confirm_password = $_POST['confirm_password'];
    $confirm_password = filter_var($confirm_password, FILTER_SANITIZE_STRING);

    if ($password != $confirm_password) {
        $message[] = 'Passwords do not match!';
    } else {
        $check_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $check_email->execute([$email]);

        if ($check_email->rowCount() > 0) {
            $message[] = 'Email already exists!';
        } else {
            $insert_user = $conn->prepare("INSERT INTO `users` (name, lastname, email, password) VALUES (?,?,?,?)");
            $insert_user->execute([$name, $lastname, $email, sha1($password)]);
            $message[] = 'Registration successful! Please login.';
            header('Location: user_login.php');
            exit();
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
    <title>User Register</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    
<?php include 'components/user_header.php'; ?>

<section class="form-container">

    <form action="" method="POST">
        <h3>Register</h3>
        <input type="text" name="name" placeholder="Enter your name" class="box" maxlength="20" required>
        <input type="text" name="lastname" placeholder="Enter your last name" class="box" maxlength="50" required>
        <input type="email" name="email" placeholder="Enter your email" class="box" maxlength="50" required>
        <input type="password" name="password" placeholder="Enter your password" class="box" maxlength="50" required>
        <input type="password" name="confirm_password" placeholder="Confirm your password" class="box" maxlength="50" required>
        <input type="submit" name="submit" class="btn" value="Register">
        <p>Already have an account? <a href="user_login.php">Login now</a></p>
    </form>

</section>


<script src="js/script.js"></script>

</body>
</html>
