<?php
    
    namespace wsm\controller;
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/base/registry.php");
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/controller/request.php");
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/controller/actionController.php");
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/controller/routeController.php");
    
    
    class Controller {
        private $request;
        
        private function __construct() {}
        
        static function run () {
           $instance = new Controller();
           $instance->handleRequest();
        }
        
        function handleRequest() {
            
            $this->request = \wsm\base\RequestRegistry::getRequest();
            if ( is_null ( $this->request ) ) {
                $this->request = new Request();
            }
            \wsm\base\campDSN::getcampDSN();
            \wsm\samodel\dom\baseDataInstall::initInstall();
            $page = \wsm\controller\routeController::getResponsePage();            
            $getAction = \wsm\controller\actionController::getAction();
            \wsm\controller\actionController::doAction($getAction);
            $this->prepareView($page);
            
        }
        
        function prepareView ($target) {
            if (! empty($target))
                include( $_SERVER['DOCUMENT_ROOT']."/wsm/view/".$target.".php" );
            exit;
        }
    }
?>
