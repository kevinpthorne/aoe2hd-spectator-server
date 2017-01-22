<?php
/**
 * Created by PhpStorm.
 * User: kevint
 * Date: 1/12/2017
 * Time: 12:56 PM
 */

namespace AoE2HDSpectatorServer;


class Util
{

    /**
     * @param \Ratchet\ConnectionInterface $conn
     * @return array parameterMap
     */
    public static function getQueryParameters(\Ratchet\ConnectionInterface $conn)
    {
        $query = array();
        $queryString = $conn->httpRequest->getUri()->getQuery();
        $rawParamsAndValues = explode("&", $queryString);
        foreach($rawParamsAndValues as $paramAndValue) {
            $split = explode("=", $paramAndValue);
            $query[urldecode($split[0])] = urldecode($split[1]);
        }
        return $query;
    }

}