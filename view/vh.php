<?php
    namespace wsm\view;
    require_once( $_SERVER['DOCUMENT_ROOT']."/wsm/base/registry.php" );

    class VH {
        static function getPageViewData() {
            return \wsm\base\ViewData::getPageViewData();
        }
        static function getFeedBack() {
            return \wsm\base\ViewData::getFeedBack();
        }
        static function getInitData() {
            return \wsm\base\ViewData::getInitData();
        }
    }
?>
