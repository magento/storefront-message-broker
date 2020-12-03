<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\ConfigurationImportMessageBroker\Model\Configuration\Import;

/**
 * Provide mapping of services subscribed for configuration changes
 */
interface MappingProviderInterface
{
    /**
     * Get mapping for all services subscribed for configuration changes.
     *
     * @return array
     * Result will be in following format:
     * [
     *     "catalog" => [
     *        "name" => "catalog",
     *        "mapping" => [
     *            "catalog/seo/category_canonical_tag" => "category_canonical_enabled"
     *        ]
     *     ]
     */
    public function getMapping(): array;

    /**
     * Get mapping for specified service subscribed for configuration changes.
     *
     * @param string $serviceAlias
     * @return array
     * Result array of config paths that specified service is expects for import:
     * [
     *    "name" => "catalog"
     *    "mapping" => [
     *        "catalog/seo/category_canonical_tag" => "category_canonical_enabled"
     *        "catalog/seo/product_canonical_tag" => "product_canonical_enabled"
     *    ]
     * ]
     */
    public function getMappingForService(string $serviceAlias): array;

    /**
     * Get services subscribed to specific config path.
     *
     * @param string $configPath
     * @return array
     * Result is array of services subscribed to specified config path with mapped path:
     * [
     *     'service' => "catalog",
     *     'mapping' => "product_canonical"
     * ]
     */
    public function getServicesByPath(string $configPath): array;
}
