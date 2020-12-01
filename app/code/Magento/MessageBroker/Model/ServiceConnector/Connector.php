<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\MessageBroker\Model\ServiceConnector;

use Magento\MessageBroker\Model\Installer;
use Magento\MessageBroker\Model\ServiceConnector\Configuration\ConfigurationProviderPool;

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
     * @var array
     */
    private $connectionTypeMap;

    /**
     * @param ConnectionPool $connectionPool
     * @param ConfigurationProviderPool $configurationProviderPool
     * @param array $connectionTypeMap
     */
    public function __construct(
        ConnectionPool $connectionPool,
        ConfigurationProviderPool $configurationProviderPool,
        array $connectionTypeMap = []
    ) {
        $this->connectionPool = $connectionPool;
        $this->configurationProviderPool = $configurationProviderPool;
        $this->connectionTypeMap = $connectionTypeMap;
    }

    /**
     * Retrieve service connection.
     *
     * @param string $connectionName
     * @return mixed
     */
    public function getConnection(string $connectionName)
    {
        $connectionType = $this->connectionTypeMap[$connectionName] ?? Installer::DEFAULT_CONNECTION_TYPE;

        return $this->connectionPool->retrieveByConnectionType(
            $connectionName,
            $connectionType,
            $this->configurationProviderPool->retrieveByConnectionType($connectionType, $connectionName)
        );
    }
}
