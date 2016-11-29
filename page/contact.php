<?php
class contact{
    private $db,$view,$lang;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;
        $db->where('id',7);
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
        $str.='
        </ul>';
        return $str;
    }
    function contact_insert(){
        $this->db->reset();
        if(isset($_POST['contact_send'])){
            $name=htmlspecialchars($_POST['name']);
            $adds=htmlspecialchars($_POST['adds']);
            $phone=htmlspecialchars($_POST['phone']);
            $email=htmlspecialchars($_POST['email']);
            $subject=htmlspecialchars($_POST['subject']);
            $content=htmlspecialchars($_POST['content']);
            $insert=array(
                'name'=>$name,'adds'=>$adds,'phone'=>$phone,
                'email'=>$email,'fax'=>$subject,'content'=>$content,
                'dates'=>date("Y-m-d H:i:s")
            );
            try{
                //$this->send_mail($insert);
                $this->db->insert('contact',$insert);                
               // header('Location:'.$_SERVER['REQUEST_URI']);
               echo '<script>alert("Thông tin của bạn đã được gửi đi, BQT sẽ phản hồi sớm nhất có thể, Xin cám ơn!");
                    location.href="'.$_SERVER['REQUEST_URI'].'"
               </script>';
            }catch(Exception $e){
                echo $e->errorInfo();
            }
        }
    }
    function contact(){
        $this->contact_insert();
        $this->db->reset();
        $item=$this->db->where('id',3)->getOne('qtext','content');
        /*$str.='
        <div class="container all-i-know">
        '.$this->breadcrumb().'
        <div class="row contact">
            <div class="col-md-6 left">
                <p><i>Cảm ơn Quý khách ghé thăm website của chúng tôi.<br /> 
                Mọi thông tin chi tiết xin vui lòng liên hệ:</i></p> 
                <img src="'.selfPath.'contact.jpg" alt="Liên hệ" title="Liên Hệ"/>
                <p class="qtext">
                    '.common::qtext($this->db,3).'
                </p>
            </div>
            <div class="col-md-6 right clearfix">
                <p><i>Hoặc vui lòng gởi thông tin liên hệ cho chúng tôi theo form dưới đây: </i></p>
                <form role="form" data-toggle="validator" method="post">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Họ tên..." class="form-control" data-error="Vui lòng nhập họ tên" required/>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" name="adds" placeholder="Địa chỉ..." class="form-control" data-error="Vui lòng nhập địa chỉ của bạn" required/>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" name="phone" placeholder="Điện thoại..." class="form-control" data-error="Vui lòng nhập số phone của bạn" required/>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email..." class="form-control" data-error="Định dạng email không đúng" required/>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" name="subject" placeholder="Tiêu đề..." class="form-control" data-error="Vui lòng nhập tiêu đề" required/>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <textarea name="content" placeholder="Nội dung..." class="form-control" rows="3" data-error="Vui lòng nhập nội dung" required></textarea>
                        <div class="help-block with-errors"></div>
                    </div>
                    <button type="submit" name="contact_send" class="btn btn-default">Gửi</button> 
                    <button type="reset" class="btn btn-default">Xoá</button>
                </form>';
        $str.='
            </div>
        </div>';*/
        $str.='
        <div class="col-md-12 text-center" style="margin-bottom:30px">
            <img src="'.selfPath.'contact.png" alt="" title="" class="img-responsvie"/>
        </div>
        <div class="map col-md-12">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.2328363585175!2d106.67373531433044!3d10.793470992309906!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3175292a7ea077b7%3A0xd54db40b905130ec!2zOEEgVHLhuqduIEjhu691IFRyYW5nLCBwaMaw4budbmcgMTEsIFBow7ogTmh14bqtbiwgSOG7kyBDaMOtIE1pbmgsIFZpZXRuYW0!5e0!3m2!1sen!2s!4v1464939889567" width="1300" height="500" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>';
       
        return $str;
    }
    function contact_content(){
        $str='
        <div class="contact-form bk_white">
            <div class="container bk_white">
                <p class="ghichu1">
                    Hoặc vui lòng gởi thông tin liên hệ cho chúng tôi theo form dưới đây:

                </p>
                <div class="mess_error"><ul></ul></div>
                <form class="form" role="form" method="post" action=""  enctype="multipart/form-data">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" id="exampleInputName" name="hoten" placeholder="Họ và tên (*)" value="">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="exampleInputEmail1" name="dienthoai" placeholder="Điện thoại (*)" value="">
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" id="exampleInputPhone" name="email" placeholder="Email (*)" value="">
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" id="exampleInputName" name="diachi" placeholder="Địa chỉ (*)" value="">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="exampleInputEmail1" name="fax" placeholder="Fax" value="">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="exampleInputPhone" name="phongban" placeholder="Phòng ban" value="">
                    </div>
                    <p>
                        <span class="redmeko"> Chú ý : </span>

                        <span class="ghichu">
                            Dấu (*) các trường bắt buộc phải nhập vào. Quý vị có thể gõ chữ tiếng Việt không dấu hoặc chữ tiếng Việt có dấu theo font UNICODE (UTF-8).
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <textarea rows="13" class="form-control" style="padding: 5px ! important;" name="ghichu" placeholder="Ghi Chú..."></textarea>
                    </div>

                    <div class="form-group">
                       <button type="submit" class="btn btn-primary" name="ok" value="Submit">Gửi</button>
                       <button type="reset" class="btn btn-primary" value="reset">Xoá</button>
                    </div>
                </div>
                </form>
            </div>
        </div>';
        return $str;
    }
    function send_mail($item){
        //Create a new PHPMailer instance
        $mail = new PHPMailer;
        $mail->setFrom('info@quangdung.com.vn', 'Website administrator');
        //Set an alternative reply-to address
        $mail->addReplyTo($item['email'], $item['name']);
        //Set who the message is to be sent to
        $mail->addAddress('czanubis@gmail.com');
        //Set the subject line
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject =  'Contact sent from website';
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
        //Replace the plain text body with one created manually
        $mail->Body = '
        <html>
        <head>
        	<title>'.$mail->Subject.'</title>
        </head>
        <body>
        	<p>Full Name: '.$item['name'].'</p>
        	
        	<p>Address: '.$item['adds'].'</p>
        	<p>Phone: '.$item['phone'].'</p>
        	
        	<p>Email: '.$item['email'].'</p>
            <p>Tiêu Đề: '.$item['fax'].'</p>
        	<p>Content: '.nl2br($item['content']).'</p>
        </body>
        </html>
        ';
        //Attach an image file
        //$mail->addAttachment('images/phpmailer_mini.png');
        
        //send the message, check for errors
        //$mail->send();
        if ($mail->send()) {
            echo "Message sent!";
        } else {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    }
}
?>
