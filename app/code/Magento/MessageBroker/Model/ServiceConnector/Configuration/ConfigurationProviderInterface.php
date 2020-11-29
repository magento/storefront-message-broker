<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\MessageBroker\Model\ServiceConnector\Configuration;

/**
 * Interface for configuration providers for service connection.
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
