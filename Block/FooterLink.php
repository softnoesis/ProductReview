<?php
/**
 * Softnoesis
 * Copyright(C) 21/2022 Softnoesis <contact@softnoesis.com>
 * @package Softnoesis_RelatedProduct
 * @copyright Copyright(C) 2015 Softnoesis (contact@softnoesis.com)
 * @author Softnoesis <contact@softnoesis.com>
 */
namespace Softnoesis\ProductReview\Block;

class FooterLink extends \Magento\Framework\View\Element\Html\Link
{
    public function _toHtml()
    {
        if (!$this->_scopeConfig->isSetFlag('Softnoesis_tab/general/enable')
        ) {
            return '';
        }
        return parent::_toHtml();
    }
}
