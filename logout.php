<?php

session_start();

// clearing the session
session_unset();

// destory the session.
session_destroy();

// redirect to home page
header("location: index.php");
exit;
