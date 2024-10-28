<?php 
require_once 'includes/db.php'; // Database connection
session_start(); // Start the session

///////////////////////////////////////////////
// LOGIN FOREVER
// if (isset($_SESSION['login'])) {
//     header('Location: admin/admin.php'); // Correct path to admin panel
//     exit();
// }
//////////////////////////////////////////////


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Query to check if user exists
    $sql = $pdo->prepare("SELECT id, login FROM users WHERE login=:login AND password=:password");
    $sql->execute(['login' => $login, 'password' => $password]);
    $user = $sql->fetch(PDO::FETCH_ASSOC);

    // If user is found, set session and redirect
    if ($user && $user['id'] > 0) {
        $_SESSION['login'] = $user['login'];
        header('Location: admin/admin.php');
        exit();
    } else {
        // Display error if login fails
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration Panel</title>

    <!-- CSS CONNECTION -->
    <link rel="stylesheet" href="css/login.css">

</head>
<body>
    <div class="registration-container">
        <div class="logo">
            <img src="assets/files/logo.png" alt="Logo">
        </div>
        <form action="" method="POST" class="registration-form">
            <h2>Панель Администратора</h2>
            <div class="input-group">
                <label for="username">Имя пользователя</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="submit-btn">Login</button>
        </form>

        <?php if (!empty($error)) { ?>
            <div class='error'><?= $error ?></div>
        <?php } ?>
    </div>
</body>
</html>
