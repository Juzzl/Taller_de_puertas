<?php
session_start();
 // Este metodo se va a usar para eliminar todas las variables de sesión
session_unset();
session_destroy();

header("Location: ../login.html");
exit();
?>
