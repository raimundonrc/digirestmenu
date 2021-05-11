<?php 
    require '../digirestmenu/vendor/autoload.php';
    use App\Route;

    date_default_timezone_set('America/Sao_Paulo');

    $route = new Route;
    $route->run();
?>