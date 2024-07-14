<?php
session_start();
foreach ($_SESSION['favtracks'] as $key => $value) {
    echo $value. " ";
}
?>