<?php
/*
$pagenumber = 57;
$totalrecords = 45533;
include_once("pagination.php"); 
$pg=new Pagination(array('limit'=>1,'count'=>20,'page'=>$page,'type'=>0));
$pg->set_url(array('def'=>'index.php','url'=>'index.php?page=[p]'));
echo $pg->process
*/
class Pagination
{
    public $pagenumber;
    public $pagesize;
    public $totalrecords;
    public $showfirst;
    public $showlast;
    public $paginationcss;
    public $paginationstyle;
    public $defaultUrl;
    public $paginationUrl;
	
    // pager style
    public $prevCss;
    public $nextCss;
	
	function __construct($options=array())
	{
		$this->pagenumber = isset($options['page'])?intval($options['page']):1;
		$this->pagesize = isset($options['limit'])?intval($options['limit']):20;
		$this->totalrecords = isset($options['count'])?intval($options['count']):0;
		$this->showfirst = isset($options['first'])?$options['first']:true;
		$this->showlast = isset($options['last'])?$options['last']:true;
		$this->paginationcss = "pagination-small";
		$this->paginationstyle = isset($options['type'])?intval($options['type']):0;  // 1: advance, 0: normal, 2: pager		
		$this->defaultUrl = "#"; // in case of ajax pagination
		$this->paginationUrl = "#"; // # incase of ajax pagination e.g index.php?p=[p] -->		
		$this->prevCss = "previous";
		$this->nextCss = "next";
	}
	function set_url($options=array()){
	   $this->defaultUrl=isset($options['def'])?$options['def']:'';
       $this->paginationUrl=isset($options['url'])?$options['url']:'';
       return $this;
	}
	function process()
	{		
	    $paginationlst = "";
		$firstbound =0;
		$lastbound =0;
		$tooltip = "";
		
		if($this->totalrecords > $this->pagesize)
	    {
		     $totalpages = ceil($this->totalrecords / $this->pagesize);
   
			if ($this->pagenumber > 1)
			{
			   if ($this->showfirst && $this->paginationstyle != 2)
			   {
				   $firstbound = 1;
				   $lastbound = $firstbound + $this->pagesize - 1;
				   $tooltip = "showing " . $firstbound . " - " . $lastbound . " records of " . $this->totalrecords . " records";
				   // First Link
				   if($this->defaultUrl == "")
				      $this->defaultUrl = "#";
				   $paginationlst .= "<li><a id=\"p_1\" href=\"" . $this->defaultUrl . "\" class=\"pagination-css\" data-toggle=\"tooltip\" title=\"" . $tooltip . "\"><i class=\"fa fa-backward\"></i></a></li>\n";
				}
				$firstbound = (($totalpages - 1) * $this->pagesize);
				$lastbound = $firstbound + $this->pagesize - 1;
				if ($lastbound > $this->totalrecords)
				{
					$lastbound = $this->totalrecords;
				}
				$tooltip = "showing " . $firstbound . " - " . $lastbound . " records of " . $this->totalrecords . " records";
				// Previous Link Enabled
				if($this->paginationUrl == "")
				  $this->paginationUrl = "#";
				
				$pid = ($this->pagenumber - 1);
				if($pid < 1)  $pid = 1;
				$prevPageCss = "";
				$prevIcon = "<i class=\"fa fa-chevron-left\"></i>";
				if($this->paginationstyle == 2)
				{
					if($this->prevCss != "")
					  $prevPageCss = " class=\"" . $this->prevCss . "\"";
					$prevIcon = "&larr; Previous";
				}
				$paginationlst .= "<li" . $prevPageCss . "><a id=\"pp_" . $pid . "\" href=\"" . $this->prepareUrl($pid) . "\" data-toggle=\"tooltip\" class=\"pagination-css\" title=\"" . $tooltip . "\">" . $prevIcon . "</a></li>\n";
				// Normal Links
				if($this->paginationstyle != 2)
				    $paginationlst .= $this->generate_pagination_links($totalpages, $this->totalrecords, $this->pagenumber, $this->pagesize);
			   
				if($this->pagenumber < $totalpages)
				{
					 $paginationlst .= $this->generate_previous_last_links($totalpages, $this->totalrecords, $this->pagenumber, $this->pagesize, $this->showlast);
				}
			}
			else
			{
				// Normal Links
				if($this->paginationstyle != 2)
			  	    $paginationlst .= $this->generate_pagination_links($totalpages, $this->totalrecords, $this->pagenumber, $this->pagesize);
				// Next Last Links
				$paginationlst .= $this->generate_previous_last_links($totalpages, $this->totalrecords, $this->pagenumber, $this->pagesize, $this->showlast);
			}
		}
		$paginationCss = "pagination " . $this->paginationcss;
		if($this->paginationstyle == 2)
		   $paginationCss = "pager";
		return "<ul class=\"" . $paginationCss . "\">\n" . $paginationlst . "</ul>\n";
	}
	
    function generate_pagination_links($totalpages, $totalrecords, $pagenumber, $pagesize)
    {
        $script = "";
        $firstbound = 0;
        $lastbound = 0;
        $tooltip = "";
		if($this->paginationstyle == 1)
		   $arr = $this->advance_pagination_links($totalpages, $pagenumber);
		else
		   $arr = $this->simple_pagination_links($totalpages, 15, $pagenumber);
		if(count($arr) > 0)
	    {
		   foreach ($arr as $item){
			   $firstbound = (($item - 1) * $pagesize) + 1;
			   $lastbound = $firstbound + $pagesize - 1;
                if ($lastbound > $totalrecords)
                    $lastbound = $totalrecords;
                $tooltip = "showing " . $firstbound . " - " . $lastbound . " records  of " . $totalrecords . " records";
                $css = "";
                if ($item == $pagenumber){
                    $css = ' class="active"';
                    $lnk= "";
                }else{
                    $lnk= ' href="'.$this->prepareUrl($item).'"';
                }
                    
                $script.='<li'.$css.'><a id="pg_'.$item.'"'.$lnk.' class="pagination-css" data-toggle="tooltip" title="'.$tooltip.'">'.$item.'</a></li>'."\n";
	       }
	   }
       return $script;
    }
    function generate_previous_last_links($totalpages, $totalrecords, $pagenumber, $pagesize, $showlast)
    {
        $script = "";
        $firstbound = (($pagenumber) * $pagesize) + 1;
        $lastbound = $firstbound + $pagesize - 1;
        if ($lastbound > $totalrecords)
            $lastbound = $totalrecords;
        $tooltip = "showing " . $firstbound . " - " . $lastbound . " records of " . $totalrecords . " records";
        // Next Link
		$pid = ($pagenumber + 1);
		if($pid > $totalpages) $pid = $totalpages;
		$nextPageCss = "";
		$nextPageIcon = "<i class=\"fa fa-chevron-right\"></i>";
		if($this->paginationstyle == 2)
		{
			if($this->nextCss != "")
			  $nextPageCss = " class=\"" . $this->nextCss . "\"";
			$nextPageIcon = "Next &rarr;"; 
		}
        $script .= "<li" . $nextPageCss . "><a id=\"pn_" . $pid . "\" href=\"" . $this->prepareUrl($pid) . "\" class=\"pagination-css\" data-toggle=\"tooltip\" title=\"" . $tooltip . "\">" . $nextPageIcon . "</a></li>\n";
        if ($showlast && $this->paginationstyle != 2)
        {
            // Last Link
            $firstbound = (($totalpages - 1) * $pagesize) + 1;
            $lastbound = $firstbound + $pagesize - 1;
            if ($lastbound > $totalpages)
                $lastbound = $totalpages;
            $tooltip = "showing " . $firstbound . " - " . $lastbound . " records of " . $totalrecords . " records";
            $script .= "<li><a id=\"pl_" . $totalpages . "\" href=\"" . $this->prepareUrl($totalpages) . "\" class=\"pagination-css\" data-toggle=\"tooltip\" title=\"" . $tooltip . "\"><i class=\"fa fa-forward\"></i></a></li>\n";
        }
        return $script;
    }
	
    function prepareUrl($pid)
    {
    	if($this->paginationUrl == "")
    	  $this->paginationUrl = "#";
    	if($pid >= 1)
    	  return preg_replace("/\[p\]/", $pid, $this->paginationUrl);
    	else
    	  return preg_replace("/\[p\]/", $pid, $this->defaultUrl);
    }
    // Main Pagination Logic
    function simple_pagination_links($totalpages,$totallinks,$selectedpage) 
    {
        $i = 0;
        $arr = array();
        if ($totalpages < $totallinks)
        {
            for( $i=1; $i<=$totalpages; $i++)
            {
                $arr[] = $i;
            }
        }
        else
        {
            $startindex  = $selectedpage;
            $lowerbound = $startindex - floor($totallinks / 2);
            $upperbound = $startindex + floor($totallinks / 2);
            if ($lowerbound < 1)
            {
                //calculate the difference and increment the upper bound
                $upperbound = $upperbound + (1 - $lowerbound);
                $lowerbound = 1;
            }
            //if upperbound is greater than total page is
            if ($upperbound > $totalpages)
            {
                //calculate the difference and decrement the lower bound
                $lowerbound = $lowerbound - ($upperbound - $totalpages);
                $upperbound = $totalpages;
            }
            for($i=$lowerbound;$i<=$upperbound;$i++)
            {
                $arr[] = $i;
            }
        }
        return $arr;    
    }	 
    // Advance pagination logic
    function advance_pagination_links($totalpages, $selectedpage)
    {
        $i = 0;
        $value = 0;
        $arr = array();
        $lower_arr = array();
        $upper_arr = array();
        
		$indexer = array("4","40","50","400","500","4000","5000","40000","50000");
		$patter = array("1", "1", "1", "4", "40", "50", "400", "500", "4000", "5000", "40000");
        if ($selectedpage == 1)
        {
			// 15 links
			for($i = 1; $i <= 16; $i++)
			{
				if($i <= 7)
				  $value = $i;
				else
  				  $value = $value + $indexer[$i-8];
				if($value > $totalpages)
				   $value = $totalpages;
                if(!in_array($value,$arr))
				  $arr[] = $value;
			}
        }
		
        if ($selectedpage > 1)
        {   
		    if ($totalpages <= 16)
			{
			    for($i = 1; $i <= 16; $i++)
				{
				    $value = $i;
					if($value > $totalpages)
					   $value = $totalpages;
					if(!in_array($value,$arr))
					  $arr[] = $value;
				}
			}
			else
			{
				for ($i = 0; $i <= 7; $i++)
				{	
					if($value == 0)
					   $value = $selectedpage - $patter[$i];			
					else
					   $value = $value - $patter[$i];
					
					if($value > 0)
					{
					   if(!in_array($value,$lower_arr))
					     $lower_arr[] = $value;
					}
					 
				}
				$value = 0;
				for ($i = 0; $i <= 7; $i++)
				{
					if($value == 0)
					   $value = $selectedpage + $patter[$i];			
					else
					   $value = $value + $patter[$i];
					
					if($value > $totalpages)
					  $value = $totalpages;  
					   
					if(!in_array($value,$upper_arr))
					   $upper_arr[] = $value;
				}
				//// add lower array values
				for ($i = 0; $i <= count($lower_arr) - 1; $i++)
				{
					$rev_index = (count($lower_arr) - 1) - $i;
					$arr[] = $lower_arr[$rev_index];
				}
				//// add selected record
                if($selectedpage<$totalpages){
                    $arr[] = $selectedpage;   
                }
				//// add upper array values
				for ($i = 0; $i <= count($upper_arr) - 1; $i++)
				{
                    $arr[] = $upper_arr[$i];    			
				}
			}
        }
        return $arr;
    }
}
?>