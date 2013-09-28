<?php
    namespace wsm\controller;
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/base/registry.php");
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/samodel/dom/baseDatainstall.php");
    
    class Request {
        
        public $properties = array();

        function __construct() {
            $this->init();
            \wsm\base\RequestRegistry::setRequest($this);
        }

        function init() {
            
            if ( is_null (\wsm\base\controllerArray::$controllerArray) && ! isset (\wsm\base\DSN::$instance) )  {
                \wsm\base\controllerArray::setControllerArray();
                \wsm\base\campDSN::getcampDSN();
                \wsm\base\DSN::getDSN();
                \wsm\samodel\dom\baseDataInstall::initInstall();
                \wsm\base\ViewData::setInitData(\wsm\samodel\dom\baseDataInstall::$initData);
            }

            if ( !empty($_POST) ) {
                require_once $_SERVER['DOCUMENT_ROOT'].'/wsm/samodel/dom/postQuery.php';
                new \wsm\samodel\dom\postQuery($_POST);
            }
            
            if ( $_SERVER['REQUEST_URI'] ) {
                $prerequest = explode('/', $_SERVER['REQUEST_URI']);
                $none = "";
                $request = array();
                foreach ($prerequest as $req) {                            
                    if ($req !== $none) {
                        $this->properties[] = $req; 
                    }
                }
                return;
            }
        }
    }
?>
