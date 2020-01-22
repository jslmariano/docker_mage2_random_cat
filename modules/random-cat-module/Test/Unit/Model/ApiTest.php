<?php

namespace Orba\RandomCat\Test\Unit\Model;

use Orba\RandomCat\Helper\Data as RandomCat_Helper;
use Orba\RandomCat\Logger\Logger;
use Orba\RandomCat\Model\Api;
use \Magento\Framework\Json\Helper\Data as Json_Helper;
use \Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * This class describes an api test for ramdom cat api model.
 */
class ApiTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $_objectManager;

    /**
     * @var Orba\RandomCat\Model\Api
     */
    protected $_model;

    /**
     * Current testing flags
     *
     * @var        integer
     */
    const FLAG_GOOD_CALL          = 1;
    const FLAG_BAD_CALL           = 2;
    const FLAG_RESOURCE_FAIL_CALL = 3;

    /**
     * This function is called before the test runs.
     * Ideal for setting the values to variables or objects.
     */
    protected function setUp()
    {
        $this->_objectManager   = new ObjectManager($this);
        $this->json_helper      = $this->_objectManager->getObject(Json_Helper::class);
        $this->randomcat_helper = $this->_objectManager->getObject(RandomCat_Helper::class);
        $this->randomcat_logger = $this->getMockBuilder(Logger::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * This function is called after the test runs.
     * Ideal for setting the values to variables or objects.
     */
    public function tearDown()
    {
        $this->_curl_factory = null;
        $this->_model        = null;
    }

    /**
     * This funtion is called and test the api for case good scenario
     */
    public function testGoodApiCall()
    {
        $expected_result = array("url" => "http://randomcatapi.orbalab.com/images/cat7.jpg");
        $result          = $this->getModelApiFactory(self::FLAG_GOOD_CALL)->loadFromApi();
        $this->assertEquals($result, $expected_result);
    }

    /**
     * This funtion is called and test the api for case failed call scenario
     */
    public function testFailedApiCall()
    {
        $model                   = $this->getModelApiFactory(self::FLAG_BAD_CALL);
        $result_from_api         = $model->loadFromApi();
        $result_random_cat_image = $model->getRadomCatImage();
        $this->assertEquals($result_from_api, array());
        $this->assertEquals($result_random_cat_image, null);
    }

    /**
     * This funtion is called and test the api for case bad image scenario
     */
    public function testBadImage()
    {
        $model                   = $this->getModelApiFactory(self::FLAG_RESOURCE_FAIL_CALL);
        $result_random_cat_image = $model->getRadomCatImage();
        $result_is_ok            = $model->isCatUrlOk();
        $this->assertEquals($result_random_cat_image, null);
        $this->assertEquals($result_is_ok, false);
    }

    /**
     * Gets the model api factory.
     *
     * This creates the model with different curlfactory response dependeing on
     * the test case scenarios
     *
     * @param      int  $_current_test_flag  The current test flag
     *
     * @return     Orba\RandomCat\Model\Api  The model api factory.
     */
    public function getModelApiFactory($_current_test_flag = self::FLAG_GOOD_CALL)
    {
        switch ($_current_test_flag) {
            case self::FLAG_BAD_CALL:
                $message = $this->getNotFoundResponse();
                break;
            case self::FLAG_RESOURCE_FAIL_CALL:
                $message = $this->getResourceNotFoundResponse();
                break;
            default:
                $message = $this->getGoodResponse();
                break;
        }
        $this->_curl_factory = $this->getCurlFactory(
            $this->_objectManager, $message
        );
        $this->_model = new Api(
            $this->_curl_factory,
            $this->json_helper,
            $this->randomcat_helper,
            $this->randomcat_logger
        );
        return $this->_model;
    }

    /**
     * Gets the good response sample for curl factory return data.
     *
     * @return     string  The good response.
     */
    public function getGoodResponse()
    {
        return static::returnValue(
            'HTTP/1.1 200 OK' .
            "\r\n" . 'Server: nginx/1.14.0' .
            "\r\n\r\n" . '{"url":"http://randomcatapi.orbalab.com/images/cat7.jpg"}'
        );
    }

    /**
     * Gets the cat not found response sample for curl factory return data.
     *
     * @return     string  The good response.
     */
    public function getNotFoundResponse()
    {
        return static::returnValue(
            'HTTP/1.1 404 Not Found' .
            "\r\n" . 'Server: nginx/1.14.0' .
            "\r\n\r\n" . '404 - cat not found'
        );
    }

    /**
     * Gets the resource not found response sample for curl factory return data.
     *
     * @return     string  The good response.
     */
    public function getResourceNotFoundResponse()
    {
        return static::returnValue(
            'HTTP/1.1 404 Not Found' .
            "\r\n" . 'Server: nginx/1.14.0' .
            "\r\n\r\n" . '404 - resource not found'
        );
    }

    /**
     * Gets the curl factory with specific mock reponse message.
     *
     * @param      \Magento\Framework\TestFramework\Unit\Helper\ObjectManager  $objectHelper   The object helper
     * @param      string                                                      $mock_response  The mock response
     *
     * @return     Magento\Framework\HTTP\Adapter\CurlFactory                                                      The curl factory.
     */
    protected function getCurlFactory(ObjectManager $objectHelper, $mock_response = "")
    {
        $httpClient = $this->getMockBuilder('Magento\Framework\HTTP\Adapter\Curl')
            ->disableOriginalConstructor()
            ->setMethods(['read'])
            ->getMock();

        $httpClient->expects(static::any())
            ->method('read')
            ->will($mock_response);

        $curlFactory = $this->getMockBuilder('Magento\Framework\HTTP\Adapter\CurlFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create', 'getHttpClient'])
            ->getMock();
        $curlFactory->expects(static::any())->method('create')->willReturn($httpClient);
        $curlFactory->expects(static::any())->method('getHttpClient')->willReturn($httpClient);

        return $curlFactory;
    }

}
