<?php
/**
 * Black list for the @see \Magento\Test\Integrity\DependencyTest::testUndeclared()
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
return [
    'app/code/Magento/CatalogExport/Model/ProductRepository.php' => [
        'Magento\CatalogExportApi',
        'Magento\DataExporter',
        'Magento\Framework',
    ],
    'app/code/Magento/CatalogExport/Model/CategoryRepository.php' => [
        'Magento\CatalogExportApi',
        'Magento\DataExporter',
        'Magento\Framework',
    ],
    'app/code/Magento/CatalogExport/registration.php' => ['Magento\Framework'],
    'app/code/Magento/CatalogExport/Model/ExportConfiguration.php' => ['Magento\Framework'],
    'app/code/Magento/CatalogExport/Model/ProductVariantRepository.php' => [
        'Magento\CatalogExportApi',
        'Magento\DataExporter',
        'Magento\Framework',
    ],
    'app/code/Magento/CatalogExport/Model/DtoMapper.php' => ['Magento\Framework'],
    'app/code/Magento/CatalogExport/Model/Indexer/EntityIndexerCallback.php' => [
        'Magento\DataExporter',
        'Magento\Framework\MessageQueue',
    ],
    'app/code/Magento/CatalogExport/Console/Command/GenerateDTOFiles.php' => [
        'Magento\Framework',
        'Magento\DataExporter',
    ],
    'app/code/Magento/CatalogExport/etc/di.xml' => [
        'Magento\Framework',
        'Magento\CatalogExportApi',
    ],
];
