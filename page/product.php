<?php
class product{
    private $db,$view,$lang,$title;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;
        $db->where('id',2);
        $item=$db->getOne('menu');
        if($lang=='en'){
            $this->title=$item['e_title'];
            $this->view=$item['e_view'];
        }else{
            $this->title=$item['title'];
            $this->view=$item['view'];
        }
    }
    function breadcrumb(){
        $this->db->reset();
        $str.='
        <ul class="breadcrumb clearfix">
        	<li><a href="'.myWeb.'"><i class="fa fa-home"></i></a></li>
            <li><a href="'.myWeb.$this->lang.'/'.$this->view.'">'.$this->title.'</a></li>';
        if(isset($_GET['id'])){
            $this->db->where('id',intval($_GET['id']));
            $item=$this->db->getOne('product','id,title,pId,e_title');
            $cate=$this->db->where('id',$item['pId'])->getOne('product_cate','id,title,e_title');
            if($this->lang=='en'){
                $cate_title=$cate['e_title'];
                $title=$item['e_title'];
            }else{
                $cate_title=$cate['title'];
                $title=$item['title'];
            }
            $str.='
            <li><a href="'.myWeb.$this->lang.'/'.$this->view.'/'.common::slug($cate_title).'-p'.$cate['id'].'">'.$cate_title.'</a></li>
            <li><a href="#">'.$title.'</a></li>';
        }elseif(isset($_GET['cate_id'])){
            $cate=$this->db->where('id',intval($_GET['cate_id']))->getOne('product_cate','id,title,e_title');
            $str.='
            <li><a href="#">'.$cate['title'].'</a></li>';
        }elseif(isset($_GET['pId'])){
            $cate=$this->db->where('id',intval($_GET['pId']))->getOne('product_cate','id,title,e_title');
            if($this->lang=='en'){
                $cate_title=$cate['e_title'];
            }else{
                $cate_title=$cate['title'];
            }
            $str.='
            <li><a href="#">'.$cate_title.'</a></li>';
        }
        $str.='
        </ul>';
        return $str;
    }
    
    function product_item($item){
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $img=$this->first_image($item['id']);
        if(trim($img)==='') $img='holder.js/400x300';else $img=webPath.$img;
        $str='
        <a href="'.$lnk.'" class="product-item">
            <img src="'.$img.'" class="img-responsive" />
            <h2>'.$item['title'].'</h2>
        </a>';
        return $str;
    }
    function product_list_item($item,$type=1){
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $img=$this->first_image($item['id']);
        if(trim($img)==='') $img='holder.js/400x300';else $img=webPath.$img;
        if($type==1){
            $str='
            <div class="col-xs-12 col-sm-6 col-md-3 product-item">';    
        }else{
            $str='
            <div class="col-xs-12 col-sm-6 col-md-4 product-item">';
        }        
        $str.='
        <a href="'.$lnk.'">
            <div>
                <p>'.($item['price']==0?contact:number_format($item['price'],0,',','.').' VNĐ').'</p>
                <img src="'.$img.'" class="img-responsive" />
                <p>
                    <h2>'.$item['title'].'</h2>
                    <button class="btn btn-default">'.more.'</button>
                </p>
            </div>
        </a>
        </div>';
        return $str;
    }
    function cate(){
        $this->db->reset();
        $cate=$this->db->where('active',1)->orderBy('ind','ASC')->where('lev',1)->get('product_cate');
        $str='';
        foreach($cate as $item){
            if($this->lang=='en'){
                $title=$item['e_title'];
            }else{
                $title=$item['title'];
            }
            $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-p'.$item['id'];
            $str.='
            <div class="col-xs-12 col-md-6 product-cate">
                <a href="'.$lnk.'">
                    <img src="'.webPath.$item['img'].'" class="img-responsive" alt="'.$title.'" title="'.$title.'"/>
                    <span>
                        '.$title.'
                    </span>
                </a>
            </div>';
        }
        return $str;
    }
    function category($pId){
        $this->db->reset();
        $cate=$this->db->where('id',$pId)->getOne('product_cate','id,pId,lev');
        if($cate['lev']==1) $pId=$cate['id'];
        else $pId=$cate['pId'];
        $this->db->where('active',1)->where('lev',1)->orderBy('ind','ASC')->orderBy('id');
        $list=$this->db->get('product_cate',null,'id,title,lev,pId');
        $str='
        <span class="box-title">Danh Mục</span>
        <ul id="accordion" class="accordion">';
        foreach($list as $item){
            $dimension=($pId==$item['id'])?' id="active"':'';
            $this->db->reset();
            $sub_list=$this->db->where('pId',$item['id'])->where('active',1)->orderBy('ind','ASC')->get('product_cate',null,'id,title');
            $str.='
            <li'.$dimension.'>
                <div class="link"><i class="fa fa-chevron-circle-right"></i>'.$item['title'].'<i class="fa fa-chevron-right"></i></div>
                <ul class="submenu">';
            foreach($sub_list as $sub_item){
                $str.='
                <li><a href="'.myWeb.$this->view.'/'.common::slug($sub_item['title']).'-p'.$sub_item['id'].'">
                    '.$sub_item['title'].'
                </a></li>';
            }
            $str.='
                    <li><a href="'.myWeb.$this->view.'/'.common::slug($item['title']).'-cate'.$item['id'].'">Xem tất cả</a></li>
                </ul>
            </li>';
        }
        $str.='
        </ul>
        <script>
        $(function() {
        	var Accordion = function(el, multiple) {
        		this.el = el || {};
        		this.multiple = multiple || false;

        		// Variables privadas
        		var links = this.el.find(".link");
        		// Evento
        		links.on("click", {el: this.el, multiple: this.multiple}, this.dropdown)
        	}

        	Accordion.prototype.dropdown = function(e) {
        		var $el = e.data.el;
        			$this = $(this),
        			$next = $this.next();

        		$next.slideToggle();
        		$this.parent().toggleClass("open");

        		if (!e.data.multiple) {
        			$el.find(".submenu").not($next).slideUp().parent().removeClass("open");
        		};
        	}

        	var accordion = new Accordion($("#accordion"), false);
        });
        $("#active").toggleClass("open");
        $("#active").find(".submenu").slideToggle();
        </script>';
        return $str;
    }
    function product_cate($pId){
        $this->db->reset();
        $list=$this->db->where('active',1)->where('pId',$pId)->orderBy('ind','ASC')->orderBy('id')->get('product');
        $str='';
        foreach($list as $item){
            if($this->lang=='en'){
                $title=$item['e_title'];
            }else{
                $title=$item['title'];
            }
            $img=$this->first_image($item['id']);
            $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-i'.$item['id'];
            $str.='
            <div class="col-xs-12 col-md-6 product-cate">
                <a href="'.$lnk.'">
                    <img src="'.webPath.$img.'" class="img-responsive" alt="'.$title.'" title="'.$title.'"/>
                    <span>
                        '.$title.'
                        <em>'.number_format($item['price'],0,',','.').'VNĐ</em>
                        <b>THUÊ '.number_format($item['price'],0,',','.').'Đ/NGÀY</b>
                    </span>
                </a>
            </div>';
        }
        return $str;
    }
    function product_list($pId,$type=1){
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->reset();
        if($pId!=0) $this->db->where('pId',$pId);
        $this->db->where('active',1)->orderBy('ind','ASC')->orderBy('id');
        $this->db->pageLimit=limit;
        $list=$this->db->paginate('product',$page,'id,title,price,price_reduce');
        $str='
        <div class="row">';
        foreach($list as $item){
            $str.=$this->product_list_item($item,$type);
        }
        $str.='
        </div>';
        return $str;
    }
    function product_one($id){
        $this->db->where('id',$id);
        $item=$this->db->getOne('product','id,price,price_reduce,title,content,pId,video');
        $this->db->where('id',$item['id'],'<>')->where('pId',$item['pId'])->where('active',1)->orderBy('id');
        $list=$this->db->get('product',5,'id,title,price,price_reduce');
        $lnk=domain.'/'.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str.='
        <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2" style="margin-bottom:20px">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/'.$item['video'].'"
             frameborder="0" allowfullscreen style="max-width:100%">
            </iframe>
        </div>
        </div>';
        $str.='<div class="row popup-gallery" style="margin:10px 0px">'.$this->product_image_show($item['id']).'</div>';
        $str.='
        <div class="row">
        <div class="col-xs-12 col-md-5 text-right price">
            <p>GIÁ BÁN: '.number_format($item['price'],0,',','.').'VNĐ</p>
            <p>CHO THUÊ: '.number_format($item['price_reduce'],0,',','.').'VNĐ/NGÀY</p>
        </div>
        <div class="col-xs-12 col-md-7">
            '.$item['content'].'
        </div>
        </div>
        <div class="row ">';
        if(count($list)>0){
            
            foreach($list as $item){
                if($this->lang=='en'){
                    $title=$item['e_title'];
                }else{
                    $title=$item['title'];
                }
                $img=$this->first_image($item['id']);
                $lnk=myWeb.$this->lang.'/'.$this->view.'/'.common::slug($title).'-i'.$item['id'];
                $str.='
                <div class="col-xs-12 col-md-6 product-cate">
                    <a href="'.$lnk.'">
                        <img src="'.webPath.$img.'" class="img-responsive" alt="'.$title.'" title="'.$title.'"/>
                        <span>
                            '.$title.'
                            <em>'.number_format($item['price'],0,',','.').'VNĐ</em>
                            <b>THUÊ '.number_format($item['price'],0,',','.').'Đ/NGÀY</b>
                        </span>
                    </a>
                </div>';
            }
        }            
        $str.='
        </div>';
        return $str;
    }
    function product_image_show($id){
        $this->db->reset();
        $list=$this->db->where('active',1)->where('pId',$id)->orderBy('ind','ASC')->orderBy('id')->get('product_image');
        foreach($list as $item){
            $str.='
            <div class="col-xs-3">
            <a href="'.webPath.$item['img'].'" class="">
                <img src="'.webPath.$item['img'].'" alt="" title="" class="img-responsive" />
            </a> 
            </div>';
        }
        return $str;
        /*$temp=$tmp='';
        foreach($list as $item){
            $temp.='
            <li>
                <a href="'.webPath.$item['img'].'" >
                    <img src="'.webPath.$item['img'].'" alt="" title="" class=""/>
                </a>
            </li>';
            $tmp.='
            <li>
                <img src="'.webPath.'thumb_'.$item['img'].'" alt="" title=""/>
            </li>';
        }
        $str.='
        <!-- Place somewhere in the <body> of your page -->
        <div id="image-slider" class="flexslider">
          <ul class="slides popup-gallery">
            '.$temp.'
          </ul>
        </div>
        <div id="carousel" class="flexslider" style="margin-top:-50px;margin-bottom:10px">
          <ul class="slides">
            '.$tmp.'
          </ul>
        </div>
        <script>
        $(window).load(function() {
          // The slider being synced must be initialized first
          $("#carousel").flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: false,
            slideshow: false,
            itemWidth: 80,
            itemMargin: 5,
            asNavFor: "#image-slider"
          });
         
          $("#image-slider").flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: false,
            slideshow: false,
            sync: "#carousel"
          });
        });
        </script>';*/
        return $str;
    }
    function first_image($id){
        $this->db->reset();
        $this->db->where('active',1)->where('pId',$id)->orderBy('ind','ASC')->orderBy('id');
        $img=$this->db->getOne('product_image','img');
        return $img['img'];
    }
}
?>