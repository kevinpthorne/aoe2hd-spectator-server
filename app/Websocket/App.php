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
$app->addRoute("/test/echo", new EchoComponent());
$app->addRoute("/test/becho", new BEchoComponent());
$app->addRoute("/upstream", new UpStream());
$app->addRoute("/downstream", new DownStream());
try {
    $app->run();
} catch (\Exception $e) {
    $app->stdout($e->getMessage());
}