<?php

$config = array(
    'port'              => 8084, 
    'debug'             => true,
    'allowedOrigins'    => null,
    'IpBlackList'       => null,
    'authAdapter'       => 'MgRTC\Session\AuthSimple',
    'facebook'          => array(
        'appId'         => '251017698383358',
        'secret'        => '0ee6bd094ef97478417ef9602232524d'
    ),
    'wordpress'         => array(
        'dir'           => __DIR__ . '/../../client/demos/wordpress'
    ),
    'simple'            => array(
        'allowAnonim'   => true,    
        'members'       => array(
            array('id' => 11, 'username' => 'operator1', 'password' => 'operator1', 'name'   => 'Tech Support')
        )
    ),
    'auth_query'            => [
        'allowAnonim'   => true,
        'members'       => [
            ['id' => 11, 'username' => 'bot', 'password' => 'your_password', 'name'   => 'Support Bot']
        ]
    ],    
    'operators'         => null,
    'allowDuplicates'   => true,    
    'rooms'             => array(
        1   => array(
            'authAdapter'   => 'MgRTC\Session\AuthSimple',
            'disableVideo'  => false,
            'disableAudio'  => false
        ),
        2   => array(
            'authAdapter'   => 'MgRTC\Session\AuthFacebook2', //use AuthFacebook2 for facebook api 2
        ),
        3   => array(
            'authAdapter'   => 'MgRTC\Session\AuthWordpress',
        ),
        4   => array(
            'authAdapter'   => 'MgRTC\Session\AuthSimple',
            'operators'     => array(11),
            //allow "viewonly" group visitors in the room as operators as
            //presenters, either webcam or desktop share
            /*
            'group'         => true,                                            
            'limit'         => 3,
            'desktopShare'  => true,
            'disableVideoNonOperator' => true,
            'disableAudioNonOperator' => true,
             */
        ),
        5   => array(                                                           
            'authAdapter'   => 'MgRTC\Session\AuthSimple',                      
            'group'         => true,                                            
            'limit'         => 3
        ),                                                                      
        6   => array(                                                           
            'authAdapter'   => 'MgRTC\Session\AuthSimple',                      
            'roulette'      => true                                             
        ),                                                                      
        7   => array(                                                           
            'file'          => true,                                            
            'authAdapter'   => 'MgRTC\Session\AuthSimple'
        ),
        8   => array(
            'authAdapter'   => 'MgRTC\Session\AuthSimple',
            'desktopShare'  => true
        ),
        9   => [
            'authAdapter'   => 'MgRTC\Session\AuthQuery',
            'disableVideo'  => true,
            'disableAudio'  => true,
            'operators'     => [11],
        ],        
        10   => array(
            'authAdapter'   => 'MgRTC\Session\AuthERP',
            'disableVideo'  => false,
            'disableAudio'  => false,
        ),
        'group_%'   => array(
            'authAdapter'   => 'MgRTC\Session\AuthSimple',
            'group'         => true,
            'limit'         => 3
        )
    ),
    /*'routes' => [
        ['path' => '/web', 'server_class' => 'MgRTC\Web'],//sample standard web endpoint
    ],*/
);
