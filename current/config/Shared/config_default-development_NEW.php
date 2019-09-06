<?php

/**
 * This is the global runtime configuration for Yves and Generated_Yves_Zed in a development environment.
 */

use Monolog\Logger;
use NxsSpryker\Service\NxsErrorHandler\NxsErrorHandlerConfig;
use NxsSpryker\Service\Sentry\SentryConfig;
use NxsSpryker\Yves\SentryWidget\SentryWidgetConfig;
use Portal\Shared\IManClient\IManConstants;
use Shared\Shared\CapsClient\CapsClientConstants;
use Shared\Shared\FileUpload\FileUploadConfig;
use Shared\Shared\Prometheus\PrometheusConstants;
use Shared\Zed\Mail\MailConfig;
use Shared\Zed\SmsChannel\SmsChannelConfig;
use Spryker\Shared\Acl\AclConstants;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Collector\CollectorConstants;
use Spryker\Shared\Config\ConfigConstants;
use Spryker\Shared\ErrorHandler\ErrorHandlerConstants;
use Spryker\Shared\ErrorHandler\ErrorRenderer\WebExceptionErrorRenderer;
use Spryker\Shared\Event\EventConstants;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Log\LogConstants;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Shared\PropelOrm\PropelOrmConstants;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Shared\RabbitMq\RabbitMqEnv;
use Spryker\Shared\Search\SearchConstants;
use Spryker\Shared\Session\SessionConstants;
use Spryker\Shared\Setup\SetupConstants;
use Spryker\Shared\Storage\StorageConstants;
use Spryker\Shared\Twig\TwigConstants;
use Spryker\Shared\WebProfiler\WebProfilerConstants;
use Spryker\Shared\ZedNavigation\ZedNavigationConstants;
use Spryker\Shared\ZedRequest\ZedRequestConstants;

$CURRENT_STORE = Store::getInstance()->getStoreName();

// ---------- General environment
$config[KernelConstants::SPRYKER_ROOT] = APPLICATION_ROOT_DIR . '/vendor/spryker';
$config[KernelConstants::STORE_PREFIX] = 'DEV';
$config[ApplicationConstants::ENABLE_APPLICATION_DEBUG] = true;
$config[WebProfilerConstants::ENABLE_WEB_PROFILER]
    = $config[ConfigConstants::ENABLE_WEB_PROFILER]
    = true;

$config[ApplicationConstants::ZED_SSL_ENABLED] = false;
$config[ApplicationConstants::YVES_SSL_ENABLED] = false;

// ---------- Propel
$config[PropelConstants::PROPEL_DEBUG] = true;
$config[PropelOrmConstants::PROPEL_SHOW_EXTENDED_EXCEPTION] = true;
$config[PropelConstants::ZED_DB_HOST] = 'db';
$config[PropelConstants::ZED_DB_USERNAME] = 'spryker';
$config[PropelConstants::ZED_DB_PASSWORD] = 'mate20mg';
$config[PropelConstants::ZED_DB_PORT] = 5432;
$config[PropelConstants::ZED_DB_ENGINE] = $config[PropelConstants::ZED_DB_ENGINE_PGSQL];
$config[PropelQueryBuilderConstants::ZED_DB_ENGINE] = $config[PropelConstants::ZED_DB_ENGINE_PGSQL];

// ---------- Redis
$config[StorageConstants::STORAGE_REDIS_PROTOCOL] = 'tcp';
$config[StorageConstants::STORAGE_REDIS_HOST] = 'ardb';
$config[StorageConstants::STORAGE_REDIS_PORT] = '16379';
$config[StorageConstants::STORAGE_REDIS_PASSWORD] = false;
$config[StorageConstants::STORAGE_REDIS_DATABASE] = 0;

$config[SearchConstants::ELASTICA_PARAMETER__HOST] = 'elasticsearch';
$config[SearchConstants::ELASTICA_PARAMETER__PORT] = 9200;

// ---------- RabbitMQ
$config[RabbitMqEnv::RABBITMQ_API_HOST] = 'rabbitmq';
$config[RabbitMqEnv::RABBITMQ_API_PORT] = '15672';
$config[RabbitMqEnv::RABBITMQ_API_USERNAME] = 'admin';
$config[RabbitMqEnv::RABBITMQ_API_PASSWORD] = 'mate20mg';

$storenames = getenv('APPLICATION_STORE_NAMES');
$config[RabbitMqEnv::RABBITMQ_CONNECTIONS] = [];

foreach (explode(',', $storenames) as $storename) {
    $config[RabbitMqEnv::RABBITMQ_CONNECTIONS][$storename] =
         [
            RabbitMqEnv::RABBITMQ_CONNECTION_NAME => $storename . '-connection',
            RabbitMqEnv::RABBITMQ_HOST => 'rabbitmq',
            RabbitMqEnv::RABBITMQ_PORT => '5672',
            RabbitMqEnv::RABBITMQ_PASSWORD => 'mate20mg',
            RabbitMqEnv::RABBITMQ_USERNAME => $storename . '_development',
            RabbitMqEnv::RABBITMQ_VIRTUAL_HOST => '/' . $storename . '_development_zed',
            RabbitMqEnv::RABBITMQ_STORE_NAMES => [$storename],
            RabbitMqEnv::RABBITMQ_DEFAULT_CONNECTION => $CURRENT_STORE === $storename,
    ];
}

// ---------- Session
$config[SessionConstants::YVES_SESSION_COOKIE_SECURE] = false;
$config[SessionConstants::YVES_SESSION_REDIS_PROTOCOL] = $config[StorageConstants::STORAGE_REDIS_PROTOCOL];
$config[SessionConstants::YVES_SESSION_REDIS_HOST] = $config[StorageConstants::STORAGE_REDIS_HOST];
$config[SessionConstants::YVES_SESSION_REDIS_PORT] = $config[StorageConstants::STORAGE_REDIS_PORT];
$config[SessionConstants::YVES_SESSION_REDIS_PASSWORD] = $config[StorageConstants::STORAGE_REDIS_PASSWORD];
$config[SessionConstants::YVES_SESSION_REDIS_DATABASE] = 1;
$config[SessionConstants::ZED_SESSION_COOKIE_SECURE] = false;
$config[SessionConstants::ZED_SESSION_REDIS_PROTOCOL] = $config[SessionConstants::YVES_SESSION_REDIS_PROTOCOL];
$config[SessionConstants::ZED_SESSION_REDIS_HOST] = $config[SessionConstants::YVES_SESSION_REDIS_HOST];
$config[SessionConstants::ZED_SESSION_REDIS_PORT] = $config[SessionConstants::YVES_SESSION_REDIS_PORT];
$config[SessionConstants::ZED_SESSION_REDIS_PASSWORD] = $config[SessionConstants::YVES_SESSION_REDIS_PASSWORD];
$config[SessionConstants::ZED_SESSION_REDIS_DATABASE] = 2;
$config[SessionConstants::ZED_SESSION_TIME_TO_LIVE] = SessionConstants::SESSION_LIFETIME_1_YEAR;

// ---------- Jenkins
$config[SetupConstants::JENKINS_BASE_URL] = 'http://jenkins:8080/';
$config[SetupConstants::JENKINS_DIRECTORY] = '/data/shop/development/shared/data/common/jenkins';

// ---------- Zed request
$config[ZedRequestConstants::TRANSFER_DEBUG_SESSION_FORWARD_ENABLED] = true;
$config[ZedRequestConstants::SET_REPEAT_DATA] = true;
$config[ZedRequestConstants::YVES_REQUEST_REPEAT_DATA_PATH] = APPLICATION_ROOT_DIR . '/data/' . Store::getInstance()->getStoreName() . '/' . APPLICATION_ENV . '/yves-requests';

// ---------- Navigation
$config[ZedNavigationConstants::ZED_NAVIGATION_CACHE_ENABLED] = true;

// ---------- Error handling
$config[ErrorHandlerConstants::DISPLAY_ERRORS] = true;
$config[ErrorHandlerConstants::ERROR_RENDERER] = WebExceptionErrorRenderer::class;

// ---------- ACL
$config[AclConstants::ACL_USER_RULE_WHITELIST][] = [
    'bundle' => 'wdt',
    'controller' => '*',
    'action' => '*',
    'type' => 'allow',
];
// ---------- Twig
$config[TwigConstants::ZED_TWIG_OPTIONS] = [
    'cache' => false,
];
$config[TwigConstants::YVES_TWIG_OPTIONS] = [
    'cache' => false,
];
// ---------- Auto-loader
$config[KernelConstants::AUTO_LOADER_UNRESOLVABLE_CACHE_ENABLED] = false;

// ---------- Logging
$config[LogConstants::LOG_LEVEL] = Logger::INFO;

$baseLogFilePath = sprintf('%s/data/%s/logs', APPLICATION_ROOT_DIR, $CURRENT_STORE);

$config[LogConstants::EXCEPTION_LOG_FILE_PATH_YVES] = $baseLogFilePath . '/YVES/exception.log';
$config[LogConstants::EXCEPTION_LOG_FILE_PATH_ZED] = $baseLogFilePath . '/ZED/exception.log';

// ---------- Events
$config[EventConstants::LOGGER_ACTIVE] = true;

// ----------- NXS Error Handler
$config[NxsErrorHandlerConfig::IS_DEBUG] = true;

// ---------- Sentry
// DEV: https://0b52859ef6634165924523f8096d8caa@sentry.lekkerland.c.korekontrol.net/2

$config[SentryConfig::URL_KEY] = '0b52859ef6634165924523f8096d8caa';
$config[SentryConfig::URL_DOMAIN] = 'sentry.lekkerland.c.korekontrol.net';
$config[SentryConfig::URL_PROJECT] = '2';

$config[SentryConfig::IS_ACTIVE] = true;
$config[SentryConfig::CLIENT_URL] = sprintf(
    'https://%s@%s/%s',
    $config[SentryConfig::URL_KEY],
    $config[SentryConfig::URL_DOMAIN],
    $config[SentryConfig::URL_PROJECT]
);

$config[SentryConfig::IGNORE_ERROR_TYPES] = E_DEPRECATED | E_USER_DEPRECATED;
$config[SentryConfig::RUN_PREVIOUS_HANDLER] = true;
$config[ErrorHandlerConstants::ERROR_LEVEL_LOG_ONLY] = $config[SentryConfig::IGNORE_ERROR_TYPES];

$config[SentryWidgetConfig::URL_KEY] = '0b52859ef6634165924523f8096d8caa';
$config[SentryWidgetConfig::URL_DOMAIN] = 'sentry.lekkerland.c.korekontrol.net';
$config[SentryWidgetConfig::URL_PROJECT] = '2';

$config[SentryWidgetConfig::JS_IS_ACTIVE] = true;
$config[SentryWidgetConfig::JS_CLIENT_URL] = sprintf(
    'https://%s@%s/%s',
    $config[SentryWidgetConfig::URL_KEY],
    $config[SentryWidgetConfig::URL_DOMAIN],
    $config[SentryWidgetConfig::URL_PROJECT]
);

$config[SentryConfig::IGNORE_ERROR_TYPES] = E_DEPRECATED | E_USER_DEPRECATED;
$config[SentryConfig::RUN_PREVIOUS_HANDLER] = true;
$config[ErrorHandlerConstants::ERROR_LEVEL_LOG_ONLY] = $config[SentryConfig::IGNORE_ERROR_TYPES];

$config[IManConstants::SERVER_URI] = 'https://ll24imant.burdadigitalsystems.de';

$config[PrometheusConstants::ADAPTER_OPTIONS] = [
    'host' => $config[StorageConstants::STORAGE_REDIS_HOST],
    'port' => $config[StorageConstants::STORAGE_REDIS_PORT],
    'password' => $config[StorageConstants::STORAGE_REDIS_PASSWORD],
    'database' => $config[StorageConstants::STORAGE_REDIS_DATABASE],
];

$config[CapsClientConstants::CAPS_IS_ACTIVE] = false;

$config[ZedRequestConstants::ZED_API_SSL_ENABLED] = false;
$config[ApplicationConstants::ZED_SSL_ENABLED] = false;
$config[SessionConstants::YVES_SSL_ENABLED] = false;
$config[ApplicationConstants::YVES_SSL_ENABLED] = false;

$config[FileUploadConfig::FE_IMAGE_PATH] = '/assets/customer-import';
$config[FileUploadConfig::IMAGE_PATH] = '/data' . $config[FileUploadConfig::FE_IMAGE_PATH];
$config[FileUploadConfig::FULL_IMAGE_PATH] = APPLICATION_ROOT_DIR . $config[FileUploadConfig::IMAGE_PATH];

$config[PropelConstants::USE_SUDO_TO_MANAGE_DATABASE] = false;

$config[SessionConstants::YVES_SESSION_REDIS_HOST] = $config[StorageConstants::STORAGE_REDIS_HOST];
$config[SessionConstants::YVES_SESSION_REDIS_PORT] = $config[StorageConstants::STORAGE_REDIS_PORT];
$config[SessionConstants::ZED_SESSION_REDIS_HOST] = $config[StorageConstants::STORAGE_REDIS_HOST];
$config[SessionConstants::ZED_SESSION_REDIS_PORT] = $config[StorageConstants::STORAGE_REDIS_PORT];

$config[SetupConstants::JENKINS_DIRECTORY] = '/data/shop/development/shared/data/common/jenkins';

$config[PropelConstants::PROPEL_DEBUG] = true;

$config[ZedRequestConstants::TRANSFER_DEBUG_SESSION_FORWARD_ENABLED] = true;
$config[ZedRequestConstants::TRANSFER_DEBUG_SESSION_NAME] = 'XDEBUG_SESSION';

$config[MailConfig::MAIL_SMTP_HOST] = 'mailcatcher';
$config[MailConfig::MAIL_SMTP_PORT] = 1025;
$config[MailConfig::MAIL_SMTP_USERNAME] = '';
$config[MailConfig::MAIL_SMTP_PASSWORD] = '';
$config[MailConfig::MAIL_SMTP_TLS] = '';

$config[SmsChannelConfig::AWS_API_KEY] = 'WRONG_KEY';
$config[SmsChannelConfig::AWS_API_SECRET] = 'WRONG_SECRET';
$config[SmsChannelConfig::AWS_DEBUG] = true;

// ---------- Twig
$config[TwigConstants::ZED_TWIG_OPTIONS] = [
    'cache' => false,
];
$config[TwigConstants::YVES_TWIG_OPTIONS] = [
    'cache' => false,
];

$config[SessionConstants::YVES_SESSION_SAVE_HANDLER] = SessionConstants::SESSION_HANDLER_REDIS;

$config[PrometheusConstants::ADAPTER_OPTIONS] = [
    'host' => $config[StorageConstants::STORAGE_REDIS_HOST],
    'port' => $config[StorageConstants::STORAGE_REDIS_PORT],
    'password' => $config[StorageConstants::STORAGE_REDIS_PASSWORD],
    'database' => $config[StorageConstants::STORAGE_REDIS_DATABASE],
];

// ---------- Propel
$config[PropelConstants::ZED_DB_DATABASE] = $CURRENT_STORE . '_development_zed';

// ---------- Elasticsearch
$ELASTICA_INDEX_NAME = strtolower($CURRENT_STORE) . '_search';
$config[CollectorConstants::ELASTICA_PARAMETER__INDEX_NAME] = $ELASTICA_INDEX_NAME;

// ---------- RabbitMQ
$config[RabbitMqEnv::RABBITMQ_CONNECTIONS][$CURRENT_STORE][RabbitMqEnv::RABBITMQ_DEFAULT_CONNECTION] = true;
$config[RabbitMqEnv::RABBITMQ_API_VIRTUAL_HOST] = '/' . $CURRENT_STORE . '_development_zed';
