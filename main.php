<?php
    require_once("session_helper.php");
    if(isset($_SESSION["userLogin"])){
        echo "Welcome, ".$_SESSION["userLogin"]."<br>";
    }
    else{
        redirect("index.php");
    }
    if(isset($_GET['logout'])){
        logout();
    }
    require_once("components/header.php")
?>
<a href="main.php?logout=true">Exit</a>
<br><hr>
<label>Додати фільм</label>
<form action="addFilm.php" method="post">
    <label for="title">Назва</label>
    <input type="text" name="title">
    <label for="realese_year">Рік випуску</label>
    <input type="number" name="realese_year">
    <label for="format">Формат</label>
    <select name="format">
        <option value = "VHS">VHS</option>
        <option value = "DVD">DVD</option>
        <option value = "Blu-Ray">Blu-Ray</option>
    </select>
    <label for="actors">Актори</label>
    <input type="text" name="actors">
    <button type="submit">Додати фільм</button>
</form>
<?php if(isset($_SESSION["addFilm"]["insert"])){ 
            echo $_SESSION["addFilm"]["insert"];
        } 
?>
<br><hr>
<label>Додати фільми з файлу</label>
<form action="addFilmFromFile.php" method="post" enctype="multipart/form-data">
    <br><input type="file" name="fileWithFilms" accept="text/plain"><br>
    <br>
    <button type="submit">Завантажити фільми з файлу</button>
</form>
<?php if(isset($_SESSION["addFilm"]["file"])){ 
            echo $_SESSION["addFilm"]["file"];
        } 
?>
<hr>
<label>Вивести всі фільми</label>
<form action="viewFilms.php">
    <button type="submit">Переглянути всі фільми</button>
</form>
<hr>
<label>Вивести всі у алфавітному порядку</label>
<form action="viewFilmsOrderBy.php">
    <button type="submit">Виконати</button>
</form>
<hr>
<label>Знайти фільм за назвою</label>
<form action="viewFilmByTitle.php" method="GET">
    <label for="title">Назва</label>
    <input type="text" name="title">
    <button type="submit">Виконати</button>
</form>
<?php if(isset($_SESSION["findFilm"]["title"])){ 
            echo $_SESSION["findFilm"]["title"];
        } 
?>
<hr>
<label>Знайти фільм за ім'ям актора</label>
<form action="viewFilmByName.php" method="GET">
    <label for="name">Ім'я</label>
    <input type="text" name="name">
    <button type="submit">Виконати</button>
</form>
<hr>
<?php require_once("components/footer.php")?>

