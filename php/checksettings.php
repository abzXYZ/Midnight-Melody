<?php
$settingsfound = isset($_COOKIE['client_settings']);
$settings = [];
if ($settingsfound) {
    $cookie = $_COOKIE['client_settings'];
    $settings = explode(',', $cookie);
} else {
    $settings = ["autoplay_tracks"];
}

?>