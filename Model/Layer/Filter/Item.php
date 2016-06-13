<?php

class S900_Seo_Model_Layer_Filter_Item extends Mage_Catalog_Model_Layer_Filter_Item
{
	/**
     * Get filter item url
     *
     * @return string
     */
    public function getUrl()
    {   
        $query = array(
            $this->getFilter()->getRequestVar()=>$this->getLabel(),
            Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
        );
        return Mage::getUrl('*/*/*', array('_current'=>true, '_use_rewrite'=>true, '_query'=>$query));

    }
}