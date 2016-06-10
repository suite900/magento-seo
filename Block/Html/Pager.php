<?php

class S900_Seo_Block_Html_Pager extends Mage_Page_Block_Html_Pager
{
	public function getPreviousPageUrl()
    {
        $prev = $this->getCollection()->getCurPage() -1 == 1 ? null : $this->getCollection()->getCurPage() -1;
        return $this->getPageUrl($prev);
    }
    
    public function getPageUrl($page)
    {

    	$page = $page == 1 ? array($this->getPageVarName()=>null) : array($this->getPageVarName()=>$page);
        return $this->getPagerUrl($page);
    }
    
}