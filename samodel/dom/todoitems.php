<?php
    namespace wsm\samodel\dom;
    //require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/samodel/apiBaseCamp/libApiBaseView.php");
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/base/registry.php");
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/samodel/dom/getQuery.php");
    
    class viewItems {
        
        public $queryView;
        public $query = 'viewitems';
        
        public function __construct() {
            $ob = new \wsm\samodel\dom\getQuery($this->query);
            $this->queryView['items'] = $ob->info;
            $this->queryView['list_id'] = \wsm\base\ViewData::getNo();
            \wsm\base\viewData::setPageViewData($this->queryView);
        }       
    }
    new viewItems();
?>
