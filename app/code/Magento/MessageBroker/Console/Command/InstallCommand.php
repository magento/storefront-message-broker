<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\MessageBroker\Console\Command;

use Magento\Framework\Config\ConfigOptionsListConstants;
use Magento\Framework\Setup\Option\SelectConfigOption;
use Magento\Framework\Setup\Option\TextConfigOption;
use Magento\MessageBroker\Model\ConsoleOptionValidate;
use Magento\MessageBroker\Model\Installer;
use Magento\Framework\Console\Cli;
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
    private $input;

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
        $this->setName('message-broker:install')
            ->setDescription('Install Message Broker')
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
        $cacheKey = ConfigOptionsListConstants::KEY_CACHE_FRONTEND . '/default/'.
            ConfigOptionsListConstants::CONFIG_PATH_BACKEND_OPTIONS;
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
                Installer::GRPC_CONNECTON_TYPE,
                null,
                \Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL,
                'gRPC connection type'
            ),
            new InputOption(
                Installer::CONSUMER_WAIT_FOR_MESSAGES,
                null,
                $mode,
                'Consumer wait for messages'
            ),
            new ConsoleOptionValidate(
                Installer::INPUT_KEY_CACHE_BACKEND,
                function($optionData) {
                    $key = Installer::INPUT_KEY_CACHE_BACKEND;
                    if (!in_array($optionData, [Installer::INPUT_VALUE_CACHE_REDIS])) {
                        throw new \InvalidArgumentException("Value specified for '{$key}' is not supported: '{$optionData}'");
                    }
                    if ($optionData === Installer::INPUT_VALUE_CACHE_REDIS) {
                        $givenOptions = $this->input->getOptions();

                        try {
                            $redisClient = new \Credis_Client(
                                $givenOptions[Installer::INPUT_KEY_CACHE_BACKEND_REDIS_SERVER],
                                $givenOptions[Installer::INPUT_KEY_CACHE_BACKEND_REDIS_PORT],
                                null,
                                '',
                                $givenOptions[Installer::INPUT_KEY_CACHE_BACKEND_REDIS_DATABASE],
                                $givenOptions[Installer::INPUT_KEY_CACHE_BACKEND_REDIS_PASSWORD]
                            );
                            $redisClient->setMaxConnectRetries(1);
                            $redisClient->ping();
                            $redisClient->close(true);
                        } catch (\CredisException $e) {
                            throw new \Symfony\Component\Console\Exception\RuntimeException(
                                sprintf("Error connecting to the Redis server: %s", $e->getMessage())
                            );
                        }
                    }
                },
                SelectConfigOption::FRONTEND_WIZARD_SELECT,
                InputOption::VALUE_OPTIONAL,
                ConfigOptionsListConstants::KEY_CACHE_FRONTEND. '/default/backend',
                'Default cache handler',
                null
            ),
            new TextConfigOption(
                Installer::INPUT_KEY_CACHE_BACKEND_REDIS_SERVER,
                TextConfigOption::FRONTEND_WIZARD_TEXT,
                $cacheKey .'/server',
                'Redis server',
                Installer::INPUT_VALUE_CACHE_REDIS
            ),
            new TextConfigOption(
                Installer::INPUT_KEY_CACHE_BACKEND_REDIS_DATABASE,
                TextConfigOption::FRONTEND_WIZARD_TEXT,
                $cacheKey . '/database',
                'Database number for the cache',
                '3'
            ),
            new TextConfigOption(
                Installer::INPUT_KEY_CACHE_BACKEND_REDIS_PORT,
                TextConfigOption::FRONTEND_WIZARD_TEXT,
                $cacheKey . '/port',
                'Redis server listen port',
                '6379'
            ),
            new TextConfigOption(
                Installer::INPUT_KEY_CACHE_BACKEND_REDIS_PASSWORD,
                TextConfigOption::FRONTEND_WIZARD_TEXT,
                $cacheKey . '/password',
                'Redis server password',
                ''
            ),
            new TextConfigOption(
                Installer::INPUT_KEY_CACHE_BACKEND_REDIS_COMPRESS_DATA,
                TextConfigOption::FRONTEND_WIZARD_TEXT,
                $cacheKey . '/compress_data',
                'Set to 0 to disable compression (default is 1, enabled)',
                '1'
            ),
            new TextConfigOption(
                Installer::INPUT_KEY_CACHE_BACKEND_REDIS_COMPRESSION_LIB,
                TextConfigOption::FRONTEND_WIZARD_TEXT,
                $cacheKey . '/compression_lib',
                'Compression lib to use [snappy,lzf,l4z,zstd,gzip] (leave blank to determine automatically)',
                ''
            ),
            new TextConfigOption(
                Installer::INPUT_KEY_CACHE_ID_PREFIX,
                TextConfigOption::FRONTEND_WIZARD_TEXT,
                ConfigOptionsListConstants::KEY_CACHE_FRONTEND. '/default/id_prefix',
                'ID prefix for cache keys',
                substr(\hash('sha256', dirname(__DIR__, 6)), 0, 3) . '_'
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
        $this->input = $input;
        $givenOptions = $input->getOptions();
        foreach ($this->getOptionsList() as $option) {
            if ($option instanceof \Magento\Framework\Setup\Option\AbstractConfigOption) {
                $option->validate($input->getOption($option->getName()));
            } elseif($option->isValueRequired()) {
                if (!isset($givenOptions[$option->getName()])) {
                    throw new \Symfony\Component\Console\Exception\RuntimeException(
                        sprintf("%s option is not specified. Please, specify all required options", $option->getName())
                    );
                }
            }
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
        $this->installer->install($input->getOptions(), $this->getDefinition());

        $output->writeln('Installation complete');

        return Cli::RETURN_SUCCESS;
    }
}
