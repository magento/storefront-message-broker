<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\ConfigurationImportMessageBroker\Model\Configuration\Import;

/**
 * Configuration import mappings config
 */
class MappingProvider implements MappingProviderInterface
{
    /**
     * @var \Magento\Framework\Config\DataInterface
     */
    private $dataStorage;

    /**
     * @param \Magento\Framework\Config\DataInterface $dataStorage
     */
    public function __construct(\Magento\Framework\Config\DataInterface $dataStorage)
    {
        $this->dataStorage = $dataStorage;
    }

    /**
     * @inheritDoc
     */
    public function getMapping(): array
    {
        return $this->dataStorage->get(null, []);
    }

    /**
     * @inheritDoc
     */
    public function getMappingForService(string $serviceAlias): array
    {
        return $this->dataStorage->get("{$serviceAlias}", []);
    }

    /**
     * @inheritDoc
     */
    public function getServicesByPath(string $configPath): array
    {
        $result = [];
        $mapping = $this->getMapping();

        foreach ($mapping as $service) {
            if (isset($service['mapping'][$configPath])) {
                $result[] = [
                    'service' => $service['name'],
                    'mapping' => $service['mapping'][$configPath]
                ];
            }
        }

        return $result;
    }
}
