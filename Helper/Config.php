<?php

namespace Hibrido\MetaTags\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Laminas\Log\Logger;
use Laminas\Log\Writer\Stream;

class Config extends AbstractHelper
{
    const BASE = 'metatags';
    const GENERAL_GROUP = '/general';
    const MODULE_ENABLED = self::BASE . self::GENERAL_GROUP . '/active';

    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    public function getConfigValue($field, $storeId = null){
        try {
            return $this->scopeConfig->getValue($field, ScopeInterface::SCOPE_STORE, $storeId);
        }
        catch(\Exception $e){
            $this->createLog($e->getMessage());
            throw $e;
        }
    }

    public function isModuleEnabled($storeId = null){
        try {

            return filter_var(
                $externalServiceKey = $this->scopeConfig->getValue(
                    self::MODULE_ENABLED,
                    ScopeInterface::SCOPE_STORE,
                    $storeId
                ),
                FILTER_VALIDATE_BOOLEAN
            );

        } catch (\Exception $e) {
            $this->createLog($e->getMessage);
            throw $e;
        }
    }

    public static function createLog($data, $logName = false){
        if ($logName){
            $logName = 'hibrido_log';
        }
        $writer = new Stream(BP . '/var/log/' . $logName . '.log');
        $logger = new Logger();
        $logger->addWriter($writer);
        $logger->info(json_encode($data, JSON_PRETTY_PRINT));
    }
}
