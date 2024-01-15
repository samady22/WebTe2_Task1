<?php
include 'config.php';
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}
// here I'm retreving all details of a person under his id 
$person_id = mysqli_real_escape_string($conn, $_GET["id"]);
$query = "SELECT *
 FROM person, position 
 WHERE person.id='$person_id' 
 AND position.person_id = '$person_id'  ";
$result = mysqli_query($conn, $query);

$person_detail = [];
if ($result->num_rows > 0) {
    // assigning the fetched data to an array
    $person_detail = $result->fetch_all(MYSQLI_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Person details</title>
</head>

<body>
    <div class="container mt-4">
        <div class="card">
            <h3 class="card-header">Person Details <a role="button" class="btn btn-danger btn-sm float-end" href="index.php">Back</a></h3>
            <div class="card-body">
                <?php

                // In this buttom query I get top ten player form person table using join with positon table
                $query2 = "SELECT distinct person.name, person.id as p_id, count(position.placing) as count_gold FROM (person inner join position on person.id=position.person_id) where position.placing=1 group by person.id limit 10";
                $result2 = mysqli_query($conn, $query2);
                $person_detail2 = [];
                if ($result2->num_rows > 0) {
                    $person_detail2 = $result2->fetch_all(MYSQLI_ASSOC);
                }

                ?>
                <?php if (!empty($person_detail2)) { ?>
                    <form style="display:flex" action="person_detail.php" method="get">
                        <select name="id" class="form-select" aria-label="Default select example">
                            <!-- here is our drop donw list for top then player we fetched data for it in above lines -->
                            <option value="" disabled selected>Select a top 10 player</option>;
                            <?php foreach ($person_detail2 as $person) { ?>
                                <option value=<?php echo $person['p_id'] ?> name=<?php echo  $person['p_name'] ?>><?php echo $person['name'] . ' ' . $person['count_gold'] . ' Gold Medal'; ?></option>

                            <?php } ?>

                        </select>
                        <input class="btn btn-primary btn-sm" type="submit" name="Hey">
                    </form>
                <?php } ?>

                <!-- In bottom lines I'm rendering the user data which I have reterived from database -->
                <?php if (!empty($person_detail)) { ?>
                    <?php foreach ($person_detail as $key => $person) { ?>
                        <?php if ($key == 0) { ?>

                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><?php echo 'Name: ' . $person['name']; ?></li>
                                <li class="list-group-item"><?php echo 'Surname: ' . $person['surname']; ?></li>
                                <li class="list-group-item"><?php echo 'Date of birth: ' . $person['birth_day']; ?></li>
                                <li class="list-group-item"><?php echo 'Date of death: ' . $person['death_day']; ?></li>
                                <li class="list-group-item"><?php echo 'Place of birth: ' . $person['birth_place']; ?></li>
                                <li class="list-group-item"><?php echo 'Country of birth: ' . $person['birth_country']; ?></li>
                            <?php } ?>
                            <li class="list-group-item"><?php echo 'Position: ' . $person['placing'] . ' ' . $person['discipline']; ?></li>
                            </ul>

                        <?php } ?>
                    <?php } ?>
            </div>


        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>