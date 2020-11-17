<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\CatalogMessageBroker\Model\StorefrontConnector;

use Magento\CatalogStorefrontApi\Api\CatalogInterface;
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
     * @var CatalogInterface[]
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
     * Retrieve connection by specified type.
     *
     * @param string $type
     * @param array $params
     *
     * @return CatalogInterface
     *
     * @throws \InvalidArgumentException
     */
    public function retrieveByConnectionType(string $type, array $params): CatalogInterface
    {
        if (!isset($this->connectionMap[$type])) {
            throw new \InvalidArgumentException('Invalid connection type provided.');
        }

        if (!isset($this->registry[$type])) {
            $this->registry[$type] = $this->objectManager->create($this->connectionMap[$type], $params);
        }

        return $this->registry[$type];
    }
}
