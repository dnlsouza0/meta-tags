<?php

namespace Hibrido\MetaTags\Block;

use Magento\Framework\View\Element\Template;
use Hibrido\MetaTags\Helper\Config;
use Magento\Cms\Model\Page;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Api\StoreRepositoryInterface;

class Tag extends Template
{
    protected $configHelper;
    protected $page;
    protected $store;
    protected $storeRepository;

    public function __construct(
        Template\Context $context,
        Config $configHelper,
        Page $page,
        StoreInterface $store,
        StoreRepositoryInterface $storeRepository,
        array $data = []
    )
    {
        $this->configHelper = $configHelper;
        $this->page = $page;
        $this->store = $store;
        $this->storeRepository = $storeRepository;
        parent::__construct($context, $data);
    }

    public function getCurrentPageId(){
        return $this->page->getId();
    }

    public function moduleIsEnable(){
        return $this->configHelper->isModuleEnabled();
    }

    public function pageHaveMultStoreViews(){
        if (is_array($this->page->getStoreId())){
            if (count($this->page->getStoreId()) > 1){
                return true;
            }
            else if ($this->page->getStoreId()[0] == 0){
                return true;
            }
            return false;
        }
        return false;
    }

    public function getPage(){
        return $this->page;
    }

    public function getStoreLanguage($storeId){
        return $this->configHelper->getConfigValue('general/locale/code', $storeId);
    }

    public function getAllStoresView(){
        $storeViews = $this->page->getStoreId();
        if (count($storeViews) == 1 && $storeViews[0] == 0){
            return $this->storeRepository->getList();
        }
        return $storeViews;
    }
}
