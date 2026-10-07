@php
    $imageSrc = $props['image_src'] ?? '';
    $imageAlt = $props['image_alt'] ?? '';
    $name = $props['name'] ?? '';
    $price = $props['price'] ?? '';
    $oldPrice = $props['old_price'] ?? '';
    $description = $props['description'] ?? '';
    $ctaText = $props['cta_text'] ?? 'Buy Now';
    $ctaUrl = $props['cta_url'] ?? '#';
    $ctaBgColor = $props['cta_bg_color'] ?? '#378ADD';
    $badgeText = $props['badge_text'] ?? '';
    $badgeBgColor = $props['badge_bg_color'] ?? '#e53e3e';
@endphp
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;">
    <tr>
        <td style="padding:12px 24px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;border:1px solid #e8e8e8;border-radius:8px;overflow:hidden;">
                @if ($imageSrc)
                    <tr>
                        <td style="position:relative;">
                            <img src="{{ $imageSrc }}" alt="{{ $imageAlt }}" width="552" style="display:block;width:100%;height:auto;border:0;" />
                            @if ($badgeText)
                                <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="position:absolute;top:12px;left:12px;">
                                    <tr>
                                        <td style="background-color:{{ $badgeBgColor }};color:#ffffff;font-size:11px;font-weight:bold;padding:4px 10px;border-radius:4px;font-family:Arial,sans-serif;">
                                            {{ $badgeText }}
                                        </td>
                                    </tr>
                                </table>
                            @endif
                        </td>
                    </tr>
                @endif
                <tr>
                    <td style="padding:16px 20px;">
                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;">
                            <tr>
                                <td style="font-family:Arial,sans-serif;font-size:18px;font-weight:bold;color:#1a1a1a;padding-bottom:8px;">
                                    {{ $name }}
                                </td>
                            </tr>
                            <tr>
                                <td style="font-family:Arial,sans-serif;font-size:20px;color:#1a1a1a;padding-bottom:8px;">
                                    @if ($oldPrice)
                                        <span style="text-decoration:line-through;color:#999999;font-size:14px;margin-right:8px;">{{ $oldPrice }}</span>
                                    @endif
                                    <span style="font-weight:bold;">{{ $price }}</span>
                                </td>
                            </tr>
                            @if ($description)
                                <tr>
                                    <td style="font-family:Arial,sans-serif;font-size:14px;color:#666666;line-height:1.5;padding-bottom:16px;">
                                        {{ $description }}
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td>
                                    <!--[if mso]>
                                    <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $ctaUrl }}" style="height:40px;v-text-anchor:middle;width:200px;" arcsize="10%" strokecolor="{{ $ctaBgColor }}" fillcolor="{{ $ctaBgColor }}">
                                    <w:anchorlock/>
                                    <center style="color:#ffffff;font-family:Arial,sans-serif;font-size:14px;font-weight:bold;">{{ $ctaText }}</center>
                                    </v:roundrect>
                                    <![endif]-->
                                    <!--[if !mso]><!-->
                                    <a href="{{ $ctaUrl }}" target="_blank" style="display:inline-block;background-color:{{ $ctaBgColor }};color:#ffffff;font-family:Arial,sans-serif;font-size:14px;font-weight:bold;text-decoration:none;padding:10px 28px;border-radius:4px;text-align:center;">
                                        {{ $ctaText }}
                                    </a>
                                    <!--<![endif]-->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
