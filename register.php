<?php
    session_start();
    include ('modules/functions.php');
    include ('modules/database.php');

    $errors = [];
    $inputs = [];

    const UNIQUE_EMAIL_REQUIRED = 'Er bestaat al een account met deze email';
    const REQUIRED = 'Dit veld is verplicht';

    if (isset($_POST['submit'])) {

        $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_SPECIAL_CHARS);
        if ($firstname === false) {
            $errors['firstname'] = REQUIRED;
        } else {
            $inputs['firstname'] = $firstname;
        }

        $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_SPECIAL_CHARS);
        if ($lastname === false) {
            $errors['lastname'] = REQUIRED;
        } else {
            $inputs['lastname'] = $lastname;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if ($email === false) {
            $errors['email'] = REQUIRED;
        } else {
            global $pdo;
            $sth = $pdo->prepare("SELECT * FROM user WHERE email = :email");
            $sth->bindValue(':email', $email);
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_CLASS, 'User');
            if (count($result) >= 1) {
                $errors['email'] =  UNIQUE_EMAIL_REQUIRED;
            } else{
                $inputs['email'] = $email;
            }
        }

        $password = filter_input(INPUT_POST, 'password');
        if (empty($password)) {
            $error['password'] = REQUIRED;
        } else {
            $inputs['password'] = $password;
        }

        if (empty($errors)) {
            global $pdo;

            $pw = password_hash($inputs['password'], PASSWORD_DEFAULT);

            $sth = $pdo->prepare("INSERT INTO user (first_name, last_name, email, password, role)
                VALUES (:firstname, :lastname, :email, :password, 'member')");
            $sth->bindParam(":firstname", $inputs['firstname']);
            $sth->bindParam(":lastname", $inputs['lastname']);
            $sth->bindParam(":email", $inputs['email']);
            $sth->bindParam(":password", $pw);
            $result = $sth->execute();
            header('Location: index.php');
        }
    }
?>

<!DOCTYPE html>
<html lang="nl">
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
    <div class="container">
        <h2>Registreren</h2>
        <form method="post" action="register.php">
            <div class="mb-3">
                <label for="fn" class="form-label">Voornaam</label>
                <input type="text" class="email" name="firstname" id="fn" value="<?php echo $inputs['firstname'] ?? '' ?>">
                <div class="form-text text-danger">
                    <?= $errors['firstname'] ?? '' ?>
                </div>
            </div>
            <div class="mb-3">
                <label for="ln" class="form-label">Achternaam</label>
                <input type="text" class="email" name="lastname" id="ln" value="<?php echo $inputs['lastname'] ?? '' ?>">
                <div class="form-text text-danger">
                    <?= $errors['lastname'] ?? '' ?>
                </div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="email" name="email" id="email" value="<?php echo $inputs['email'] ?? '' ?>">
                <div class="form-text text-danger">
                    <?= $errors['email'] ?? '' ?>
                </div>
            </div>
            <div class="mb-3">
                <label for="pw" class="form-label">Wachtwoord</label>
                <input type="password" class="password" name="password" id="pw" value="<?php echo $inputs['wachtwoord'] ?? '' ?>">
                <div class="form-text text-danger">
                    <?= $errors['wachtwoord'] ?? '' ?>
                </div>
            </div>
            <button type="submit" name="submit" class="btn btn-primary mb-5">Registreer</button>
        </form>
    </div>
</body>
