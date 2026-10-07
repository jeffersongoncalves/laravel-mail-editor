@php
    $color = $props['color'] ?? '#E0E0E0';
    $thickness = $props['thickness'] ?? '1';
    $style = $props['style'] ?? 'solid';
    $padding = $props['padding'] ?? '16px 24px';
    $width = $props['width'] ?? '100%';
@endphp
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;">
    <tr>
        <td style="padding:{{ $padding }};">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="{{ $width }}" style="width:{{ $width }};" align="center">
                <tr>
                    <td style="border-top:{{ $thickness }}px {{ $style }} {{ $color }};font-size:0;line-height:0;height:0;overflow:hidden;">
                        &nbsp;
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
