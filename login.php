<?php
session_start();
include_once 'modules/database.php';
include_once 'modules/functions.php';

$errors = [];
$inputs = [];

const EMAIL_REQUIRED = 'Email invullen';
const EMAIL_INVALID = 'Geldig email aders invullen';
const PASSWORD_REQUIRED = 'password invullen';
const CREDENTIAL_NOT_VALID = 'Verkeerde email en/of password';

if(isset($_POST['login'])) {

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if ($email === false) {
        $errors['email'] = EMAIL_REQUIRED;
    } else {
        $inputs['email'] = $email;
    }

    $password = filter_input(INPUT_POST, 'password');
    if (empty($password)) {
        $error['password'] = PASSWORD_REQUIRED;
    } else {
        $inputs['password'] = $password;
    }

    if (count($errors) === 0) {

        $result = checkLogin($inputs);

        switch ($result) {
            case 'ADMIN':
                header('Location: admin.php');
                break;

            case 'FAILURE':
                $errors['credentials'] = CREDENTIAL_NOT_VALID;
                include_once "login.php";
                break;
        }
    }
}

function checkLogin($inputs):string
{
    global $pdo;
    $sql = 'SELECT * FROM `user` WHERE `email` = :e AND `password` = :p';
    $sth = $pdo->prepare($sql);
    $sth->bindParam(':e', $inputs['email']);
    $sth->bindParam(':p', $inputs['password']);
    $sth->setFetchMode(PDO::FETCH_CLASS, "User");
    $sth->execute();
    $user = $sth->fetch();
    var_dump($user);

    if($user!==false)
    {
        $_SESSION['user']=$user;
        if($_SESSION['user']->role=="admin")
        {
            return 'ADMIN';
        }
    }
    return 'FAILURE';
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SmartPhone4u Home</title>
    <link rel="stylesheet" href="css/phones.css">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand text-white fs-3" href="index.php">SmartPhone4u</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active fs-5 text-white" aria-current="page" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-secondary fs-5" href="vendor.php">Bestellen</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="login.php">inloggen</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<header>
    <div class="container-fluid py-5 "  style="background: url('img/header.png'); background-size: cover">
        <div class="row py-5"></div>
    </div>
</header>
<main>
    <div class="container-lg">
        <h4>Inloggen</h4>
        <?php if(!empty($errors['credentials'])): ?>
        <div class="'alert alert-danger">
            <?= $errors['credentials'] ?? '' ?>
        </div>
    </div>
    <?php endif;?>

    <form method="post">
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="email" name="email" id="email" value="<?php echo $inputs['email'] ?? '' ?>">
            <div class="form-text text-danger">
                <?= $errors['email'] ?? '' ?>
            </div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="password" name="password" id="password">
            <div class="form-text text-danger">
                <?= $errors['password'] ?? '' ?>
            </div>
        </div>
        <button type="submit" name="login" class="btn btn-primary mb-5">Login</button>
    </form>
</main>
<footer class="bg-dark">
    <div class="container-fluid text-white">
        <div class="row pb-3">
            <div class="col-md-6 mt-4 text-center">
                <ul class="list-unstyled">
                    <li class="list-group-item fw-bold">Contactgegevens</li>
                    <li class="list-group-item">SmartPhone4u</li>
                    <li class="list-group-item">Phoenixstraat 1</li>
                    <li class="list-group-item">1111AA Delft</li>
                    <li class="list-group-item">smartphones4u@gmail.com</li>
                    <li class="list-group-item">06- 12345678</li>
                </ul>
            </div>
            <div class="col-md-6 mt-4 text-center">
                <ul class="list-unstyled">
                    <li class="list-group-item fw-bold">Openingstijden</li>
                    <li class="list-group-item">Maandag: Gesloten</li>
                    <li class="list-group-item">Dinsdag: 16:00 - 22:00</li>
                    <li class="list-group-item">Woensdag: 16:00 - 22:00</li>
                    <li class="list-group-item">Donderdag: 16:00 - 22:00</li>
                    <li class="list-group-item">Vrijdag: 15:00 - 22:00</li>
                    <li class="list-group-item">Zaterdag: 15:00 - 22:00</li>
                    <li class="list-group-item">Zondag: Gesloten</li>
                </ul>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
