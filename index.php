<?php

session_start();
include 'config.php';
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
}

try {
    $query = "SELECT person.id as personId, person.name, person.surname, place.year, place.city, place.type,
     position.discipline, position.placing, position.id as posId
    FROM ((person
    INNER JOIN position ON person.id = position.person_id)
    INNER JOIN place ON position.place_id = place.id)
    ORDER BY position.placing ASC"
        or die("Error in the consult.." . mysqli_error($conn));
    $result = mysqli_query($conn, $query);

    $arr_users = [];
    if ($result->num_rows > 0) {
        $arr_users = $result->fetch_all(MYSQLI_ASSOC);
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css" />
    <title>OH</title>
</head>

<body>
    <?php include 'header.php' ?>
    <div class="container">
        <?php include 'alert.php'; ?>
        <div class="table-responsive">
            <button onclick="window.location='add.php'" class="btn m-1 btn-success btn-block float-end">Add Player</button>
            <table id="userDataList" class="table table-hover table-bordered" style="width:100%">
                <thead style="background-color:silver">
                    <th>#ID</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Year</th>
                    <th>Type</th>
                    <th>City</th>
                    <th>Discipline</th>
                    <th>Action</th>
                </thead>
                <tbody>

                    <?php if (!empty($arr_users)) { ?>
                        <?php foreach ($arr_users as $user) { ?>
                            <tr>
                                <td><?php echo $user['posId']; ?></td>
                                <td class="clickable" style="cursor: pointer;" onclick="window.location='person_detail.php?id=<?= $user['personId'] ?>' "><?php echo $user['name']; ?></td>
                                <td><?php echo $user['surname']; ?></td>
                                <td><?php echo $user['year']; ?></td>
                                <td><?php echo $user['type']; ?></td>
                                <td><?php echo $user['city']; ?></td>
                                <td><?php echo $user['discipline']; ?></td>
                                <td><a href="edit.php?id=<?= $user["personId"] ?>" class="btn btn-outline-primary btn-sm">Edit</a>
                                    <form action="service.php" method="POST" class="d-inline">
                                        <button name="delete" type="submit" id="del" value=<?= $user["posId"]; ?> class="btn btn-outline-danger btn-sm">Del</button>
                                    </form>
                                </td>

                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include 'footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
    <script>
        jQuery(document).ready(function($) {
            $('#userDataList').DataTable({
                responsive: true
            });

        });
    </script>
</body>

</html>