<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tennis Training</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>GLENDIS TENNIS</h1>
    <div id="coachDiv">
    <p id="coach">Hi <?php echo $_GET["player"]. "!" . " Please select a date and one or more coaches:"; ?></p><br>

    <?php

        $connect = connect();
        $player = $_GET['player'];
        $pid = $_GET['playerid'];

        $sql = "SELECT * FROM Player WHERE name=:n AND playerId=:pid;";
                
        $handle = $connect->prepare($sql);
        $handle->execute(array(":n" => "$player", "pid" => "$pid"));
		$connect = null;
        $result = $handle->fetchAll();
        

        if(!$result){
            echo "<script>alert('Name and Player Id do not match'); window.location = 'index.php?status=notInDB';</script>";
            exit();
        } 
  
    ?>

    <form action="check.php" method="get">

    <!-- store values in case user doesn't select a coach in check.php -->
    <?php 
            echo "<input type='hidden' name='player' value='".$player."'>";
            echo "<input type='hidden' name='playerid' value='".$pid."'>";
    ?>

    <table>

        <tr>
            <input type="date" name="coachDate" value="2017-02-25" id="date">
        </tr>

        <tr>
            <th>Name</th>
            <th>Location</th>
            <th>Gender</th>
        </tr>

     <?php
	$connect = connect();

    try{
	        $sql = "SELECT Coach.name, Coach.coachId, Court.location, Coach.gender FROM Coach JOIN Court ON Coach.courtNo = Court.courtNo ORDER BY name ASC";
			$handle = $connect->prepare($sql);
			$handle->execute();
			$connect = null;
			$res = $handle->fetchAll();            

			foreach($res as $row){	
                		
				echo "<tr>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['location'] . "</td>";
                echo "<td>" . $row['gender'] . "</td>";
                echo "<td>" . "<input type='checkbox' name='coachName[]' value='".$row['name']."'>" . "</td>";
                echo "</tr>";

			}

        } catch (PDOException $e) {
            echo "PDOException: ".$e->getMessage();
            }

    ?>
    
    </table>

      <input type="submit" value="Check availability" class="submit">

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