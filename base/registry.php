<?php
    namespace wsm\base;
    
    abstract class Registry {
        abstract protected function get( $key );
        abstract protected function set( $key, $val );
    }
    
    class RequestRegistry extends Registry {
        private $values = array();
       
        private static $instance;

        private function __construct() {}
        static function instance() {
            if ( ! isset(self::$instance) ) { self::$instance = new self(); }
            return self::$instance;
        }

        protected function get( $key ) {
            if ( isset( $this->values[$key] ) ) {
                return $this->values[$key];
            }
            return null;
        }

        protected function set( $key, $val ) {
            $this->values[$key] = $val;
        }

        static function getRequest() {
            return self::instance()->get('request');
        }
        
        static function setRequest( \wsm\controller\Request $request ) {
            return self::instance()->set('request', $request );
        }   
    }
    
    class ViewData extends Registry {
        private $values = array();
       
        private static $instance;

        private function __construct() {}
        static function instance() {
            if ( ! isset(self::$instance) ) { self::$instance = new self(); }
            return self::$instance;
        }

        protected function get( $key ) {
            if ( isset( $this->values[$key] ) ) {
                return $this->values[$key];
            }
            return null;
        }

        protected function set( $key, $val ) {
            $this->values[$key] = $val;
        }

        static function getInitData() {
            return self::instance()->get('init');
        }
        
        static function setInitData( array $initData ) {
            return self::instance()->set('init', $initData );
        }
        
        static function setFeedBack( $feedBack ) {
            return self::instance()->set('feedBack', $feedBack );
        }
        
        static function getFeedBack() {
            return self::instance()->get('feedBack');
        }
        
        static function setPageViewData( $pageViewData ) {
            return self::instance()->set('pageViewData', $pageViewData );
        }
        
        static function getPageViewData() {
            return self::instance()->get('pageViewData');
        }
        
        static function setNo( $No ) {
            return self::instance()->set('No', $No );
        }
        
        static function getNo() {
            return self::instance()->get('No');
        }
    }    
    
    class controllerArray {
        
        private static $instance;
        static $controllerArray;
        
        private function __construct() {
            if(is_null(self::$controllerArray)) {
                self::$controllerArray = self::controllerArray();
            }
        }
        
        static function instance() {
            if ( ! isset(self::$instance) ) { self::$instance = new self(); }
            return self::$instance;
        }
        
        static function setControllerArray () {
            self::$controllerArray = self::controllerArray();
        }
        
        static private function controllerArray () {
            $controllerArray = array ("main" => "view",
                                      "message" => array ("view", "create", "delete", "edit", "update"),
                                      "todolists" => array ("view", "create", "delete", "edit", "createcomment"),
                                      "todo" => array(""),
                                      "item" => array(""),
                                      "items" => array(""));
            return $controllerArray;
        }
        
    }
    
    class DSN {
        
        static private $instance;
        static $DBH;
        private function __construct () {}
        
        static function instance() {
            if ( ! isset(self::$instance) ) { self::$instance = new self(); }
            return self::$instance;
        }        
        
        static function getDSN () { 
            if ( ! isset(self::$DBH) ) { self::instance()->setDSN(); };
        }
        
        private function setDSN () {
            require_once $_SERVER['DOCUMENT_ROOT'].('/wsm/base/conf/dsn.php');
            $con = new \DB();
            self::$DBH = $con->DB;
        }
    }
    
    class campDSN {
        
        static private $instance;
        static $HC;
        private function __construct () {}
        
        static function instance() {
            if ( ! isset(self::$instance) ) { self::$instance = new self(); }
            return self::$instance;
        }        
        
        static function getcampDSN () { 
            if ( ! isset(self::$HC) ) { self::instance()->setcampDSN(); };
        }
        
        private function setcampDSN () {
            require_once $_SERVER['DOCUMENT_ROOT'].('/wsm/base/conf/basecampDSN.php');
            $con = new \basecampDSN();
            self::$HC = $con->auth;
        }
    }
?>
