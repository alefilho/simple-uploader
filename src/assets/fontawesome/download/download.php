<?php
return;
// GET SRC
$count = 1;
foreach(file('urls.txt') as $line) {
    if (strpos($line, 'src: url') !== false) {
        if ($count % 2 == 1) {
            $src = str_replace('.eot");', "", str_replace('src: url("../webfonts/', "", $line));
            $srcs[] = $src;
        }
        $count++;
    }
}

$urlBase = "https://kit-pro.fontawesome.com/releases/v5.12.1/webfonts";

foreach ($srcs as $key => $value) {
    $value = trim($value);
    echo "{$urlBase}/{$value}.eot<br>";
    file_put_contents("assets/fontawesome/webfonts/{$value}.eot", fopen("{$urlBase}/{$value}.eot", 'r'));
    file_put_contents("assets/fontawesome/webfonts/{$value}.woff2", fopen("{$urlBase}/{$value}.woff2", 'r'));
    file_put_contents("assets/fontawesome/webfonts/{$value}.woff", fopen("{$urlBase}/{$value}.woff", 'r'));
    file_put_contents("assets/fontawesome/webfonts/{$value}.ttf", fopen("{$urlBase}/{$value}.ttf", 'r'));
    file_put_contents("assets/fontawesome/webfonts/{$value}.svg", fopen("{$urlBase}/{$value}.svg", 'r'));
}
?>
