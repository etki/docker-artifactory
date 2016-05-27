<?php

namespace Etki\Docker\Artifactory;

use Psr\Log\LoggerInterface;

/**
 * A simple wrapper of application state.
 *
 * @version 0.1.0
 * @since   0.1.0
 * @package Etki\Docker\Artifactory
 * @author  Etki <etki@etki.name>
 */
class RunContext
{
    /**
     * Project root directory
     *
     * @type string
     * @since 0.1.0
     */
    private $rootDirectory;
    /**
     * Logger instance.
     *
     * @type LoggerInterface
     * @since 0.1.0
     */
    private $logger;

    /**
     * Rendering context.
     *
     * @type array
     * @since 0.1.0
     */
    private $renderingContext;
    
    /**
     * Returns rootDirectory.
     *
     * @return string
     * @since 0.1.0
     */
    public function getRootDirectory()
    {
        return $this->rootDirectory;
    }

    /**
     * Sets rootDirectory.
     *
     * @param string $rootDirectory
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function setRootDirectory($rootDirectory)
    {
        $this->rootDirectory = $rootDirectory;
        return $this;
    }

    /**
     * Returns logger.
     *
     * @return LoggerInterface
     * @since 0.1.0
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Sets logger.
     *
     * @param LoggerInterface $logger
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * Returns renderingContext.
     *
     * @return array
     * @since 0.1.0
     */
    public function getRenderingContext()
    {
        return $this->renderingContext;
    }

    /**
     * Sets renderingContext.
     *
     * @param array $renderingContext
     *
     * @return $this Current instance.
     * @since 0.1.0
     */
    public function setRenderingContext($renderingContext)
    {
        $this->renderingContext = $renderingContext;
        return $this;
    }
}