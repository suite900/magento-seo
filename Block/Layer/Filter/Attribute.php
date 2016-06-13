<?php

class S900_Seo_Block_Layer_Filter_Attribute extends Mage_Catalog_Block_Layer_Filter_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->_filterModelName = 's900_seo/layer_filter_attribute';
    }
    
    protected function _prepareFilter()
    { 

        $this->_filter->setAttributeModel($this->getAttributeModel());
        return $this;
    }

    
}
