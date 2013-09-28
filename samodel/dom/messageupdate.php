<?php
    namespace wsm\samodel\dom;
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/samodel/dom/sync.php");
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/samodel/dom/getQuery.php");
    
    class messageUpdate {
        
        public $query = 'viewmessage';
        
        function __construct() { 
            \wsm\samodel\dom\sync::syncMessage();
            $ob = new \wsm\samodel\dom\getQuery($this->query);
            $this->queryView = $ob->info;
            \wsm\base\viewData::setPageViewData($this->queryView);
        }
    }
    new messageUpdate();
?>
