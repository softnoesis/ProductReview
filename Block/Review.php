<?php
/**
 * Softnoesis
 * Copyright(C) 21/2022 Softnoesis <contact@softnoesis.com>
 * @package Softnoesis_RelatedProduct
 * @copyright Copyright(C) 2015 Softnoesis (contact@softnoesis.com)
 * @author Softnoesis <contact@softnoesis.com>
 */
namespace Softnoesis\ProductReview\Block;

class Review extends \Magento\Framework\View\Element\Template
{

    protected $_reviewCollectionFactory;
    protected $_storeManager;
    protected $_reviewFactory;
    protected $_product;
    protected $_urlInterface;
    protected $_scopeConfig;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Review\Model\ResourceModel\Review\CollectionFactory $reviewCollectionFactory,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\API\ProductRepositoryInterface $productRepository,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
         $this->_reviewCollectionFactory = $reviewCollectionFactory;
         $this->_storeManager = $storeManager;
         $this->_reviewFactory = $reviewFactory;
         $this->productFactory = $productFactory;
         $this->productRepository = $productRepository;
         $this->_urlInterface = $urlInterface;
         $this->_scopeConfig = $scopeConfig;
         parent::__construct($context, $data);
    }
   
    public function getCurrentStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getReviewsCollection()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                ''
            )->setAvailableLimit([5 => 5, 10 => 10, 15 => 15, 20 => 20])
                ->setShowPerPage(true)->setCollection(
                    $this->getReviewsCollection()
                );
                $this->setChild('pager', $pager);
            $this->getReviewsCollection()->load();
        }
        return $this;
    }
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
    public function getReviewsCollection()
    {
         $setreviewpagesize = $this->_scopeConfig->getValue('Softnoesis_tab/general/reviewpagelimit', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
         $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
         $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : $setreviewpagesize;
         $currentStoreId = $this->getCurrentStoreId();
         $reviewsCollection = $this->_reviewCollectionFactory->create()
         ->addFieldToSelect('*')
         ->addStoreFilter($currentStoreId)
         ->addStatusFilter(\Magento\Review\Model\Review::STATUS_APPROVED)
         ->setDateOrder()
         ->setPageSize($pageSize)
         ->setCurPage($page)
         ->addRateVotes();
         return $reviewsCollection;
    }

    public function getHomeReviewsCollection()
    {
         $homepagesize = $this->_scopeConfig->getValue('Softnoesis_tab/general/homepagelimit', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
         $StoreId = $this->getCurrentStoreId();
         $homereviewsCollection = $this->_reviewCollectionFactory->create()
         ->addFieldToSelect('*')
         ->addStoreFilter($StoreId)
         ->addStatusFilter(\Magento\Review\Model\Review::STATUS_APPROVED)
         ->setDateOrder()
         ->setPageSize($homepagesize)
         ->addRateVotes();
         return $homereviewsCollection;
    }
    public function getProduct($id)
    {
        return $this->productRepository->getById($id);
    }

    public function getBaseMediaDir()
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    public function getRatingSummaryCount($product)
    {
        $this->_reviewFactory->create()->getEntitySummary($product, $this->_storeManager->getStore()->getId());
        $ratingSummary = $product->getRatingSummary()->getReviewsCount();
        return $ratingSummary;
    }
    public function getRatingSummary($product)
    {
        $this->_reviewFactory->create()->getEntitySummary($product, $this->_storeManager->getStore()->getId());
        $ratingSummary = $product->getRatingSummary()->getRatingSummary();
        return $ratingSummary;
    }
    public function getReviewConfigValue()
    {
        $myconfig = $this->_scopeConfig->getValue('Softnoesis_tab/general/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $myconfig;
    }
    public function getHomeConfigValue()
    {
        $mycatalog = $this->_scopeConfig->getValue('Softnoesis_tab/general/homeenable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $mycatalog;
    }
}
