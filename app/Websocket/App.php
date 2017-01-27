<?php
/**
 * Created by PhpStorm.
 * User: kevint
 * Date: 1/14/2017
 * Time: 12:40 AM
 */

namespace AoE2HDSpectatorServer;

require '../../vendor/autoload.php';
require 'autoload.php';

use WsLib\RoutedWsServer;
use Illuminate\Database\Capsule\Manager as Capsule;

class AoE2StreamingServer extends RoutedWsServer
{

    /**
     * AoE2StreamingServer constructor.
     * @param $capsule
     */
    public function __construct($addr, $port)
    {
        parent::__construct($addr, $port);

        $config = $this->loadConfig();

        $capsule = new Capsule;
        $capsule->addConnection([
            'driver'    => $config['DB_CONNECTION'],
            'host'      => $config['DB_HOST'],
            'database'  => $config['DB_DATABASE'],
            'username'  => $config['DB_USERNAME'],
            'password'  => $config['DB_PASSWORD'],
            'charset'   => 'utf8',
            'collation' => 'utf8_general_ci',
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

    function loadConfig() {
        $result = array();
        $lines = explode("\n", file_get_contents("../../.env"));
        $key = "";
        $value = "";
        $isWaitingOtherLine = false;

        foreach($lines as $i=>$line) {
            if(empty($line) || (!$isWaitingOtherLine && strpos($line,"#") === 0)) continue;

            if(!$isWaitingOtherLine) {
                $key = substr($line,0,strpos($line,'='));
                $value = substr($line,strpos($line,'=') + 1, strlen($line));
            } else {
                $value .= $line;
            }

            /* Check if ends with single '\' */
            if(strrpos($value,"\\") === strlen($value)-strlen("\\")) {
                $value = substr($value, 0, strlen($value)-1)."\n";
                $isWaitingOtherLine = true;
            } else {
                $isWaitingOtherLine = false;
            }

            $result[$key] = $value;
            unset($lines[$i]);
        }

        return $result;
    }

    public static function Instance() {
        static $INSTANCE = null;
        if($INSTANCE === null) {
            $INSTANCE = new AoE2StreamingServer('0.0.0.0', '8082');
        }
        return $INSTANCE;
    }

}

$app = AoE2StreamingServer::Instance();
$app->addRoute("/test/echo", "AoE2HDSpectatorServer\\EchoComponent");
$app->addRoute("/test/becho", "AoE2HDSpectatorServer\\BEchoComponent");
$app->addRoute("/upstream", "AoE2HDSpectatorServer\\UpStream");
$app->addRoute("/downstream", "AoE2HDSpectatorServer\\DownStream");
try {
    $app->run();
} catch (\Exception $e) {
    $app->stdout($e->getMessage());
}