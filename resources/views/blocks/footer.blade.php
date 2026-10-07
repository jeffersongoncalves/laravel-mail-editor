@php
    $bgColor = $props['bg_color'] ?? '#f5f5f5';
    $textColor = $props['text_color'] ?? '#888888';
    $linkColor = $props['link_color'] ?? '#666666';
    $fontFamily = $props['font_family'] ?? 'Arial, Helvetica, sans-serif';
    $fontSize = $props['font_size'] ?? '13';
    $padding = $props['padding'] ?? '32px 24px';
    $address = $props['address'] ?? '';
    $unsubscribeUrl = $props['unsubscribe_url'] ?? '#';
    $unsubscribeText = $props['unsubscribe_text'] ?? 'Unsubscribe';
    $webVersionUrl = $props['web_version_url'] ?? '';
    $webVersionText = $props['web_version_text'] ?? 'View in browser';
    $socialLinks = $props['social_links'] ?? [];
    $copyright = $props['copyright'] ?? '';
@endphp
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;background-color:{{ $bgColor }};">
    <tr>
        <td style="padding:{{ $padding }};text-align:center;font-family:{{ $fontFamily }};font-size:{{ $fontSize }}px;line-height:1.6;color:{{ $textColor }};" align="center">
            @if (count($socialLinks) > 0)
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:0 auto 16px auto;" align="center">
                    <tr>
                        @foreach ($socialLinks as $social)
                            <td style="padding:0 8px;">
                                <a href="{{ $social['url'] ?? '#' }}" target="_blank" style="text-decoration:none;color:{{ $linkColor }};">
                                    @if (isset($social['icon']))
                                        <img src="{{ $social['icon'] }}" alt="{{ $social['label'] ?? '' }}" width="24" height="24" style="display:block;border:0;outline:none;width:24px;height:24px;" />
                                    @else
                                        {{ $social['label'] ?? '' }}
                                    @endif
                                </a>
                            </td>
                        @endforeach
                    </tr>
                </table>
            @endif

            @if ($address)
                <p style="margin:0 0 8px 0;font-family:{{ $fontFamily }};font-size:{{ $fontSize }}px;color:{{ $textColor }};line-height:1.6;">
                    {{ $address }}
                </p>
            @endif

            <p style="margin:0 0 8px 0;font-family:{{ $fontFamily }};font-size:{{ $fontSize }}px;line-height:1.6;">
                <a href="{{ $unsubscribeUrl }}" target="_blank" style="color:{{ $linkColor }};text-decoration:underline;">{{ $unsubscribeText }}</a>
                @if ($webVersionUrl)
                    &nbsp;&bull;&nbsp;
                    <a href="{{ $webVersionUrl }}" target="_blank" style="color:{{ $linkColor }};text-decoration:underline;">{{ $webVersionText }}</a>
                @endif
            </p>

            @if ($copyright)
                <p style="margin:0;font-family:{{ $fontFamily }};font-size:{{ $fontSize }}px;color:{{ $textColor }};line-height:1.6;">
                    {{ $copyright }}
                </p>
            @endif
        </td>
    </tr>
</table>
