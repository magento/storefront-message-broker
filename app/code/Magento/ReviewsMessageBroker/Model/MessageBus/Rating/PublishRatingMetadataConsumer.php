<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\ReviewsMessageBroker\Model\MessageBus\Rating;

use Magento\MessageBroker\Model\ServiceConnector\Connector;
use Magento\ReviewsMessageBroker\Model\ServiceConfig;
use Magento\ReviewsStorefrontApi\Api\Data\ImportRatingsMetadataRequestMapper;
use Magento\ReviewsMessageBroker\Model\FetchRatingsMetadataInterface;
use Magento\ReviewsMessageBroker\Model\MessageBus\ConsumerEventInterface;
use Psr\Log\LoggerInterface;

/**
 * Publish ratings metadata into storage
 */
class PublishRatingMetadataConsumer implements ConsumerEventInterface
{
    /**
     * @var FetchRatingsMetadataInterface
     */
    private $fetchRatingsMetadata;

    /**
     * @var ImportRatingsMetadataRequestMapper
     */
    private $importRatingsMetadataRequestMapper;

    /**
     * @var Connector
     */
    private $connector;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param FetchRatingsMetadataInterface $fetchRatingsMetadata
     * @param ImportRatingsMetadataRequestMapper $importRatingsMetadataRequestMapper
     * @param Connector $connector
     * @param LoggerInterface $logger
     */
    public function __construct(
        FetchRatingsMetadataInterface $fetchRatingsMetadata,
        ImportRatingsMetadataRequestMapper $importRatingsMetadataRequestMapper,
        Connector $connector,
        LoggerInterface $logger
    ) {
        $this->fetchRatingsMetadata = $fetchRatingsMetadata;
        $this->importRatingsMetadataRequestMapper = $importRatingsMetadataRequestMapper;
        $this->connector = $connector;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function execute(array $entities, string $scope = null): void
    {
        $ratingsMetadata = $this->fetchRatingsMetadata->execute($entities, $scope);

        foreach ($ratingsMetadata as &$data) {
            $data['id'] = $data['rating_id'];
        }

        $importRequest = $this->importRatingsMetadataRequestMapper->setData(
            [
                'metadata' => $ratingsMetadata,
                'store' => $scope,
            ]
        )->build();

        $importResult = $this->connector
            ->getConnection(ServiceConfig::SERVICE_NAME_RATINGS_METADATA)
            ->importRatingsMetadata($importRequest);

        if ($importResult->getStatus() === false) {
            $this->logger->error(\sprintf('Ratings metadata import is failed: "%s"', $importResult->getMessage()));
        }
    }
}
