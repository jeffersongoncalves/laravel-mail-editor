<?php

namespace JeffersonGoncalves\MailEditor\Blocks;

use JeffersonGoncalves\MailEditor\Blocks\Contracts\EmailBlock;

abstract class AbstractEmailBlock implements EmailBlock
{
    public static function category(): string
    {
        return 'content';
    }

    public function render(array $props): string
    {
        $merged = array_merge(static::defaultProps(), $props);

        return view('mail-editor::blocks.'.static::type(), ['props' => $merged])->render();
    }

    public function getMediaQueries(): string
    {
        return '';
    }
}
