@php
    $color = $props['color'] ?? '#555555';
    $fontSize = $props['font_size'] ?? '16';
    $lineHeight = $props['line_height'] ?? '1.6';
    $align = $props['align'] ?? 'left';
    $fontFamily = $props['font_family'] ?? 'Arial, Helvetica, sans-serif';
    $padding = $props['padding'] ?? '0 24px';
@endphp
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;">
    <tr>
        <td style="padding:{{ $padding }};text-align:{{ $align }};" align="{{ $align }}">
            <p style="margin:0;font-family:{{ $fontFamily }};font-size:{{ $fontSize }}px;line-height:{{ $lineHeight }};color:{{ $color }};">
                {!! $props['html'] ?? '' !!}
            </p>
        </td>
    </tr>
</table>
