@php
    $text = $props['text'] ?? 'Click here';
    $url = $props['url'] ?? '#';
    $bgColor = $props['bg_color'] ?? '#185FA5';
    $textColor = $props['text_color'] ?? '#ffffff';
    $borderRadius = $props['border_radius'] ?? '4';
    $fontSize = $props['font_size'] ?? '16';
    $padding = $props['padding'] ?? '14px 28px';
    $align = $props['align'] ?? 'center';
    $fontFamily = $props['font_family'] ?? 'Arial, Helvetica, sans-serif';
    $width = $props['width'] ?? 'auto';
    $isFullWidth = $width === 'full';
    $outerPadding = $props['outer_padding'] ?? '8px 24px';
@endphp
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;">
    <tr>
        <td style="padding:{{ $outerPadding }};text-align:{{ $align }};" align="{{ $align }}">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" {{ $isFullWidth ? 'width="100%" style="width:100%;"' : 'style="margin:0 auto;" align="' . $align . '"' }}>
                <tr>
                    <td style="border-radius:{{ $borderRadius }}px;background-color:{{ $bgColor }};text-align:center;" align="center" bgcolor="{{ $bgColor }}">
                        <!--[if mso]>
                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $url }}" style="height:auto;v-text-anchor:middle;{{ $isFullWidth ? 'width:100%;' : 'width:auto;' }}" arcsize="{{ round(($borderRadius / 44) * 100) }}%" strokecolor="{{ $bgColor }}" fillcolor="{{ $bgColor }}">
                        <w:anchorlock/>
                        <center style="color:{{ $textColor }};font-family:{{ $fontFamily }};font-size:{{ $fontSize }}px;font-weight:bold;">{{ $text }}</center>
                        </v:roundrect>
                        <![endif]-->
                        <!--[if !mso]><!-->
                        <a href="{{ $url }}" target="_blank" style="background-color:{{ $bgColor }};border-radius:{{ $borderRadius }}px;color:{{ $textColor }};display:{{ $isFullWidth ? 'block' : 'inline-block' }};font-family:{{ $fontFamily }};font-size:{{ $fontSize }}px;font-weight:bold;padding:{{ $padding }};text-align:center;text-decoration:none;{{ $isFullWidth ? 'width:100%;box-sizing:border-box;' : '' }}-webkit-text-size-adjust:none;mso-hide:all;">
                            {{ $text }}
                        </a>
                        <!--<![endif]-->
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
