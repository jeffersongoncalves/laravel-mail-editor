@php
    $endDate = $props['end_date'] ?? null;
    $timezone = $props['timezone'] ?? 'America/Sao_Paulo';
    $label = $props['label'] ?? 'Offer ends in';
    $style = $props['style'] ?? 'default';
    $width = (int) ($props['width'] ?? 500);
    $height = (int) ($props['height'] ?? 80);
    $expiredText = $props['expired_text'] ?? 'Offer expired';

    $countdownUrl = $endDate
        ? rtrim(config('mail-editor.app_url', config('app.url')), '/') .
          '/mail-editor/countdown?' . http_build_query([
              'end' => $endDate,
              'tz' => $timezone,
              'style' => $style,
              'w' => $width,
              'h' => $height,
              'expired' => $expiredText,
          ])
        : null;
@endphp
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;">
    <tr>
        <td style="text-align:center;padding:16px 24px;">
            @if ($countdownUrl)
                <img src="{{ $countdownUrl }}"
                     alt="{{ $label }}"
                     width="{{ $width }}"
                     height="{{ $height }}"
                     style="display:block;margin:0 auto;max-width:100%;height:auto;">
            @else
                <p style="font-family:Arial,sans-serif;font-size:14px;color:#999;text-align:center;margin:0;">
                    {{ $label }}: {{ $expiredText }}
                </p>
            @endif
        </td>
    </tr>
</table>
