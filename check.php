<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tennis Training</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>GLENDIS TENNIS</h1>
    <div id="checkDiv">
    <p id="check"> Availability of coaches you selected, on <?php echo $_GET["coachDate"] . ":" ?></p>

    <form  action="book.php" method="get">    

    <!-- store values in case user doesn't select a time slot -->
    <?php 

        $coachDate = $_GET["coachDate"];
        $player = $_GET['player'];
        $pid = $_GET['playerid'];
        echo "<input type='hidden' name='player' value='".$player."'>";
        echo "<input type='hidden' name='playerid' value='".$pid."'>";
        echo "<input type='hidden' name='coachDate' value='".$coachDate."'>";

    ?>

    <table>
        
        <tr>
            <th></th>
            <th>10:00</th>
            <th>12:00</th>
            <th>16:00</th>
        </tr>

    <?php

    try{
        $coachDate = $_GET["coachDate"];
        $coachNames = $_GET['coachName'];
        $player = $_GET['player'];
        $pid = $_GET['playerid'];

        // check if no coaches are selected
        if(empty($_GET['coachName'])){
            echo "<script>alert('Please select a coach'); window.location = 'coach.php?status=noCoachSelected&player=$player&playerid=$pid';</script>";
            exit();
        }

        $connect = connect();

        // loop through the array of coach names to echo in separate rows with radio buttons for time slots
        foreach($coachNames as $coachName){

            $sql = "SELECT name FROM Coach WHERE name=:c;";
            $handle = $connect->prepare($sql);
            $handle->execute(array(":c" => "$coachName"));
            $res = $handle->fetchAll();

            foreach($res as $row){	

                if (!empty($coachNames)){
                    echo "<tr>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . "<input type='radio' name='time' value='10:00:00;". $coachName ."'>" . "</td>";
                    echo "<td>" . "<input type='radio' name='time' value='12:00:00;". $coachName ."'>" . "</td>";
                    echo "<td>" . "<input type='radio' name='time' value='16:00:00;". $coachName ."'>" . "</td>";
                }   
            }
        }

        $connect = null;

        $connect = connect(); 
        
        // fetch the entries from Training table where the player has a training already and disable the radio button associated with the details

        $sql = "SELECT atTime, onDate FROM Training WHERE playerId='$pid' AND onDate='$coachDate'";
        $handle = $connect->prepare($sql);
        $handle->execute();
        $result = $handle->fetchAll();
        $result_str = json_encode($result);

        if($result){
            ?>
            <script>
                var playerTime = <?php echo $result_str?>;
                var radioButtons = document.querySelectorAll("input[type=radio]");
                playerTime.forEach(time => {
                    radioButtons.forEach(radio => {
                        if(radio.value.includes(time.atTime)){
                            radio.disabled=true;
                        }
                    })
                })
                </script>
            <?php
        }

        // fetch all the entries from the Training table and disable the radio buttons associated with the coach name, chosen date and timing

        $sql = "SELECT Coach.name, Training.onDate, Training.atTime 
        FROM Coach JOIN Training 
        ON Coach.coachId = Training.coachId AND Training.onDate = '$coachDate' ORDER BY Coach.name ASC;";

        $handle = $connect->prepare($sql);
        $handle->execute();
        $res = $handle->fetchAll();  
        $res_str = json_encode($res);

        if($res){
            ?>
            <script>
                var coachTime = <?php echo $res_str ?>;
                var radioButtons = document.querySelectorAll("input[type=radio]");
                coachTime.forEach(time => {
                    radioButtons.forEach(radio => {
                        if(radio.value.includes(time.atTime) && radio.value.includes(time.name)){
                            radio.disabled=true;
                        }
                    })
                })
                </script>
            <?php
        }

        } catch (PDOException $e) {
            echo "PDOException: ".$e->getMessage();
        }

        $connect = null;

    ?>

    

    </table>
    <!-- create two buttons that direct to different php files when clicked -->
    <button type="submit" name="selection" value="Change Selection" class="submit" formaction="coach.php">Change Selection</button>
    <button type="submit" name="booking" value="Book training" class="submit" formaction="book.php">Book Training</button>

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