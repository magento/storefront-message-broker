<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\ConfigurationImportMessageBroker\Model\MessageBus\Import;

use Magento\ConfigurationImportMessageBroker\Event\Data\Config;

/**
 * Process import of configuration
 */
interface ConsumerEventInterface
{
    /**
     * Execute import into mapped services of changed configuration
     *
     * @param Config[] $configuration
     *
     * @return void
     */
    public function execute(array $configuration = []): void;
}
