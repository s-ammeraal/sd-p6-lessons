<?php
    session_start();

    include_once 'modules/database.php';
    include_once 'modules/functions.php';

    $inputs = [];
    $errors = [];

    
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<div class="container-lg">
    <h4>log in</h4>
    <?php
        if (!empty($errors)) {
            echo "
                <div class='alert alert-danger'>
                  $errors[credentials]
                </div>      
            ";
        }
    ?>
</div>
<form action="login.php" method="post">
    <label for="mail" class="form-label">Email</label>
    <input type="email" id="mail" name="email" class="form-control" value="<?= $inputs['email'] ?>">
    <div class="text-danger form-text">
        <?= $errors['email'] ?>
    </div>
    <label for="pw" class="form-label">Password</label>
    <input type="email" id="pw" name="password" class="form-control" value="<?= $inputs['password'] ?>">
    <div class="text-danger form-text">
        <?= $errors['password'] ?>
    </div>

    <input type="submit" class="btn btn-primary mb-5" name="login" value="login">
</form>