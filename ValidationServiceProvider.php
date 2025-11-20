<?php

namespace Alphavel\Validation;

use Alphavel\Framework\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Merge package config with application config
        $this->mergeConfigFrom(
            __DIR__ . '/config/validation.php',
            'validation'
        );

        $this->app->bind('validator', function ($app) {
            $config = $app->config('validation', []);
            return new Validator($config);
        });

        // Auto-register facade
        $this->facades([
            'Validator' => 'validator',
        ]);
    }

    public function boot(): void
    {
        // Publish configuration file
        $basePath = dirname(__DIR__, 3);
        
        $this->publishes([
            __DIR__ . '/config/validation.php' => $basePath . '/config/validation.php',
        ], 'config');
    }

    protected function mergeConfigFrom(string $path, string $key): void
    {
        if (!file_exists($path)) {
            return;
        }

        $packageConfig = require $path;
        $appConfig = $this->app->config($key, []);
        $merged = array_replace_recursive($packageConfig, $appConfig);

        $tempFile = sys_get_temp_dir() . '/alphavel_validation_config_' . uniqid() . '.php';
        file_put_contents($tempFile, '<?php return ' . var_export([$key => $merged], true) . ';');
        
        $this->app->loadConfig($tempFile);
        unlink($tempFile);
    }

    protected function publishes(array $paths, string $group = null): void
    {
        foreach ($paths as $source => $destination) {
            $configDir = dirname($destination);
            if (!is_dir($configDir) && strpos($configDir, '/config') !== false) {
                @mkdir($configDir, 0755, true);
            }
        }
    }
}
