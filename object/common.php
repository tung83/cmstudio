<?php
class common{
    static function __contstruct(){
        
    }
    static function basic_css(){
        return '
        <link rel="stylesheet" href="'.myWeb.'css/hover.css"/>
        <link rel="stylesheet" href="'.myWeb.'css/bootstrap.min.css"/>
        <link rel="stylesheet" href="'.myWeb.'css/font-awesome.css"/>
        <link rel="stylesheet" href="'.myWeb.'css/jquery-ui.css"/>
        <link rel="stylesheet" href="'.myWeb.'css/layerslider.css"/>
        <link rel="stylesheet" href="'.myWeb.'css/magnific-popup.css"/>
        <link rel="stylesheet" href="'.myWeb.'css/page-transitions.css"/>
        <link rel="stylesheet" href="'.myWeb.'css/jAlert-v3.css"/>
        <link rel="stylesheet" href="'.myWeb.'css/webslidemenu.css"/>
        <link rel="stylesheet" href="'.myWeb.'css/owl.carousel.css">
        <link rel="stylesheet" href="'.myWeb.'css/owl.theme.css">
        <link rel="stylesheet" href="'.myWeb.'css/flexslider.css"/>
        <link rel="stylesheet" href="'.myWeb.'self.css"/>';
    }
    static function basic_js(){
        return '
        <script type="text/javascript" src="'.myWeb.'js/jquery.js"></script>
        <script type="text/javascript" src="'.myWeb.'js/jquery-ui.js"></script>
        <script type="text/javascript" src="'.myWeb.'js/jquery-migrate.js"></script>
        <script type="text/javascript" src="'.myWeb.'js/bootstrap.min.js"></script>
        <script type="text/javascript" src="'.myWeb.'js/validator.min.js"></script>
        <script type="text/javascript" src="'.myWeb.'js/layerslider.js"></script>
        <script type="text/javascript" src="'.myWeb.'js/layerslider.greensock.js"></script>
        <script type="text/javascript" src="'.myWeb.'js/layerslider.transitions.js"></script>
        <script type="text/javascript" src="'.myWeb.'js/jquery.magnific-popup.js"></script>
        <script type="text/javascript" src="'.myWeb.'js/jAlert-v3.js"></script>
        <script type="text/javascript" src="'.myWeb.'js/jAlert-functions.js"></script>
        <script type="text/javascript" src="'.myWeb.'js/webslidemenu.js"></script>
        <script type="text/javascript" src="'.myWeb.'js/owl.carousel.js"></script>
        <script type="text/javascript" src="'.myWeb.'js/jquery.flexslider.js"></script>
        <script type="text/javascript" src="'.myWeb.'js/functions.js"></script>';
    }
    static function language_change($url,$lang='vi',$pos=1){
        $url=explode('/',$url);
        $url[$pos]=$lang;
        return implode('/',$url);
    }
    static function file_check($file,$type='image'){
        if($file['error']>0) return false;
		else if(strstr($file['type'],$type)!=NULL) return true;
		else return false;
    }
    static function slug($str, $options = array()){
        // Make sure string is in UTF-8 and strip invalid UTF-8 characters    	
    	$str=self::vn_str_filter($str);
    	$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
    	$defaults = array(
    	'delimiter' => '-',
    	'limit' => null,
    	'lowercase' => true,
    	'replacements' => array(),
    	'transliterate' => false,
    	);
    	// Merge options
    	$options = array_merge($defaults, $options);
    	$char_map = array(
    	// Latin
    	'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
    	'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
    	'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
    	'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
    	'ß' => 'ss',
    	'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
    	'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
    	'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
    	'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
    	'ÿ' => 'y',
    	 
    	// Latin symbols
    	'©' => '(c)',
    	 
    	// Greek
    	'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
    	'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
    	'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
    	'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
    	'Ϋ' => 'Y',
    	'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
    	'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
    	'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
    	'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
    	'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
    	 
    	// Turkish
    	'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
    	'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
    	 
    	// Russian
    	'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
    	'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
    	'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
    	'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
    	'Я' => 'Ya',
    	'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
    	'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
    	'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
    	'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
    	'я' => 'ya',
    	 
    	// Ukrainian
    	'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
    	'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
    	 
    	// Czech
    	'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
    	'Ž' => 'Z',
    	'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
    	'ž' => 'z',
    	 
    	// Polish
    	'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
    	'Ż' => 'Z',
    	'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
    	'ż' => 'z',
    	 
    	// Latvian
    	'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
    	'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
    	'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
    	'š' => 's', 'ū' => 'u', 'ž' => 'z'
    	);
    	// Make custom replacements
    	$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
    	// Transliterate characters to ASCII
    	if ($options['transliterate']) {
    	$str = str_replace(array_keys($char_map), $char_map, $str);
    	}
    	// Replace non-alphanumeric characters with our delimiter
    	$str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
    	// Remove duplicate delimiters
    	$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
    	// Truncate slug to max. characters
    	$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
    	// Remove delimiter from ends
    	$str = trim($str, $options['delimiter']);
    	return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;    
    }
    static function str_cut($str, $len, $charset='UTF-8'){
        $str = html_entity_decode($str, ENT_QUOTES, $charset);
	    if(mb_strlen($str, $charset)> $len){
    		$arr = explode(' ', $str);
    		$str = mb_substr($str, 0, $len, $charset);
    		$arrRes = explode(' ', $str);
    		$last = $arr[count($arrRes)-1];
    		unset($arr);
    		if(strcasecmp($arrRes[count($arrRes)-1], $last))
    			{
    				unset($arrRes[count($arrRes)-1]);
    			}
    		return implode(' ', $arrRes)."...";
		}
		return $str;
    }
    static function vn_str_filter($str){
        $unicode = array(        
           'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',        
           'd'=>'đ',        
           'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',        
           'i'=>'í|ì|ỉ|ĩ|ị',        
           'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',        
           'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',        
           'y'=>'ý|ỳ|ỷ|ỹ|ỵ',        
           'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',        
           'D'=>'Đ',        
           'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',        
           'I'=>'Í|Ì|Ỉ|Ĩ|Ị',        
           'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',        
           'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',        
           'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',        
        );    
        foreach($unicode as $nonUnicode=>$uni){    
           $str = preg_replace("/($uni)/i", $nonUnicode, $str);    
        }    
        return $str;
    }
    static function page_head($param=array('title'=>head_title,'keyword'=>head_keyword,'description'=>head_description)){
        echo '
        <title>'.$param['title'].'</title>
        <meta name="keyword" content="'.$param['keyword'].'" />
        <meta name="description" content="'.$param['description'].'" />
        ';
    }
    static function lat_long($address=''){
        // We get the JSON results from this request
        $geo = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false');
        // We convert the JSON to an array
        $geo = json_decode($geo, true);
        // If everything is cool
        if ($geo['status'] = 'OK') {
          // We set our values
          $latitude = $geo['results'][0]['geometry']['location']['lat'];
          $longitude = $geo['results'][0]['geometry']['location']['lng'];
        }
        $arr=array('lat'=>$latitude,'long'=>$longitude);
        return $arr;
    }
    static function load($class_name,$type='object'){
        switch($type){
            case 'page':
                include_once pageLib.$class_name.'.php';
                break;
            case 'object': 
            default:
                include_once phpLib.$class_name.'.php';
                break;                
        }        
    }
    static function qtext($db,$id,$lang='vi',$table='qtext'){
        $db->where('id',$id);
        $item=$db->getOne($table,'content,e_content');
        if($lang=='en'){
            return $item['e_content'];    
        }else{
            return $item['content'];
        }
        
    }
    function ads_banner($db){
        $db->reset();
        $db->where('active',1)->orderBy('ind','ASC')->orderBy('id');
        $list=$db->get('ads_banner');
        $str='
        <p class="ads-banner">';
        if($db->count>0){
            foreach($list as $item){
                $lnk=$item['lnk']!=''?'http://'.$item['lnk']:'javascript:void(0)';
                $str.='
                <a href="'.$lnk.'"><img src="'.webPath.$item['img'].'" alt="" title=""/></a>';
            }   
        }
        $str.='
        </p>';
        return $str;
    }
}
?>