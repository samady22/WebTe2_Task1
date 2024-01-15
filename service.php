<?php
session_start();
// database config inclued
include 'config.php';

// when edit button clicked in the table row
if (isset($_POST['person_update'])) {
    // getting data from the form 
    $person_id = mysqli_real_escape_string($conn, $_POST['person_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $surname = mysqli_real_escape_string($conn, $_POST['surname']);
    $birthPlcae = mysqli_real_escape_string($conn, $_POST['birth_place']);
    // $place_type = mysqli_real_escape_string($conn, $_POST['place_type']);
    // $place_year = mysqli_real_escape_string($conn, $_POST['place_year']);
    // $pos_discipline = mysqli_real_escape_string($conn, $_POST['position_discipline']);


    // update query for the database updating person details
    // $query = "UPDATE position pos
    // INNER JOIN person pe
    //   ON pos.person_id = pe.id
    // INNER JOIN place pl
    //   ON pos.place_id = pl.id
    // SET pe.name = '$name'
    //     ,pe.surname= '$surname', pe.birth_place='$birthPlace',
    //      pl.type='$place_type', pos.discipline='$pos_discipline'
    // WHERE pos.id = $pos_id
    //   ";

    $query = "UPDATE person SET name='$name', surname='$surname', birth_place='$birthPlace' WHERE id='$person_id' ";
    // try and catch for better determining errors
    try {
        // run query 
        $query_run = mysqli_query($conn, $query);
        $userId = $_SESSION['user_id'];
        // insert changes which has done by the user save to the database in the history table.
        $query2 = "INSERT INTO history (user_id, action) VALUES ('$userId', 'updated a player data under id ($person_id) data')";
        $query_run2 = mysqli_query($conn, $query2);
        $_SESSION['msg'] = "Person Updated Successfully";
        // redirect
        header("Location: index.php");
    } catch (Exception $e) {
        // error message.
        echo $e->getMessage();
        $_SESSION['msg'] = "Person Not Updated";
        header("Location: index.php");
        exit(0);
    }
}

// when save button clicked in add player page
if (isset($_POST['save_player'])) {
    // getting data from the form
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $surname = mysqli_real_escape_string($conn, $_POST['surname']);
    $birthPlace = mysqli_real_escape_string($conn, $_POST['place']);
    $birthDate = date('Y-m-d', strtotime($_POST['birth_date']));
    $birthCountry = mysqli_real_escape_string($conn, $_POST['country']);
    // insert person query for the database

    try {
        $query2 = "SELECT * FROM person where person.name='$name' && person.surname='$surname'";
        $result2 = mysqli_query($conn, $query2);
        // $data = mysqli_fetch_assoc($result2);
        if (mysqli_num_rows($result2) > 0) {
            $_SESSION['msg'] = "Person already exist";
            header("Location: add.php");
        } else {
            $query = "INSERT INTO person (name, surname, birth_day, birth_place, birth_country) VALUES ('$name', '$surname', '$birthDate','$birthPlace','$birthCountry')";
            // run query
            $query_run = mysqli_query($conn, $query);
            $last_id = $conn->insert_id;
            $_SESSION['added_person_id'] = $last_id;
            $_SESSION['msg'] = "Person successfully added";
            $userId = $_SESSION['user_id'];
            // insert changes to the history in the database 
            $query2 = "INSERT INTO history (user_id, action) VALUES ('$userId', 'added a new peron under ID ($last_id)')";
            $query_run2 = mysqli_query($conn, $query2);
            // redirect to the add page.
            header("Location: add_place.php");
        }
    } catch (Exception $e) {
        // error message
        $_SESSION['msg'] = "Person not added";
        echo 'Error: ' . $e->getMessage();
    }
}

// when save button clicked in add player page
if (isset($_POST['save_player_place'])) {
    // getting data from the form
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $game_order = mysqli_real_escape_string($conn, $_POST['game_order']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    // insert person query for the database

    try {

        $query = "INSERT INTO place (city, country, game_order, year, type) VALUES ('$city', '$country', '$game_order','$year','$type')";
        // run query
        $query_run = mysqli_query($conn, $query);
        $last_id = $conn->insert_id;
        $_SESSION['added_place_id'] = $last_id;
        $_SESSION['msg'] = "Player's place successfully added";
        $userId = $_SESSION['user_id'];
        // insert changes to the history in the database 
        $query2 = "INSERT INTO history (user_id, action) VALUES ('$userId', 'added a new person place under place ID ($last_id)')";
        $query_run2 = mysqli_query($conn, $query2);
        // redirect to the add page.
        header("Location: add_position.php");
    } catch (Exception $e) {
        // error message
        $_SESSION['msg'] = "Person's place not added";
        echo 'Error: ' . $e->getMessage();
    }
}


// when save button clicked in add player page
if (isset($_POST['save_player_position'])) {
    // getting data from the form
    $placing = mysqli_real_escape_string($conn, $_POST['placing']);
    $discipline = mysqli_real_escape_string($conn, $_POST['discipline']);
    // insert person query for the database

    try {
        $added_person_id = $_SESSION['added_person_id'];
        $added_place_id = $_SESSION['added_place_id'];
        $query = "INSERT INTO position (person_id, place_id, placing, discipline) VALUES ('$added_person_id', '$added_place_id', '$placing',' $discipline')";
        // run query
        $query_run = mysqli_query($conn, $query);
        $last_id = $conn->insert_id;
        $_SESSION['msg'] = "Person's position successfully added";
        $userId = $_SESSION['user_id'];
        // insert changes to the history in the database 
        $query2 = "INSERT INTO history (user_id, action) VALUES ('$userId', 'added the new person position uder position ID ($last_id)')";
        $query_run2 = mysqli_query($conn, $query2);
        // redirect to the add page.
        header("Location: add.php");
    } catch (Exception $e) {
        // error message
        $_SESSION['msg'] = "Person not added";
        echo 'Error: ' . $e->getMessage();
    }
}


//when button delete clicked from table row
if (isset($_POST["delete"])) {
    // get the positon id
    $position_id = mysqli_real_escape_string($conn, $_POST["delete"]);
    //delete positon with the under the given id
    $query = "DELETE FROM position WHERE id='$position_id' ";
    try {
        // run query
        $result = mysqli_query($conn, $query);
        $userId = $_SESSION['user_id']; // get user id from session 
        // inseret the changes to history in database
        $query2 = "INSERT INTO history (user_id, action) VALUES ('$userId', 'deleted a position under ID ($position_id)')";
        $query_run2 = mysqli_query($conn, $query2);
        // put msg in to session for alert
        $_SESSION["msg"] = "Successfully deleted";
        header("Location: index.php");
        exit(0);
    } catch (Exception $e) {
        // print error 
        echo $e->getMessage();
        $_SESSION["msg"] = "Not deleted!";
        header("Location: index.php");
        exit(0);
    }
}
