<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">

        <?php include('alert.php'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Add Player Place
                            <a href="add.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="service.php" method="POST">
                            <div class="mb-3">
                                <label>Country</label>
                                <input type="text" name="country" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>City</label>
                                <input type="text" name="city" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Type</label>
                                <input type="text" name="type" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Year</label>
                                <input type="number" name="year" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Game_order</label>
                                <input type="number" name="game_order" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <button type="submit" name="save_player_place" class="btn btn-primary">Save Location</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>