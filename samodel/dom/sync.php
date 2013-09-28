<?php
    namespace wsm\samodel\dom;
    
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/base/registry.php");
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/samodel/dom/baseDatainstall.php");
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/samodel/apiBaseCamp/libApiBaseView.php");
    
    
    class sync extends baseDataInstall {
        private static $instance;
        public $settings;
        static private $projectId;
        
        private function __construct() {}
        
        static function instance() {
            if ( ! isset(self::$instance) ) { self::$instance = new self(); }
            return self::$instance;
        }
        
        static function syncMessage () {
            self::$instance = self::instance();
            self::$instance->settings = \wsm\base\campDSN::$HC;
            $id = \wsm\base\ViewData::getInitData();
            self::$projectId = $id['project_id'];
            $messages = \wsm\samodel\apiBaseCamp\formView::getMessages(self::$instance->settings, self::$projectId);
            self::$instance->diffMessage($messages);
        }
        
        static function syncToDo () {
            self::$instance = self::instance();
            self::$instance->settings = \wsm\base\campDSN::$HC;
            $id = \wsm\base\ViewData::getInitData();
            self::$projectId = $id['project_id'];
            $todo = \wsm\samodel\apiBaseCamp\formView::getToDoLists(self::$instance->settings);
            self::$instance->diffToDo($todo);
        }
        
        private static function syncCommentMessage ($message_id) {
            $comments = \wsm\samodel\apiBaseCamp\formView::getRecentCommentsForMessage(self::$initData['project_id'], $message_id);
            if ($comments != null) {
                foreach ($comments as $com) {
                        self::$instance->insertNewComment($com);
                        self::$instance->insertCommentsMessagesIDs($message_id, $com->id);
                }
            }
        }
        
        private static function syncCommentItem ($item_id) {
            $comments = \wsm\samodel\apiBaseCamp\formView::getRecentCommentsForItem(self::$initData['project_id'], $item_id);
            if ($comments != null) {
                foreach ($comments as $com) {
                        self::$instance->insertNewComment($com);
                        self::$instance->insertCommentsItemsIDs($item_id, $com->id);
                }
            }
        }
        
        protected function diffMessage ($messages) {
            $databaseMessage = self::$instance->selectIdDatabaseMessages();
            if (empty($databaseMessage)) {
                foreach ($messages as $pt => $value) {
                    $newMessage = array('project_id' => self::$initData['project_id'],
                                        'message_id' => (int)$value->id,
                                        'message_title' => (string)$value->title,
                                        'message_body' => (string)$value->body);
                    self::syncCommentMessage($value->id);
                    self::$instance->insertNewMessage($newMessage);
                }
            } else {
                foreach ($messages as $pt => $value) {
                    $state = false;                    
                    foreach ($databaseMessage as $st) {
                        if ($st == $value['id'])
                          $state = true;  
                    }
                    if ($state == false) {
                        $newMessage = array('project_id' => self::$initData['project_id'],
                                            'message_id' => $value->id,
                                            'message_title' => $value->title,
                                            'message_body' => $value->body);
                        self::syncCommentMessage($value->id);
                        self::$instance->insertNewMessage($newMessage);
                    }
                }
            }
        }
        
        private function diffToDo ($todos) {
            $databaseToDo = self::$instance->selectIdDatabaseToDo();
            if ( empty($databaseToDo) ) {
                foreach ($todos as $pt => $value) {
                    $newToDo = array('project_id' => self::$initData['project_id'],
                                        'to_do_list_id' => $value->id,
                                        'to_do_list_name' => $value->name,
                                        'to_do_list_description' => $value->description);
                    self::$instance->insertNewToDo($newToDo);
                    self::$instance->diffToDoItems($value->id);
                }
            } else {
                foreach ($todos as $pt => $value) {
                    $state = false;                    
                    foreach ($databaseToDo as $st) {
                        if ($st == $value->id)
                          $state = true;  
                    }
                    if ($state == false) {
                        $newToDo = array('project_id' => self::$initData['project_id'],
                                            'to_do_list_id' => $value->id,
                                            'to_do_list_name' => $value->name,
                                            'to_do_list_description' => $value->description);
                        self::$instance->insertNewToDo($newToDo);
                        self::$instance->diffToDoItems($value->id);
                    }
                }
            }
        }
        
        private function diffToDoItems ($todo_id) {
            $databaseToItems = self::$instance->selectIdItems((int)$todo_id);
            $items = \wsm\samodel\apiBaseCamp\formView::getTodoItems(self::$instance->settings, $todo_id);
            if ( empty($databaseToDo) ) {
                foreach ($items as $it) {
                    $newItems = array ( 'project_id' => self::$initData['project_id'],
                                        'id' => (int)$it->id,
                                        'content' => (string)$it->content,
                                        'to_do_list_id' => (int)$todo_id,
                                        'comments_count' => $it->{"comments-count"},
                                        'completed' => (string)$it->completed);
                    self::$instance->syncCommentItem((int)$it->id);
                    self::$instance->insertNewItem($newItems);
                }
            } else {
                foreach ($items as $it) {
                    $state = false;
                    foreach ($databaseToItems as $st) {
                        if ($st == $it->id)
                          $state = true;  
                    }
                    if ( $state == false ) {
                        $newItems = array ( 'project_id' => self::$initData['project_id'],
                                        'id' => (int)$it->id,
                                        'content' => (string)$it->content,
                                        'to_do_list_id' => (int)$todo_id,
                                        'comments_count' => $it->{"comments-count"},
                                        'completed' => (string)$it->completed);
                        self::$instance->syncCommentItem((int)$it->id);
                        self::$instance->insertNewItem($newItems);
                    }
                }
            }
        }
    }
?>
