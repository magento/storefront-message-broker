<?php
# Generated by the Magento PHP proto generator.  DO NOT EDIT!

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\CatalogStorefrontApi\Api\Data;

use Magento\Framework\ObjectManagerInterface;

/**
 * Autogenerated description for ProductOptionValue class
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.UnusedPrivateField)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
final class ProductOptionValueMapper
{
    /**
     * @var string
     */
    private static $dtoClassName = ProductOptionValueInterface::class;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
    * Set the data to populate the DTO
    *
    * @param mixed $data
    * @return $this
    */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
    * Build new DTO populated with the data
    *
    * @return ProductOptionValue
    */
    public function build()
    {
        $dto = $this->objectManager->create(self::$dtoClassName);
        foreach ($this->data as $key => $valueData) {
            if ($valueData === null) {
                continue;
            }
            $this->setByKey($dto, $key, $valueData);
        }
        return $dto;
    }

    /**
    * Set the value of the key using setters.
    *
    * In case if the field is object, the corresponding Mapper would be create and DTO representing the field data
    * would be built
    *
    * @param ProductOptionValue $dto
    * @param string $key
    * @param mixed $value
    */
    private function setByKey(ProductOptionValue $dto, string $key, $value): void
    {
        switch ($key) {
            case "id":
                $dto->setId((string) $value);
                break;
            case "label":
                $dto->setLabel((string) $value);
                break;
            case "sort_order":
                $dto->setSortOrder((string) $value);
                break;
            case "default":
                $dto->setDefault((bool) $value);
                break;
            case "image_url":
                $dto->setImageUrl((string) $value);
                break;
            case "qty_mutability":
                $dto->setQtyMutability((bool) $value);
                break;
            case "qty":
                $dto->setQty((float) $value);
                break;
            case "info_url":
                $dto->setInfoUrl((string) $value);
                break;
            case "price":
                $dto->setPrice((float) $value);
                break;
        }
    }
}
