<?php 
    require_once("session_helper.php");
    $errorMsg = "";
    $_SESSION["errorMsg"]["login"] = "";
    if(isset($_POST["login"])){
        require_once("dbFunctions.php");
        $login = $_POST["login"];
        $pass = $_POST["pass"];
        if(login($login,$pass)){
            $_SESSION['userLogin'] = $login;
            redirect("main.php");
        }
    }
?>

<?php require_once("components/header.php") ?>

    <div>
        <form method="post">
            <label for="login">login</label>
            <input type="text" name="login">
            <label for="pass">password</label>
            <input type="password" name="pass">
            <button type="submit">Sign in</button>
        </form>
    </div>
    <?php if(isset($_SESSION["errorMsg"]["login"])){ 
                echo $_SESSION["errorMsg"]["login"];
            } ?>
    <br>
    <a href="registration.php">Sign up</a>
    
<?php require_once("components/footer.php") ?>