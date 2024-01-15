<?php



require 'vendor/autoload.php';
include 'config.php';

use PHPGangsta_GoogleAuthenticator;
// ------- Pomocne funkcie -------
function checkEmpty($field)
{
    // Funkcia pre kontrolu, ci je premenna po orezani bielych znakov prazdna.
    // Metoda trim() oreze a odstrani medzery, tabulatory a ine "whitespaces".
    if (empty(trim($field))) {
        return true;
    }
    return false;
}

function checkLength($field, $min, $max)
{
    // Funkcia, ktora skontroluje, ci je dlzka retazca v ramci "min" a "max".
    // Pouzitie napr. pre "login" alebo "password" aby mali pozadovany pocet znakov.
    $string = trim($field);     // Odstranenie whitespaces.
    $length = strlen($string);      // Zistenie dlzky retazca.
    if ($length < $min || $length > $max) {
        return false;
    }
    return true;
}

function checkUsername($username)
{
    // Funkcia pre kontrolu, ci username obsahuje iba velke, male pismena, cisla a podtrznik.
    if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($username))) {
        return false;
    }
    return true;
}

function checkGmail($email)
{
    // Funkcia pre kontrolu, ci zadany email je gmail.
    if (!preg_match('/^[\w.+\-]+@gmail\.com$/', trim($email))) {
        return false;
    }
    return true;
}

function userExist($login, $email)
{
    // Funkcia pre kontrolu, ci pouzivatel s "login" alebo "email" existuje.
    include 'config.php';
    $exist = false;
    try {
        $param_login = trim($login);
        $param_email = trim($email);
        $sql = "SELECT id FROM user WHERE login = '$param_login' OR email = '$param_email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $exist = true;
        }
        return $exist;
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

// ------- ------- ------- -------



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errmsg = "";

    // user name validation 
    if (checkEmpty($_POST['login']) === true) {
        $errmsg .= "<p>Enter login.</p>";
    } elseif (checkLength($_POST['login'], 6, 32) === false) {
        $errmsg .= "<p>Login must have min. 6 and max. 32 characters.</p>";
    } elseif (checkUsername($_POST['login']) === false) {
        $errmsg .= "<p>The login can only contain uppercase and lowercase letters, numbers and underscores.</p>";
    }

    // Kontrola pouzivatela
    if (userExist($_POST['login'], $_POST['email']) === true) {
        $errmsg .= "User with already exist</p>";
    }

    // Validacia mailu
    if (checkGmail($_POST['email'])) {
        $errmsg .= "Please login with Google";
        // Ak pouziva google mail, presmerujem ho na prihlasenie cez Google.
        // header("Location: google_login.php");
    }

    // TODO: Validacia hesla
    // TODO: Validacia mena, priezviska

    if (empty($errmsg)) {


        // 2FA pomocou PHPGangsta kniznice: https://github.com/PHPGangsta/GoogleAuthenticator

        $g2fa = new PHPGangsta_GoogleAuthenticator();
        $user_secret = $g2fa->createSecret();
        $qrCodeUrl = $g2fa->getQRCodeGoogleUrl('Blog', $user_secret);
        $oneCode = $g2fa->getCode($user_secret);
        $codeURL = $g2fa->getQRCodeGoogleUrl('Olympic Games', $user_secret);

        $name = $_POST['firstname'];
        $surname = $_POST['lastname'];
        $email = $_POST['email'];
        $login = $_POST['login'];
        $hashed_password = password_hash($_POST['password'], PASSWORD_ARGON2ID);

        $sql1 = "INSERT INTO user (name, surname, login, email, password, 2fa_code) VALUES ('$name','$surname','$login', '$email', '$hashed_password', '$user_secret')";
        $result1 = mysqli_query($conn, $sql1);
    }
}

?>

<!doctype html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>2FA - Register</title>
    <link href="signin.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>


    <div class="container">
        <div class="row">
            <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                <div class="card border-0 shadow rounded-3 my-5">
                    <div class="card-body p-4 p-sm-5">
                        <h5 class="card-title text-center mb-5 fw-light fs-5"><strong>Sign Up </strong></h5>
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                            <div class="form-floating mb-3">
                                <input name="firstname" type="text" class="form-control form-control-sm" id="firstname" placeholder="Enter name" required>
                                <label for="name">Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input name="lastname" type="text" class="form-control form-control-sm" id="lastname" placeholder="Enter lastname" required>
                                <label for="surname">Surname</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input name="login" type="text" class="form-control form-control-sm" id="login" placeholder="Enter login" required>
                                <label for="login">Login</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input name="email" type="email" class="form-control form-control-sm" id="email" placeholder="name@example.com">
                                <label for="email">Email address</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input name="password" type="password" class="form-control form-control-sm" id="password" placeholder="Password" required>
                                <label for="password">Password</label>
                            </div>

                            <div class="d-grid">
                                <button name="sign_up" class="btn btn-primary btn-sm btn-login text-uppercase" type="submit">Register</button>
                            </div>
                            <?php
                            if (!empty($errmsg)) {
                                // Tu vypis chybne vyplnene polia formulara.
                                echo $errmsg;
                            }
                            if (isset($codeURL)) {
                                // Pokial bol vygenerovany QR kod po uspesnej registracii, zobraz ho.
                                $message = '<p>Scan QR code into the Authenticator app for 2FA: <br><img src="' . $codeURL . '" alt="qr code for authenticator application"><br>' . $oneCode . '.</p>';

                                echo $message;
                                echo '<p>You can login now by clicking <strong>Sign in here</strong></p>';
                            }
                            ?>

                            <div class="mt-3" style="text-align:center">
                                Have an account? <a href="login.php"> Sign in here</a>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>



</body>

</html>