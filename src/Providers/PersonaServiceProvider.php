<?php

namespace Persona\Providers;

use Illuminate\Support\ServiceProvider;
use Persona\Contracts\CountryNormalizerContract;
use Persona\Contracts\DocumentVerificationProvider;
use Persona\Contracts\EmailNormalizerContract;
use Persona\Contracts\HandleNormalizerContract;
use Persona\Contracts\PhoneNormalizerContract;
use Persona\Normalizers\DefaultCountryNormalizer;
use Persona\Normalizers\DefaultEmailNormalizer;
use Persona\Normalizers\DefaultHandleNormalizer;
use Persona\Normalizers\DefaultPhoneNormalizer;
use Persona\Services\NullDocumentVerificationProvider;
use RuntimeException;

class PersonaServiceProvider extends ServiceProvider
{
    /**
     * Register the services and contract bindings for the package.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/persona.php', 'persona');

        $this->app->singleton(EmailNormalizerContract::class, DefaultEmailNormalizer::class);
        $this->app->singleton(PhoneNormalizerContract::class, DefaultPhoneNormalizer::class);
        $this->app->singleton(HandleNormalizerContract::class, DefaultHandleNormalizer::class);
        $this->app->singleton(CountryNormalizerContract::class, DefaultCountryNormalizer::class);
        $this->app->singleton(DocumentVerificationProvider::class, NullDocumentVerificationProvider::class);
    }

    /**
     * Boot the package services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->publishes([
            __DIR__ . '/../../config/persona.php' => config_path('persona.php'),
        ], 'persona-config');

        $this->publishes([
            __DIR__ . '/../../stubs' => database_path('persona/stubs'),
        ], 'persona-stubs');

        $this->assertHashKeyIsConfigured();
    }

    /**
     * Ensure that the PERSONA_HASH_KEY environment value is present.
     *
     * If the configured hash key is null or empty, booting will fail to
     * signal that sensitive Persona hashes can not be secured.
     *
     * @throws \RuntimeException
     */
    protected function assertHashKeyIsConfigured(): void
    {
        $hashKey = config('persona.hash_key');

        if (is_null($hashKey) || $hashKey === '') {
            throw new RuntimeException(
                'PERSONA_HASH_KEY is missing or empty. Please generate a unique key and set it in your .env file to secure sensitive Persona hashes.'
            );
        }
    }
}