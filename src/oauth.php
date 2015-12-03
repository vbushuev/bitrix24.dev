<?php
ob_start();
print_r($_REQUEST);
file_put_contents("log.log",ob_get_clean());
?>
