<?php
    require_once("session_helper.php");
    require_once("dbFunctions.php");
    require_once("components/header.php");
    require_once("Pagination.php");
?>
<script>
function del_Film(id,url){
	if(confirm("Ви впевнені що хочете видалити фільм?")){
		document.location.href = 'delFilm.php?web_page='+url+'&id='+id;
	}
}
</script>
<?php
    $url = $_SERVER['REQUEST_URI'];
    $page = $_GET['page']??1;
    $per_page = 5;
    $total = get_count('films');
    $pagination = new Pagination($page, $per_page, $total);
    $start = $pagination->get_start();
    $films = getAllFilmsOrderBy($start,$per_page);

    if(!empty($films)){
        echo "<table><tr><th>Id</th><th>Title</th><th>format</th><th>Release year</th><th>Actors</th><th>Видалити фільм</th></tr>";
        foreach($films as $film){
            echo "<tr>";
                echo "<td>" . $film["film_id"] . "</td>";
                echo "<td>" . $film["title"] . "</td>";
                echo "<td>" . $film["format"] . "</td>";
                echo "<td>" . $film["release_year"] . "</td>";
                echo "<td>" . $film["actors"] . "</td>";
                echo "<td><a href='javascript:del_Film(".$film["film_id"].",\"".$url."\")'>Видалити</a></td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<br>";
        if($total>$per_page){
            echo $pagination->get_html();
        }
    }
    else{
        echo "Фільми не знайдено";
    }
    echo "<br>";
    echo "<a href='main.php'>Повернутися</a>";
    require_once("components/footer.php");
?>