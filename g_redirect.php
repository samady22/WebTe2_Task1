<?php

include 'config.php';
require 'vendor/autoload.php';
session_start();
$clientId = "1074687977530-f9qinn9rmh3j4c6jf7b05derc7mqjk1h.apps.googleusercontent.com";
$clientSecret = "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";
$url = "https://site205.webte.fei.stuba.sk/samadycv1/g_redirect.php";

$client = new Google_Client();
$client->setClientId($clientId);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($url);
$client->addScope('profile');
$client->addScope('email');

if ($_GET['code']) {

    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);
    $gauth = new Google\Service\Oauth2($client);
    $google_info = $gauth->userinfo->get();
    $email = $google_info->email;
    $name = $google_info->givenName;
    $surName = $google_info->familyName;
    try {

        $query2 = "SELECT * FROM user where user.email='$email'";
        $result2 = mysqli_query($conn, $query2);
        $data = mysqli_fetch_assoc($result2);
        if (mysqli_num_rows($result2) > 0) {

            $_SESSION["user_id"] = $data['id'];
            $_SESSION["loggedin"] = true;
            $_SESSION["login"] = $email;
            $_SESSION["fullname"] = $name . ' ' . $surName;
            $_SESSION["email"] = $email;
            $_SESSION["created_at"] = $data['created_at'];
            $_SESSION['msg'] = "Welcome " . $name . "!";
        } else {
            $query = "INSERT INTO user (email, name, surname,login) VALUES ('$email', '$name','$surName','$email')";
            $query_run = mysqli_query($conn, $query);
            $last_id = $conn->insert_id;
            $_SESSION["user_id"] = $last_id;
            $_SESSION["loggedin"] = true;
            $_SESSION["login"] = $email;
            $_SESSION["fullname"] = $name . ' ' . $surName;
            $_SESSION["email"] = $email;
            $_SESSION["created_at"] = date("Y-m-d H:i:s");
            $_SESSION['msg'] = "Welcome " . $name . "!";
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

header("Location: index.php");
