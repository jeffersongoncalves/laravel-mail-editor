@php
    $bgColor = $props['bg_color'] ?? '#ffffff';
    $align = $props['align'] ?? 'center';
    $logoSrc = $props['logo_src'] ?? '';
    $logoAlt = $props['logo_alt'] ?? '';
    $logoHeight = $props['logo_height'] ?? '50';
    $logoLink = $props['logo_link'] ?? '';
    $webLink = $props['web_link'] ?? '';
    $padding = $props['padding'] ?? '16px 24px';
@endphp
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;background-color:{{ $bgColor }};">
    <tr>
        <td style="padding:{{ $padding }};text-align:{{ $align }};" align="{{ $align }}">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;">
                <tr>
                    <td style="text-align:{{ $align }};" align="{{ $align }}">
                        @if ($logoSrc)
                            @if ($logoLink)
                                <a href="{{ $logoLink }}" target="_blank" style="text-decoration:none;">
                            @endif
                            <img src="{{ $logoSrc }}" alt="{{ $logoAlt }}" style="display:inline-block;max-height:{{ $logoHeight }}px;width:auto;border:0;outline:none;text-decoration:none;" />
                            @if ($logoLink)
                                </a>
                            @endif
                        @endif
                    </td>
                    @if ($webLink)
                        <td style="text-align:right;vertical-align:middle;" align="right">
                            <a href="{{ $webLink }}" target="_blank" style="color:#666666;font-family:Arial,Helvetica,sans-serif;font-size:12px;text-decoration:underline;">
                                View in browser
                            </a>
                        </td>
                    @endif
                </tr>
            </table>
        </td>
    </tr>
</table>
