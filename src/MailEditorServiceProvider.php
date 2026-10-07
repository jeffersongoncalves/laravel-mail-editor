<?php

namespace JeffersonGoncalves\MailEditor;

use Illuminate\Support\Facades\Mail;
use JeffersonGoncalves\MailEditor\Blocks\AlertBlock;
use JeffersonGoncalves\MailEditor\Blocks\ButtonBlock;
use JeffersonGoncalves\MailEditor\Blocks\CountdownBlock;
use JeffersonGoncalves\MailEditor\Blocks\CouponBlock;
use JeffersonGoncalves\MailEditor\Blocks\DataTableBlock;
use JeffersonGoncalves\MailEditor\Blocks\DividerBlock;
use JeffersonGoncalves\MailEditor\Blocks\FooterBlock;
use JeffersonGoncalves\MailEditor\Blocks\HeaderBlock;
use JeffersonGoncalves\MailEditor\Blocks\HeadingBlock;
use JeffersonGoncalves\MailEditor\Blocks\HeroBlock;
use JeffersonGoncalves\MailEditor\Blocks\ImageBlock;
use JeffersonGoncalves\MailEditor\Blocks\ListBlock;
use JeffersonGoncalves\MailEditor\Blocks\LogoGridBlock;
use JeffersonGoncalves\MailEditor\Blocks\ParagraphBlock;
use JeffersonGoncalves\MailEditor\Blocks\PreheaderBlock;
use JeffersonGoncalves\MailEditor\Blocks\ProductCardBlock;
use JeffersonGoncalves\MailEditor\Blocks\RatingBlock;
use JeffersonGoncalves\MailEditor\Blocks\SpacerBlock;
use JeffersonGoncalves\MailEditor\Blocks\TestimonialBlock;
use JeffersonGoncalves\MailEditor\Blocks\ThreeColumnsBlock;
use JeffersonGoncalves\MailEditor\Blocks\TwoColumnsBlock;
use JeffersonGoncalves\MailEditor\Blocks\VideoThumbBlock;
use JeffersonGoncalves\MailEditor\Commands\MakeTemplateCommand;
use JeffersonGoncalves\MailEditor\Commands\ReleaseLocksCommand;
use JeffersonGoncalves\MailEditor\Support\BlockRegistry;
use JeffersonGoncalves\MailEditor\Support\TemplateMailableBridge;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MailEditorServiceProvider extends PackageServiceProvider
{
    public static string $name = 'mail-editor';

    /** @var list<class-string<Blocks\Contracts\EmailBlock>> */
    public const BLOCKS = [
        PreheaderBlock::class,
        HeaderBlock::class,
        HeroBlock::class,
        HeadingBlock::class,
        ParagraphBlock::class,
        ButtonBlock::class,
        ImageBlock::class,
        DividerBlock::class,
        SpacerBlock::class,
        TestimonialBlock::class,
        AlertBlock::class,
        TwoColumnsBlock::class,
        ThreeColumnsBlock::class,
        FooterBlock::class,
        ListBlock::class,
        VideoThumbBlock::class,
        ProductCardBlock::class,
        RatingBlock::class,
        DataTableBlock::class,
        CouponBlock::class,
        LogoGridBlock::class,
        CountdownBlock::class,
    ];

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasRoute('web')
            ->hasMigrations([
                'create_email_template_categories_table',
                'create_email_templates_table',
                'create_saved_email_blocks_table',
                'create_email_template_variants_table',
                'create_email_template_versions_table',
                'create_email_brand_kits_table',
                'create_email_template_activities_table',
                'create_email_template_notifications_table',
                'create_email_template_schedules_table',
                'create_email_themes_table',
            ])
            ->hasCommands([
                MakeTemplateCommand::class,
                ReleaseLocksCommand::class,
            ]);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(BlockRegistry::class, function () {
            $registry = new BlockRegistry;

            foreach ([...static::BLOCKS, ...config('mail-editor.blocks', [])] as $blockClass) {
                $registry->register($blockClass);
            }

            return $registry;
        });
    }

    public function packageBooted(): void
    {
        if (! Mail::hasMacro('template')) {
            Mail::macro('template', function (string $slug, array $variables = []) {
                return new TemplateMailableBridge($slug, $variables);
            });
        }
    }
}
