<?php
/**
 * Softnoesis
 * Copyright(C) 21/2022 Softnoesis <contact@softnoesis.com>
 * @package Softnoesis_RelatedProduct
 * @copyright Copyright(C) 2015 Softnoesis (contact@softnoesis.com)
 * @author Softnoesis <contact@softnoesis.com>
 */
namespace Softnoesis\ProductReview\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        $this->_view->loadLayout();
        $title = __('Reviews');
        $this->_view->getPage()->getConfig()->getTitle()->prepend($title);
        $this->_view->renderLayout();
    }
}
