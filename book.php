<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tennis Training</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>GLENDIS TENNIS</h1>    

    <div id="bookDiv">
    <form method="get">
    <?php
	
    try{
        
        $coachDate = $_GET["coachDate"];
        $coachTime = $_GET['time'];
        $player = $_GET['player'];
        $pid = $_GET['playerid'];

        $str = $coachTime;
        list($coachTime, $coachName) = explode(';', $str);
			
        // check if no time slot is selected
        if(empty($_GET['time'])){
            echo "<script>alert('Please select a time slot'); window.location = 'coach.php?status=timeNotSelected&player=$player&playerid=$pid&coachDate=$coachDate&time=$coachTime';</script>";
            exit();
        }

        // fetch the coach ID via coach name to prepare for INSERT 
        $connect = connect();
        $coachID = "SELECT coachId FROM Coach WHERE name='$coachName'";
            
        $handle = $connect->prepare($coachID);
        $handle->execute();
        $result = $handle->fetch(PDO::FETCH_ASSOC);
        $result_str = json_encode($result);
        list($coach, $coachid) = preg_split('/(:|})/', $result_str);
    
        $connect = null;

        $connect = connect();

        // fetch all the values and insert into sql database 
        $sql = "INSERT INTO Training (playerId, onDate, atTime, coachId) VALUES (:playerId, :onDate, :atTime, $coachid)";

        $handle = $connect->prepare($sql);
        $handle->execute(array(":playerId" => "$pid", ":onDate" => "$coachDate", ":atTime" => "$coachTime"));

        if($handle){
            echo "<p id='bookh1'>Thank you $player. Your booking was successful!</p>";
            echo "<img src='bookingIcon1.png' id='image'>";
            echo "<p id='bookh2'>Summary: Training with $coachName on $coachDate at $coachTime</p>";
        }

        } catch (PDOException $e) {
            echo "PDOException: ".$e->getMessage();
        }

    ?>

   

    <!-- create two buttons that direct user to different php files when clicked -->
    <?php 
            $player = $_GET['player'];
            $pid = $_GET['playerid'];
            echo "<input type='hidden' name='player' value='".$player."'>";
            echo "<input type='hidden' name='playerid' value='".$pid."'>";

            echo "<button type='submit' name='bookAgain' value='Book Again' class='submit' formaction='coach.php?status=bookAgain&player=$player&playerid=$pid'>Book Again</button>";
            echo "<button type='submit' name='exit' value='Exit' class='submit' formaction='restart.php'>Exit</button>";
        
        ?>
    </form>
    </div>

    <?php

    // function to connect to sql database
    function connect() {
                $host = 'dragon.ukc.ac.uk';
                $dbname = 'el345';
                $user = 'el345';
                $pwd = 'phan2yw';
        try {
            $connect = new PDO("mysql:host=$host;dbname=$dbname", $user, $pwd);
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if ($connect) {	
                return $connect; 
            }
                
        } catch (PDOException $e) {
            echo "PDOException: ".$e->getMessage();
        }
    }
    ?>
    
</body>
</html>