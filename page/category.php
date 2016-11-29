<?php
class product{
    public $item,$db;
    private $table,$table_img,$table_category,$view;
    private $img;
    private $img_list;
    function __construct($db,$table){
        $this->db=$db;
        $this->table=$table;
        $this->table_img='product_image';
        $this->table_category='category';
        $this->img_list=array();
        $this->img='default.jpg';
        $this->view='san-pham';
    }
    function set_id($id){
        $this->id=$id;
        $this->db->where('id',$this->id)->where('active',1);
        $this->item=$this->db->getOne($this->table);
        $this->db->where('pId',$this->item['id']);
        $this->img_list=$this->db->get($this->table_img,null,'id,img');
        if($this->img_list[0]['img']!=''&&count($this->img_list)>0) $this->img=$this->img_list[0]['img'];
        $this->lnk=myWeb.$this->view.'/'.common::slug($this->item['title']).'-i'.$this->item['id'].'.html';
    }
    function feature_item(){
        $str='
        <div class="col-sm-4">
			<div class="product-image-wrapper">
				<div class="single-products">
						<div class="productinfo text-center">
							<img src="'.webPath.$this->img.'" alt="'.$this->item['title'].'"/>
							<h2>'.number_format($this->item['price'],0,',','.').' VNĐ</h2>
							<p>'.$this->item['title'].'</p>
                            <a href="" class="btn btn-default add-to-cart">
                                <i class="fa fa-pencil"></i>Chi Tiết
                            </a>
							<a href="#" class="btn btn-default add-to-cart">
                                <i class="fa fa-shopping-cart"></i>Mua Hàng</a>
						</div>
						<div class="product-overlay">
							<div class="overlay-content">
								<h2>'.number_format($this->item['price'],0,',','.').' VNĐ</h2>
								<p>'.$this->item['title'].'</p>
                                <a href="'.$this->lnk.'" class="btn btn-default add-to-cart">
                                    <i class="fa fa-pencil"></i>Chi Tiết
                                </a>
								<a href="#" class="btn btn-default add-to-cart">
                                <i class="fa fa-shopping-cart"></i>Mua Hàng</a>
							</div>
						</div>
				</div>
				<!--div class="choose">
					<ul class="nav nav-pills nav-justified">
						<li><a href="#"><i class="fa fa-plus-square"></i>Add to wishlist</a></li>
						<li><a href="#"><i class="fa fa-plus-square"></i>Add to compare</a></li>
					</ul>
				</div-->
			</div>
		</div>
        ';
        return $str;
    }
    function category_tab(){
        $str='
        <div class="category-tab"><!--category-tab-->
			<div class="col-sm-12">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tshirt" data-toggle="tab">T-Shirt</a></li>
					<li><a href="#blazers" data-toggle="tab">Blazers</a></li>
					<li><a href="#sunglass" data-toggle="tab">Sunglass</a></li>
					<li><a href="#kids" data-toggle="tab">Kids</a></li>
					<li><a href="#poloshirt" data-toggle="tab">Polo shirt</a></li>
				</ul>
			</div>
			<div class="tab-content">
				<div class="tab-pane fade active in" id="tshirt" >
					<div class="col-sm-3">
						<div class="product-image-wrapper">
							<div class="single-products">
								<div class="productinfo text-center">
									<img src="images/home/gallery1.jpg" alt="" />
									<h2>$56</h2>
									<p>Easy Polo Black Edition</p>
									<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
								</div>
								
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="product-image-wrapper">
							<div class="single-products">
								<div class="productinfo text-center">
									<img src="images/home/gallery2.jpg" alt="" />
									<h2>$56</h2>
									<p>Easy Polo Black Edition</p>
									<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
								</div>
								
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="product-image-wrapper">
							<div class="single-products">
								<div class="productinfo text-center">
									<img src="images/home/gallery3.jpg" alt="" />
									<h2>$56</h2>
									<p>Easy Polo Black Edition</p>
									<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
								</div>
								
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="product-image-wrapper">
							<div class="single-products">
								<div class="productinfo text-center">
									<img src="images/home/gallery4.jpg" alt="" />
									<h2>$56</h2>
									<p>Easy Polo Black Edition</p>
									<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
								</div>
								
							</div>
						</div>
					</div>
				</div>
				
				<div class="tab-pane fade" id="blazers" >
					<div class="col-sm-3">
						<div class="product-image-wrapper">
							<div class="single-products">
								<div class="productinfo text-center">
									<img src="images/home/gallery4.jpg" alt="" />
									<h2>$56</h2>
									<p>Easy Polo Black Edition</p>
									<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
								</div>
								
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="product-image-wrapper">
							<div class="single-products">
								<div class="productinfo text-center">
									<img src="images/home/gallery3.jpg" alt="" />
									<h2>$56</h2>
									<p>Easy Polo Black Edition</p>
									<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
								</div>
								
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="product-image-wrapper">
							<div class="single-products">
								<div class="productinfo text-center">
									<img src="images/home/gallery2.jpg" alt="" />
									<h2>$56</h2>
									<p>Easy Polo Black Edition</p>
									<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
								</div>
								
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="product-image-wrapper">
							<div class="single-products">
								<div class="productinfo text-center">
									<img src="images/home/gallery1.jpg" alt="" />
									<h2>$56</h2>
									<p>Easy Polo Black Edition</p>
									<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
								</div>
								
							</div>
						</div>
					</div>
				</div>
				
				<div class="tab-pane fade" id="sunglass" >
					<div class="col-sm-3">
						<div class="product-image-wrapper">
							<div class="single-products">
								<div class="productinfo text-center">
									<img src="images/home/gallery3.jpg" alt="" />
									<h2>$56</h2>
									<p>Easy Polo Black Edition</p>
									<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
								</div>
								
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="product-image-wrapper">
							<div class="single-products">
								<div class="productinfo text-center">
									<img src="images/home/gallery4.jpg" alt="" />
									<h2>$56</h2>
									<p>Easy Polo Black Edition</p>
									<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
								</div>
								
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="product-image-wrapper">
							<div class="single-products">
								<div class="productinfo text-center">
									<img src="images/home/gallery1.jpg" alt="" />
									<h2>$56</h2>
									<p>Easy Polo Black Edition</p>
									<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
								</div>
								
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="product-image-wrapper">
							<div class="single-products">
								<div class="productinfo text-center">
									<img src="images/home/gallery2.jpg" alt="" />
									<h2>$56</h2>
									<p>Easy Polo Black Edition</p>
									<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
								</div>
								
							</div>
						</div>
					</div>
				</div>
				
				<div class="tab-pane fade" id="kids" >
					<div class="col-sm-3">
						<div class="product-image-wrapper">
							<div class="single-products">
								<div class="productinfo text-center">
									<img src="images/home/gallery1.jpg" alt="" />
									<h2>$56</h2>
									<p>Easy Polo Black Edition</p>
									<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
								</div>
								
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="product-image-wrapper">
							<div class="single-products">
								<div class="productinfo text-center">
									<img src="images/home/gallery2.jpg" alt="" />
									<h2>$56</h2>
									<p>Easy Polo Black Edition</p>
									<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
								</div>
								
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="product-image-wrapper">
							<div class="single-products">
								<div class="productinfo text-center">
									<img src="images/home/gallery3.jpg" alt="" />
									<h2>$56</h2>
									<p>Easy Polo Black Edition</p>
									<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
								</div>
								
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="product-image-wrapper">
							<div class="single-products">
								<div class="productinfo text-center">
									<img src="images/home/gallery4.jpg" alt="" />
									<h2>$56</h2>
									<p>Easy Polo Black Edition</p>
									<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
								</div>
								
							</div>
						</div>
					</div>
				</div>
				
				<div class="tab-pane fade" id="poloshirt" >
					<div class="col-sm-3">
						<div class="product-image-wrapper">
							<div class="single-products">
								<div class="productinfo text-center">
									<img src="images/home/gallery2.jpg" alt="" />
									<h2>$56</h2>
									<p>Easy Polo Black Edition</p>
									<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
								</div>
								
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="product-image-wrapper">
							<div class="single-products">
								<div class="productinfo text-center">
									<img src="images/home/gallery4.jpg" alt="" />
									<h2>$56</h2>
									<p>Easy Polo Black Edition</p>
									<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
								</div>
								
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="product-image-wrapper">
							<div class="single-products">
								<div class="productinfo text-center">
									<img src="images/home/gallery3.jpg" alt="" />
									<h2>$56</h2>
									<p>Easy Polo Black Edition</p>
									<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
								</div>
								
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="product-image-wrapper">
							<div class="single-products">
								<div class="productinfo text-center">
									<img src="images/home/gallery1.jpg" alt="" />
									<h2>$56</h2>
									<p>Easy Polo Black Edition</p>
									<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
								</div>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><!--/category-tab-->
        ';
        return $str;
    }
    function other_item(){
        $str='
        <div class="col-sm-3">
			<div class="product-image-wrapper">
				<div class="single-products">
					<div class="productinfo text-center">
						<img src="'.webPath.$this->img.'" alt="" />
						<h2>'.number_format($this->item['price'],0,',','.').'VNĐ</h2>
						<p>'.$this->item['title'].'</p>
						<a href="'.$this->lnk.'" class="btn btn-default add-to-cart">
                            <i class="fa fa-pencil"></i>Chi Tiết
                        </a>
					</div>
				</div>
			</div>
		</div>
        ';
        return $str;
    }
    function recommend_item(){
        $str='
        <div class="recommended_items"><!--recommended_items-->
			<h2 class="title text-center">recommended items</h2>
			
			<div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
				<div class="carousel-inner">
					<div class="item active">	
						<div class="col-sm-4">
							<div class="product-image-wrapper">
								<div class="single-products">
									<div class="productinfo text-center">
										<img src="images/home/recommend1.jpg" alt="" />
										<h2>$56</h2>
										<p>Easy Polo Black Edition</p>
										<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
									</div>
									
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="product-image-wrapper">
								<div class="single-products">
									<div class="productinfo text-center">
										<img src="images/home/recommend2.jpg" alt="" />
										<h2>$56</h2>
										<p>Easy Polo Black Edition</p>
										<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
									</div>
									
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="product-image-wrapper">
								<div class="single-products">
									<div class="productinfo text-center">
										<img src="images/home/recommend3.jpg" alt="" />
										<h2>$56</h2>
										<p>Easy Polo Black Edition</p>
										<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
									</div>
									
								</div>
							</div>
						</div>
					</div>
					<div class="item">	
						<div class="col-sm-4">
							<div class="product-image-wrapper">
								<div class="single-products">
									<div class="productinfo text-center">
										<img src="images/home/recommend1.jpg" alt="" />
										<h2>$56</h2>
										<p>Easy Polo Black Edition</p>
										<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
									</div>
									
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="product-image-wrapper">
								<div class="single-products">
									<div class="productinfo text-center">
										<img src="images/home/recommend2.jpg" alt="" />
										<h2>$56</h2>
										<p>Easy Polo Black Edition</p>
										<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
									</div>
									
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="product-image-wrapper">
								<div class="single-products">
									<div class="productinfo text-center">
										<img src="images/home/recommend3.jpg" alt="" />
										<h2>$56</h2>
										<p>Easy Polo Black Edition</p>
										<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
									</div>
									
								</div>
							</div>
						</div>
					</div>
				</div>
				 <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
					<i class="fa fa-angle-left"></i>
				  </a>
				  <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
					<i class="fa fa-angle-right"></i>
				  </a>			
			</div>
		</div><!--/recommended_items-->
        ';
        return $str;
    }
    function product_detail(){
        $temp='';
        $tmp='';
        foreach((array)$this->img_list as $item){
            $temp.='<li><img src="'.webPath.$item['img'].'" alt="'.$this->item['title'].'" title=""/></li>
            ';
            $tmp.='<a href="#" title=""><img src="'.webPath.$item['img'].'" alt="'.$this->item['title'].'" title="'.$this->item['title'].'" /></a>
            ';
        }
        $str='
        <div class="product-details"><!--product-details-->
			<div class="col-sm-5">
                <!-- Start WOWSlider.com HEAD section -->
                <link rel="stylesheet" type="text/css" href="'.myWeb.'engine/style.css" />
                <!-- End WOWSlider.com HEAD section -->
				<!-- Start WOWSlider.com BODY section -->
                <div id="wowslider-container1">
                <div class="ws_images"><ul>
                		'.$temp.'
                	</ul></div>
                	<div class="ws_thumbs">
                <div>
                		'.$tmp.'
                	</div>
                </div>
                <div class="ws_script" style="position:absolute;left:-99%"></div>
                <div class="ws_shadow"></div>
                </div>	
                <script type="text/javascript" src="'.myWeb.'engine/wowslider.js"></script>
                <script type="text/javascript" src="'.myWeb.'engine/script.js"></script>
                <!-- End WOWSlider.com BODY section -->

			</div>
			<div class="col-sm-7">
				<div class="product-information"><!--/product-information-->
					<h2>'.$this->item['title'].'</h2>
					<p>Web ID: '.$this->item['id'].'</p>
					<span>
						<span>'.number_format($this->item['price'],0,',','.').'VNĐ</span>
						<!--label>Quantity:</label>
						<input type="text" value="1" disabled/-->
						<button type="button" class="btn btn-fefault cart">
							<i class="fa fa-shopping-cart"></i>
							Mua Ngay
						</button>
					</span>
					<p><b>Trong Kho :</b> '.($this->item['in_stock']==1?'Còn Hàng':'Hết Hàng').'</p>
					<p><b>Tình Trạng :</b> '.($this->item['condition']==1?'Mới':'Cũ').'</p>
					<p><b>Thương Hiệu :</b> E1-Team</p>
					
				</div><!--/product-information-->
			</div>
		</div><!--/product-details-->
        ';
        $str.='
        <div class="category-tab shop-details-tab"><!--category-tab-->
			<div class="col-sm-12">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#details" data-toggle="tab">Chi Tiết</a></li>
					<li><a href="#reviews" data-toggle="tab">Đánh Giá & Nhận Xét (5)</a></li>
				</ul>
			</div>
			<div class="tab-content">
				<div class="tab-pane fade active in" id="details" >
                    <div class="col-sm-12 table-responsive" style="padding:10px">
    					<article>
                        '.$this->item['detail'].'
                        </article>
                    </div>
				</div>
				
				<div class="tab-pane fade" id="reviews" >
					<div class="col-sm-12">
						<ul>
							<li><a href=""><i class="fa fa-user"></i>EUGEN</a></li>
							<li><a href=""><i class="fa fa-clock-o"></i>12:41 PM</a></li>
							<li><a href=""><i class="fa fa-calendar-o"></i>31 DEC 2014</a></li>
						</ul>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
						<p><b>Write Your Review</b></p>
						
						<form action="#">
							<span>
								<input type="text" placeholder="Your Name"/>
								<input type="email" placeholder="Email Address"/>
							</span>
							<textarea name="" ></textarea>
							<button type="button" class="btn btn-default pull-right">
								Submit
							</button>
						</form>
					</div>
				</div>
				
			</div>
		</div><!--/category-tab-->
        ';
        return $str;
    }
}
?>