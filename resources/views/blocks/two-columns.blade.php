@php
    $ratio = $props['ratio'] ?? '50-50';
    $gap = $props['gap'] ?? '16';
    $stackMobile = $props['stack_mobile'] ?? true;
    $bgColor = $props['bg_color'] ?? '#ffffff';
    $padding = $props['padding'] ?? '0 24px';
    $leftContent = $props['left_content'] ?? '';
    $rightContent = $props['right_content'] ?? '';

    $ratioMap = [
        '50-50' => ['50%', '50%'],
        '60-40' => ['60%', '40%'],
        '40-60' => ['40%', '60%'],
        '70-30' => ['70%', '30%'],
        '30-70' => ['30%', '70%'],
    ];

    [$leftWidth, $rightWidth] = $ratioMap[$ratio] ?? $ratioMap['50-50'];
    $halfGap = (int) $gap / 2;
    $mobileClass = $stackMobile ? 'two-col-td' : '';
@endphp
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;background-color:{{ $bgColor }};">
    <tr>
        <td style="padding:{{ $padding }};">
            <!--[if mso]>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
            <td valign="top" width="{{ str_replace('%', '', $leftWidth) }}%" style="width:{{ $leftWidth }};">
            <![endif]-->
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" class="{{ $mobileClass }}" style="display:inline-block;width:{{ $leftWidth }};max-width:{{ $leftWidth }};vertical-align:top;" width="{{ $leftWidth }}">
                <tr>
                    <td style="padding:0 {{ $halfGap }}px 0 0;vertical-align:top;" valign="top">
                        {!! $leftContent !!}
                    </td>
                </tr>
            </table>
            <!--[if mso]>
            </td>
            <td valign="top" width="{{ str_replace('%', '', $rightWidth) }}%" style="width:{{ $rightWidth }};">
            <![endif]-->
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" class="{{ $mobileClass }}" style="display:inline-block;width:{{ $rightWidth }};max-width:{{ $rightWidth }};vertical-align:top;" width="{{ $rightWidth }}">
                <tr>
                    <td style="padding:0 0 0 {{ $halfGap }}px;vertical-align:top;" valign="top">
                        {!! $rightContent !!}
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
