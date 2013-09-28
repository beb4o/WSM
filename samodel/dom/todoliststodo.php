<?php
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/base/registry.php");
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/samodel/dom/getQuery.php");
    
    class todoListsView {
        
        public $queryView;
        public $query = 'todoliststodo';
        
        public function __construct() {
            $ob = new \wsm\samodel\dom\getQuery($this->query);
            $this->queryView = $ob->info;
            \wsm\base\viewData::setPageViewData($this->queryView);
        }       
    }
    new todoListsView();
?>
