<?php

// select basic site info

$Select_basic_site = $pdo->prepare("SELECT * FROM basic_site WHERE site_id = 1");
$Select_basic_site->execute();
$basic_site = $Select_basic_site->fetch();




?>