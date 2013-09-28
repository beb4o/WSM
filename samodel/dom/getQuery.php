<?php
    namespace wsm\samodel\dom;
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/samodel/apiBaseCamp/libApiBaseView.php");
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/samodel/dom/baseDatainstall.php");
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/base/registry.php");
    
    class getQuery extends baseDataInstall {
        
        
        public $info;
        
        function __construct($commandQuery) {
            $this->info = $this->$commandQuery();            
        }
        
        private function viewall () {
            $arrayMessage = $this->selectMessages();
            $arrayMessage = array_reverse($arrayMessage);
            $this->info['message'] = $arrayMessage;
            $this->info['todo'] = $this->selectToDoLists();
        }
        
        private function viewitems () {
            return $this->selectItems(\wsm\base\ViewData::getNo());
        }
        
        private function viewmessage () {
            return $this->selectMessages();
        }
        
        private function viewtodolists() {
            return $this->selectToDoLists();
        }
        
        private function todoliststodo () {            
            $mc = array();
            $nc = array();
            $mc['items']  = $this->selectItemsByIdTodo(\wsm\base\ViewData::getNo());
            return $mc;
        }
        
        private function itemcomment() {
            $mc = array();
            $nc = array();
            $mc['item_data']  = $this->selectItemById(\wsm\base\ViewData::getNo());
            $nc['comments_id']   = $this->selectItemCommentById(\wsm\base\ViewData::getNo());
            $mc['comments_data'] = $this->selectCommentData($nc['comments_id']);
            return $mc;
        }
        
        private function messagecomment() {
            $mc = array();
            $nc = array();
            $mc['message_data']  = $this->selectMessageById(\wsm\base\ViewData::getNo());
            $nc['comments_id']   = $this->selectMessageCommentById(\wsm\base\ViewData::getNo());
            $mc['comments_data'] = $this->selectCommentData($nc['comments_id']);
            return $mc;
        }        
    }
?>
