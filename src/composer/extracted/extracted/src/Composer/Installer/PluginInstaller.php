<?php


namespace Composer\Installer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;


class PluginInstaller extends LibraryInstaller
{
    private $installationManager;


    public function __construct(IOInterface $io, Composer $composer, $type = 'library')
    {
        parent::__construct($io, $composer, 'composer-plugin');
        $this->installationManager = $composer->getInstallationManager();
    }


    public function supports($packageType)
    {
        return $packageType === 'composer-plugin' || $packageType === 'composer-installer';
    }


    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        $extra = $package->getExtra();
        if (empty($extra['class'])) {
            throw new \UnexpectedValueException('Error while installing ' . $package->getPrettyName() . ', composer-plugin packages should have a class defined in their extra key to be usable.');
        }

        parent::install($repo, $package);
        try {
            $this->composer->getPluginManager()->registerPackage($package, true);
        } catch (\Exception $e) {

            $this->io->writeError('Plugin installation failed, rolling back');
            parent::uninstall($repo, $package);
            throw $e;
        }
    }


    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
    {
        $extra = $target->getExtra();
        if (empty($extra['class'])) {
            throw new \UnexpectedValueException('Error while installing ' . $target->getPrettyName() . ', composer-plugin packages should have a class defined in their extra key to be usable.');
        }

        parent::update($repo, $initial, $target);
        $this->composer->getPluginManager()->registerPackage($target, true);
    }
}
