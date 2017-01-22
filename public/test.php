<?php
$files = scandir("recs/");

foreach($files as $file) {
    echo urlencode($file) . "<br>";
}