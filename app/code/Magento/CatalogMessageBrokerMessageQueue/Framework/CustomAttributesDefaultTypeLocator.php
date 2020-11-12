<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogMessageBrokerMessageQueue\Framework;

use Magento\Framework\Reflection\TypeProcessor;
use Magento\Framework\Webapi\CustomAttributeTypeLocatorInterface;

/**
 * Class to locate types for Eav custom attributes
 */
class CustomAttributesDefaultTypeLocator implements CustomAttributeTypeLocatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getType($attributeCode, $entityType)
    {
        echo (new \Exception())->getTraceAsString();
        echo __METHOD__, "\n\n";

        return TypeProcessor::NORMALIZED_ANY_TYPE;
    }

    /**
     * {@inheritDoc}
     */
    public function getAllServiceDataInterfaces()
    {
        echo (new \Exception())->getTraceAsString();
        echo __METHOD__, "\n\n";

        return [];
    }
}
