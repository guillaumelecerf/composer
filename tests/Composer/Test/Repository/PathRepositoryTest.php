<?php

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Composer\Repository;

use Composer\Package\Loader\ArrayLoader;
use Composer\Package\Version\VersionParser;
use Composer\TestCase;
use Composer\IO\NullIO;
use Composer\Config;
use Composer\Package\BasePackage;

class PathRepositoryTest extends TestCase
{
    public function testLoadPackageFromFileSystemWithVersion()
    {
        $ioInterface = $this->getMockBuilder('Composer\IO\IOInterface')
            ->getMock();

        $config = new \Composer\Config();
        $loader = new ArrayLoader(new VersionParser());
        $versionGuesser = null;

        $repositoryUrl = implode(DIRECTORY_SEPARATOR, array(__DIR__, 'Fixtures', 'path', 'with-version'));
        $repository = new PathRepository(array('url' => $repositoryUrl), $ioInterface, $config, $loader);
        $repository->getPackages();

        $this->assertEquals(1, $repository->count());
        $this->assertTrue($repository->hasPackage($this->getPackage('test/path', '0.0.2')));
    }

    public function testLoadPackageFromFileSystemWithoutVersion()
    {
        $ioInterface = $this->getMockBuilder('Composer\IO\IOInterface')
            ->getMock();

        $config = new \Composer\Config();
        $loader = new ArrayLoader(new VersionParser());
        $versionGuesser = null;

        $repositoryUrl = implode(DIRECTORY_SEPARATOR, array(__DIR__, 'Fixtures', 'path', 'without-version'));
        $repository = new PathRepository(array('url' => $repositoryUrl), $ioInterface, $config, $loader);
        $packages = $repository->getPackages();

        $this->assertEquals(1, $repository->count());

        $package = $packages[0];
        $this->assertEquals('test/path', $package->getName());

        $packageVersion = $package->getVersion();
        $this->assertTrue(!empty($packageVersion));
    }
}
