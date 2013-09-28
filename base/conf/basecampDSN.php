<?php
class basecampDSN {
private $xmlstr = <<<XML
<?xml version='1.0' standalone='yes'?>
<options>
 <account>websitemovers.basecamphq.com</account>
 <baseurl>https://websitemovers.basecamphq.com</baseurl>
 <url>http://websitemovers.basecamphq.com</url>
 <api_key>590c0e954e9eda8525241c0fdaa48631defd46ba</api_key>
 <user>bcapitest@websitemovers.com</user>
 <password>S?s+d2br</password>
 <format>xml</format>
</options>
XML;
public $auth;
        
    function __construct () {
        $opt = simplexml_load_string($this->xmlstr);
        
        $this->auth = array ('account' => $opt->account,
                              'baseurl' => $opt->baseurl,
                              'api_key' => $opt->api_key,
                              'user'    => $opt->user,
                              'password' => $opt->password,
                              'format' => $opt->format);
    }
}
?>
