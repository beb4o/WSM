<?php
    namespace wsm\controller;
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/base/registry.php");
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/controller/routeController.php");
    
    class actionController {
        
        private static $instance;
        
        
        private function __construct() {}
        
        static function instance() {
            if ( ! isset(self::$instance) ) { self::$instance = new self(); }
            return self::$instance;
        }
        
        static function getAction () {
            $listRegistry = \wsm\base\RequestRegistry::getRequest();
            if ( ! isset ($listRegistry->properties[2]) )
                return array ("view");
            else {
                return array (substr($listRegistry->properties[1], 1), $listRegistry->properties[2]);
            }
        }
        
        static function doAction (array $actionData) {
            if ( count($actionData) == 1) {
                require_once $_SERVER['DOCUMENT_ROOT'].('/wsm/samodel/dom/'.$actionData[0].'Main.php');
            } else {
                require_once $_SERVER['DOCUMENT_ROOT'].('/wsm/samodel/dom/'.$actionData[0].$actionData[1].'.php');
            } 
        }
    }
?>
