<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\ConfigurationImportMessageBroker\Model\MessageBus;

use Magento\ConfigurationImportMessageBroker\Event\Data\Config;
use Magento\ConfigurationImportMessageBroker\Event\Data\ChangedConfig;
use Magento\ConfigurationImportMessageBroker\Model\MessageBus\Import\ConsumerEventInterfaceFactory;
use Psr\Log\LoggerInterface;

/**
 * Process configuration update
 */
class ConfigurationConsumer
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ConsumerEventInterfaceFactory
     */
    private $consumerEventFactory;

    /**
     * @param LoggerInterface $logger
     * @param ConsumerEventInterfaceFactory $consumerEventFactory
     */
    public function __construct(
        LoggerInterface $logger,
        ConsumerEventInterfaceFactory $consumerEventFactory
    ) {
        $this->logger = $logger;
        $this->consumerEventFactory = $consumerEventFactory;
    }

    /**
     * Process message
     *
     * @param \Magento\ConfigurationImportMessageBroker\Event\Data\ChangedConfig $message
     * @return void
     */
    public function processMessage(ChangedConfig $message): void
    {
        try {
            $eventType = $message->getMeta() ? $message->getMeta()->getEvent() : null;
            $configurations = $message->getData() ? $message->getData()->getConfig() : [];

            if (empty($configurations)) {
                throw new \InvalidArgumentException('Configuration data is missing in payload');
            }

            $consumerEvent = $this->consumerEventFactory->create($eventType);
            $consumerEvent->execute($configurations);
        } catch (\Throwable $e) {
            $this->logger->error(
                \sprintf(
                    'Unable to process collected configuration data. Event type: "%s", config paths:  "%s"',
                    $eventType ?? '',
                    \implode(',', \array_map(function (Config $config) {
                        return $config->getName();
                    }, $configurations ?? []))
                ),
                ['exception' => $e]
            );
        }
    }
}
