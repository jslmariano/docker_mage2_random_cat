<?php

namespace Orba\RandomCat\Logger;

use \Magento\Framework\Logger\Handler\Base;

/**
 * This class describes a logger handler.
 */
class Handler extends Base
{
    const FILE_NAME = '/var/log/random_cat_images.log';
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = Logger::INFO;

    /**
     * File name
     * @var string
     */
    protected $fileName = HANDLER::FILE_NAME;
}
