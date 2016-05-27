<?php

namespace Etki\Docker\Artifactory;

/**
 * A small helper class.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Docker\Artifactory
 * @author  Etki <etki@etki.name>
 */
class FilesystemUtility
{
    const DATA_DIRECTORY_NAME = 'data';
    const BUILD_DIRECTORY_NAME = 'build';
    const TEMPLATE_DIRECTORY_NAME = 'template';

    public static function joinPaths(array $paths) {
        return implode(DIRECTORY_SEPARATOR, $paths);
    }

    public static function getDataDirectory(RunContext $runContext) {
        $paths = [$runContext->getRootDirectory(), self::DATA_DIRECTORY_NAME];
        return self::joinPaths($paths);
    }
    
    public static function getBuildDirectory(RunContext $runContext) {
        $paths = [$runContext->getRootDirectory(), self::BUILD_DIRECTORY_NAME];
        return self::joinPaths($paths);
    }
    
    public static function getTemplateDirectory(RunContext $runContext) {
        $paths = [
            $runContext->getRootDirectory(), 
            self::TEMPLATE_DIRECTORY_NAME
        ];
        return self::joinPaths($paths);
    }
}