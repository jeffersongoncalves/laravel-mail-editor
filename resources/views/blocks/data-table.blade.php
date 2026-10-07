@php
    $headers = $props['headers'] ?? [];
    $rows = $props['rows'] ?? [];
    $striped = $props['striped'] ?? true;
    $headerBgColor = $props['header_bg_color'] ?? '#378ADD';
    $headerTextColor = $props['header_text_color'] ?? '#ffffff';
    $stripeColor = $props['stripe_color'] ?? '#f8f9fa';
    $fontSize = $props['font_size'] ?? 13;
    $borderColor = $props['border_color'] ?? '#e8e8e8';
@endphp
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;">
    <tr>
        <td style="padding:12px 24px;">
            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;border:1px solid {{ $borderColor }};border-collapse:collapse;font-family:Arial,sans-serif;font-size:{{ $fontSize }}px;">
                @if (count($headers))
                    <thead>
                        <tr>
                            @foreach ($headers as $header)
                                <th style="background-color:{{ $headerBgColor }};color:{{ $headerTextColor }};font-weight:bold;padding:10px 12px;text-align:left;border:1px solid {{ $borderColor }};">
                                    {{ $header }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                @endif
                <tbody>
                    @foreach ($rows as $rowIndex => $row)
                        @php
                            $rowBg = ($striped && $rowIndex % 2 === 1) ? $stripeColor : '#ffffff';
                        @endphp
                        <tr>
                            @foreach ((array) $row as $cell)
                                <td style="background-color:{{ $rowBg }};padding:8px 12px;border:1px solid {{ $borderColor }};color:#333333;">
                                    {{ $cell }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </td>
    </tr>
</table>
