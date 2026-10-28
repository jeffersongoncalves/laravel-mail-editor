<?php

namespace JeffersonGoncalves\MailEditor\Tests;

use JeffersonGoncalves\MailEditor\MailEditorServiceProvider;
use JeffersonGoncalves\PestPluginDatabaseMigrations\LoadsPackageMigrations;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use LoadsPackageMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        config(['mail-editor.app_url' => 'http://localhost']);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadPackageMigrations(MailEditorServiceProvider::class);
    }

    protected function getPackageProviders($app): array
    {
        return [
            MailEditorServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        config()->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
    }
}
