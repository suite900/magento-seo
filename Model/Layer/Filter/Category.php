<?php

class S900_Seo_Model_Layer_Filter_Category extends S900_Seo_Model_Layer_Filter_Abstract
{
    /**
     * Active Category Id
     *
     * @var int
     */
    protected $_categoryId;

    /**
     * Applied Category
     *
     * @var Mage_Catalog_Model_Category
     */
    protected $_appliedCategory = null;

    /**
     * Class constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->_requestVar = 'cat';
    }

    /**
     * Get filter value for reset current filter state
     *
     * @return mixed
     */
    public function getResetValue()
    {
        if ($this->_appliedCategory) {
            /**
             * Revert path ids
             */
            $pathIds = array_reverse($this->_appliedCategory->getPathIds());
            $curCategoryId = $this->getLayer()->getCurrentCategory()->getId();
            if (isset($pathIds[1]) && $pathIds[1] != $curCategoryId) {
                return $pathIds[1];
            }
        }
        return null;
    }

    /**
     * Apply category filter to layer
     *
     * @param   Zend_Controller_Request_Abstract $request
     * @param   Mage_Core_Block_Abstract $filterBlock
     * @return  Mage_Catalog_Model_Layer_Filter_Category
     */
    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock)
    {
        $filter =  $request->getParam($this->getRequestVar());
        if (!$filter) {
            return $this;
        }

        if( is_numeric($filter) ){
            $this->_categoryId = $filter; 
            Mage::register('current_category_filter', $this->getCategory(), true);
        }else{
            //else if $filter is a category name
            $catName = urldecode($filter);
            $category = Mage::getResourceModel('catalog/category_collection')
                        ->addFieldToFilter('name', $catName)
                        ->getFirstItem();

            Mage::register('current_category_filter',$category, true);

            $filter  = $category->getId();
            $this->_categoryId = $category->getId();

        }
        
        
        

        $this->_appliedCategory = Mage::getModel('catalog/category')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($filter);

        if ($this->_isValidCategory($this->_appliedCategory)) {
            $this->getLayer()->getProductCollection()
                ->addCategoryFilter($this->_appliedCategory);

            $this->getLayer()->getState()->addFilter(
                $this->_createItem($this->_appliedCategory->getName(), $filter)
            );
        }

        return $this;
    }

    /**
     * Validate category for be using as filter
     *
     * @param   Mage_Catalog_Model_Category $category
     * @return unknown
     */
    protected function _isValidCategory($category)
    {
        return $category->getId();
    }

    /**
     * Get filter name
     *
     * @return string
     */
    public function getName()
    {
        return Mage::helper('catalog')->__('Category');
    }

    /**
     * Get selected category object
     *
     * @return Mage_Catalog_Model_Category
     */
    public function getCategory()
    {
        if (!is_null($this->_categoryId)) {
            $category = Mage::getModel('catalog/category')
                ->load($this->_categoryId);
            if ($category->getId()) {
                return $category;
            }
        }
        return $this->getLayer()->getCurrentCategory();
    }

    /**
     * Get data array for building category filter items
     *
     * @return array
     */
    protected function _getItemsData()
    {
        $key = $this->getLayer()->getStateKey().'_SUBCATEGORIES';
        $data = $this->getLayer()->getAggregator()->getCacheData($key);

        if ($data === null) {
            $categoty   = $this->getCategory();
            /** @var $categoty Mage_Catalog_Model_Categeory */
            $categories = $categoty->getChildrenCategories();

            $this->getLayer()->getProductCollection()
                ->addCountToCategories($categories);

            $data = array();
            foreach ($categories as $category) {
                if ($category->getIsActive() && $category->getProductCount()) {
                    $data[] = array(
                        'label' => Mage::helper('core')->escapeHtml($category->getName()),
                        'value' => $category->getId(),
                        'count' => $category->getProductCount(),
                    );
                }
            }
            $tags = $this->getLayer()->getStateTags();
            $this->getLayer()->getAggregator()->saveCacheData($data, $key, $tags);
        }
        return $data;
    }
}
