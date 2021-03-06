<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: reviews/reviews.proto

namespace Magento\ReviewsStorefrontApi\Proto;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>magento.reviewsStorefrontApi.proto.ImportReviewsRequest</code>
 */
class ImportReviewsRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>repeated .magento.reviewsStorefrontApi.proto.ImportReview reviews = 1;</code>
     */
    private $reviews;
    /**
     * Generated from protobuf field <code>string store = 2;</code>
     */
    protected $store = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \Magento\ReviewsStorefrontApi\Proto\ImportReview[]|\Google\Protobuf\Internal\RepeatedField $reviews
     *     @type string $store
     * }
     */
    public function __construct($data = null)
    {
        \Magento\ReviewsStorefrontApi\Metadata\Reviews::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>repeated .magento.reviewsStorefrontApi.proto.ImportReview reviews = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * Generated from protobuf field <code>repeated .magento.reviewsStorefrontApi.proto.ImportReview reviews = 1;</code>
     * @param \Magento\ReviewsStorefrontApi\Proto\ImportReview[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setReviews($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Magento\ReviewsStorefrontApi\Proto\ImportReview::class);
        $this->reviews = $arr;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string store = 2;</code>
     * @return string
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * Generated from protobuf field <code>string store = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setStore($var)
    {
        GPBUtil::checkString($var, true);
        $this->store = $var;

        return $this;
    }
}
