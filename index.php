<?php include_once 'function.php';?><!doctype html>
<html lang="en">
<head>
    <title>.:Cinemagic Studio:.</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <?=common::basic_css()?>
    <?=common::basic_js()?>
</head>
<body>
<div class="container">
    <section id="header">
    <div class="row">
        <img src="<?=selfPath?>logo.png" class="img-responsive" alt="" title=""/>
        <?=flag_show($lang)?>
    </div>
    </section>
    <?=menu($db,$view,$lang)?>
    <?php
        switch($view){
            case 'san-pham':
            case 'product':
                echo product($db,$view,$lang);
                break;
            case 'hop-tac':
            case 'cooperate':
                echo about($db,$view,$lang);
                break;
            case 'project':
            case 'du-an':
                echo news($db,$view,$lang);
                break;
            case 'cmfilm':
                echo video($db,$lang);
                break;
            case 'guide':
            case 'huong-dan':
                echo manual($db,$lang);
                break;
            case 'contact':
            case 'lien-he':
                echo contact($db,$lang);
                break;
            default:
                echo home($db,$view,$lang);
                break;
        }
    ?>
    <section id="footer">
        <div class="row">
            <div class="col-md-6">
                <img src="<?=selfPath?>soc.png" class="img-responsive" alt="" title=""/>
            </div>
            <div class="col-md-6">
                <?=common::qtext($db,4,$lang)?>
            </div>
        </div>
    </section>
</div>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.6&appId=1526299550957309";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
</body>
</html>