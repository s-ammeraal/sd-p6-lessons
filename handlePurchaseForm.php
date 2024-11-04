<?php

const FNAME_REQUIRED = 'Voornaam invullen';
const LNAME_REQUIRED = 'Achternaam invullen';
const EMAIL_REQUIRED = 'Email invullen';
const EMAIL_INVALID = 'Geldig email adres invullen';
const ADRESS_REQUIRED = 'Adres invullen';
const ZIPCODE_REQUIRED = 'Postcode invullen';
const CITY_REQUIRED = 'Woonplaats invullen';
const AGREE_REQUIRED = 'Voorwaarden accepteren';

// sanitize and validate fname
$fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_SPECIAL_CHARS);

$fname = trim($fname);
if (empty($fname)) {
    $errors['fname'] = FNAME_REQUIRED;
} else {
    $inputs['fname'] = $fname;
}

// sanitize and validate lname
$lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_SPECIAL_CHARS);

$lname = trim($lname);
if (empty($lname)) {
    $errors['lname'] = LNAME_REQUIRED;
} else {
    $inputs['lname'] = $lname;
}

$email=filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL);

if($email===false){
    $errors['email'] = EMAIL_INVALID;
} else {
    $inputs['email'] = $email;
}


// sanitize and validate adress
$adress = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_SPECIAL_CHARS);
$adress = trim($adress);

if (empty($adress)) {
    $errors['address'] = ADRESS_REQUIRED;
} else {
    $inputs['address'] = $adress;
}

// sanitize and validate zipcode
$zipcode = filter_input(INPUT_POST, 'zipcode', FILTER_SANITIZE_SPECIAL_CHARS);
$zipcode = trim($zipcode);

if (empty($zipcode)) {
    $errors['zipcode'] = ZIPCODE_REQUIRED;
} else {
    $inputs['zipcode'] = $zipcode;
}

// sanitize and validate city
$city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_SPECIAL_CHARS);
$city = trim($city);

if (empty($city)) {
    $errors['city'] = CITY_REQUIRED;
} else {
    $inputs['city'] = $city;
}

// accept terms
$agree = filter_input(INPUT_POST, 'agree', FILTER_SANITIZE_SPECIAL_CHARS);

// check against the valid value
if ($agree===null) {
    $errors['agree'] = AGREE_REQUIRED;
}


if (count($errors) === 0) {
    $result=savePurchase($inputs);
    $smartphone=getSmartphone($_GET['id']);
    if($result===true){
        $_SESSION['message']="Je $smartphone->name is besteld";
    } else {
         $_SESSION['message']="Je $smartphone->name is niet besteld";
    }
    header("Location: index.php");
}





