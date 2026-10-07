@php
    $logos = $props['logos'] ?? [];
    $cols = (int) ($props['cols'] ?? 3);
    $grayscale = $props['grayscale'] ?? true;
    $cellPadding = $props['cell_padding'] ?? 16;
    $colWidth = floor(100 / $cols);
    $imgFilter = $grayscale ? 'filter:grayscale(100%);' : '';
    $chunks = array_chunk($logos, $cols);
@endphp
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;">
    <tr>
        <td style="padding:12px 24px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;">
                @foreach ($chunks as $row)
                    <tr>
                        @foreach ($row as $logo)
                            @php
                                $src = is_array($logo) ? ($logo['src'] ?? '') : $logo;
                                $alt = is_array($logo) ? ($logo['alt'] ?? '') : '';
                                $link = is_array($logo) ? ($logo['link'] ?? '') : '';
                            @endphp
                            <td width="{{ $colWidth }}%" align="center" valign="middle" style="padding:{{ $cellPadding }}px;">
                                @if ($link)
                                    <a href="{{ $link }}" target="_blank" style="text-decoration:none;">
                                @endif
                                <img
                                    src="{{ $src }}"
                                    alt="{{ $alt }}"
                                    width="{{ floor(552 / $cols - $cellPadding * 2) }}"
                                    style="display:block;max-width:100%;height:auto;border:0;{{ $imgFilter }}"
                                />
                                @if ($link)
                                    </a>
                                @endif
                            </td>
                        @endforeach
                        @for ($i = count($row); $i < $cols; $i++)
                            <td width="{{ $colWidth }}%" style="padding:{{ $cellPadding }}px;">&nbsp;</td>
                        @endfor
                    </tr>
                @endforeach
            </table>
        </td>
    </tr>
</table>
