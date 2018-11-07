<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new MyApp\UserBundle\MyAppUserBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new MyApp\AdminBundle\MyAppAdminBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),

            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Ob\HighchartsBundle\ObHighchartsBundle(),
            new Nomaya\SocialBundle\NomayaSocialBundle(),
            new Ras\Bundle\FlashAlertBundle\RasFlashAlertBundle(),

            new Mgilet\NotificationBundle\MgiletNotificationBundle(),
            new Gregwar\CaptchaBundle\GregwarCaptchaBundle(),

            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new blackknight467\StarRatingBundle\StarRatingBundle(),

            new Craue\FormFlowBundle\CraueFormFlowBundle(),

            new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}