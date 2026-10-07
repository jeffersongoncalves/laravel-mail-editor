<?php

namespace JeffersonGoncalves\MailEditor\Blocks;

/**
 * Renders a video thumbnail image that links to the video URL.
 *
 * Email clients do not support embedded video (<video> tag). This block
 * displays a thumbnail with a play icon overlay that links to the video
 * URL (YouTube, Vimeo, etc.). Falls back to a gray placeholder with a
 * centered play triangle when no thumbnail is provided.
 *
 * @see https://www.emailonacid.com/blog/article/email-development/video-in-email/
 */
class VideoThumbBlock extends AbstractEmailBlock
{
    public static function type(): string
    {
        return 'video-thumb';
    }

    public static function label(): string
    {
        return 'Video Thumbnail';
    }

    public static function icon(): string
    {
        return 'heroicon-o-play-circle';
    }

    public static function category(): string
    {
        return 'marketing';
    }

    public static function defaultProps(): array
    {
        return [
            'thumb_src' => '',
            'video_url' => '',
            'alt' => 'Watch video',
            'width' => '100%',
            'play_icon_color' => 'rgba(255,255,255,0.9)',
        ];
    }
}
