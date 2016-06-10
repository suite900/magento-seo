<?php


class S900_Seo_Block_Product_List extends Mage_Catalog_Block_Product_List
{
	protected $_pageVarName;
	//this block is loaded twice in a page: head, content
	// so the collection is save in registry to reduce db query
	protected function _getProductCollection()
	{

		if( Mage::register('s900_product_collection') ){
			$this->_productCollection = Mage::registry('s900_product_collection');
		}
		if (is_null($this->_productCollection)) {
			parent::_getProductCollection(); 
		}
		
		return $this->_productCollection;
	}


	public function getPaginationMarkup()
	{
		$markup = $this->getChild('pagination_markup');

		if($markup instanceof Varien_Object )
		{

		}
		return '';
	}

	public function getPageVarName()
	{
		if( is_null($this->_pageVarName) ){
			$toolbar = $this->getLayout()->createBlock('catalog/product_list_toolbar');
			$this->_pageVarName = $toolbar->getPageVarName();
		}
		return $this->_pageVarName;
	}

	public function getPageUrl($page)
    {
        return $this->getPagerUrl(array($this->getPageVarName()=>$page));
    }
    
	public function getPagerUrl($params=array())
    {
        $urlParams = array();
        $urlParams['_current']  = true;
        $urlParams['_escape']   = true;
        $urlParams['_use_rewrite']   = true;
        $urlParams['_query']    = $params;
        return $this->getUrl('*/*/*', $urlParams);
    }
	


}