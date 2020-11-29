<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\ConfigurationImportMessageBroker\Model\MessageBus\Import;

/**
 * Factory for creating configuration event for respective import type
 */
class ConsumerEventInterfaceFactory
{
    /**
     * @var array
     */
    private $eventTypeMap;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param array $eventTypeMap
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $eventTypeMap = []
    ) {
        $this->objectManager = $objectManager;
        $this->eventTypeMap = $eventTypeMap;
    }

    /**
     * Create event instance for respective configuration import type
     *
     * @param string $eventType
     * @return ConsumerEventInterface
     */
    public function create(string $eventType): ConsumerEventInterface
    {
        if (isset($this->eventTypeMap[$eventType])) {
            $processor = $this->objectManager->create($this->eventTypeMap[$eventType]);

            if (!$processor instanceof ConsumerEventInterface) {
                throw new \InvalidArgumentException(
                    \sprintf(
                        'Configuration import processor should implement %s',
                        ConsumerEventInterface::class
                    )
                );
            }

            return $processor;
        }

        throw new \InvalidArgumentException(
            \sprintf(
                'The provided event type "%s" was not recognized',
                $eventType
            )
        );
    }
}
