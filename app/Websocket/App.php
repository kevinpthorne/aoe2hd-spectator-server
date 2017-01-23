<?php
/**
 * Created by PhpStorm.
 * User: kevint
 * Date: 1/14/2017
 * Time: 12:40 AM
 */

namespace AoE2HDSpectatorServer;

require 'autoload.php';

use WsLib\RoutedWsServer;


class AoE2StreamingServer extends RoutedWsServer
{

}


$app = new RoutedWsServer("0.0.0.0", "8082");
$app->addRoute("/test/echo", "EchoComponent");
$app->addRoute("/test/becho", "BEchoComponent");
$app->addRoute("/upstream", "UpStream");
$app->addRoute("/downstream", "DownStream");
try {
    $app->run();
} catch (\Exception $e) {
    $app->stdout($e->getMessage());
}