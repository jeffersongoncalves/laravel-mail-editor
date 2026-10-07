<?php

namespace JeffersonGoncalves\MailEditor\Tests;

use JeffersonGoncalves\MailEditor\MailEditorServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['mail-editor.app_url' => 'http://localhost']);
    }

    protected function defineDatabaseMigrations(): void
    {
        $migrationPath = __DIR__.'/../database/migrations';
        $files = glob($migrationPath.'/*.php.stub');

        foreach ($files as $file) {
            $migrationFile = $migrationPath.'/'.basename($file, '.stub');

            if (! file_exists($migrationFile)) {
                copy($file, $migrationFile);
            }
        }

        $this->loadMigrationsFrom($migrationPath);

        $this->beforeApplicationDestroyed(function () use ($migrationPath, $files) {
            foreach ($files as $file) {
                $migrationFile = $migrationPath.'/'.basename($file, '.stub');

                if (file_exists($migrationFile)) {
                    unlink($migrationFile);
                }
            }
        });
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
