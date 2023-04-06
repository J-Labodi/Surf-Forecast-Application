<?php

session_start();

include('config.php');
include ('index_logic.php');

?>

<!DOCTYPE html>
<head>
    <meta name="viewport" content="width=device-width", initial-scale="1.0">
    <title>Wavy Surf Forecast</title>
    <link rel="stylesheet" href="../css/index-style.css">
</head>
<body>
    <div class="hero">
        <video autoplay loop muted plays-inline class="back-video">
            <source src="../video/filtered.mp4" type="video/mp4">
        </video>
        <nav>
            <img src="../images/logo.png" class="logo" alt="logo">
            <div class ="layer">
                <div class="form">
                    <h1>Login</h1>
                    <hr id="login-hr">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div id="first-form-element">
                            <label for="username">Username</label><br>
                            <input type="text" id="username" name="username" required><br>
                        </div>
                        <div id="second-form-element">
                            <label for="password">Password</label><br>
                            <input type="password" id="password" name="password" required><br>
                        </div>
                        <a href="#" id="first-hlink">Forgot password?</a><br>
                        <?php
                        if($fail == true){
                        echo '<p>Invalid login details</p>';
                        }
                        ?>
                        <input type="submit" value="Sign in">
                    </form>
                    <hr id="bottom-hr">
                    <h4>Don't have an account?</h4>
                    <a href="#" id="second-hlink">Sign up</a>
                </div>
            </div>
        </nav>
    </div>
</body>
</html>