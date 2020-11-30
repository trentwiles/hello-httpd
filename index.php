<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "glitch";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST["dream"]))
{
  $dream = htmlspecialchars($_POST["dream"]); // Prevent XSS attacks
  $epoch = time(); // Record the time of the dream
  
  $stmt = $conn->prepare("INSERT INTO dreams (dream, epoch) VALUES (?, ?)");
  $stmt->bind_param("si", $dream, $epoch);

  $stmt->execute();
  $stmt->close();
}

if(isset($_GET["clear"]))
{
  $sql = "DELETE FROM dreams WHERE 1=1";
  $result = $conn->query($sql);
  die(header("Location: /"));
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A cool thing made with Glitch">

    <title>Welcome to Glitch!</title>

    <link id="favicon" rel="icon" href="https://glitch.com/edit/favicon-app.ico" type="image/x-icon">
    <!-- import the webpage's stylesheet -->
    <link rel="stylesheet" href="/style.css">
    
    <!-- import the webpage's client-side javascript file -->
    <script src="/client.js" defer></script>
  </head>
  <body>
    <header>
      <h1>
        A Dream of the Future
      </h1>
    </header>

    <main>
      <p class="bold">Oh hi,</p>
      
      <p>Tell me your hopes and dreams:</p>
      
      <form method="post">
        <input name="dream" aria-label="a new dream" type="text" maxlength="100" placeholder="Dreams!">
        <button type="submit" id="submit-dream">Submit Dream</button>
      </form>
      
      <section class="dreams">
        <?php
        $sql = "SELECT * FROM dreams ORDER BY epoch DESC";
        $result = $conn->query($sql);
        if (!empty($result) && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
              echo "<ul id='dreams'>" . $row["dream"] . "</ul>";
            }
        }
        ?>
        
        <button id="clear-dreams" onclick="clearDreams()">
          Clear Dreams
        </button>
      </section>
      
    </main>

    <footer>
      Made with <a href="https://glitch.com">Glitch</a>!
    </footer>

    <!-- include the Glitch button to show what the webpage is about and
          to make it easier for folks to view source and remix -->
    <div class="glitchButton" style="position:fixed;top:20px;right:20px;"></div>
    <script src="https://button.glitch.me/button.js" defer></script>
  </body>
</html>