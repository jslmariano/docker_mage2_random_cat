<?php
namespace Orba\RandomCat\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Store\Model\ScopeInterface;

/**
 * This class describes a data which a default helper.
 */
class Data extends AbstractHelper
{
    /**
     * Config xml path for randomcats
     *
     * @var        string
     */
    const XML_PATH_RANDOMCATS = 'randomcats/';

    /**
     * Gets the configuration value.
     *
     * @param      string  $field     The field
     * @param      string  $store_id  The store identifier
     *
     * @return     mixed  The configuration value.
     */
    public function getConfigValue($field, $store_id = null)
    {
        return $this->scopeConfig->getValue(
            $field, ScopeInterface::SCOPE_STORE, $store_id
        );
    }

    /**
     * Gets the general configuration.
     *
     * @param      string  $code      The code
     * @param      string  $store_id  The store identifier
     *
     * @return     mixed  The general configuration.
     */
    public function getGeneralConfig($code, $store_id = null)
    {

        return $this->getConfigValue(self::XML_PATH_RANDOMCATS .'general/'. $code, $store_id);
    }

    /**
     * Determines whether the specified string is json.
     *
     * @param      string   $string  The string
     *
     * @return     boolean  True if the specified string is json, False otherwise.
     */
    public function isJSON($string){
       return is_string($string) && is_array(json_decode($string, true)) ? true : false;
    }
}
