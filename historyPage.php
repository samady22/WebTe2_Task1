<?php

session_start();
include 'config.php';
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

try {
    $userId = $_SESSION['user_id'];
    $query = "SELECT * FROM history WHERE user_id='$userId' ORDER BY history.id DESC "
        or die("Error in the consult.." . mysqli_error($conn));
    $result = mysqli_query($conn, $query);

    $arr_history = [];
    if ($result->num_rows > 0) {
        $arr_history = $result->fetch_all(MYSQLI_ASSOC);
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

?>
<!doctype html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>
    <?php include 'header.php'; ?>
    <section style="background-color: #eee;">

        <div class="card" style="border-radius: 15px;">
            <div class="card-body ">
                <h4 class="mb-2"><?php echo $_SESSION['fullname'] . "'s" . " " . " Timeline" ?></h4>
                <?php if (!empty($arr_history)) { ?>
                    <?php foreach ($arr_history as $history) { ?>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <?php $color = 'blue';
                                echo 'User <span style="color: ' . $color . '">' . $_SESSION['fullname'] . "</span>: <strong>" . $history['action'] . "</strong> at <strong>" . $history['timestamp'] . "</strong>" ?></li>

                        </ul>
                    <?php } ?>
                <?php } ?>

            </div>

    </section>
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>