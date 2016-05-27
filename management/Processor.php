<?php

namespace Etki\Docker\Artifactory;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use SplFileInfo;
use Symfony\Component\Filesystem\Filesystem;
use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * This class builds docker files in exchange of version it receives.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Docker\Artifactory
 * @author  Etki <etki@etki.name>
 */
class Processor
{
    const DEFAULT_SOURCES_DIRECTORY_NAME = 'default';

    /**
     * Context instance
     *
     * @type RunContext
     * @since 0.1.0
     */
    private $context;
    
    /**
     * Filesystem instance.
     *
     * @type Filesystem
     * @since 0.1.0
     */
    private $filesystem;

    /**
     * Initializer.
     *
     * @param RunContext $context Context in which program is run.
     *
     * @since 0.1.0
     */
    public function __construct(RunContext $context)
    {
        $this->context = $context;
        $this->filesystem = new Filesystem();
    }

    /**
     * Processes single version.
     *
     * @param Version $version Version to process.
     *
     * @return void
     * @since 0.1.0
     */
    public function process(Version $version) {
        $sourceDirectory = $this->getSourceDirectory($version);
        $targetDirectory = $this->getTargetDirectory($version);
        $this->context->getLogger()->info(
            'Processing version {version}, source directory: {source}, ' .
            'target directory: {target}',
            [
                'version' => $version->__toString(),
                'source' => $sourceDirectory,
                'target' => $targetDirectory,
            ]
        );
        $sourceFilesIterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($sourceDirectory)
        );
        $renderingContext = $this->context->getRenderingContext();
        $renderingContext['version'] = $version;
        $twig = new Twig_Environment(new Twig_Loader_Filesystem($sourceDirectory));
        /** @type SplFileInfo $sourceNode */
        foreach ($sourceFilesIterator as $sourceNode) {
            if (in_array($sourceNode->getFilename(), ['.', '..'], true)) {
                continue;
            }
            // todo not quite safe
            $targetNode = str_replace(
                $sourceDirectory,
                $targetDirectory,
                $sourceNode->getPathname()
            );
            $this->context->getLogger()->info(
                'Building `{target}` from `{source}`',
                ['source' => $sourceNode->getPathname(), 'target' => $targetNode]
            );
            if (file_exists($targetNode)) {
                $this->context->getLogger()->debug(
                    'Target node `{target}` already exists, removing',
                    ['target' => $targetNode,]
                );
                $this->filesystem->remove($targetNode);
            }
            if ($sourceNode->isDir()) {
                $this->context->getLogger()->debug(
                    'Creating directory {target}',
                    ['target' => $targetNode]
                );
                $this->filesystem->mkdir($targetNode);
            } else {
                $this->context->getLogger()->debug(
                    'Rendering node {target}',
                    ['target' => $targetNode]
                );
                $this->filesystem->mkdir(dirname($targetNode));
                $content = $twig->render(
                    $sourceNode->getFilename(),
                    $renderingContext
                );
                $this->filesystem->dumpFile($targetNode, $content);
            }
        }
    }

    /**
     * Retrieves source directory.
     *
     * @param Version $version Version to retrieve directory for
     *
     * @return string
     * @since 0.1.0
     */
    private function getSourceDirectory(Version $version) {

        $root = FilesystemUtility::getTemplateDirectory($this->context);
        $candidates = [
            $version->getMajor() . '.' . $version->getMinor() . '.' .
                $version->getPatch(),
            $version->getMajor() . '.' . $version->getMinor(),
            $version->getMajor(),
            self::DEFAULT_SOURCES_DIRECTORY_NAME
        ];
        foreach ($candidates as $candidate) {
            $path = FilesystemUtility::joinPaths([$root, $candidate,]);
            if (file_exists($path)) {
                return $path;
            }
        }
        $message = 'Failed to find source directory for version' . $version;
        throw new RuntimeException($message);
    }

    /**
     * Retrieves target directory for particular version
     *
     * @param Version $version
     *
     * @return string
     * @since 0.1.0
     */
    private function getTargetDirectory(Version $version) {
        $paths = [
            FilesystemUtility::getBuildDirectory($this->context),
            $version->__toString(),
        ];
        return FilesystemUtility::joinPaths($paths);
    }
}