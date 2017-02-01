<?php

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class AppKernel
{

    /** @var  ContainerInterface */
    private $container;

    function __construct()
    {

        $this->container = new ContainerBuilder();
        $loader = new YamlFileLoader($this->container, new FileLocator(__DIR__));

        $loader->load(__DIR__ . '/config/config.yml');

        $this->container->setParameter('root_dir', __DIR__ . '/..');
        $this->container->addCompilerPass(new \Anodet\Core\Manager\ModulesPass());

        $this->container->compile();

    }

    public function boot()
    {
        $this->container->get('manager')->run();
    }
}
