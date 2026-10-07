@php
    $code = $props['code'] ?? '';
    $discountText = $props['discount_text'] ?? '';
    $expiresText = $props['expires_text'] ?? '';
    $bgColor = $props['bg_color'] ?? '#fff3cd';
    $borderColor = $props['border_color'] ?? '#EF9F27';
    $borderStyle = $props['border_style'] ?? 'dashed';
    $textColor = $props['text_color'] ?? '#333333';
@endphp
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;">
    <tr>
        <td style="padding:12px 24px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;background-color:{{ $bgColor }};border:2px {{ $borderStyle }} {{ $borderColor }};border-radius:8px;">
                <tr>
                    <td align="center" style="padding:24px 20px;">
                        @if ($discountText)
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="font-family:Arial,sans-serif;font-size:18px;font-weight:bold;color:{{ $textColor }};padding-bottom:12px;text-align:center;">
                                        {{ $discountText }}
                                    </td>
                                </tr>
                            </table>
                        @endif
                        <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="font-family:'Courier New',monospace;font-size:28px;font-weight:bold;color:{{ $textColor }};letter-spacing:4px;padding:8px 20px;text-align:center;">
                                    {{ $code }}
                                </td>
                            </tr>
                        </table>
                        @if ($expiresText)
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="font-family:Arial,sans-serif;font-size:12px;color:#999999;padding-top:12px;text-align:center;">
                                        {{ $expiresText }}
                                    </td>
                                </tr>
                            </table>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
