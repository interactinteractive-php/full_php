<?php

namespace MgRTC\Session;

use MgRTC\Session\AuthInterface;
use Ratchet\ConnectionInterface;

class AuthERP extends AuthBase implements AuthInterface {

    /**
     * @param ConnectionInterface $conn
     * @param array $cookies
     * @return array
     */
    public function authUser(ConnectionInterface $conn, array $cookies) {
        $parameters = $conn->WebSocket->request->getQuery()->toArray();
        return array(
            'provider' => 'erp',
            'id'       => $parameters['userid']
        );
    }
}