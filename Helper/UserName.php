<?php

namespace Molsm\CustomerAuthByUsername\Helper;

use Magento\Store\Model\ScopeInterface;

class UserName extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_AUTHENTICATE_BY_USER_NAME = 'customer/startup/authenticate_by_user_name';

    /** @var \Magento\Customer\Model\CustomerFactory $customerFactory */
    protected $customerFactory;

    protected $scopeConfig;

    public function __construct(\Magento\Customer\Model\CustomerFactory $customerFactory, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
        $this->customerFactory = $customerFactory;
    }

    /**
     * @return bool
     */
    public function isEnabledAuthenticationByLoginName()
    {
        return (bool) $this->scopeConfig->getValue(self::XML_PATH_AUTHENTICATE_BY_USER_NAME, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param $login
     * @return mixed
     * @throws \UnexpectedValueException
     */
    public function retrieveCustomerEmail($login)
    {
        /** @var \Magento\Customer\Model\ResourceModel\Customer\Collection $collection */
        $collection = $this->customerFactory->create()->getCollection();
        $collection->addAttributeToFilter('user_name', ['eq' => $login]);

        if ($collection->getSize() === 1) {
            if ($customerEmail = $collection->getFirstItem()->getEmail()) {
                return $customerEmail;
            }
        }

        throw new \UnexpectedValueException('Can not retrieve correct customer email value');
    }

    /**
     * @param $value
     * @return bool
     */
    public function isEmail($value)
    {
        if(filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }
}