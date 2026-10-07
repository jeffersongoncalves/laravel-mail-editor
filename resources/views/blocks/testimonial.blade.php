@php
    $quote = $props['quote'] ?? '';
    $authorName = $props['author_name'] ?? '';
    $authorRole = $props['author_role'] ?? '';
    $avatarSrc = $props['avatar_src'] ?? '';
    $accentColor = $props['accent_color'] ?? '#185FA5';
    $textColor = $props['text_color'] ?? '#555555';
    $nameColor = $props['name_color'] ?? '#333333';
    $bgColor = $props['bg_color'] ?? '#ffffff';
    $fontFamily = $props['font_family'] ?? 'Arial, Helvetica, sans-serif';
    $padding = $props['padding'] ?? '16px 24px';
@endphp
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;background-color:{{ $bgColor }};">
    <tr>
        <td style="padding:{{ $padding }};">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;">
                <tr>
                    <td style="border-left:3px solid {{ $accentColor }};padding:16px 20px;">
                        @if ($quote)
                            <p style="margin:0 0 16px 0;font-family:{{ $fontFamily }};font-size:16px;line-height:1.6;color:{{ $textColor }};font-style:italic;">
                                &ldquo;{!! $quote !!}&rdquo;
                            </p>
                        @endif
                        <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                @if ($avatarSrc)
                                    <td style="vertical-align:middle;padding-right:12px;" valign="middle">
                                        <img src="{{ $avatarSrc }}" alt="{{ $authorName }}" width="40" height="40" style="display:block;width:40px;height:40px;border-radius:20px;border:0;outline:none;" />
                                    </td>
                                @endif
                                <td style="vertical-align:middle;" valign="middle">
                                    @if ($authorName)
                                        <p style="margin:0;font-family:{{ $fontFamily }};font-size:14px;font-weight:bold;color:{{ $nameColor }};line-height:1.4;">
                                            {{ $authorName }}
                                        </p>
                                    @endif
                                    @if ($authorRole)
                                        <p style="margin:0;font-family:{{ $fontFamily }};font-size:13px;color:{{ $textColor }};line-height:1.4;">
                                            {{ $authorRole }}
                                        </p>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
