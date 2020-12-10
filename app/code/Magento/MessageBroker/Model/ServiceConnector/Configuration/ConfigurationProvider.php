<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\MessageBroker\Model\ServiceConnector\Configuration;

/**
 * Configuration provider for storefront connection.
 */
class ConfigurationProvider
{
    /**
     * @var array
     */
    private $providersMap;

    /**
     * @param ConfigurationInterface[] $providersMap
     */
    public function __construct(array $providersMap = [])
    {
        $this->providersMap = $providersMap;
    }

    /**
     * Retrieve connection parameters.
     *
     * @param string $type
     * @param string $connectionName
     *
     * @return array
     */
    public function provide(string $type, string $connectionName): array
    {
        if (!isset($this->providersMap[$type])) {
            return [];
        }

        return $this->providersMap[$type]->retrieve($connectionName);
    }
}
