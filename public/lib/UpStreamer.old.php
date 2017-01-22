<?php

class UpStreamer
{
    private $_fileName;
    private $_contentLength;
    private $_directory;

    public function __construct($directory)
    {
        if (!isset($_SERVER['HTTP_X_FILENAME'])
            && !isset($_SERVER['CONTENT_LENGTH'])
        ) {
            throw new Exception("No headers found!");
        }
        $this->_fileName = $_SERVER['HTTP_X_FILENAME'];
        $this->_contentLength = $_SERVER['CONTENT_LENGTH'];
        $this->_directory = $directory;
    }

    public function isValid()
    {
        if (($this->_contentLength > 0) && (strpos($this->_fileName, '.aoe2record') !== false)) {
            return true;
        }
        return false;
    }

    public function setDirectory($directory)
    {
        $this->_directory = $directory;
    }

    public function receive()
    {
        error_log("Writing stream");
        if (!$this->isValid()) {
            header($_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request", true, 400);
            die(400);
        }
        $upStream = fopen("php://input", "r");
        $fileWriter = fopen($this->_directory . urldecode($this->_fileName), "w+");
        $step = 0;
        while (true) {
            $buffer = fgets($upStream, 64);
            if (strlen($buffer) == 0) {
                error_log("EOF, closing");
               // ftruncate($fileWriter, filesize($this->_directory . urldecode($this->_fileName)) - 64);

                fclose($upStream);
                fclose($fileWriter);

                error_log("steps: " . $step);
                return true;
            }
            //if ($step > (64 * 6)) {
                fwrite($fileWriter, $buffer);
            //}
            $step += 64;
        }
        return false;
    }
}