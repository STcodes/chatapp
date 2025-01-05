<?php
    require "database.php";

    $user_id = $user_name = $con_user_password = $con_user_name = $bu_user_name = $bu_user_password = $message = "";
    $i = $j = 0;

    if(!empty($_POST['con_user_name'])){
        $con_user_name = checkInput($_POST['con_user_name']);
        $con_user_password = checkInput($_POST['con_user_password']);

        $db = Database::connect();
        $statement = $db->prepare('SELECT user.id , user.name FROM user WHERE user.name = ? AND user.password = ?');
        $statement->execute(array($con_user_name,$con_user_password));
        while($user = $statement->fetch()){
            $i ++;
            $user_id = $user['id'];
            $user_name = $user['name'];
        }

        if($i == 1){
            header("Location: index.php?user_id=$user_id&user_name=$user_name");
        }else{        
            $message = "Nom ou mot de passe incorrect";
        }
        Database::disconnect();
    }

    if(!empty($_POST['bu_user_name'])){
        $bu_user_name = checkInput($_POST['bu_user_name']);
        $bu_user_password = checkInput($_POST['bu_user_password']);
        
        $db = Database::connect();
        $statement = $db->prepare('SELECT user.name FROM user WHERE user.name = ?');
        $statement->execute(array($bu_user_name));
        while($a = $statement->fetch()){
            $j++;
        }
        if($j >= 1){
            $message = "Ce nom est deja utilisé";
        }else{  
            $statement = $db->prepare('INSERT INTO user(name,password) VALUES(?,?)');
            $statement->execute(array($bu_user_name,$bu_user_password));
            
            $statement = $db->prepare('SELECT user.id FROM user WHERE user.name = ? AND user.password = ?');
            $statement->execute(array($bu_user_name,$bu_user_password));
            $b = $statement->fetch();
            $bu_user_id = $b['id'];
            header("Location: index.php?user_id=$bu_user_id&user_name=$bu_user_name");
        }
        Database::disconnect();
    }

    function checkInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>What's Up Polytech</title>
    <link rel="shortcut icon" href="images/sitio.png">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <h1>What's Up Polytech</h1>
    <form action="connect.php" method="POST" class="connect-form active" id="container1">
        <h2>Se connecter</h2> 
        <input type="text" placeholder="Votre nom..." class="connect-input" name="con_user_name" required><br>
        <input type="password" placeholder="Votre mot de passe..." class="connect-input" name="con_user_password" required><br>

        <p style="color: red;"><?php echo $message; ?></p>

        <button type="submit">Valider</button>

        <p> Vous n'avez pas de compte ? <a class="submit" id="con">Creez un compte</a></p>
    </form>

    <form action="connect.php" method="POST" class="connect-form" id="container2">
        <h2>Creer votre compte</h2>
        <input type="text" placeholder="Votre nom..." class="connect-input" name="bu_user_name" required><br>
        <input type="password" placeholder="Votre mot de passe..." class="connect-input" name="bu_user_password" required><br>

        <p style="color: red;"><?php echo $message; ?></p>

        <button type="submit">Enregistrer</button>

        <p> Vous avez deja un compte ? <a class="submit" id="build">Connectez-vous</a></p>
    </form>

    <script type="text/javascript">
        let bu = document.getElementById('build');
        let con = document.getElementById('con')
        let container1 = document.getElementById('container1');
        let container2 = document.getElementById('container2');

        bu.addEventListener("click", ()=>{
            container1.classList.add('active');
            container2.classList.remove('active');
        });

        con.addEventListener("click", ()=>{
            container1.classList.remove('active');
            container2.classList.add('active');
        });


    </script>
</body>
</html>