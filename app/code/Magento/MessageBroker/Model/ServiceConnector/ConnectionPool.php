<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\MessageBroker\Model\ServiceConnector;

use Magento\Framework\ObjectManagerInterface;

/**
 * Pool of connection providers.
 */
class ConnectionPool
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var array
     */
    private $connectionMap;

    /**
     * @var array
     */
    private $registry;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param string[] $connectionMap
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        array $connectionMap = []
    ) {
        $this->objectManager = $objectManager;
        $this->connectionMap = $connectionMap;
    }

    /**
     * Retrieve connection for specified service by type.
     *
     * @param string $serviceName
     * @param string $type
     * @param array $params
     *
     * @return mixed
     */
    public function retrieveByConnectionType(string $serviceName, string $type, array $params)
    {
        if (!isset($this->connectionMap[$serviceName][$type])) {
            throw new \InvalidArgumentException('Invalid connection type or service name provided.');
        }

        if (!isset($this->registry[$serviceName][$type])) {
            $this->registry[$serviceName][$type] = $this->objectManager->create(
                $this->connectionMap[$serviceName][$type],
                $params
            );
        }

        return $this->registry[$serviceName][$type];
    }
}
