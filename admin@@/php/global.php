<?php
	$act = isset($_GET["act"])?$_GET["act"]:"";
	if(!isset($_GET["act"])) require "dashboard.php";
	else
	{
		switch ($act) 
		{
			default:
			{
				require $act.".php";
				break;	
			}
		}
	}
    function del_img($id,$lnk,$tab)
    {
        $tb=mysql_query("select img from $tab where id=$id");
        $r=mysql_fetch_object($tb);
        if(trim($r->img)!='') unlink(webPath.$r->img);
    }
?>