<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogMessageBroker\Console\Command;

use Magento\CatalogMessageBroker\Model\Installer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to backup code base and user data
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InstallCommand extends Command
{
    /**
     * @var Installer
     */
    private $installer;

    /**
     * TopologyInstall constructor.
     * @param Installer $installer
     * @param string|null $name
     */
    public function __construct(
        Installer $installer,
        string $name = null
    ) {
        parent::__construct($name);
        $this->installer = $installer;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('catalog:message-broker:install')
            ->setDescription('Install catalog message broker')
            ->setDefinition($this->getOptionsList());

        parent::configure();
    }

    /**
     * Prepare options list
     *
     * @param int $mode
     * @return InputOption[]
     */
    public function getOptionsList($mode = InputOption::VALUE_REQUIRED)
    {
        return [
            new InputOption(
                Installer::AMQP_HOST,
                null,
                $mode,
                'AMQP host'
            ),
            new InputOption(
                Installer::AMQP_PORT,
                null,
                $mode,
                'AMQP port'
            ),
            new InputOption(
                Installer::AMQP_PASSWORD,
                null,
                $mode,
                'AMQP password'
            ),
            new InputOption(
                Installer::AMQP_USER,
                null,
                $mode,
                'AMQP user'
            ),
            new InputOption(
                Installer::BASE_URL,
                null,
                $mode,
                'Base URL'
            ),
            new InputOption(
                Installer::BASE_URL,
                null,
                $mode,
                'Base URL'
            ),
        ];
    }

    /**
     * Install message-broker application
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws \Magento\Framework\Exception\FileSystemException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->installer->install(
            $this->mapOptions($input->getOptions())
        );
    }
}
