@php
    $stars = (int) ($props['stars'] ?? 5);
    $text = $props['text'] ?? '';
    $author = $props['author'] ?? '';
    $starColor = $props['star_color'] ?? '#EF9F27';

    $filledStars = str_repeat("\u{2605}", $stars);
    $emptyStars = str_repeat("\u{2606}", 5 - $stars);
@endphp
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;">
    <tr>
        <td style="padding:16px 24px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;">
                <tr>
                    <td style="font-size:28px;line-height:1;padding-bottom:12px;color:{{ $starColor }};font-family:Arial,sans-serif;">
                        {{ $filledStars }}{{ $emptyStars }}
                    </td>
                </tr>
                @if ($text)
                    <tr>
                        <td style="font-family:Arial,sans-serif;font-size:14px;line-height:1.6;color:#555555;font-style:italic;padding-bottom:8px;">
                            &ldquo;{{ $text }}&rdquo;
                        </td>
                    </tr>
                @endif
                @if ($author)
                    <tr>
                        <td style="font-family:Arial,sans-serif;font-size:13px;font-weight:bold;color:#333333;">
                            &mdash; {{ $author }}
                        </td>
                    </tr>
                @endif
            </table>
        </td>
    </tr>
</table>
