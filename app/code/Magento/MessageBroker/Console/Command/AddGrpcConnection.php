<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\MessageBroker\Console\Command;

use Magento\Framework\App\DeploymentConfig\Writer;
use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to add gRPC connection
 */
class AddGrpcConnection extends Command
{
    private const GRPC_PORT = 'grpc-port';
    private const GRPC_HOST = 'grpc-host';
    private const GRPC_CONNECTION_NAME = 'name';

    /**
     * @var Writer
     */
    private $deploymentWriter;

    /**
     * @var array
     */
    private array $supportedServices;

    /**
     * @param Writer $deploymentWriter
     * @param array $supportedServices
     * @param string|null $name
     */
    public function __construct(
        Writer $deploymentWriter,
        array $supportedServices = [],
        string $name = null
    ) {
        parent::__construct($name);
        $this->deploymentWriter = $deploymentWriter;
        $this->supportedServices = $supportedServices;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('message-broker:grpc-connection:add')
            ->setDescription('Add gRPC connection')
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
                self::GRPC_PORT,
                null,
                $mode,
                'gRPC port'
            ),
            new InputOption(
                self::GRPC_HOST,
                null,
                $mode,
                'gRPC host'
            ),
            new InputOption(
                self::GRPC_CONNECTION_NAME,
                null,
                $mode,
                'gRPC name'
            )
        ];
    }

    /**
     * Validate input options
     *
     * @param InputInterface $input
     * @return void
     * @throws \Symfony\Component\Console\Exception\RuntimeException
     */
    private function validate(InputInterface $input): void
    {
        $givenOptions = $input->getOptions();
        foreach ($this->getOptionsList() as $option) {
            if ($option->isValueRequired()) {
                if (!isset($givenOptions[$option->getName()])) {
                    throw new \Symfony\Component\Console\Exception\RuntimeException(
                        sprintf("%s option is not specified. Please, specify all required options", $option->getName())
                    );
                }
            }
        }

        if (!\in_array($input->getOption('name'), $this->supportedServices, true)) {
            throw new \Symfony\Component\Console\Exception\RuntimeException(
                sprintf(
                    'Service name is not correct. Supported services: %s',
                    \implode(', ', $this->supportedServices)
                )
            );
        }
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
        $this->validate($input);
        $this->deploymentWriter->saveConfig([
            'app_env' => [
                'GRPC_CONNECTION_TYPE' => 'network',
                'grpc' => [
                    'connections' => [
                        $input->getOption(self::GRPC_CONNECTION_NAME) => [
                            'hostname' => $input->getOption(self::GRPC_HOST),
                            'port' => $input->getOption(self::GRPC_PORT)
                        ]
                    ]
                ]
            ]
        ]);
        $output->writeln('Service successfully added. "GRPC_CONNECTION_TYPE" changed to "network"');
        return Cli::RETURN_SUCCESS;
    }
}
