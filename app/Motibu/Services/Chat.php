<?php
namespace Motibu\Services;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;
    protected $userToClient;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->userToClient = [];
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

    	$payload = json_decode($msg);

    	if ($payload) {
	    	switch ($payload->type) {
	    		case 'authenticate':
				    $access_token = $payload->access_token;

				    $token = \Motibu\Models\OauthAccessToken::findById($access_token);
				    if ($token) {
				        $user = $token->oauthSession->user;
				        if ($user) {
				        	$this->userToClient[$user->id][] = $from;
				        } else {
					        $this->clients->detach($conn);

					        echo "Connection {$conn->resourceId} is not authenticated.\n";
				        }
				    }
	    			break;

	    		case 'check_messages':
	    			$userId = $payload->userId;
	    			foreach ($this->userToClient[$userId] as $client) {
	    				$client->send('check_messages');
	    			}
	    			break;
	    		case 'check_notifications':
	    			$userId = $payload->userId;
	    			foreach ($this->userToClient[$userId] as $client) {
	    				$client->send('check_notifications');
	    			}
	    			break;
	    		
	    		default:
	    			# code...
	    			break;
	    	}
    	}

        // foreach ($this->clients as $client) {
        //     if ($from !== $client) {
        //         // The sender is not the receiver, send to each client connected
        //         $client->send($msg);
        //     }
        // }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
