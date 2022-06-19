<?php
    require_once("session_helper.php");
    require_once("dbFunctions.php");

    function uploadFile($file){
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        if(strcmp($extension,"txt") !==0){
            $_SESSION["addFilm"]["file"] = "Завантажувати можна файли тільки з розширенням txt";
            redirect("main.php");
        }
        $filename = uniqid().".".$extension;
        move_uploaded_file($file['tmp_name'], "upload/".$filename);
        return "upload/".$filename;
    }

    $filename = uploadFile($_FILES['fileWithFilms']);
    $lines = file_exists($filename) ? file($filename, FILE_IGNORE_NEW_LINES) : [];
    if(empty($lines)){
        $_SESSION["addFilm"]["file"] = "Файл пустий";
        redirect("main.php");
    }
    while (empty(end($lines))) {
        unset($lines[key($lines)]);
    }

    $film = array();
    foreach($lines as $field){
        if(empty($field)){
            $title = trim($film[0]);
            $release_year = trim($film[1]);
            $format = trim($film[2]);
            $actors = array_filter(explode(",",$film[3]));
            if(!addFilm($title, $release_year, $format, $actors)){
                $_SESSION["addFilm"]["file"] = "Під час додавання фільму виникла помилка";
                redirect("main.php");
            }
            $film = array();
            continue;
        }
        array_push($film, str_replace(["Title: ","Release Year: ","Format: ","Stars: "],"",$field));  
    }
    $_SESSION["addFilm"]["file"] = "Фільми з файлу успішно додані";
    redirect("main.php");
?>