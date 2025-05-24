<?php
session_start();
session_unset(); // e pastron te gjitha variablat e sesionit
session_destroy(); // e shkaterron sesionin
header("Location: index.php"); // ridrejton në home pas logout
exit();
