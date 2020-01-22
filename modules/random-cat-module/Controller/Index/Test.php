<?php
namespace Orba\RandomCat\Controller\Index;

class Test extends \Magento\Framework\App\Action\Action
{
    /**
     * @var        \Magento\Framework\View\Result\PageFactory
     */
    protected $_page_factory;

    /**
     * Constructs a new instance.
     *
     * @param      \Magento\Framework\App\Action\Context       $context       The context
     * @param      \Magento\Framework\View\Result\PageFactory  $page_factory  The page factory
     * @param      \Orba\RandomCat\Block\Product\CustomImage   $custom_image  The custom image
     *
     * @return     <type>                                      ( description_of_the_return_value )
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $page_factory,
        \Orba\RandomCat\Block\Product\CustomImage $custom_image
    )
    {
        $this->_page_factory = $page_factory;
        $this->_custom_image = $custom_image;
        return parent::__construct($context);
    }


    public function execute()
    {
        fn_print_r(
            get_class($this->_custom_image),
            $this->_custom_image->getImageUrl(),
            $this->_custom_image->isAPiEnabled()
        );
        exit;
    }
}