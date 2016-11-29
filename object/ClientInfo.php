<?php
/**
 * This class allows you to preform various operations with
 * Media Access Control (MAC addresses) on UNIX type systems.
 * 
 * @author Blake Gardner <blakegardner[at]cox.net>
 * @copyright Copyright (c) 2012, Blake Gardner
 * @license MIT License (see License.txt)
 */
/**
 * Usage
 * use BlakeGardner\MacAddress;
 * var_dump(MacAddress::getCurrentMacAddress('eth0'));
 * var_dump(MacAddress::generateMacAddress());
 * var_dump(MacAddress::validateMacAddress('00-B0-D0-86-BB-F7'));
 * var_dump(MacAddress::setFakeMacAddress('eth0'));
 * var_dump(MacAddress::setFakeMacAddress('eth0', '00:E4:01:2C:79:DA'));
 */
class ClientInfo
{
    private $user_agent      = '';
    private $user_ip         = '';
    private $user_country    = 'unknow';
    private $user_city       = 'unknow'; 

    public function __construct()
    {
        $this->user_agent = $_SERVER['HTTP_USER_AGENT'];
        $this->user_ip    = $_REQUEST['REMOTE_ADDR'];
        $this->getLocation();
    }
    public function getCurrentMacAddress()
    {
        ob_start(); // Turn on output buffering
        system('ipconfig /all'); //Execute external program to display output
        $mycom=ob_get_contents(); // Capture the output into a variable
        ob_clean(); // Clean (erase) the output buffer
        $findme = "Physical";
        $pmac = strpos($mycom, $findme); // Find the position of Physical text
        $mac=substr($mycom,($pmac+36),17); // Get Physical Address
        return $mac;
    }
    public function getUserCountry(){
        return $this->user_country;
    }
    public function getUserCity(){
        return $this->user_city;
    }
    public function getUserIp(){
        return $this->user_ip;
    }
    private function getMacLinux() {
        exec('netstat -ie', $result);
        if(is_array($result)) {
        $iface = array();
        foreach($result as $key => $line) {
            if($key > 0) {
            $tmp = str_replace(" ", "", substr($line, 0, 10));
            if($tmp <> "") {
                $macpos = strpos($line, "HWaddr");
                if($macpos !== false) {
                    $iface[] = array('iface' => $tmp, 'mac' => strtolower(substr($line, $macpos+7, 17)));
                }
            }
            }
        }
        return $iface[0]['mac'];
        } else {
        return "notfound";
        }
    }
    public function getOS() {
        $os_platform    =   "Unknown OS Platform";
        $os_array       =   array(
                                '/windows nt 10/i'     =>  'Windows 10',
                                '/windows nt 6.3/i'     =>  'Windows 8.1',
                                '/windows nt 6.2/i'     =>  'Windows 8',
                                '/windows nt 6.1/i'     =>  'Windows 7',
                                '/windows nt 6.0/i'     =>  'Windows Vista',
                                '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                                '/windows nt 5.1/i'     =>  'Windows XP',
                                '/windows xp/i'         =>  'Windows XP',
                                '/windows nt 5.0/i'     =>  'Windows 2000',
                                '/windows me/i'         =>  'Windows ME',
                                '/win98/i'              =>  'Windows 98',
                                '/win95/i'              =>  'Windows 95',
                                '/win16/i'              =>  'Windows 3.11',
                                '/macintosh|mac os x/i' =>  'Mac OS X',
                                '/mac_powerpc/i'        =>  'Mac OS 9',
                                '/linux/i'              =>  'Linux',
                                '/ubuntu/i'             =>  'Ubuntu',
                                '/iphone/i'             =>  'iPhone',
                                '/ipod/i'               =>  'iPod',
                                '/ipad/i'               =>  'iPad',
                                '/android/i'            =>  'Android',
                                '/blackberry/i'         =>  'BlackBerry',
                                '/webos/i'              =>  'Mobile'
                            );
        foreach ($os_array as $regex => $value) { 
            if (preg_match($regex, $this->user_agent)) {
                $os_platform    =   $value;
            }
        }   
        return $os_platform;
    }

    public function getBrowser() {
        $browser        =   "Unknown Browser";
        $browser_array  =   array(
                                '/msie/i'       =>  'Internet Explorer',
                                '/firefox/i'    =>  'Firefox',
                                '/safari/i'     =>  'Safari',
                                '/chrome/i'     =>  'Chrome',
                                '/edge/i'       =>  'Edge',
                                '/opera/i'      =>  'Opera',
                                '/netscape/i'   =>  'Netscape',
                                '/maxthon/i'    =>  'Maxthon',
                                '/konqueror/i'  =>  'Konqueror',
                                '/mobile/i'     =>  'Handheld Browser'
                            );
        foreach ($browser_array as $regex => $value) { 
            if (preg_match($regex, $this->user_agent)) {
                $browser    =   $value;
            }
        }
        return $browser;
    }
    /**
     *array (
     *    'status' => 'success',
     *    'country' => 'COUNTRY',
     *    'countryCode' => 'COUNTRY CODE',
     *    'region' => 'REGION CODE',
     *    'regionName' => 'REGION NAME',
     *    'city' => 'CITY',
     *    'zip' => ZIP CODE,
     *    'lat' => LATITUDE,
     *    'lon' => LONGITUDE,
     *    'timezone' => 'TIME ZONE',
     *    'isp' => 'ISP NAME',
     *    'org' => 'ORGANIZATION NAME',
     *    'as' => 'AS NUMBER / NAME',
     *    'query' => 'IP ADDRESS USED FOR QUERY',
     *  )
     */
    public function getLocation(){
        $query = @unserialize(file_get_contents('http://ip-api.com/php/'.$this->user_ip));
        if($query && $query['status'] == 'success') {
            $this->user_country = $query['country'];
            $this->user_city = $query['city'];
            $this->user_ip = $query['query'];
        }
        return true;
    }

}