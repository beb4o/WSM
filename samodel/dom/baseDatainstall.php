<?php
    namespace wsm\samodel\dom;
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/base/registry.php");
    
    class baseDataInstall {

        private static $instance;
        static $initData = array();
        private static $db;

        private function __construct() {}

        private static function instance() {
            if ( ! isset(self::$instance) ) { self::$instance = new self(); }
            return self::$instance;
        }

        static function initInstall () {
            self::instance();            
            self::$db = \wsm\base\DSN::$DBH;
            self::$instance->getProjectId();
            self::$instance->getCompanyName();
            self::$instance->getProjectInfo();            
        }
         
        private function getProjectId () {
            $row = self::$db->query("SELECT project_id FROM projects;")->fetch();
            self::$initData['project_id'] = $row['project_id'];
        }
        
        private function getProjectInfo() {
            $stmt = self::$db->prepare("SELECT project_name, status FROM projects WHERE project_id = :project_id");
            $stmt->bindParam(':project_id', self::$initData['project_id']);
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            self::$initData['project_name'] = $result[0]['project_name'];
            self::$initData['status']       = $result[0]['status'];
         }
        
        protected function getUserId () {
            
            $stmt = self::$db->prepare("SELECT user_id FROM projects WHERE project_id = :project_id");
            $stmt->bindParam(':project_id', self::$initData['project_id']);
            $stmt->execute();
            while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $id = $result['user_id'];
            }
            return $id;
        }
        
        private function getCompanyName () {
            $row = self::$db->query("SELECT company_name FROM companies;")->fetch();
            self::$initData['company_name'] = $row['company_name'];
        }

        protected function getLastMessageId () {
            $arrayId = $this->selectIdDatabaseMessages();
            $lastId = array_pop($arrayId);
            return $lastId;
        }
        
        protected function selectMessages () {
            $data = array();
            $stmt = self::$db->prepare("SELECT message_id, message_title, message_body FROM messages WHERE project_id = :project_id");
            $stmt->bindParam(':project_id', self::$initData['project_id']);
            $stmt->execute();
            while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $data[$result['message_id']] = array('message_title' => $result['message_title'],
                                                     'message_body'  => $result['message_body']);                
            }
            return $data;
        }
        
        protected function selectToDoLists () {
            $data = array();
            $stmt = self::$db->prepare("SELECT to_do_list_id, to_do_list_name, to_do_list_description FROM to_do_list WHERE project_id = :project_id");
            $stmt->bindParam(':project_id', self::$initData['project_id']);
            $result = $stmt->execute();
            while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $data[$result['to_do_list_id']] = array ('to_do_list_name'          => $result['to_do_list_name'],
                                                         'to_do_list_description'   => $result['to_do_list_description']);
            }
            return $data;
        }
        
        protected function selectIdDatabaseMessages () {
            $stmt = self::$db->prepare("SELECT message_id FROM messages WHERE project_id = :project_id;");
            $stmt->bindParam(':project_id', self::$initData['project_id']);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        }
        
        protected function selectIdDatabaseToDo () {
            $stmt = self::$db->prepare("SELECT to_do_list_id FROM to_do_list WHERE project_id = :project_id;");
            $stmt->bindParam(':project_id', self::$initData['project_id']);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        }
        
        protected function selectItemsByIdTodo($id) {
            $data = array();
            $stmt = self::$db->prepare("SELECT item_id, item_name FROM items_to_do_list WHERE to_do_list_id = :to_do_list_id");
            $stmt->bindParam(':to_do_list_id', $id);
            $result = $stmt->execute();
            while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $data[$result['item_id']] = array ('item_name' => $result['item_name']);
            }
            return $data;
        }
                
        protected function selectIdItems($todo_id) {
            $stmt = self::$db->prepare("SELECT item_id FROM items_to_do_list WHERE to_do_list_id = :to_do_list_id");
            $stmt->bindParam(':to_do_list_id', $todo_id);
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $data;
        }
        
        protected function selectItemByIdFor ($item_id) {
            $data = array();
            $stmt = self::$db->prepare("SELECT item_id, item_name, comments_count, completed FROM items_to_do_list WHERE item_id = :item_id");
            $stmt->bindParam(':item_id', $item_id);
            $result = $stmt->execute();
            while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $data[] = array ('item_name' => $result['item_name'],
                                  'comments_count' => $result['comments_count'],
                                   'completed' => $result['completed']);
            }
            return $data;
        }
        
        protected function selectItemById ($item_id) {
            $data = array();
            $stmt = self::$db->prepare("SELECT item_id, item_name FROM items_to_do_list WHERE item_id = :item_id");
            $stmt->bindParam(':item_id', $item_id);
            $result = $stmt->execute();
            while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $data[$result['item_id']] = array ('item_name' => $result['item_name']);
            }
            return $data;
        }
        
        protected function selectItemCommentById ($item_id) {
            $stmt = self::$db->prepare("SELECT comment_id FROM comments_to_items WHERE item_id = :item_id");
            $stmt->bindParam(':item_id', $item_id);
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $data;
        }
        
        protected function selectMessageById ($message_id) {
            $data = array();
            $stmt = self::$db->prepare("SELECT message_title, message_body FROM messages WHERE message_id = :message_id");
            $stmt->bindParam(':message_id', $message_id);
            $result = $stmt->execute();
            while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $data[] = array ('message_title' => $result['message_title'],
                                 'message_body' => $result['message_body']);
            }
            return $data;
        }
        
        protected function selectMessageCommentById ($message_id) {
            $stmt = self::$db->prepare("SELECT comment_id FROM comments_to_message WHERE message_id = :message_id");
            $stmt->bindParam(':message_id', $message_id);
            $stmt->execute();
            $data = $stmt->fetchAll();
            return $data;
        }
        
        protected function selectCommentData (array $param) {
            $data = array();
            foreach ($param as $p) {
                $stmt = self::$db->prepare("SELECT comment_id, comment_body FROM comments WHERE comment_id = :comment_id");
                $stmt->bindParam(':comment_id', $p['comment_id']);
                $stmt->execute();
                while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    $data[$result['comment_id']] = array ('comment_body' => $result['comment_body']);
                }
            }
            return $data;
        }
        
        protected function selectItems ($todo_id) {
            $data = array();
            $stmt = self::$db->prepare("SELECT item_id, item_name FROM items_to_do_list WHERE to_do_list_id = :to_do_list_id");
            $stmt->bindParam(':to_do_list_id', $todo_id);
            $stmt->execute();
            while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $data[$result['item_id']] = array('item_name' => $result['item_name']);                
            } 
            return $data;
        }
        
        protected function insertCreateMessage ($message_id, $message) {
            
            $stmt = self::$db->prepare("INSERT INTO messages (project_id, message_id, message_title, message_body) VALUES (:project_id, :message_id, :message_title, :message_body)");
            $stmt->bindParam(':project_id',     $message['project_id']);
            $stmt->bindParam(':message_id',     $message_id);
            $stmt->bindParam(':message_title',  $message['Title']);
            $stmt->bindParam(':message_body',   $message['message']);
            $stmt->execute();
        }


        protected function insertNewMessage ($message) {
            $stmt = self::$db->prepare("INSERT INTO messages (project_id, message_id, message_title, message_body) VALUES (:project_id, :message_id, :message_title, :message_body)");
            $stmt->bindParam(':project_id',     self::$initData['project_id']);
            $stmt->bindParam(':message_id',     $message['message_id']);
            $stmt->bindParam(':message_title',  $message['message_title']);
            $stmt->bindParam(':message_body',   $message['message_body']);
            $stmt->execute();
        }
        
        protected function insertCreateToDo ($todo) {
            $stmt = self::$db->prepare("INSERT INTO to_do_list (project_id, to_do_list_id, to_do_list_name, to_do_list_description) VALUES (:project_id, :to_do_list_id, :to_do_list_name, :to_do_list_description)");
            $stmt->bindParam(':project_id',     $todo['project_id']);
            $stmt->bindParam(':to_do_list_id',  $todo['id']);
            $stmt->bindParam(':to_do_list_name',$todo['Title']);
            $stmt->bindParam(':to_do_list_description', $todo['description']);            
            $stmt->execute();
        }
        
        protected function insertNewToDo ($todo) {
            $stmt = self::$db->prepare("INSERT INTO to_do_list (project_id, to_do_list_id, to_do_list_name, to_do_list_description) VALUES (:project_id, :to_do_list_id, :to_do_list_name, :to_do_list_description)");
            $stmt->bindParam(':project_id',     $todo['project_id']);
            $stmt->bindParam(':to_do_list_id',  $todo['to_do_list_id']);
            $stmt->bindParam(':to_do_list_name',$todo['to_do_list_name']);
            $stmt->bindParam(':to_do_list_description', $todo['to_do_list_description']);            
            $stmt->execute();
        }
        
        protected function insertCreateComment ($comment_id, $insertData) {
            $stmt = self::$db->prepare("INSERT INTO comments (project_id, comment_id, comment_body) VALUES (:project_id, :comment_id, :comment_body)");
            $stmt->bindParam(':project_id', self::$initData['project_id']);
            $stmt->bindParam(':comment_id', $comment_id);
            $stmt->bindParam(':comment_body', $insertData['comment_body']);
            $stmt->execute();
        }
        
        protected function insertNewComment ($insertData) {
            $stmt = self::$db->prepare("INSERT INTO comments (project_id, comment_id, comment_body) VALUES (:project_id, :comment_id, :comment_body)");
            $stmt->bindParam(':project_id', self::$initData['project_id']);
            $stmt->bindParam(':comment_id', $insertData->id);
            $stmt->bindParam(':comment_body', $insertData->body);
            $stmt->execute();
        }
        
        protected  function insertNewItem ($insertData) {
            $stmt = self::$db->prepare("INSERT INTO items_to_do_list (project_id, item_id, to_do_list_id, item_name, comments_count, completed) VALUES (:project_id, :item_id, :to_do_list_id, :item_name, :comments_count, :completed)");
            $stmt->bindParam(':project_id',     $insertData['project_id']);
            $stmt->bindParam(':item_id',        $insertData['id']);
            $stmt->bindParam(':to_do_list_id',  $insertData['to_do_list_id']);
            $stmt->bindParam(':item_name',      $insertData['content']);
            $stmt->bindParam(':comments_count', $insertData['comments_count']);
            $stmt->bindParam(':completed',      $insertData['completed']);
            $stmt->execute();
        }
        
        protected function insertCommentsMessagesIDs($message_id, $comment_id) {
            $stmt = self::$db->prepare("INSERT INTO comments_to_message (message_id, comment_id) VALUES (:message_id, :comment_id)");
            $stmt->bindParam(':message_id', $message_id);
            $stmt->bindParam(':comment_id', $comment_id);
            $stmt->execute();
        }
                
        protected function insertCommentsItemsIDs ($item_id, $comment_id) {
            $stmt = self::$db->prepare("INSERT INTO comments_to_items (item_id, comment_id) VALUES (:item_id, :comment_id)");
            $stmt->bindParam(':item_id',    $item_id);
            $stmt->bindParam(':comment_id', $comment_id);
            $stmt->execute();
        }
        
        protected function insertCreateItem ($item_id, $message) {
            $completed = 'false';
            $stmt = self::$db->prepare("INSERT INTO items_to_do_list (project_id, item_id, to_do_list_id, item_name, completed) VALUES (:project_id, :item_id, :to_do_list_id, :item_name, :completed)");
            $stmt->bindParam(':project_id',    $message['project_id']);
            $stmt->bindParam(':item_id',       $item_id);
            $stmt->bindParam(':to_do_list_id', $message['list_item']);
            $stmt->bindParam(':item_name',     $message['message']);
            $stmt->bindParam(':completed',     $completed);
            $stmt->execute();
        }
        
        protected function updateItemInfoCount ($item_id) {
            $stmt = self::$db->prepare("UPDATE items_to_do_list SET comments_count = comments_count + 1 WHERE item_id = :item_id");
            $stmt->bindParam('item_id', $item_id);
            $stmt->execute();
        }
        
        protected function downCommentItem ($item_id) {
            $stmt = self::$db->prepare("UPDATE items_to_do_list SET comments_count = comments_count - 1 WHERE item_id = :item_id");
            $stmt->bindParam('item_id', $item_id);
            $stmt->execute();
        }
        
        protected function deleteCommentFromBase ($comment_id) {
            $stmt = self::$db->prepare("DELETE FROM comments_to_message WHERE comment_id = :comment_id");
            $stmt->bindParam('comment_id', $comment_id);
            $stmt->execute();
            $stmt = self::$db->prepare("DELETE FROM comments WHERE comment_id = :comment_id");
            $stmt->bindParam('comment_id', $comment_id);
            $stmt->execute();
        }
        
        protected function deleteItemFromBase ($item_id) {
            $stmt = self::$db->prepare("DELETE FROM  items_to_do_list WHERE item_id = :item_id");
            $stmt->bindParam('item_id', $item_id);
            $stmt->execute();
            $stmt = self::$db->prepare("DELETE FROM  comments_to_items WHERE item_id = :item_id");
            $stmt->bindParam('item_id', $item_id);
            $stmt->execute();
        }
    }
    
?>
