<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\CatalogMessageBroker\Model\StorefrontConnector;

use Magento\CatalogMessageBroker\Model\StorefrontConnector\Configuration\ConfigurationProviderPool;
use Magento\CatalogStorefrontApi\Api\CatalogInterface;

/**
 * Class responsible for providing connection by specified connection type
 */
class Connector
{
    /**
     * @var ConnectionPool
     */
    private $connectionPool;

    /**
     * @var ConfigurationProviderPool
     */
    private $configurationProviderPool;

    /**
     * @var string
     */
    private $connectionType;

    /**
     * @param ConnectionPool $connectionPool
     * @param ConfigurationProviderPool $configurationProviderPool
     * @param string $connectionType
     */
    public function __construct(
        ConnectionPool $connectionPool,
        ConfigurationProviderPool $configurationProviderPool,
        string $connectionType = \Magento\MessageBroker\Model\Installer::DEFAULT_CONNECTION_TYPE
    ) {
        $this->connectionPool = $connectionPool;
        $this->configurationProviderPool = $configurationProviderPool;
        $this->connectionType = $connectionType;
    }

    /**
     * Retrieve storefront connection.
     *
     * @param string $connectionName
     * @return CatalogInterface
     */
    public function getConnection(string $connectionName): CatalogInterface
    {
        return $this->connectionPool->retrieveByConnectionType(
            $this->connectionType,
            $this->configurationProviderPool->retrieveByConnectionType($this->connectionType, $connectionName)
        );
    }
}
