<?php
require_once("dbConfig.php");




try{
    $db->query('DROP TABLE IF EXISTS users');
    $db->query('DROP TABLE IF EXISTS actors');
    $db->query('DROP TABLE IF EXISTS films');

    //створення таблиці films
    $db->query("CREATE TABLE films(
        film_id int PRIMARY KEY AUTO_INCREMENT,
        release_year int NOT NULL,
        title varchar(250) NOT NULL,
        format varchar(20) NOT NULL
    );");
    //створення таблиці actors
    $db->query("CREATE TABLE actors(film_id int,
        actor_name varchar(250) NOT NULL,
        actor_lastname varchar(250) NOT NULL,
        FOREIGN KEY (film_id) REFERENCES films(film_id)
    );");
    //створення таблиці users
    $db->query("CREATE Table users(
        user_id int PRIMARY KEY AUTO_INCREMENT,
        login varchar(100) NOT NULL,
        pass varchar(100) NOT NULL,
        CONSTRAINT UC_Person UNIQUE (login,pass)
    );");
}
catch(PDOException $e){
    echo "Помилка: ".$e->getMessage();
}
echo "Таблиці успішно створені";