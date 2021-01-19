<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\MessageBroker\Stub;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Reflection\TypeProcessor;
use Magento\Framework\Webapi\CustomAttributeTypeLocatorInterface;

/**
  * Stub to allow instantiation of \Magento\Framework\Webapi\ServiceInputProcessor which required by
 * \Magento\Framework\MessageQueue\Consumer ... \Magento\Framework\MessageQueue\MessageEncoder ...
 */
class CustomAttributesDefaultTypeLocator implements CustomAttributeTypeLocatorInterface
{
    /**
     * Class in Magento monolith for instantiation for case with monolithic installation
     */
    private const MONOLITH_CLASS = '\Magento' . '\Eav\Model\TypeLocator';

    /**
     * @var bool
     */
    private $isMonolithicInstallation;

    /**
     * @inheritdoc
     */
    public function getType($attributeCode, $entityType)
    {
        if ($this->isMonolithicInstallation()) {
            return ObjectManager::getInstance()->get(self::MONOLITH_CLASS)->getType($attributeCode, $entityType);
        }

        return TypeProcessor::NORMALIZED_ANY_TYPE;
    }

    /**
     * @inheritDoc
     */
    public function getAllServiceDataInterfaces()
    {
        if ($this->isMonolithicInstallation()) {
            return ObjectManager::getInstance()->get(self::MONOLITH_CLASS)->getAllServiceDataInterfaces();
        }
        return [];
    }

    /**
     * Ad-hoc solution for monolithic installation, used to run tests on CI.
     * No actual dependency for standalone installation
     *
     * @return bool
     */
    private function isMonolithicInstallation(): bool
    {
        if ($this->isMonolithicInstallation === null) {
            $this->isMonolithicInstallation = \class_exists('\Magento' . '\Config\Model\Config');
        }

        return $this->isMonolithicInstallation;
    }
}
