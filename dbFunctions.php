<?php
    session_start();
    require_once("migration/dbConfig.php");

    function registration($login, $pass){
        global $db;
        $sql = "INSERT INTO users(`login`,pass) Values(:login, :pass)";
        try{
            $statement = $db->prepare($sql);
            $statement->bindValue(":login", $login); 
            $statement->bindValue(":pass", $pass);
            $statement->execute();
            if($statement){
                return true;
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            $_SESSION["errorMsg"]["registration"] = "Логін або пароль вже зайнятий";
        }
    }
    function login($login, $pass){
        global $db;
        try{
            $sql = "SELECT * FROM users Where login = :login and pass= :pass";
            $statement = $db->prepare($sql);
            $statement->bindValue(":login", $login); 
            $statement->bindValue(":pass", $pass);
            $statement->execute();
            $count = $statement->rowCount();
            if($count>0){
                return true;
            }
            else{
                $_SESSION["errorMsg"]["login"] = "Користувач не знайдений";
                return false;
            }
        }
        catch (PDOException $e) {
            $_SESSION["errorMsg"]["login"] = "Database error: " . $e->getMessage();
        }
    }

    function CheckData($login,$pass){
        if(empty($login) || empty($pass)){
            $_SESSION["errorMsg"]["registration"] = "Заповніть всі поля";
            return false;
        }
        if(!preg_match("/^[a-zA-Z0-9]*$/", $login)){
            $_SESSION["errorMsg"]["registration"] = "В логіні можна використовувати тільки букви і цифри";
            return false;
        }
        if(iconv_strlen($pass)<6){
            $_SESSION["errorMsg"]["registration"] = "Пароль має бути більше 6 символів";
            return false;
        }
        return true;
    }

    function AddFilm($title, $release_year, $format, $actors, $message="file"){
        global $db;
        $sql = "INSERT INTO films(title,release_year,`format`) Values(:title, :release_year, :format)";
        try{
            $statement = $db->prepare($sql);
            $statement->bindValue(":title", $title); 
            $statement->bindValue(":release_year", $release_year);
            $statement->bindValue(":format", $format);
            $statement->execute();
            if($statement){
                $film_id = $db->lastInsertId();
                foreach($actors as $actor){
                    $actor = explode(" ",trim($actor));
                    $actor_name = trim($actor[0]);
                    $actor_lastname = trim($actor[1]);
                    $sql = "INSERT INTO actors(film_id,actor_name, actor_lastname) Values(:film_id, :actor_name, :actor_lastname)";
                    try{
                        $statement = $db->prepare($sql);
                        $statement->bindValue(":film_id", $film_id); 
                        $statement->bindValue(":actor_name", $actor_name);
                        $statement->bindValue(":actor_lastname", $actor_lastname);
                        $statement->execute();
                        if(!$statement){
                            return false;
                        }
                    }
                    catch (PDOException $e) {
                        $_SESSION["addFilm"][$message] = $e->getMessage(); //"Виникла помилка під час додавання даних, перевірьте корректність введених даних";
                        return false;
                    }
                }
                //$_SESSION["addFilm"][$message] = "Фільм успішно доданий";
                return true;
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            $_SESSION["addFilm"][$message] = $e->getMessage(); //"Виникла помилка під час додавання даних, перевірьте корректність введених даних";
            return false;
        }
    }

    function getAllFilms($start,$per_page){
        global $db;
        $sql = "SELECT films.film_id, films.title,films.release_year,films.format, GROUP_CONCAT(Concat(actors.actor_name,' ',actors.actor_lastname)) as actors FROM films,actors WHERE films.film_id = actors.film_id  GROUP BY film_id LIMIT $start, $per_page;";
        $result = $db->query($sql);
        $films = $result->fetchAll();
        return $films;
        
    }
    function get_count($table){
        global $db;
        $res = $db->query("SELECT COUNT(*) FROM {$table}");
        return $res->fetchColumn();
    }
    function getAllFilmsOrderBy($start,$per_page){
        global $db;
        $sql = "SELECT films.film_id, films.title,films.release_year,films.format, GROUP_CONCAT(Concat(actors.actor_name,' ',actors.actor_lastname)) as actors FROM films,actors WHERE films.film_id = actors.film_id  GROUP BY film_id ORDER By films.title COLLATE  utf8mb4_unicode_ci LIMIT $start, $per_page;";
        $result = $db->query($sql);
        $films = $result->fetchAll();
        return $films;
        
    }
    function delFilm($id){
        global $db;
        $sql = "DELETE FROM actors WHERE film_id = $id; DELETE FROM films WHERE film_id = $id;";
        $affectedRowsNumber = $db->exec($sql);
        if($affectedRowsNumber>0){
            return true;
        }
        else{
            return false;
        }
    }
    function get_count_title($title){
        global $db;
        $res = $db->query("SELECT COUNT(*) FROM films Where title LIKE '%$title%'");
        return $res->fetchColumn();
    }

    function getFilmByTitle($start,$per_page,$title){
        global $db;
        $sql = "SELECT films.film_id, films.title,films.release_year,films.format, GROUP_CONCAT(Concat(actors.actor_name,' ',actors.actor_lastname)) as actors FROM films,actors WHERE films.film_id = actors.film_id and films.title LIKE '%$title%' GROUP BY film_id LIMIT $start, $per_page;";
        $result = $db->query($sql);
        $films = $result->fetchAll();
        return $films;
    }

    function get_count_actors($name){
        global $db;
        $res = $db->query("SELECT Count(film_id) from actors WHERE actor_name LIKE '%$name%'");
        return $res->fetchColumn();
    }
    function getFilmByName($start,$per_page,$name){
        global $db;
        $sql = "SELECT film_id from actors WHERE actor_name LIKE '%$name%' GROUP BY film_id LIMIT $start, $per_page;";
        $result = $db->prepare($sql);
        $result->execute();
        $id_films = $result->fetchALL(PDO::FETCH_ASSOC);
        $films = array();
        foreach($id_films as $id_film){
            $sql = "SELECT films.film_id, films.title,films.release_year,films.format, GROUP_CONCAT(Concat(actors.actor_name,' ',actors.actor_lastname)) as actors FROM films,actors WHERE films.film_id = ".$id_film["film_id"]." and films.film_id = actors.film_id  GROUP BY film_id;";
            $result = $db->prepare($sql);
            $result->execute();
            array_push($films, $result->fetch(PDO::FETCH_ASSOC));
        }
        return $films;
    }

    function checkSimilarFilms($title, $release_year, $format, $actors){
        global $db;
        try{
            $sql = "SELECT film_id FROM films WHERE title = '$title' and `format` = '$format' and release_year = $release_year;";
            $result = $db->prepare($sql);
            $result->execute();
            $id = $result->fetchALL(PDO::FETCH_ASSOC);
            if(isset($id[0]["film_id"])){
                foreach($id as $i)
                {
                    $film_id = $i['film_id'];
                    $sql = "SELECT GROUP_CONCAT(CONCAT(actor_name,' ',actor_lastname)) as actorsName FROM actors WHERE film_id = $film_id;";
                    $result = $db->prepare($sql);
                    $result->execute();
                    $actors_array = $result->fetch(PDO::FETCH_ASSOC);
                    $_SESSION["addFilm"]["insert"] = "$actors and ".$actors_array['actorsName'];
                    if(strcasecmp($actors,$actors_array['actorsName'])==0){
                        return true;
                    }
                }  
                return false;
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            $_SESSION["addFilm"]["insert"] = "Database error: " . $e->getMessage();
        }
    }
    function similarActors($actors){
        $actors = explode(",",str_replace(", ",",",$actors));
        $actors_unique = array_unique($actors);
        if($actors==$actors_unique){
            return false;
        }
        return true;
    }