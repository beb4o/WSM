<?php
    namespace wsm\samodel\dom;
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/samodel/apiBaseCamp/libApiBaseCreate.php");
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/samodel/apiBaseCamp/libApiBaseView.php");
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/base/registry.php");
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/samodel/dom/baseDatainstall.php");

    class postQuery {

        private $type;
        private $queryToBC;

        function __construct ($dataQuery) {
            $this->type      = $dataQuery['type'];
            $this->handleRequest($dataQuery);
        }

        private function handleRequest ($dataQuery) {
            $className = $this->type;
            switch ($className) {
                case 'commentmessage': new \wsm\samodel\dom\commentmessage($dataQuery);
                    break;
                case 'createmessage': new \wsm\samodel\dom\createmessage($dataQuery);
                    break;
                case 'todolist': new \wsm\samodel\dom\todolist($dataQuery); 
                    break;
                case 'itemcomment': new \wsm\samodel\dom\itemcomment($dataQuery); 
                    break;
                case 'deletemessagecomment': new \wsm\samodel\dom\deletemessagecomment($dataQuery);
                    break;
                case 'deleteitemcomment': new \wsm\samodel\dom\deleteitemcomment($dataQuery);
                    break;
                case 'deletetodoitem': new \wsm\samodel\dom\deletetodoitem($dataQuery);
                    break;
                case 'createitem': new \wsm\samodel\dom\createitem($dataQuery);
                    break;
            }
        }   
    }
    
    class deletetodoitem extends baseDataInstall {
        static $settings;
        static $queryBasecamp;
        static $feedBack;
        
        public function __construct($data) {
            if ( ! isset ( self::$settings ))
                self::$settings = \wsm\base\campDSN::$HC;
             $this->deleteitem($data);
        }
        
        private function deleteitem ($data) {
            self::$queryBasecamp = new \wsm\samodel\apiBaseCamp\Basecamp(self::$settings['baseurl'], self::$settings['user'], self::$settings['password']);
            self::$queryBasecamp->deleteTodoItem($data['item_id']);
            $this->deleteItemFromBase($data['item_id']);
        }
    }
    
    class createitem extends baseDataInstall {
        static $settings;
        static $queryBasecamp;
        static $feedBack;
        
        public function __construct($data) {
            if ( ! isset ( self::$settings ))
                self::$settings = \wsm\base\campDSN::$HC;
             $data['user_id'] = $this->getUserId();
             $this->createitem($data);
        }
        
        private function createitem($data) {
            self::$queryBasecamp = new \wsm\samodel\apiBaseCamp\Basecamp((string)self::$settings['baseurl'], self::$settings['user'], self::$settings['password']);
            $message_query = self::$queryBasecamp->createTodoItemForList((int)$data['list_item'], $data['message'], 'person', $data['user_id']);
            if ($message_query['status'] == 201) {
                $item_id = $message_query['id'];
                self::$feedBack = "Your item has been added.";
                \wsm\base\viewData::setFeedBack(self::$feedBack);
                $this->insertCreateItem($item_id, $data);
            } else {
                self::$feedBack = "I now, it's very annoying, but try again.";
                \wsm\base\viewData::setFeedBack(self::$feedBack);
            }
        }
    }
    
    
    class deleteitemcomment extends baseDataInstall {
        static $settings;
        static $queryBasecamp;
        static $feedBack;
        
        public function __construct($data) {
            if ( ! isset ( self::$settings ))
                self::$settings = \wsm\base\campDSN::$HC;
             $this->deletecomment($data);
        }
        
        private function deletecomment ($data) {
            self::$queryBasecamp = new \wsm\samodel\apiBaseCamp\Basecamp(self::$settings['baseurl'], self::$settings['user'], self::$settings['password']);
            self::$queryBasecamp->deleteComment($data['comment_id']);
            $this->deleteCommentFromBase($data['comment_id']);
            $this->downCommentItem($data['item_id']);
        }
    }
    
    
    class deletemessagecomment extends baseDataInstall {
        static $settings;
        static $queryBasecamp;
        static $feedBack;
        
        public function __construct($data) {
            if ( ! isset ( self::$settings ))
                self::$settings = \wsm\base\campDSN::$HC;
             $this->deletecomment($data);
        }
        
        private function deletecomment ($data) {
            self::$queryBasecamp = new \wsm\samodel\apiBaseCamp\Basecamp(self::$settings['baseurl'], self::$settings['user'], self::$settings['password']);
            self::$queryBasecamp->deleteComment($data['comment_id']);
            $this->deleteCommentFromBase($data['comment_id']);
        }
    }
    
    
    class itemcomment extends baseDataInstall {
        
        static $settings;
        static $queryBasecamp;
        static $feedBack;
        
        
        public function __construct($data) {
            if ( ! isset ( self::$settings ))
                self::$settings = \wsm\base\campDSN::$HC;
             $this->itemcomment($data);
        }
        
        private function isChangeItem ($item) {
            $arrayItem = \wsm\samodel\apiBaseCamp\formView::getItem(self::$settings, $item);
            foreach ($arrayItem as $pt => $k) {
                if ($pt == 'comments-count') {
                    $settingsItem['comments-count'] = (int)$k;
                } elseif ($pt == 'completed') {
                    $settingsItem['completed'] = (string)$k;
                } elseif ($pt == 'content') {
                    $settingsItem['content'] = (string)$k;                    
                }
            }            
            $arrayItemFromBase = $this->selectItemByIdFor($item);
            $arrayItemFromBase = $arrayItemFromBase[0];
            $diff = array_diff($settingsItem, $arrayItemFromBase);
            
            if ( empty($diff) ) {
                $state = 'item';
            } else {
                $state = 'message';
            }
            return $state;
        }
        
        private function itemcomment ($data) {
            self::$queryBasecamp = new \wsm\samodel\apiBaseCamp\Basecamp(self::$settings['baseurl'], self::$settings['user'], self::$settings['password']);
            $change = $this->isChangeItem($data['item_id']);
            if ($change == 'item') {
                $message_query = self::$queryBasecamp->createCommentForItem('todo_items', $data['item_id'], $data['comment_body']);
                if ($message_query['status'] == 201) {
                        $comment_id = $message_query['id'];
                        self::$feedBack = "Your comment has been added.";
                        $this->insertCreateComment($comment_id, $data);
                        $this->updateItemInfoCount($data['item_id']);
                        $this->insertCommentsItemsIDs($data['item_id'], $comment_id);
                }
            } else {
                self::$feedBack = "Don't worry, you comment posted at last message. Why? This is will of master.";
                \wsm\base\viewData::setFeedBack(self::$feedBack);
                $last_id = $this->getLastMessageId();
                $data['message_id'] = $last_id[0];
                new commentmessage($data);
            }
        }
    }
                      
    class todolist extends baseDataInstall {
        
        static $settings;
        static $queryBasecamp;
        static $feedBack;
        
        function __construct($data) {
             if ( ! isset ( self::$settings ))
                self::$settings = \wsm\base\campDSN::$HC;
             if ( ! isset ( self::$queryBasecamp ) )
                self::$queryBasecamp = new \wsm\samodel\apiBaseCamp\Basecamp(self::$settings['baseurl'], self::$settings['user'], self::$settings['password']);
            $this->createtodolist($data);
        }
        
        
        private function createtodolist ($data) {
            self::$queryBasecamp = new \wsm\samodel\apiBaseCamp\Basecamp(self::$settings['baseurl'], self::$settings['user'], self::$settings['password']);
            $message_query = self::$queryBasecamp->createTodoListForProject((int)$data['project_id'], $data['Title'], $data['description']);
            if ($message_query['status'] == 201) {
                $todo_id = $message_query['id'];
                $data['id'] = $todo_id;
                self::$feedBack = "To-Do has been created.";
                \wsm\base\viewData::setFeedBack(self::$feedBack);
                $this->insertCreateToDo($data);
            } else {
                self::$feedBack = "I now, it's very annoying, but try again.";
                \wsm\base\viewData::setFeedBack(self::$feedBack);
            }
        }
    }
    
    
    class commentmessage extends baseDataInstall {
        static $settings;
        static $queryBasecamp;
        static $feedBack;
        
        function __construct($data) {
             if ( ! isset ( self::$settings ))
                self::$settings = \wsm\base\campDSN::$HC;
             if ( ! isset ( self::$queryBasecamp ) )
                self::$queryBasecamp = new \wsm\samodel\apiBaseCamp\Basecamp(self::$settings['baseurl'], self::$settings['user'], self::$settings['password']);
            $this->commentmessage($data);
        }
        
        private function commentmessage ($data) {
            
            self::$queryBasecamp = new \wsm\samodel\apiBaseCamp\Basecamp(self::$settings['baseurl'], self::$settings['user'], self::$settings['password']);
            $message_query = self::$queryBasecamp->createCommentForMessage('posts', $data['message_id'], $data['comment_body']);
            if ($message_query['status'] == 201) {
                $comment_id = $message_query['id'];
                self::$feedBack = "Your comment has been added.";
                $this->insertCreateComment($comment_id, $data);
                $this->insertCommentsMessagesIDs($data['message_id'], $comment_id);
            } else {
                self::$feedBack = "I now, it's very annoying, but try again.";
                \wsm\base\viewData::setFeedBack(self::$feedBack);
            }
        }
    }
    
    class createmessage extends baseDataInstall {
        
        static $queryBasecamp;
        public $message_id;
        static $feedBack;
        static $settings;
        
        public function __construct($data) {
            if ( ! isset ( self::$settings ))
                self::$settings = \wsm\base\campDSN::$HC;
            if ( ! isset ( self::$queryBasecamp ) )
                self::$queryBasecamp = new \wsm\samodel\apiBaseCamp\Basecamp(self::$settings['baseurl'], self::$settings['user'], self::$settings['password']);
            $this->createmessage($data);
        }
        
        private function createmessage ($data) {
            
            $dataInit = \wsm\base\ViewData::getInitData();
            $project_id = $dataInit['project_id'];
            $message_query = self::$queryBasecamp->createMessageForProject($project_id, $data['Title'], $data['message']);
            if ($message_query['status'] == 201) {
                $message_id = $message_query['id'];
                self::$feedBack = "Your message has been added.";
                \wsm\base\viewData::setFeedBack(self::$feedBack);
                $this->insertCreateMessage($message_id, $data);
            } else {
                self::$feedBack = "I now, it's very annoying, but try again.";
                \wsm\base\viewData::setFeedBack(self::$feedBack);
            }
        }
    }
    
?>
