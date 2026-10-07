@php
    $gap = $props['gap'] ?? 12;
    $stackMobile = $props['stack_mobile'] ?? true;
    $col1 = $props['col1_content'] ?? '';
    $col2 = $props['col2_content'] ?? '';
    $col3 = $props['col3_content'] ?? '';
    $halfGap = (int) $gap / 2;
@endphp
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;">
    <tr>
        <td style="padding:0 24px;">
            <!--[if mso]>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
            <td valign="top" width="33%" style="width:33.33%;">
            <![endif]-->
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" class="three-col-td" style="display:inline-block;width:33.33%;max-width:33.33%;vertical-align:top;" width="33%">
                <tr>
                    <td style="padding:0 {{ $halfGap }}px;vertical-align:top;" valign="top">
                        {!! $col1 !!}
                    </td>
                </tr>
            </table>
            <!--[if mso]>
            </td>
            <td valign="top" width="33%" style="width:33.33%;">
            <![endif]-->
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" class="three-col-td" style="display:inline-block;width:33.33%;max-width:33.33%;vertical-align:top;" width="33%">
                <tr>
                    <td style="padding:0 {{ $halfGap }}px;vertical-align:top;" valign="top">
                        {!! $col2 !!}
                    </td>
                </tr>
            </table>
            <!--[if mso]>
            </td>
            <td valign="top" width="33%" style="width:33.33%;">
            <![endif]-->
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" class="three-col-td" style="display:inline-block;width:33.33%;max-width:33.33%;vertical-align:top;" width="33%">
                <tr>
                    <td style="padding:0 {{ $halfGap }}px;vertical-align:top;" valign="top">
                        {!! $col3 !!}
                    </td>
                </tr>
            </table>
            <!--[if mso]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </td>
    </tr>
</table>
