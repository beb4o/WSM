<?php
    
    namespace wsm\controller;
    require_once $_SERVER['DOCUMENT_ROOT'].( "/wsm/base/registry.php");
    
    class routeController {
        
        private static $instance;
        public static $error = 'error';
        
        private function __construct() {}
        
        static function instance() {
            if ( ! isset(self::$instance) ) { self::$instance = new self(); }
            return self::$instance;
        }
        
        static function getResponsePage() {
                return $target = self::instance()->getTarget();
        }

        protected function getTarget() {
            $listRegistry = \wsm\base\RequestRegistry::getRequest();
            $page = $listRegistry->properties[1];
            if ( $page == "?" || $page == NULL || $page == "index.php")
                return $page = "main";
            if ( array_key_exists(substr($page, 1), \wsm\base\controllerArray::$controllerArray) ) {
                if ( isset ($listRegistry->properties[2]) ) {
                    $do = $listRegistry->properties[2];
                    if ($do == 'update')
                        $do = 'view';
                    $newpage = substr($page, 1).$do;
                }
                if (isset ($listRegistry->properties[3])) {
                    \wsm\base\ViewData::setNo($listRegistry->properties[3]);
                }
                return $newpage;
            }
            else
                require_once $_SERVER['DOCUMENT_ROOT'].('/wsm/404.php');
        }
    }
?>
