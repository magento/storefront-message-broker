<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\MessageBroker\Model\ServiceConnector;

use Magento\MessageBroker\Model\Installer;
use Magento\MessageBroker\Model\ServiceConnector\Configuration\ConfigurationProvider;

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
     * @var ConfigurationProvider
     */
    private $configurationProvider;

    /**
     * @var string
     */
    private $connectionType;

    /**
     * @param ConnectionPool $connectionPool
     * @param ConfigurationProvider $configurationProvider
     * @param string $connectionType
     */
    public function __construct(
        ConnectionPool $connectionPool,
        ConfigurationProvider $configurationProvider,
        string $connectionType = Installer::DEFAULT_CONNECTION_TYPE
    ) {
        $this->connectionPool = $connectionPool;
        $this->configurationProvider = $configurationProvider;
        $this->connectionType = $connectionType;
    }

    /**
     * Retrieve service connection.
     *
     * @param string $connectionName
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function getConnection(string $connectionName)
    {
        $configuration = $this->configurationProvider->provide($this->connectionType, $connectionName);

        return $this->connectionPool->retrieve($connectionName, $this->connectionType, $configuration);
    }
}
