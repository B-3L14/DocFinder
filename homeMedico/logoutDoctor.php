<?php
session_start();
session_destroy();
header("Location: ../loginMedico/doctorLogin.html");
exit();
?>
