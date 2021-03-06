<?php

namespace WalkerChiu\Account;

use Illuminate\Support\ServiceProvider;

class AccountServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes([
           __DIR__ .'/config/account.php' => config_path('wk-account.php'),
        ], 'config');

        // Publish migration files
        $from = __DIR__ .'/database/migrations/';
        $to   = database_path('migrations') .'/';
        $this->publishes([
            $from .'create_wk_account_table.php'
                => $to .date('Y_m_d_His', time()) .'_create_wk_account_table.php'
        ], 'migrations');

        $this->loadTranslationsFrom(__DIR__.'/translations', 'php-account');
        $this->publishes([
            __DIR__.'/translations' => resource_path('lang/vendor/php-account'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                config('wk-account.command.cleaner')
            ]);
        }

        config('wk-core.class.account.profile')::observe(config('wk-core.class.account.profileObserver'));
    }

    /**
     * Register the blade directives
     *
     * @return void
     */
    private function bladeDirectives()
    {
    }

    /**
     * Merges user's and package's configs.
     *
     * @return void
     */
    private function mergeConfig()
    {
        if (!config()->has('wk-account')) {
            $this->mergeConfigFrom(
                __DIR__ .'/config/account.php', 'wk-account'
            );
        }

        $this->mergeConfigFrom(
            __DIR__ .'/config/account.php', 'account'
        );
    }

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param String  $path
     * @param String  $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key)
    {
        if (
            !(
                $this->app instanceof CachesConfiguration
                && $this->app->configurationIsCached()
            )
        ) {
            $config = $this->app->make('config');
            $content = $config->get($key, []);

            $config->set($key, array_merge(
                require $path, $content
            ));
        }
    }
}
