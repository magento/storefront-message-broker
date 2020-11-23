<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\MessageBroker\Stub;

use Magento\Framework\Reflection\TypeProcessor;
use Magento\Framework\Webapi\CustomAttributeTypeLocatorInterface;

/**
  * Stub to allow instantiation of \Magento\Framework\Webapi\ServiceInputProcessor which required by
 * \Magento\Framework\MessageQueue\Consumer ... \Magento\Framework\MessageQueue\MessageEncoder ...
 */
class CustomAttributesDefaultTypeLocator implements CustomAttributeTypeLocatorInterface
{
    /**
     * @inheritdoc
     */
    public function getType($attributeCode, $entityType)
    {
        return TypeProcessor::NORMALIZED_ANY_TYPE;
    }

    /**
     * @inheritDoc
     */
    public function getAllServiceDataInterfaces()
    {
        return [];
    }
}
