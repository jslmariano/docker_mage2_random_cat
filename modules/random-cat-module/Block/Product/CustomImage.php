<?php
namespace Orba\RandomCat\Block\Product;

class CustomImage extends \Magento\Catalog\Block\Product\Image
{
    /**
     * @var Magento\Framework\HTTP\Adapter\CurlFactory
     */
    public $curlFactory;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    public $jsonHelper;

    /**
     * Constructs a new instance.
     *
     * @param      \Magento\Framework\View\Element\Template\Context  $context
     * @param      \Orba\RandomCat\Model\Api                         $cat_api  The cat api
     * @param      array                                             $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Orba\RandomCat\Helper\Data $helper,
        \Orba\RandomCat\Model\Api $cat_api,
        array $data = []
    ) {
        if (isset($data['template'])) {
            $this->setTemplate($data['template']);
            unset($data['template']);
        }
        parent::__construct($context, $data);
        $this->_cat_api = $cat_api;
        $this->_helper = $helper;
    }

    /**
     * Gets the cat not found.
     *
     * @return     string  The cat not found.
     */
    public function getCatNotFound()
    {
        return $this->getViewFileUrl('Orba_RandomCat::images/404.jpg');
    }

    /**
     * Gets the original image url.
     *
     * @return     string  The original image url.
     */
    public function getOriginalImageUrl()
    {
        return parent::getImageUrl();
    }

    /**
     * Gets the image url.
     *
     * @return     string  The image url.
     */
    public function getImageUrl()
    {
        if (!$this->_helper->getGeneralConfig('enable')) {
            return $this->getOriginalImageUrl();
        }

        $image_url = $this->_cat_api->getRadomCatImage();
        if (is_null($image_url)) {
            return $this->getCatNotFound();
        }
        return $image_url;
    }

}
