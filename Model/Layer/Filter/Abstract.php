<?php

class S900_Seo_Model_Layer_Filter_Abstract extends Mage_Catalog_Model_Layer_Filter_Abstract
{
	protected function _createItem($label, $value, $count=0)
    {
        return Mage::getModel('s900_seo/layer_filter_item')
            ->setFilter($this)
            ->setLabel($label)
            ->setValue($value)
            ->setCount($count);
    }
}