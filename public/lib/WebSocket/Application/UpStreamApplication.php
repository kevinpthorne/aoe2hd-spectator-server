<?php
/**
 * Created by PhpStorm.
 * User: kevint
 * Date: 1/11/2017
 * Time: 5:29 PM
 */

namespace WebSocket\Application;

use WebSocket\Connection;

class UpStreamApplication extends Application
{

    private $streamers = array();

    /**
     * @param Connection $streamer
     */
    public function onConnect($streamer)
    {
        $id = $streamer->getClientId();
        $this->streamers[$id] = $streamer;
    }

    /**
     * @param Connection $streamer
     */
    public function onDisconnect($streamer)
    {
        $id = $streamer->getClientId();
        unset($this->streamers[$id]);
    }

    /**
     * @param Connection $streamer
     */
    public function onData($request, $streamer)
    {
        //pass
    }

    /**
     * @param Connection $streamer
     */
    public function onBinaryData($data, $streamer)
    {
        $filename = str_replace(".aoe2record", "", $streamer->getQueryParameters()['filename']);
        $player = $streamer->getQueryParameters()['player'];
        $fileWriter = fopen("recs/" . $player . "." . $filename . '.aoe2record', "w");
        $success = fwrite($fileWriter, $data);
        if ($success) {
            $msg = 'continue';
        } else {
            $msg = 'error';
        }
        fclose($fileWriter);
        $streamer->send($this->_encodeData('echo', $msg));
    }
}