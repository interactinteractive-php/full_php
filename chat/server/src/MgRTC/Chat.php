<?php

namespace MgRTC;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {

    /**
     * Assoc array of clients resourceId: {connection: , desc: }
     * 
     * @var array
     */
    protected $clients;

    /**
     *
     * @var array
     */
    protected $config;
    
    /**
     *
     * @var MgRTC\Friendlist\CallableInterface
     */
    protected $_friendlistAdapter;
    
    protected function _createFriendlistAdapter() {
        $flClass = isset($this->config['friendlistAdapter']) ? $this->config['friendlistAdapter'] : 'MgRTC\Friendlist\CallableOperator';

        //$this->debug("Creating friendlist adapter [$flClass]");
        $this->_friendlistAdapter = new $flClass($this->config);
    }    

    /**
     * Constructor
     */
    public function __construct(array $config) {
        $this->clients = array();
        $this->config = $config;
        $this->_createFriendlistAdapter();
    }

    /**
     * New Connection opened
     * 
     * @param ConnectionInterface $conn
     * @param mixed $request
     */
    public function onOpen(ConnectionInterface $conn, $request = null) {        
        //$this->debug("New anonim connection! ({$conn->resourceId}) in room ({$conn->Room})");
    }

    /**
     * add connection when logged in
     * 
     * @param ConnectionInterface $conn
     * @param array $userDesc
     */
    protected function _addConnection(ConnectionInterface $conn, $userDesc) {
        // Store the new connection to send messages to later
        $this->clients[$conn->Room][$conn->User['id']][$conn->resourceId] = array(
            'connection'    => $conn,
            'desc'          => $userDesc
        );
        
        /*$this->debug("New logged connection! ({$conn->resourceId}) in room ({$conn->Room})");
        $this->debug($userDesc);*/
    }

    /**
     * Broadcast message for all but sender
     * 
     * @param string|array $msg
     * @param ConnectionInterface $from
     */
    public function broadcast($msg, ConnectionInterface $from) {
        //no room created
        if (!isset($this->clients[$from->Room]) || !isset($this->clients[$from->Room][$from->User['id']])) {
            return false;
        }
        
        if (is_array($msg)) {
            $msg = json_encode($msg);
        }
        
        // broadcast message to all connected clients
        foreach ($this->clients[$from->Room] as $clients) {
            
            foreach ($clients as $client) {
                //not to sender
                if ($from !== $client['connection']) {
                    $this->_send($msg, $client['connection']);
                }
            }
        }
    }
    
    /**
     * Broadcast message for all but sender
     * 
     * @param string|array $msg
     * @param ConnectionInterface $from
     */
    public function broadcastAll($msg, ConnectionInterface $from) {
        //no room created
        if (!isset($this->clients[$from->Room])) {
            return false;
        }
        
        if (is_array($msg)) {
            $msg = json_encode($msg);
        }
        
        // broadcast message to all connected clients
        foreach ($this->clients[$from->Room] as $clients) {
            
            foreach ($clients as $client) {
                //not to sender
                if ($from !== $client['connection']) {
                    $this->_send($msg, $client['connection']);
                }
            }
        }
    }

    /**
     * Send a message to a connection
     * 
     * @param string $msg
     * @param ConnectionInterface $to
     */
    
    protected function send($msg, $room, $connectionId) {
        
        $clients = $this->clients[$room][$connectionId];
        $count = count($clients);
        $n = 1;
        
        foreach ($clients as $client) {
            
            if ($count == $n) {
                $msg['data']['notify'] = 1;
            }
            
            $this->_send($msg, $client['connection']);
            
            $n++;
        }
    }
    
    protected function sendOwnAll($msg, $userId, ConnectionInterface $from) {
        
        $clients = $this->clients[$from->Room][$from->User['id']];
        
        if (count($clients) > 1) {
            
            $msg['data']['me'] = 1;
            $msg['data']['connectionId'] = $userId;
            
            foreach ($clients as $client) {
                
                if ($from !== $client['connection']) {
                    $this->_send($msg, $client['connection']);
                }
            }
        }
        
        return;
    }
    
    protected function _send($msg, ConnectionInterface $to) {
        if (is_array($msg)) {
            $msg = json_encode($msg);
        }
        $to->send($msg);
    }

    /**
     * Get client in a room by user id
     * 
     * @param mixed $userId
     * @param int $room
     * @return array|false
     */
    protected function findClient($userId, $room){
        //no room created
        if (!isset ($this->clients[$room])) {
            return false;
        }
        foreach ($this->clients[$room] as $client) {
            if ($client['desc']['data']['userData']['id'] == $userId) {
                return $client;
            }
        }
    }

    /**
     * Get all res.ids for media ready connections
     * 
     * @param ConnectionInterface $from
     * @return array
     */
    protected function getAllMediaReadyConnectionIds(ConnectionInterface $from){
        $ids = array();
        foreach ($this->clients[$from->Room] as $resourceId => $client) {
            //only if operator or peer is operator
            if ($from !== $client['connection'] && isset ($client['desc']['media_ready']) && $client['desc']['media_ready']) {
                $ids[] = $resourceId;
            }
        }
        return $ids;
    }

    /**
     * Populate room option
     * @param int $room
     * @param string $optionName
     * @param array $options
     */
    public function getRoomOption($room, $optionName, &$options = null){
        $optionValue = null;
        //$this->debug("Searching for option [{$optionName}] room id [{$room}]");
        //get from specific room
        if (isset ($this->config['rooms']) && isset ($this->config['rooms'][$room])) {
            $roomConfig = $this->config['rooms'][$room];
            if (isset($roomConfig[$optionName])) {
                $optionValue = $roomConfig[$optionName];
            }
        } else {
            //get from pattern room name
            $roomConfig = $this->getRoomOptionsByPattern($room);
            //get global
            if ($roomConfig === false) {
                $roomConfig = $this->config;
            }
            if (isset($roomConfig[$optionName])) {
                $optionValue = $roomConfig[$optionName];
            }
        }
        
        if (isset($options) && isset($optionValue)) {
            $options[$optionName] = $optionValue;
        }      
        
        return $optionValue;
    }
    
    /**
     * get room options by pattern
     * 
     * @param int|string $room
     * @return false|array 
     */
    protected function getRoomOptionsByPattern($room){        
        if (!isset ($this->config['rooms']) && !is_array($this->config['rooms'])) {
            return false;
        }
        
        foreach ($this->config['rooms'] as $roomPattern => $roomConfig) {
            if (!is_string($roomPattern) || strpos($roomPattern, '%') === false) {
                continue;
            }
            $roomPattern = str_replace('%', '', $roomPattern);
            if (strpos($room, $roomPattern) !== false) {
                return $roomConfig;
            }
        }
        
        return false;
    }

    /**
     * Get chat room options
     *
     * @param int $room
     * @return array
     */
    public function getRoomOptions($room){
        $options = array();
        $optionNames = array(
            'file',              
            'group',        
            'limit',
            'disableVideo',
            'disableAudio',
            'desktopShare',
            'disableVideoNonOperator',
            'disableAudioNonOperator',
        );
        foreach ($optionNames as $optionName) {
            $this->getRoomOption($room, $optionName, $options);
        }
        return $options;
    }
    
    /**
     * Should we disable video or audio for non-operator
     * 
     * @param string $videoOrAudio audio|video
     * @param ConnectionInterface $from
     * @param array $roomOptions
     * @return boolean
     */
    protected function shouldDisableVideoOrAudio($videoOrAudio, ConnectionInterface $from, $roomOptions)
    {
        $media = $videoOrAudio == 'audio'? 'Audio' : 'Video';
        $key = "disable{$media}NonOperator";
        if (isset($roomOptions[$key]) && $roomOptions[$key] && !$from->User['operator']) {
            return true;
        }
        return false;
    }

    /**
     * Execute on message "login"
     * 
     * @param ConnectionInterface $from
     * @param array $message
     * @param array $roomOptions
     * @return mixed
     */
    protected function onMessageLogin(ConnectionInterface $from, $message, $roomOptions){
        if (!isset($from->User)) {
            return false;
        }       
        
        //check for duplicates
        if (isset($this->config['allowDuplicates']) && $this->config['allowDuplicates'] === false && $this->findClient($from->User['id'], $from->Room)) {
            //if same ID found get out
            $this->debug("Duplicate attemp in room {$from->Room} for user id {$from->User['id']}");
            //send system message
            $this->_send(array(
                "type"  => "message",
                "data"  => array(
                    "type"          => "warning",
                    "text"          => "You are already logged in"
                )
            ), $from);
            
            return false;
        }
        
        //process non operator options
        if ($this->shouldDisableVideoOrAudio('video', $from, $roomOptions)) {
            $roomOptions['disableVideo'] = true;
        }
        if ($this->shouldDisableVideoOrAudio('audio', $from, $roomOptions)) {
            $roomOptions['disableAudio'] = true;
        }   
        
        $userDesc = array(
            'data'  => array(
                'userData'  => $from->User,
                'loginParams' => $message['data']
            )
        );
        
        //add connection
        $this->_addConnection($from, $userDesc);
        
        //send connection id and data
        $this->_send(array(
            'type' => 'connectionId',
            'data' => array(
                'connectionId' => $from->User['id'],
                'data'         => $userDesc,
                'room'         => $roomOptions,
                'room_id'      => $from->Room,
                'users_count'  => count($this->clients[$from->Room])
            )
        ), $from);                                                                   

        //prepare and send all existing connections to new peer
        $peerConnections = array();
        
        foreach ($this->clients[$from->Room] as $userId => $clients) {
            
            if ($userId != $from->User['id']) {
                foreach ($clients as $client) {
                    if ($from !== $client['connection']) {
                        $peerConnections[$client['desc']['data']['userData']['id']] = $client['desc'];
                    }
                }
            }
        }
        
        $this->_send(array(
            'type' => 'connections',
            'data' => $peerConnections
        ), $from);

        //inform old peers about new connection
        $this->broadcast(array(
            'type' => 'connection_add',
            'data' => array(
                'connectionId' => $from->User['id'],
                'data'         => $userDesc,
                'users_count'  => count($this->clients[$from->Room])
            )
        ), $from);
    }

    /**
     * New message received
     * 
     * @param ConnectionInterface $from
     * @param string $message
     * @return mixed
     */
    public function onMessage(ConnectionInterface $from, $message) {
        
        $roomOptions = $this->getRoomOptions($from->Room);
        $msg = json_decode($message, true);
        
        switch($msg['type']){

            case 'login':
                
                $this->onMessageLogin($from, $msg, $roomOptions);
                
            break;
            
            case 'chat_message':    
            case 'read_message':  
            case 'remove_message':      
                
                if (!$this->_isLogged($from)) {
                    return false;
                }
                
                $peerConnectionId = $msg['data']['connectionId'];
                
                if ($msg['type'] == 'read_message') {
                    $msg['data']['readDate'] = date('Y-m-d H:i:s');
                }
                
                $this->sendOwnAll($msg, $peerConnectionId, $from);
                
                if (!isset($this->clients[$from->Room][$peerConnectionId])) {
                    return false;
                }

                $msg['data']['connectionId'] = $from->User['id'];
                
                $this->send($msg, $from->Room, $peerConnectionId);
                
            break;
                
            case 'start_typing':  
            case 'stop_typing':  
            case 'file_downloaded':        
                
                if (!$this->_isLogged($from)) {
                    return false;
                }
                
                $peerConnectionId = $msg['data']['connectionId'];
                
                if (!isset($this->clients[$from->Room][$peerConnectionId])){
                    return false;
                }
                
                $msg['data']['connectionId'] = $from->User['id'];
                
                $this->send($msg, $from->Room, $peerConnectionId);
                
            break;    
                
            case 'delete_conversations':  
            case 'open_chatbox':
            case 'close_chatbox':    
            case 'open_groupchatbox': 
            case 'close_groupchatbox':       
            case 'read_group_message':     
                
                if (!$this->_isLogged($from)) {
                    return false;
                }
                
                $this->sendOwnAll($msg, $msg['data']['connectionId'], $from);
                    
            break;     
                
            case 'pushusers_togroup':    
            case 'rename_group': 
            case 'delete_group':
            case 'exit_group':
            case 'group_chat_message':   
            case 'remove_group_message':     
                
                if (!$this->_isLogged($from)) {
                    return false;
                }
                
                $this->sendOwnAll($msg, $from->User['id'], $from);
                
                $msg['data']['connectionId'] = $from->User['id'];
                $members = $msg['data']['members'];
                
                foreach ($members as $memberId => $val) {
                
                    if (isset($this->clients[$from->Room][$memberId]) && $from->User['id'] != $memberId) {
                        $this->send($msg, $from->Room, $memberId);
                    }
                }
                
            break;    
                
            case 'pushremoveusers_togroup': 
                
                if (!$this->_isLogged($from)) {
                    return false;
                }
                
                $msg['data']['connectionId'] = $from->User['id'];
                $msg['data']['isRemovedUser'] = 0;
                
                $this->sendOwnAll($msg, $from->User['id'], $from);
                
                $members = $msg['data']['members'];
                $removedUsers = $msg['data']['removedUsers'];
                
                foreach ($members as $memberId => $val) {
                
                    if (isset($this->clients[$from->Room][$memberId]) && $from->User['id'] != $memberId) {
                        $this->send($msg, $from->Room, $memberId);
                    }
                }
                
                unset($msg['data']['members']);
                unset($msg['data']['removedUsers']);
                
                $msg['data']['isRemovedUser'] = 1;
                
                foreach ($removedUsers as $key => $removedUserId) {
                
                    if (isset($this->clients[$from->Room][$removedUserId]) && $from->User['id'] != $removedUserId) {
                        $this->send($msg, $from->Room, $removedUserId);
                    }
                }
                
            break; 
                
            case 'logout':
                
                if (!isset($this->clients[$from->Room][$from->User['id']])) {
                    return;
                }
                
                unset($this->clients[$from->Room][$from->User['id']][$from->resourceId]);                       

                if (count($this->clients[$from->Room][$from->User['id']]) == 0) {

                    $this->broadcast(array(
                        'type'  => 'connection_remove',
                        'data'  => array(
                            'connectionId' => $from->User['id'],
                            'users_count'  => count($this->clients[$from->Room]) - 1
                        )
                    ), $from);

                    unset($this->clients[$from->Room][$from->User['id']]);            
                }
          
            break;  
                
            case 'chatstatus':
                
                if (!isset($this->clients[$from->Room][$from->User['id']])) {
                    return;
                }
                
                if ($msg['data']['status'] == 'idle') {
                    
                    $this->setUserDataStatus($from->User['id'], $from->Room, 'idle');
                    
                    $this->broadcast(array(
                        'type'  => 'connection_idle',
                        'data'  => array(
                            'connectionId' => $from->User['id']
                        )
                    ), $from);
                    
                } else {
                    
                    $this->setUserDataStatus($from->User['id'], $from->Room, 'online');
                    
                    $this->broadcast(array(
                        'type' => 'connection_online',
                        'data' => array(
                            'connectionId' => $from->User['id']
                        )
                    ), $from);
                }
                
            break;      
                
            case 'userstatus':   
                
                if (!isset($this->clients[$from->Room][$from->User['id']])) {
                    return;
                }
                
                $this->broadcast(array(
                    'type' => 'connection_changestatus',
                    'data' => array(
                        'connectionId' => $from->User['id'], 
                        'status' => $msg['data']['status']
                    )
                ), $from);
                
            break;
            
            case 'api_send_all_user':
                
                if (!$this->_isLogged($from)) {
                    return false;
                }
                
                $this->broadcastAll($msg, $from); 
                
            break;
            
            case 'api_send_one_user':      
                
                if (!$this->_isLogged($from)) {
                    return false;
                }
                
                $peerConnectionId = $msg['connectionId'];
                
                $this->sendOwnAll($msg, $peerConnectionId, $from);
                
                if (!isset($this->clients[$from->Room][$peerConnectionId])) {
                    return false;
                }

                $msg['data']['connectionId'] = $from->User['id'];
                
                $this->send($msg, $from->Room, $peerConnectionId);
                
            break;
            
            case 'pushnotification':
                
                $peerConnectionId = $msg['data']['connectionId'];
                
                if (!isset($this->clients[10][$peerConnectionId])) {
                    return false;
                }
                
                $this->send($msg, 10, $peerConnectionId);
                
            break;    
        }
    }
    
    protected function setUserDataStatus($userId, $roomId, $status) {
        
        if (isset($this->clients[$roomId][$userId])) {
            foreach ($this->clients[$roomId][$userId] as $k => $client) {
                $this->clients[$roomId][$userId][$k]['desc']['data']['userData']['status'] = $status;
            }
        }
        
        return true;
    }

    /**
     * Check if connection is logged in room
     * 
     * @param ConnectionInterface $from
     * @return boolean
     */
    protected function _isLogged(ConnectionInterface $from){
        return isset($this->clients[$from->Room][$from->User['id']]);
    }

    /**
     * Closing connection
     * 
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn) {
        if (!isset($this->clients[$conn->Room][$conn->User['id']])) {
            return;
        }
        
        //$this->debug("Connection {$conn->resourceId} from room {$conn->Room} has disconnected");
        //$this->debug($this->clients[$conn->Room][$conn->resourceId]['desc']);

        // The connection is closed, remove it, as we can no longer send it messages
        unset($this->clients[$conn->Room][$conn->User['id']][$conn->resourceId]);            
        
        if (count($this->clients[$conn->Room][$conn->User['id']]) == 0) {
            
            //inform about closed connection
            $this->broadcast(array(
                'type' => 'connection_remove',
                'data' => array(
                    'connectionId' => $conn->User['id'],
                    'users_count'  => count($this->clients[$conn->Room]) - 1
                )
            ), $conn);
        
            unset($this->clients[$conn->Room][$conn->User['id']]);            
        }
    }

    /**
     * Close on error
     * 
     * @param ConnectionInterface $conn
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e) {
        $this->debug("An error has occurred: {$e->getMessage()}");
        $conn->close();
    }

    /**
     * Var dump obj
     * 
     * @param mixed $obj
     */
    public function debug($obj){
        if (!isset ($this->config['debug']) || !$this->config['debug']) {
            return;
        }
        if (is_scalar($obj)) {
            echo "$obj\n";
        } else {
            print_r($obj);
        }
    }
    
    /**
     * Get clients connections
     * 
     * @return array
     */
    public function getClients()
    {
        return $this->clients;
    }
    
}