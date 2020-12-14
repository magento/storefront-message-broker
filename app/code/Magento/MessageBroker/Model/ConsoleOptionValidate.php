<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\MessageBroker\Model;

class ConsoleOptionValidate extends \Magento\Framework\Setup\Option\AbstractConfigOption
{
    /**
     * @var \Closure
     */
    private $validator;

    /**
     * @param string $name
     * @param \Closure $validator
     * @param string $frontendType
     * @param int $mode
     * @param string $configPath
     * @param string $description
     * @param string|array|null $defaultValue
     * @param string|array|null $shortcut
     */
    public function __construct(
        $name,
        \Closure $validator,
        $frontendType,
        $mode,
        $configPath,
        $description = '',
        $defaultValue = null,
        $shortcut = null
    ) {
        $this->validator = $validator;
        parent::__construct($name, $frontendType, $mode, $configPath, $description, $defaultValue, $shortcut);
    }

    /**
     * Validates input data
     * @param mixed $data
     * @return void
     */
    public function validate($data)
    {
        ($this->validator)($data);
        parent::validate($data);
    }
}
