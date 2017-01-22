<?php
/**
 * Created by PhpStorm.
 * User: kevint
 * Date: 1/12/2017
 * Time: 11:21 PM
 */

namespace AoE2HDSpectatorServer;


use Ratchet\ConnectionInterface;

class DownStream implements \Ratchet\WebSocket\MessageComponentInterface
{
    protected $spectators;

    /**
     * DownStream constructor.
     */
    public function __construct()
    {
        $this->spectators = new \SplObjectStorage();
    }

    /**
     * When a new connection is opened it will be passed to this method
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    function onOpen(ConnectionInterface $conn)
    {
        echo "New connection! ({$conn->resourceId})\n";
        $this->spectators->attach($conn);
        $conn->sizeSent = 0;
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    function onClose(ConnectionInterface $conn)
    {
        echo "Connection {$conn->resourceId} has disconnected, sent {$conn->sizeSent}kb of data \n";
        $this->spectators->detach($conn);
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param  ConnectionInterface $conn
     * @param  \Exception $e
     * @throws \Exception
     */
    function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    /**
     * Triggered when a client sends data through the socket
     * @param  \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
     * @param  string $msg The message received
     * @throws \Exception
     */
    function onMessage(ConnectionInterface $from, \Ratchet\RFC6455\Messaging\MessageInterface $msg)
    {
        echo $msg;
        if(str_contains($msg, "1")) {
            $query = Util::getQueryParameters($from);
            $filename = str_replace(".aoe2record", "", $query['filename']);
            $player = $query['player'];

            echo "{$from->resourceId} requested \"recs/$player.$filename.aoe2record\n";

            $errors = 0;
            $position = 0;

            $fileReader = fopen("recs/" . $player . "." . $filename . '.aoe2record', "r");
            if($fileReader == false) {
                $from->close();
                return;
            }
            if(isset($query['position'])) {
                fseek($fileReader, $query['position']);
                $position = $query['position'];
            }
            while($errors < 5) {
                if ($position < (256 * 1024)) { //mgz header information
                    $buffer = fgets($fileReader, (256 * 1024));
                    $step = 256*1024;
                } else {
                    $buffer = fgets($fileReader, 4096);
                    $step = 4096;
                }
                if (strlen($buffer) == 0) {
                    echo "Retrying...[Attempt " . ($errors+1) . "/5]\n";
                    fseek($fileReader, $position);
                    $errors++;
                    sleep(1);
                    continue;
                }
                $errors = 0;
                $from->send($buffer);
                $position += $step;

                usleep(10000);
            }

            fclose($fileReader);
            //$conn->send("{'position':$position}");
            $from->sizeSent += $position/1024; //@todo: fix math
            $from->close();
        }
    }
}