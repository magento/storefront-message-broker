<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\CatalogMessageBroker\Model\StorefrontConnector\Configuration;

/**
 * Interface for configuration providers for storefront connection.
 */
interface ConfigurationProviderInterface
{
    /**
     * Retrieve connection parameters.
     *
     * @param string $connectionName
     * @return array
     */
    public function provide(string $connectionName): array;
}
