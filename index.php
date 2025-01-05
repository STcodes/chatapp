<?php
    require "database.php";
    $user_id = $user_name = $contain = "";

    if(!empty($_GET['user_id'])){
        $user_id = checkInput($_GET['user_id']);
        $user_name = checkInput($_GET['user_name']);
    }else{
        header("Location: connect.php");
    }

    if(!empty($_POST['contenu'])){
        $contain = checkInput($_POST['contenu']);
        $db = Database::connect();
        $statement = $db->prepare('INSERT INTO message(message.expediteur,message.message) VALUES(?,?)');
        $statement->execute(array($user_id,$contain));
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
    <title>Hey what's up !</title>
    <link rel="shortcut icon" href="images/sitio.png">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
</head>
<body>
    <h1>Hey What's Up!</h1>
    <div id="index-container-1">
        <div id="index-container-2" >
            <div id="index-container" >
                <?php
                    $db = Database::connect();
                    $statement = $db->query('SELECT DISTINCT message.date FROM message ORDER BY date DESC');
                    $b = "";
                    while($date = $statement->fetch()){
                        
                        list($annee,$mois,$jour) = sscanf(gmdate('Y-m-d'), "%d-%d-%d");
                        $jour--;
                        $jour = str_pad($jour,2,'0', STR_PAD_LEFT);
                        $mois = str_pad($mois,2,'0', STR_PAD_LEFT);
                        $hier = "".$annee."-".$mois."-".$jour."";
    
                        list($a,$m,$j) = sscanf($date['date'], "%d-%d-%d");
                        $m = date("F", mktime( 0, 0, 0, $m, 10));
                        $journee = "".$j." ".$m." ".$a."";
    
                        
    
                        $statement2 = $db->prepare('SELECT *,user.name FROM message,user WHERE user.id = message.expediteur AND message.date = ? ORDER BY time DESC'); 
                        $statement2->execute(array($date['date']));
                        while($message = $statement2->fetch()){
        
                            if($message['name'] == $user_name){
                                echo '
                                    <div class="index-message message-right">
                                        <div class="message-name">Vous</div>
                                        '.$message['message'].'
                                        <br><div class="message-time">'.substr($message['time'],0,-3).'</div>
                                    </div>
                                '; 
                            }else{
                                echo '
                                    <div class="index-message">
                                        <div class="message-name">'.$message['name'].'</div>
                                        '.$message['message'].'
                                        <br><div class="message-time">'.substr($message['time'],0,-3).'</div>
                                    </div>
                                ';
                            }
                        }
    
                        if($hier == $date['date']){
                            echo '<div class="index-date">Hier</div>';
                        }elseif(gmdate('Y-m-d') == $date['date']){
                            echo "<div class='index-date'> Aujourd'hui </div>";
                        }else{
                            echo '<div class="index-date"> '.$journee.'</div>';
                        }
                    }                   
                    Database::disconnect();
                ?>
            </div>
        </div>
        <form action="index.php?user_id=<?php echo $user_id; ?>&user_name=<?php echo $user_name; ?>" method="POST" id="index-form">
            <input type="text" required placeholder="Message..." autocomplete="off" id="input-message" name="contenu">
            <button type="submit"><i class="fa fa-send"></i></button>
        </form>
    </div>

</body>
    <script language="javascript">        

        setInterval(() => {
            if($("#index-container").scrollTop() < 240){
                $("#index-container-2").load(" #index-container-2");   
            }
        }, 3000);

    </script>
</html>