<?php
# Generated by the Magento PHP proto generator.  DO NOT EDIT!

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\ReviewsStorefrontApi\Api\Data;

/**
 * Autogenerated description for ReadReview interface
 *
 * @SuppressWarnings(PHPMD.BooleanGetMethodName)
 */
interface ReadReviewInterface
{
    /**
     * Autogenerated description for getId() interface method
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Autogenerated description for setId() interface method
     *
     * @param string $value
     * @return void
     */
    public function setId(string $value): void;

    /**
     * Autogenerated description for getProductId() interface method
     *
     * @return string
     */
    public function getProductId(): string;

    /**
     * Autogenerated description for setProductId() interface method
     *
     * @param string $value
     * @return void
     */
    public function setProductId(string $value): void;

    /**
     * Autogenerated description for getTitle() interface method
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Autogenerated description for setTitle() interface method
     *
     * @param string $value
     * @return void
     */
    public function setTitle(string $value): void;

    /**
     * Autogenerated description for getNickname() interface method
     *
     * @return string
     */
    public function getNickname(): string;

    /**
     * Autogenerated description for setNickname() interface method
     *
     * @param string $value
     * @return void
     */
    public function setNickname(string $value): void;

    /**
     * Autogenerated description for getText() interface method
     *
     * @return string
     */
    public function getText(): string;

    /**
     * Autogenerated description for setText() interface method
     *
     * @param string $value
     * @return void
     */
    public function setText(string $value): void;

    /**
     * Autogenerated description for getRatings() interface method
     *
     * @return \Magento\ReviewsStorefrontApi\Api\Data\RatingInterface[]
     */
    public function getRatings(): array;

    /**
     * Autogenerated description for setRatings() interface method
     *
     * @param \Magento\ReviewsStorefrontApi\Api\Data\RatingInterface[] $value
     * @return void
     */
    public function setRatings(array $value): void;
}