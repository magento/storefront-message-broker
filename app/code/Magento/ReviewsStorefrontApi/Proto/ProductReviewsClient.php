<?php
// GENERATED CODE -- DO NOT EDIT!

// Original file comments:
// *
// Copyright © Magento, Inc. All rights reserved.
// See COPYING.txt for license details.
namespace Magento\ReviewsStorefrontApi\Proto;

/**
 */
class ProductReviewsClient extends \Grpc\BaseStub
{

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null)
    {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Magento\ReviewsStorefrontApi\Proto\ImportReviewsRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function importProductReviews(
        \Magento\ReviewsStorefrontApi\Proto\ImportReviewsRequest $argument,
        $metadata = [],
        $options = []
    )
    {
        return $this->_simpleRequest(
            '/magento.reviewsStorefrontApi.proto.ProductReviews/importProductReviews',
            $argument,
            ['\Magento\ReviewsStorefrontApi\Proto\ImportReviewsResponse', 'decode'],
            $metadata,
            $options
        );
    }

    /**
     * @param \Magento\ReviewsStorefrontApi\Proto\DeleteReviewsRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function deleteProductReviews(
        \Magento\ReviewsStorefrontApi\Proto\DeleteReviewsRequest $argument,
        $metadata = [],
        $options = []
    )
    {
        return $this->_simpleRequest(
            '/magento.reviewsStorefrontApi.proto.ProductReviews/deleteProductReviews',
            $argument,
            ['\Magento\ReviewsStorefrontApi\Proto\DeleteReviewsResponse', 'decode'],
            $metadata,
            $options
        );
    }

    /**
     * @param \Magento\ReviewsStorefrontApi\Proto\ProductReviewRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function getProductReviews(
        \Magento\ReviewsStorefrontApi\Proto\ProductReviewRequest $argument,
        $metadata = [],
        $options = []
    )
    {
        return $this->_simpleRequest(
            '/magento.reviewsStorefrontApi.proto.ProductReviews/getProductReviews',
            $argument,
            ['\Magento\ReviewsStorefrontApi\Proto\ProductReviewResponse', 'decode'],
            $metadata,
            $options
        );
    }

    /**
     * @param \Magento\ReviewsStorefrontApi\Proto\CustomerProductReviewRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function getCustomerProductReviews(
        \Magento\ReviewsStorefrontApi\Proto\CustomerProductReviewRequest $argument,
        $metadata = [],
        $options = []
    )
    {
        return $this->_simpleRequest(
            '/magento.reviewsStorefrontApi.proto.ProductReviews/getCustomerProductReviews',
            $argument,
            ['\Magento\ReviewsStorefrontApi\Proto\ProductReviewResponse', 'decode'],
            $metadata,
            $options
        );
    }

    /**
     * @param \Magento\ReviewsStorefrontApi\Proto\ProductReviewCountRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function getProductReviewCount(
        \Magento\ReviewsStorefrontApi\Proto\ProductReviewCountRequest $argument,
        $metadata = [],
        $options = []
    )
    {
        return $this->_simpleRequest(
            '/magento.reviewsStorefrontApi.proto.ProductReviews/getProductReviewCount',
            $argument,
            ['\Magento\ReviewsStorefrontApi\Proto\ProductReviewCountResponse', 'decode'],
            $metadata,
            $options
        );
    }
}
