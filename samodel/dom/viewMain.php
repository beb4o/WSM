<?php
namespace wsm\samodel\dom;
require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/samodel/apiBaseCamp/libApiBaseView.php");
require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/samodel/dom/baseDatainstall.php");
require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/base/registry.php");


class getviewMain {
    
    public $info = array();
    public $queryView;
    public $query = 'viewall';
    
    public function __construct() {
        
    }    
}

new getviewMain();
?>
