<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tennis Training</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>GLENDIS TENNIS</h1>

    <div id="indexDiv">
        <p id="index">Please insert your name and player id to begin</p>
        <form action="coach.php" method="get">
            <label for="name">Name: </label><input type="text" name="player" value="Mike Stand" class="indexLabel" required><br>
            <label for="playerid">Player Id: </label><input type="number" name="playerid" class="indexLabel" required><br>          
            <input type="submit" value="Enter" class="submit">
        </form>
    </div>

</body>
</html>


