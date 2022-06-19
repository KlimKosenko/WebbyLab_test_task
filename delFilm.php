<?php
    require_once("session_helper.php");
    require_once("dbFunctions.php");

    if(isset($_GET["web_page"]) && isset($_GET["id"])){
        if(delFilm($_GET["id"])){
            redirect($_GET["web_page"]);
        }
        else{
            echo "Виникла помилка";
        }
    }