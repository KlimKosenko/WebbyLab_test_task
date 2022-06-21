<?php
    require_once("session_helper.php");
    require_once("dbFunctions.php");

    $title = trim($_POST["title"]);
    $release_year = trim($_POST["realese_year"]);
    $format = trim($_POST["format"]);
    $actors = array_filter(explode(",",$_POST["actors"]));

    //Перевірка значень
    if(empty($title) || empty($release_year) || empty($format) || empty($actors)){
        $_SESSION["addFilm"]["insert"] = "Заповніть всі поля";
        redirect("main.php");
    }
    if($release_year<1895 || $release_year>2022){
        $_SESSION["addFilm"]["insert"] = "Введіть коректну дату фільму(>1895,<2023)";
        redirect("main.php");
    }
    if(!preg_match("/^[а-я А-Я a-z A-Z,-іІїЇєЄ]+$/u",$_POST["actors"])){
        $_SESSION["addFilm"]["insert"] = "Некоректні символи в полі актор";
        redirect("main.php");
    }
    if($format!="VHS" && $format!="DVD" && $format!="Blu-Ray"){
        $_SESSION["addFilm"]["insert"] = "Невірний формат(VHS,DVD,Blu-Ray)";
        redirect("main.php");
    }
    if(similarActors($_POST["actors"])){
        $_SESSION["addFilm"]["insert"] = "В списке акторів є повторення";
        redirect("main.php");
    }
    if(checkSimilarFilms($title, $release_year, $format, $_POST["actors"])){
        $_SESSION["addFilm"]["insert"] = "Вже є такий фільм";
        redirect("main.php");
    }


    //після всіх перевірок
    if(addFilm(htmlspecialchars($title), $release_year, $format, $actors,$message = "insert")){
        $_SESSION["addFilm"]["insert"] = "Фільм успішно доданий";
        redirect("main.php");
    }
    else{
        redirect("main.php");
    }

?>