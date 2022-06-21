<?php
    require_once("session_helper.php");
    require_once("dbFunctions.php");
    require_once("components/header.php");
    require_once("Pagination.php");
?>

<?php
    $url = $_SERVER['REQUEST_URI'];
    $name = $_GET['name'];
    //превірка імені
    //redirect("main")
    //$_SESSION["findFilm"]["title"] = введіть коректну назву фільму
    //$_SESSION["findFilm"]["title"] = ""
    $page = $_GET['page']??1;
    $per_page = 5;
    $total = get_count_actors($name);
    $pagination = new Pagination($page, $per_page, $total);
    $start = $pagination->get_start();
    $films = getFilmByName($start,$per_page,$name);

    if(!empty($films)){
        echo "<table><tr><th>Id</th><th>Title</th><th>format</th><th>Release year</th><th>Actors</th><th>Видалити фільм</th></tr>";
        foreach($films as $film){
            echo "<tr>";
                echo "<td>" . $film["film_id"] . "</td>";
                echo "<td>" . $film["title"] . "</td>";
                echo "<td>" . $film["format"] . "</td>";
                echo "<td>" . $film["release_year"] . "</td>";
                echo "<td>" . $film["actors"] . "</td>";
                echo "<td><button onclick='showModal(". $film["film_id"] .",\"".$url."\")'>Видалити</button></td>";;
            echo "</tr>";
        }
        echo "</table>";
        echo "<br>";
        if($total>=$per_page){
            echo $pagination->get_html();
        }
        ?>
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>Ви дійсно хочете видалити цей фільм</p>
                <button onclick="delFilm()">Так</button>
                <button onclick="hideModal()">Ні</button>
            </div>
        </div>
      <?php
        
    }
    else{
        echo "Фільми не знайдено";
    }
    echo "<br>";
    echo "<a href='main.php'>Повернутися</a>";
    require_once("components/footerWithjs.php");
?>