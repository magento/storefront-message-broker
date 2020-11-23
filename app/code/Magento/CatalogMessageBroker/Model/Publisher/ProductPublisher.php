<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogMessageBroker\Model\Publisher;

use Magento\CatalogExport\Model\ChangedEntitiesMessageBuilder;
use Magento\CatalogMessageBroker\Model\FetchProductsInterface;
use Magento\CatalogMessageBroker\Model\MessageBus\Product\PublishProductsConsumer;
use Magento\CatalogMessageBroker\Model\DataMapper;
use Magento\CatalogMessageBroker\Model\StorefrontConnector\Connector;
use Magento\CatalogStorefrontApi\Api\Data\ImportProductDataRequestMapper;
use Magento\CatalogStorefrontApi\Api\Data\ImportProductsRequestInterface;
use Magento\CatalogStorefrontApi\Api\Data\ImportProductsRequestInterfaceFactory;
use Psr\Log\LoggerInterface;

/**
 * Product publisher
 *
 * Push product data for given product ids and store id to the Storefront via Import API
 */
class ProductPublisher
{
    /**
     * Service name for communication
     */
    const SERVICE_NAME = 'catalog';

    /**
     * @var int
     */
    private $batchSize;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ImportProductsRequestInterfaceFactory
     */
    private $importProductsRequestInterfaceFactory;

    /**
     * @var DataMapper
     */
    private $dataMapper;

    /**
     * @var ImportProductDataRequestMapper
     */
    private $importProductDataRequestMapper;

    /**
     * @var Connector
     */
    private $connector;

    /**
     * @param LoggerInterface $logger
     * @param ImportProductsRequestInterfaceFactory $importProductsRequestInterfaceFactory
     * @param DataMapper $dataMapper
     * @param ImportProductDataRequestMapper $importProductDataRequestMapper
     * @param FetchProductsInterface $fetchProducts
     * @param ChangedEntitiesMessageBuilder $changedEntitiesMessageBuilder
     * @param Connector $connector
     * @param int $batchSize
     */
    public function __construct(
        LoggerInterface $logger,
        ImportProductsRequestInterfaceFactory $importProductsRequestInterfaceFactory,
        DataMapper $dataMapper,
        ImportProductDataRequestMapper $importProductDataRequestMapper,
        Connector $connector,
        int $batchSize
    ) {
        $this->batchSize = $batchSize;
        $this->logger = $logger;
        $this->importProductsRequestInterfaceFactory = $importProductsRequestInterfaceFactory;
        $this->dataMapper = $dataMapper;
        $this->importProductDataRequestMapper = $importProductDataRequestMapper;
        $this->connector = $connector;
    }

    /**
     * Publish data to Storefront
     *
     * @param array $products
     * @param string $storeCode
     * @param string $actionType
     *
     * @return void
     *
     * @throws \Exception
     */
    public function publish(
        array $products,
        string $storeCode,
        string $actionType
    ): void {
        foreach (\array_chunk($products, $this->batchSize) as $productsData) {
            $this->logger->debug(
                \sprintf(
                    'Import products with ids "%s" in store %s',
                    \implode(', ', array_keys($productsData)),
                    $storeCode
                ),
                ['verbose' => $productsData]
            );
            try {
                $this->importProducts($storeCode, array_values($productsData), $actionType);
            } catch (\Throwable $e) {
                $this->logger->critical(
                    \sprintf(
                        'Error during import product ids "%s" in store %s',
                        \implode(', ', array_keys($products)),
                        $storeCode
                    ),
                    ['exception' => $e]
                );
            }
        }
    }

    /**
     * Import products into product storage.
     *
     * @param string $storeCode
     * @param array $products
     * @param string $actionType
     *
     * @throws \Throwable
     */
    private function importProducts(
        string $storeCode,
        array $products,
        string $actionType
    ): void {
        $productsRequestData = [];

        foreach ($products as $product) {
            $product = array_replace_recursive(
                $product,
                $this->dataMapper->map($product)
            );
            // be sure, that data passed to Import API in the expected format
            $productsRequestData[] = $this->importProductDataRequestMapper->setData(
                [
                    'product' => $product,
                    'attributes' => $actionType === PublishProductsConsumer::ACTION_UPDATE ? \array_keys($product) : [],
                ]
            )->build();
        }

        /** @var ImportProductsRequestInterface $importProductRequest */
        $importProductRequest = $this->importProductsRequestInterfaceFactory->create();
        $importProductRequest->setProducts($productsRequestData);
        $importProductRequest->setStore($storeCode);

        $importResult = $this->connector->getConnection('catalog')->importProducts($importProductRequest);

        if ($importResult->getStatus() === false) {
            $this->logger->error(sprintf('Products import is failed: "%s"', $importResult->getMessage()));
        }
    }
}
