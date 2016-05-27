<?php

namespace Etki\Docker\Artifactory;

use RuntimeException;

/**
 *
 *
 * @version 0.1.0
 * @since
 * @package Etki\Docker\Artifactory
 * @author  Etki <etki@etki.name>
 */
class Version
{
    /**
     *
     *
     * @type int
     * @since
     */
    private $major;
    /**
     *
     *
     * @type int
     * @since
     */
    private $minor;

    /**
     *
     *
     * @type int
     * @since
     */
    private $patch;

    /**
     * Initializer.
     *
     * @param int $major
     * @param int $minor
     * @param int $patch
     *
     * @since
     */
    public function __construct($major, $minor, $patch)
    {
        $this->major = $major;
        $this->minor = $minor;
        $this->patch = $patch;
    }

    /**
     * Returns major.
     *
     * @return int
     * @since
     */
    public function getMajor()
    {
        return $this->major;
    }

    /**
     * Returns minor.
     *
     * @return int
     * @since
     */
    public function getMinor()
    {
        return $this->minor;
    }

    /**
     * Returns patch.
     *
     * @return int
     * @since
     */
    public function getPatch()
    {
        return $this->patch;
    }

    function __toString()
    {
        return sprintf('%d.%d.%d', $this->major, $this->minor, $this->patch);
    }


    public static function parse($version) {
        $parts = explode('.', $version);
        if (sizeof($parts) != 3) {
            throw new RuntimeException('Invalid version supported: '. $version);
        }
        return new Version((int) $parts[0], (int) $parts[1], (int) $parts[2]);
    }
}