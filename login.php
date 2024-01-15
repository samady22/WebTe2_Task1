<?php

session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: restricted.php");
    exit;
}


require 'vendor/autoload.php';
include 'config.php';

use PHPGangsta_GoogleAuthenticator;

$clientId = "1074687977530-f9qinn9rmh3j4c6jf7b05derc7mqjk1h.apps.googleusercontent.com";
$clientSecret = "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";
$url = "https://site205.webte.fei.stuba.sk/samadycv1/g_redirect.php";

$client = new Google_Client();
$client->setClientId($clientId);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($url);
$client->addScope('profile');
$client->addScope('email');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errMsg = '';
    $loginData = $_POST["login"];
    // query for selecting user with his/her login Id
    $sql = "SELECT * FROM user WHERE login = '$loginData'";
    $result = mysqli_query($conn, $sql);

    // check if user exist
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row["password"];
        // check if the user password is correct
        if (password_verify($_POST['password'], $hashed_password)) {

            $google2fa =  new PHPGangsta_GoogleAuthenticator();

            // check if the user 2fa code is correct
            if ($google2fa->verifyCode($row["2fa_code"], $_POST['2fa'], 2)) {

                // save user data to session
                $_SESSION["loggedin"] = true;
                $_SESSION['user_id'] = $row['id'];
                $_SESSION["login"] = $row['login'];
                $_SESSION["fullname"] = $row['name'] . ' ' . $row['surname'];
                $_SESSION["email"] = $row['email'];
                $_SESSION["created_at"] = $row['created_at'];

                // redirect to home page
                header("location: index.php");
            } else {
                // error message
                $errMsg = "Wrong name or password";
            }
        } else {
            $errMsg = "Wrong name or password";
        }
    } else {
        $errMsg = "Something went wrong!";
    }
}

?>

<!doctype html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Login/register s 2FA - Login</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
                        <h5 class="card-title text-center mb-5 fw-light fs-5"><strong>Sign In</strong></h5>
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                            <div class="form-floating mb-3">
                                <input name="login" type="text" class="form-control form-control-sm" id="login" required>
                                <label for="login">Login id</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input name="password" type="password" class="form-control form-control-sm" id="floatingPassword" required>
                                <label for="floatingPassword">Password</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input name="2fa" type="number" class="form-control form-control-sm" id="2fa" required>
                                <label for="floatingPassword">2FA</label>
                            </div>
                            <div style="color:red; text-align:center"><?= $errMsg ?></div>
                            <div class="d-grid">
                                <button name="sign_in" class="btn btn-primary btn-sm btn-login text-uppercase" type="submit">Sign
                                    in</button>
                            </div>

                            <div class="mt-3" style="text-align:center">
                                Don't have an account <a href="register.php">Sign Up here</a>
                            </div>
                            <hr class="my-4">
                            <!-- google button which redirect to google login page. -->
                            <div class="d-grid mb-2">
                                <a type="button" href="<?= $client->createAuthUrl() ?>" class=" btn btn-danger text-uppercase btn-sm">Sign in with <span class="bi bi-google"></span>+</a>
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