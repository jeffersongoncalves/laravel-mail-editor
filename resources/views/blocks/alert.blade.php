@php
    $type = $props['type'] ?? 'info';
    $text = $props['text'] ?? '';
    $fontFamily = $props['font_family'] ?? 'Arial, Helvetica, sans-serif';
    $padding = $props['padding'] ?? '8px 24px';

    $typeConfig = [
        'info' => [
            'bg' => '#E6F1FB',
            'border' => '#185FA5',
            'color' => '#185FA5',
            'icon' => '&#8505;',
        ],
        'warning' => [
            'bg' => '#FAEEDA',
            'border' => '#854F0B',
            'color' => '#854F0B',
            'icon' => '&#9888;',
        ],
        'error' => [
            'bg' => '#FCEBEB',
            'border' => '#A32D2D',
            'color' => '#A32D2D',
            'icon' => '&#10005;',
        ],
        'success' => [
            'bg' => '#EAF3DE',
            'border' => '#3B6D11',
            'color' => '#3B6D11',
            'icon' => '&#10003;',
        ],
    ];

    $config = $typeConfig[$type] ?? $typeConfig['info'];
    $bgColor = $props['bg_color'] ?? $config['bg'];
    $borderColor = $props['border_color'] ?? $config['border'];
    $textColor = $props['text_color'] ?? $config['color'];
    $icon = $config['icon'];
@endphp
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;">
    <tr>
        <td style="padding:{{ $padding }};">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;background-color:{{ $bgColor }};border-left:4px solid {{ $borderColor }};">
                <tr>
                    <td style="padding:14px 18px;font-family:{{ $fontFamily }};font-size:15px;line-height:1.5;color:{{ $textColor }};">
                        <span style="font-size:16px;margin-right:6px;">{!! $icon !!}</span>
                        {!! $text !!}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
