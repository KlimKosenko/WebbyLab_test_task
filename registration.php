<?php 
    require_once("session_helper.php");
    $_SESSION["errorMsg"]["registration"] = "";
    if(isset($_POST["login"])){
        require_once("dbFunctions.php");
        $login = trim($_POST["login"]);
        $pass = trim($_POST["pass"]);
        if(CheckData($login, $pass)){
            if(registration($login,$pass)){
                $_SESSION['userLogin'] = $login;
                redirect("main.php");
            }
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
            <button type="submit">Sign up</button>
            <br>
            <?php if(isset($_SESSION["errorMsg"]["registration"])){ 
                echo $_SESSION["errorMsg"]["registration"];
            }?>
        </form>
    </div>
    <br>
    <a href="index.php">Sign in</a>

<?php require_once("components/footer.php") ?>