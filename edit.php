<?php
include 'config.php';
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Edit player</title>
</head>

<body>
    <div class="container mt-4">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit player data
                            <a href="index.php" class="btn btn-danger btn-sm float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <?php
                        if (isset($_GET['id'])) {
                            $person_id = mysqli_real_escape_string($conn, $_GET['id']);
                            $query = "SELECT * FROM person WHERE id='$person_id' ";
                            $query_run = mysqli_query($conn, $query);

                            if (mysqli_num_rows($query_run) > 0) {
                                $person = mysqli_fetch_array($query_run);
                        ?>
                                <form action="service.php" method="POST">
                                    <input type="hidden" name="person_id" value="<?= $person['id']; ?>">

                                    <div class="mb-3">
                                        <label>Name</label>
                                        <input type="text" name="name" value="<?= $person['name']; ?>" class="form-control form-control-sm">
                                    </div>
                                    <div class="mb-3">
                                        <label>Surname</label>
                                        <input type="text" name="surname" value="<?= $person['surname']; ?>" class="form-control form-control-sm">
                                    </div>
                                    <div class="mb-3">
                                        <label>Place of birth</label>
                                        <input type="text" name="birth_place" value="<?= $person['birth_place']; ?>" class="form-control">
                                    </div>
                                    <!-- <div class="mb-3">
                                        <label>Place year</label>
                                        <input type="number" name="place_year" value="<?= $person['birth_place']; ?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Place type</label>
                                        <input type="text" name="place_type" value="<?= $person['birth_place']; ?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Position discipline</label>
                                        <input type="text" name="position_discipline" value="<?= $person['birth_place']; ?>" class="form-control">
                                    </div> -->

                                    <div class="mb-3">
                                        <button type="submit" name="person_update" class="btn btn-primary btn-sm">
                                            Update Person
                                        </button>
                                    </div>

                                </form>
                        <?php
                            } else {
                                echo "<h4>No Such Id Found</h4>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>