<?php
/* This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://sam.zoy.org/wtfpl/COPYING for more details. */
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../vendor/autoload.php';
require '../app/Websocket/App.php';

use AoE2HDSpectatorServer\App;
use Ratchet\Server\EchoServer;

//Run the server application through the WebSocket protocol on port 8080
print_r("Setting server up\n");
$app = new App('localhost', 8080);
$app->route('/upstream', new AoE2HDSpectatorServer\UpStream(), array('*'));
print_r("Added /upstream\n");
$app->route('/downstream', new AoE2HDSpectatorServer\DownStream(), array('*'));
print_r("Added /downstream\n");
$app->route('/echo', new EchoServer, array('*'));
$app->route('/becho', new AoE2HDSpectatorServer\MyBinaryEcho(), array('*'));

try {
    print_r("Server started\n");
    $app->run();
} catch(\Exception $e) {
    print_r($e);
}
