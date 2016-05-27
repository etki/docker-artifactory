<?php

namespace Etki\Docker\Artifactory;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use RuntimeException;

/**
 * Place where everything starts.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Docker\Artifactory
 * @author  Etki <etki@etki.name>
 */
class EntryPoint
{
    const VERSIONS_FILE_NAME = 'versions';
    const RENDERING_CONTEXT_FILE_NAME = 'context.json';
    
    public static function main() {
        $root = dirname(__DIR__);
        $logger = new Logger('artifactory-management');
        $handler = new ErrorLogHandler();
        $format = '[%datetime% %level_name%] %message%';
        $handler->setFormatter(new LineFormatter($format));
        $logger->pushHandler($handler);
        $logger->pushProcessor(new PsrLogMessageProcessor());
        $context = (new RunContext())
            ->setRootDirectory($root)
            ->setLogger($logger);
        $versions = self::getVersions($context);
        self::getRenderingContext($context);
        $processor = new Processor($context);
        foreach ($versions as $version) {
            $processor->process(Version::parse($version));
        }
    }
    
    private static function getVersions(RunContext $context) {
        $paths = [
            FilesystemUtility::getDataDirectory($context), 
            self::VERSIONS_FILE_NAME
        ];
        $path = FilesystemUtility::joinPaths($paths);
        if (!file_exists($path)) {
            $message = 'Failed to find versions file at ' . $path;
            throw new RuntimeException($message);
        }
        $versions = explode("\n", file_get_contents($path));
        $versions = array_map('trim', $versions);
        $versions = array_filter($versions, function ($line) {
            return (bool) $line;
        });
        return $versions;
    }

    private static function getRenderingContext(RunContext $context) {
        $paths = [
            FilesystemUtility::getDataDirectory($context),
            self::RENDERING_CONTEXT_FILE_NAME
        ];
        $path = FilesystemUtility::joinPaths($paths);
        if (!file_exists($path)) {
            $message = 'Failed to find rendering context file at ' . $path;
            throw new RuntimeException($message);
        }
        $renderingContext = file_get_contents($path);
        $renderingContext = json_decode($renderingContext, true);
        $context->setRenderingContext($renderingContext);
        return $renderingContext;
    }
}