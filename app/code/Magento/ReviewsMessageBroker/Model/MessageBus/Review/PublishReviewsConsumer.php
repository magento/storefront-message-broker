<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\ReviewsMessageBroker\Model\MessageBus\Review;

use Magento\MessageBroker\Model\ServiceConnector\Connector;
use Magento\ReviewsMessageBroker\Model\ServiceConfig;
use Magento\ReviewsStorefrontApi\Api\Data\ImportReviewsRequestMapper;
use Magento\ReviewsMessageBroker\Model\FetchReviewsInterface;
use Magento\ReviewsMessageBroker\Model\MessageBus\ConsumerEventInterface;
use Psr\Log\LoggerInterface;

/**
 * Publish reviews into storage
 */
class PublishReviewsConsumer implements ConsumerEventInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ImportReviewsRequestMapper
     */
    private $importReviewsRequestMapper;

    /**
     * @var Connector
     */
    private $connector;

    /**
     * @var FetchReviewsInterface
     */
    private $fetchReviews;

    /**
     * @param FetchReviewsInterface $fetchReviews
     * @param ImportReviewsRequestMapper $importReviewsRequestMapper
     * @param Connector $connector
     * @param LoggerInterface $logger
     */
    public function __construct(
        FetchReviewsInterface $fetchReviews,
        ImportReviewsRequestMapper $importReviewsRequestMapper,
        Connector $connector,
        LoggerInterface $logger
    ) {
        $this->fetchReviews = $fetchReviews;
        $this->importReviewsRequestMapper = $importReviewsRequestMapper;
        $this->connector = $connector;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function execute(array $entities, string $scope = null): void
    {
        $reviewsData = $this->fetchReviews->execute($entities);

        foreach ($reviewsData as &$data) {
            $data['id'] = $data['review_id'];
        }

        $importRequest = $this->importReviewsRequestMapper->setData(['reviews' => $reviewsData])->build();
        $importResult = $this->connector
            ->getConnection(ServiceConfig::SERVICE_NAME_REVIEWS)
            ->importProductReviews($importRequest);

        if ($importResult->getStatus() === false) {
            $this->logger->error(\sprintf('Reviews import is failed: "%s"', $importResult->getMessage()));
        }
    }
}
