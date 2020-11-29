<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\ConfigurationImportMessageBroker\Event\Data;

use Magento\ConfigurationImportMessageBroker\Event\Data\Meta;
use Magento\ConfigurationImportMessageBroker\Event\Data\Data;

/**
 * Changed config object
 */
class ChangedConfig
{
    /**
     * @var Data
     */
    private $data;

    /**
     * @var Meta
     */
    private $meta;

    /**
     * @param \Magento\ConfigurationImportMessageBroker\Event\Data\Meta $meta
     * @param \Magento\ConfigurationImportMessageBroker\Event\Data\Data $data
     */
    public function __construct(Meta $meta, Data $data)
    {
        $this->meta = $meta;
        $this->data = $data;
    }

    /**
     * Get changed config metadata
     *
     * @return \Magento\ConfigurationImportMessageBroker\Event\Data\Meta
     */
    public function getMeta(): Meta
    {
        return $this->meta;
    }

    /**
     * Get changed config data
     *
     * @return \Magento\ConfigurationImportMessageBroker\Event\Data\Data
     */
    public function getData(): Data
    {
        return $this->data;
    }
}
