<?php

declare(strict_types=1);

namespace MauticPlugin\GranamCzechVocativeBundle;

use Mautic\PluginBundle\Bundle\PluginBundleBase;

class GranamCzechVocativeBundle extends PluginBundleBase
{
    public function boot()
    {
        // Use Mautic root autoload
        if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
            require_once __DIR__ . '/../../vendor/autoload.php';
        }
    }
}