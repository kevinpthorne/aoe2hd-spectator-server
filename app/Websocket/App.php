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
$app->addRoute("/test/echo", "AoE2HDSpectatorServer\\EchoComponent");
$app->addRoute("/test/becho", "AoE2HDSpectatorServer\\BEchoComponent");
$app->addRoute("/upstream", "AoE2HDSpectatorServer\\UpStream");
$app->addRoute("/downstream", "AoE2HDSpectatorServer\\DownStream");
try {
    $app->run();
} catch (\Exception $e) {
    $app->stdout($e->getMessage());
}