<?php
    namespace wsm\samodel\apiBaseCamp;
    
    class libApiBaseView {

	private $api_key;
	private $user;
	private $password;
	private $account;	
	private $url;
        
        public function __construct($settings) {
            $this->setApiKey($settings['api_key']);
            $this->setAccount($settings['account']);
            $this->setUser($settings['user']);
            $this->setPassword($settings['password']);
        }
        
        public function setApiKey($api_key) {
		$this->api_key = $api_key;
		return $this;
	}
        
        public function setAccount($account) {
            $this->account = $account;
            $this->setURL($account);
            return $this;
	}
        
        public function setUser($user) {
            $this->user = $user;
            return $this;
	}
        
        public function setPassword($password) {
            $this->password = $password;
            return $this;
	}
        
        public function setURL($account = null) {
            $account = isset($account) ? $account : $this->account;
            $this->url = 'https://' . $account . '.basecamphq.com/';
            return $this;
	}

	public function getURL() {
            return $this->url;
	}
        
        public function getCompanies() {
            return $this->request('companies.xml');
	}
        
        public function getFiles($project_id, $offset = null) {
            return $this->request('projects/' . $project_id . '/attachments.xml?n=' . $offset);
	}
        
        public function getRecentCommentsForMessage($project_id, $id) {
                //echo ('projects/'.$project_id.'/posts/'.$id.'/comments.xml');
		return $this->request('projects/'.$project_id.'/posts/'.$id.'/comments.xml');
	}
        
        public function getRecentCommentsForItem($project_id, $id) {
		return $this->request('projects/'.$project_id.'/todo_items/'.$id.'/comments.xml');
	}
        
        public function getMessages($project_id) {
            return $this->request('projects/' . $project_id . '/posts.xml');
	}
        
        public function getProjects() {
            return $this->request('projects.xml');
	}
        
        public function getProject($id) {
            return $this->request('projects/' . $id . '.xml');
	}
        
        public function getTodoItems($list_id) {
            return $this->request('todo_lists/' . $list_id . '/todo_items.xml');
	}
        
        public function getTodoLists() {
            return $this->request('todo_lists.xml');
	}
        
        public function getTodoItem($item_id) {
            return $this->request("todo_items/".$item_id.".xml");
        } 
        
        public function getUsers() {
            return $this->request('people.xml');
	}

	private function isAuthenticated() {
            $result = ($this->account == null || $this->user == null || $this->password == null) ? false : true;
            return $result;
	}
        
        private function request($path) {

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $this->url . $path);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_HTTPGET, 1);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  
            curl_setopt($curl, CURLOPT_USERPWD, $this->user . ":" . $this->password);

            if(!$this->isAuthenticated()) throw new \Exception('Authentication Failed');

            if(curl_error($curl)) throw new Exception(curl_error($curl));

            $xml = new \SimpleXMLElement(curl_exec($curl));
            curl_close($curl);
            return $xml;
	}
    }
    
    class formView {
        
        private function __construct() {}
        static $conToABV;
        
        static function getToDoLists ($settings) {
            if ( ! isset (self::$conToABV))
                self::$conToABV = new libApiBaseView($settings);
            return self::$conToABV->getTodoLists();
        }
        
        static function getProjects ($settings) {
            if ( ! isset (self::$conToABV))
                self::$conToABV = new libApiBaseView($settings);
            return self::$conToABV->getProjects();
        }
        
        static function getTodoItems ($settings, $todo_id) {
            if ( ! isset (self::$conToABV))
                self::$conToABV = new libApiBaseView($settings);
            return self::$conToABV->getTodoItems($todo_id);
        }
        
        static function getMessages ($settings, $project_id) {            
            if ( ! isset (self::$conToABV))
                self::$conToABV = new libApiBaseView($settings);
            return self::$conToABV->getMessages($project_id);
        }
        
        static function getRecentCommentsForMessage ($project_id, $message_id) {
            return self::$conToABV->getRecentCommentsForMessage($project_id, (int)$message_id);
        }
        static function getItem ($settings, $item) {
            if ( ! isset (self::$conToABV))
                self::$conToABV = new libApiBaseView($settings);
            return self::$conToABV->getTodoItem($item);
        }        
        static function getRecentCommentsForItem ($project_id, $item_id) {
            return self::$conToABV->getRecentCommentsForItem($project_id, (int)$item_id);
        }
    }
?>
