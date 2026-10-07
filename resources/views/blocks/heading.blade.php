@php
    $level = $props['level'] ?? 2;
    $text = $props['text'] ?? '';
    $color = $props['color'] ?? '#333333';
    $align = $props['align'] ?? 'left';
    $fontFamily = $props['font_family'] ?? 'Arial, Helvetica, sans-serif';
    $padding = $props['padding'] ?? '0 24px';

    $fontSizes = [
        1 => 32,
        2 => 24,
        3 => 20,
        4 => 16,
    ];
    $fontSize = $props['font_size'] ?? ($fontSizes[$level] ?? 24);
    $tag = 'h' . min(max((int) $level, 1), 6);
@endphp
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;">
    <tr>
        <td style="padding:{{ $padding }};text-align:{{ $align }};" align="{{ $align }}">
            <{{ $tag }} style="margin:0;font-family:{{ $fontFamily }};font-size:{{ $fontSize }}px;line-height:1.3;font-weight:bold;color:{{ $color }};">
                {{ $text }}
            </{{ $tag }}>
        </td>
    </tr>
</table>
