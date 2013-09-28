<?php
    namespace wsm\samodel\dom;
    //require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/samodel/apiBaseCamp/libApiBaseView.php");
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/base/registry.php");
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/samodel/dom/getQuery.php");
    
    class viewMessages {
        
        public $queryView;
        public $query = 'viewmessage';
        
        public function __construct() {
            $ob = new \wsm\samodel\dom\getQuery($this->query);
            $this->queryView = $ob->info;
            \wsm\base\viewData::setPageViewData($this->queryView);
        }       
    }
    new viewMessages();
?>
