<?php

namespace Orba\RandomCat\Model;

/**
 * This class describes an api for random cats.
 */
class Api
{
    /**
     * Logging instance
     * @var \Orba\RandomCat\Logger\Logger
     */
    protected $_logger;

    /**
     * Constructs a new instance.
     *
     * @param      \Magento\Framework\HTTP\Adapter\CurlFactory  $curl_factory  The curl factory
     * @param      \Magento\Framework\Json\Helper\Data          $json_helper   The json helper
     * @param      \Orba\RandomCat\Helper\Data                  $helper        The helper
     * @param      \Orba\RandomCat\Logger\Logger                $logger        The logger
     */
    public function __construct(
        \Magento\Framework\HTTP\Adapter\CurlFactory $curl_factory,
        \Magento\Framework\Json\Helper\Data $json_helper,
        \Orba\RandomCat\Helper\Data $helper,
        \Orba\RandomCat\Logger\Logger $logger,
        \Magento\Framework\Serialize\Serializer\Json $json_serializer

    ) {
        $this->_curl_factory    = $curl_factory;
        $this->_json_helper     = $json_helper;
        $this->_helper          = $helper;
        $this->_logger          = $logger;
        $this->_json_serializer = $json_serializer;
    }

    /**
     * Determines if api enabled.
     *
     * @return     boolean  True if api enabled, False otherwise.
     */
    public function isApiEnabled()
    {
        return $this->getHelper()->getGeneralConfig('enable');
    }

    /**
     * Gets the curl factory.
     *
     * @return     string  The curl factory.
     */
    public function getCurlFactory()
    {
        return $this->_curl_factory;
    }

    /**
     * Gets the logger.
     *
     * @return     \Orba\RandomCat\Logger\Logger  The logger.
     */
    public function getLogger()
    {
        return $this->_logger;
    }

    /**
     * Gets the helper.
     *
     * @return     \Orba\RandomCat\Helper\Data  The helper.
     */
    public function getHelper()
    {
        return $this->_helper;
    }

    /**
     * Gets the api url.
     *
     * @return     string  The api url.
     */
    public function getApiUrl()
    {
        return $this->getHelper()->getGeneralConfig('api_url');
    }

    /**
     * Gets the api key.
     *
     * @return     string  The api key.
     */
    public function getApiKey()
    {
        return $this->getHelper()->getGeneralConfig('api_url_key');
    }

    /**
     * Gets the radom cat image.
     *
     * @return     string  The radom cat image.
     */
    public function getRadomCatImage()
    {
        $image_json = $this->loadFromApi();

        if (!isset($image_json['url'])) {
            return null;
        }

        if (!$this->isCatUrlOk($image_json['url'])) {
            return null;
        }

        return $image_json['url'];
    }

    /**
     * Determines whether the specified cat image url is ok ( http header code ).
     *
     * @param      string   $image_url  The image url
     *
     * @return     boolean  True if the specified image url is cat url ok, False otherwise.
     */
    public function isCatUrlOk($image_url = "")
    {
        $result = $this->requestCurl($image_url);
        $code   = \Zend_Http_Response::extractCode($result);
        $body   = \Zend_Http_Response::extractBody($result);
        if ($code != 200) {
            $this->getLogger()->log('notice','Invalid cat image url',
                array('url' => $image_url, 'response' => $body)
            );
        }
        return ($code == 200);
    }

    /**
     * Loads a string cat image url from random cat api.
     *
     * @return     string  Json response from the api
     */
    public function loadFromApi()
    {
        $url   = $this->getApiUrl();
        $query = [
            'api_key' => $this->getApiKey(),
        ];
        $dynamic_url = $url . '?' . http_build_query($query);
        $result      = $this->requestCurl($dynamic_url);
        $body        = \Zend_Http_Response::extractBody($result);
        $code        = \Zend_Http_Response::extractCode($result);
        $response    = null;

        if ($code != 200) {
            $this->getLogger()->log('notice','API response failure', array('body' => $body));
            return array();
        }

        return $this->_json_serializer->unserialize($body);
    }

    /**
     * Request a curl with method get
     *
     * @param      string  $url_api  The url api
     *
     * @return     string  Curl Response
     */
    public function requestCurl($url_api = "")
    {
        $http_adapter = $this->_curl_factory->create();
        $http_adapter->write(
            \Zend_Http_Client::GET,
            $url_api,
            '1.1',
            ["Content-Type:application/json"]
        );
        $result = $http_adapter->read();
        return $result;
    }
}
