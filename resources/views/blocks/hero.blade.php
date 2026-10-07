@php
    $bgColor = $props['bg_color'] ?? '#185FA5';
    $bgImage = $props['bg_image'] ?? '';
    $textColor = $props['text_color'] ?? '#ffffff';
    $title = $props['title'] ?? '';
    $titleSize = $props['title_size'] ?? '36';
    $subtitle = $props['subtitle'] ?? '';
    $subtitleSize = $props['subtitle_size'] ?? '18';
    $align = $props['align'] ?? 'center';
    $fontFamily = $props['font_family'] ?? 'Arial, Helvetica, sans-serif';
    $padding = $props['padding'] ?? '60px 24px';
    $ctaText = $props['cta_text'] ?? '';
    $ctaUrl = $props['cta_url'] ?? '#';
    $ctaBgColor = $props['cta_bg_color'] ?? '#ffffff';
    $ctaTextColor = $props['cta_text_color'] ?? '#185FA5';
    $ctaBorderRadius = $props['cta_border_radius'] ?? '4';
@endphp
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;">
    <tr>
        <td style="background-color:{{ $bgColor }};{{ $bgImage ? 'background-image:url(' . $bgImage . ');background-size:cover;background-position:center center;background-repeat:no-repeat;' : '' }}">
            @if ($bgImage)
                <!--[if gte mso 9]>
                <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="width:600px;">
                <v:fill type="tile" src="{{ $bgImage }}" color="{{ $bgColor }}"/>
                <v:textbox style="mso-fit-shape-to-text:true" inset="0,0,0,0">
                <![endif]-->
            @endif
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;">
                <tr>
                    <td style="padding:{{ $padding }};text-align:{{ $align }};" align="{{ $align }}">
                        @if ($title)
                            <h1 style="margin:0 0 16px 0;font-family:{{ $fontFamily }};font-size:{{ $titleSize }}px;line-height:1.2;font-weight:bold;color:{{ $textColor }};">
                                {{ $title }}
                            </h1>
                        @endif
                        @if ($subtitle)
                            <p style="margin:0 0 24px 0;font-family:{{ $fontFamily }};font-size:{{ $subtitleSize }}px;line-height:1.5;color:{{ $textColor }};">
                                {{ $subtitle }}
                            </p>
                        @endif
                        @if ($ctaText)
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:0 auto;" align="{{ $align }}">
                                <tr>
                                    <td>
                                        <!--[if mso]>
                                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $ctaUrl }}" style="height:44px;v-text-anchor:middle;width:auto;" arcsize="10%" strokecolor="{{ $ctaBgColor }}" fillcolor="{{ $ctaBgColor }}">
                                        <w:anchorlock/>
                                        <center style="color:{{ $ctaTextColor }};font-family:{{ $fontFamily }};font-size:16px;font-weight:bold;">{{ $ctaText }}</center>
                                        </v:roundrect>
                                        <![endif]-->
                                        <!--[if !mso]><!-->
                                        <a href="{{ $ctaUrl }}" target="_blank" style="background-color:{{ $ctaBgColor }};border-radius:{{ $ctaBorderRadius }}px;color:{{ $ctaTextColor }};display:inline-block;font-family:{{ $fontFamily }};font-size:16px;font-weight:bold;line-height:44px;padding:0 28px;text-align:center;text-decoration:none;-webkit-text-size-adjust:none;mso-hide:all;">
                                            {{ $ctaText }}
                                        </a>
                                        <!--<![endif]-->
                                    </td>
                                </tr>
                            </table>
                        @endif
                    </td>
                </tr>
            </table>
            @if ($bgImage)
                <!--[if gte mso 9]></v:textbox></v:rect><![endif]-->
            @endif
        </td>
    </tr>
</table>
