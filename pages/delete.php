<!-- made by monarch60 -->
<!DOCTYPE html>
<html>
<head>
    <title>Delete Tweet</title>
    <style>
        body {
            background-image: url('/images/background.jpg');
            background-size: cover;
            text-align: center;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
        }
        .main-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 10vh;
            height: 90vh;
        }
        .delete-box {
            background-color: black;
            border: 2px solid white;
            border-radius: 15px;
            padding: 20px;
            width: 400px;
        }
        .yheading {
            color: yellow;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .dropdown {
            width: 100%;
            margin-bottom: 10px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            box-sizing: border-box;
        }
        input[type="text"], input[type="submit"] {
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            margin-bottom: 10px;
            width: 100%;
            box-sizing: border-box;
        }
        input[type="text"] {
            background-color: yellow;
            color: black;
        }
        input[type="submit"] {
            background-color: yellow;
            color: black;
            cursor: pointer;
        }
        .yellow-token {
            background-color: #000000;
            color: white;
        }
        .green-token {
            background-color: green;
            color: white;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="delete-box">
            <h1 class="yheading">X-WASP DELETE TOOL</h1>
            <form method="post" action="">
                <h3 style="color: white;">select the token that was used to post the tweet</h3>
                <h3 style="color: white;">then paste the url of the tweet in the yellow box</h3>
                <h3 style="color: white;">then press the delete tweet button</h3>
                <select class="dropdown" name="selected_token">
                    <?php
                    exec("python3 /var/www/html/scripts/checktkn.py");
                    $lines = file("/var/www/html/data/tokens.temp", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    foreach ($lines as $line) {
                        $token_data = explode("|", $line);
                        $ip_address = end($token_data);
                        $class = '';
                        if (strpos($ip_address, '>') !== false) {
                            $class = 'yellow-token';
                        } elseif (strpos($ip_address, '^') !== false) {
                            $class = 'green-token';
                        }
						$username = str_replace('>', '', $ip_address);
                        $username = str_replace('^', '', $username);
						echo "<option value='" . htmlspecialchars($line) . "'class='$class' style='text-align: center;'>$username</option>";                    }
                    ?>
                </select>
                <br>
                <input type="text" name="tweet_url" placeholder="Paste the tweet URL here" required><br>
                <input type="submit" value="Delete Tweet">
            </form>
        </div>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $selected_token = $_POST["selected_token"];
        $tweet_url = $_POST['tweet_url'];
        $file = '/var/www/html/data/urlin.temp';
        exec("chmod 777 /var/www/html/data/urlin.temp");
        file_put_contents($file, $tweet_url . "|" . $selected_token . PHP_EOL, FILE_APPEND);
        exec("python3 /var/www/html/scripts/delete.py");	
        unlink($file);
        $fl = fopen("/var/www/html/data/urlin.temp", 'w');
        fclose($fl);
    }
    ?>
</body>
</html>
