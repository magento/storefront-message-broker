<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\CatalogMessageBroker\Model\StorefrontConnector\Configuration;

/**
 * Pool of configuration providers for storefront connection.
 */
class ConfigurationProviderPool
{
    /**
     * @var array
     */
    private $providersMap;

    /**
     * @param ConfigurationProviderInterface[] $providersMap
     */
    public function __construct(array $providersMap = [])
    {
        $this->providersMap = $providersMap;
    }

    /**
     * Retrieve connection parameters by connection type.
     *
     * @param string $type
     *
     * @param string $connectionName
     * @return array
     */
    public function retrieveByConnectionType(string $type, string $connectionName): array
    {
        if (!isset($this->providersMap[$type])) {
            return [];
        }

        return $this->providersMap[$type]->provide($connectionName);
    }
}
