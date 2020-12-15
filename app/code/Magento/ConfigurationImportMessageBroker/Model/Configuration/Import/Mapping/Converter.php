<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\ConfigurationImportMessageBroker\Model\Configuration\Import\Mapping;

class Converter implements \Magento\Framework\Config\ConverterInterface
{
    /**
     * Convert dom node tree to array
     *
     * @param \DOMDocument $source
     * @return array
     * @throws \InvalidArgumentException
     */
    public function convert($source)
    {
        $output = [];
        /** @var \DOMNodeList $configuration */
        $configuration = $source->getElementsByTagName('configuration');

        /** @var \DOMNode $configuration */
        foreach ($configuration as $item) {
            /** @var \DOMNode $service */
            foreach ($item->childNodes as $service) {
                if ($service->nodeName != 'service' || $service->nodeType != XML_ELEMENT_NODE) {
                    continue;
                }

                $config = $this->convertMappingConfig($service);
                $output[mb_strtolower($config['name'])] = isset($output[mb_strtolower($config['name'])])
                    ? array_merge_recursive($output[mb_strtolower($config['name'])], $config)
                    : $config;
            }
        }

        return $output;
    }

    /**
     * Convert service mapping configuration
     *
     * @param \DOMNode $service
     * @return array
     */
    public function convertMappingConfig(\DOMNode $service): array
    {
        $output = [];

        $serviceNameAttribute = $service->attributes->getNamedItem('name');
        if (!$serviceNameAttribute) {
            throw new \InvalidArgumentException('Service name attribute is missed');
        }

        $output['name'] = $serviceNameAttribute->nodeValue;

        /** @var \DOMNode $path */
        foreach ($service->childNodes as $path) {
            if ($path->nodeName != 'path' || $path->nodeType != XML_ELEMENT_NODE) {
                continue;
            }

            $pathNameAttribute = $path->attributes->getNamedItem('name');
            if (!$pathNameAttribute) {
                throw new \InvalidArgumentException('Path name attribute is missed');
            }

            $output['mapping'][mb_strtolower($pathNameAttribute->nodeValue)] = !empty($path->nodeValue)
                ? $path->nodeValue
                : $pathNameAttribute->nodeValue;
        }

        return $output;
    }
}
