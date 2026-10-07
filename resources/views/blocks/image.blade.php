@php
    $src = $props['src'] ?? '';
    $alt = $props['alt'] ?? '';
    $link = $props['link'] ?? '';
    $width = $props['width'] ?? '100%';
    $align = $props['align'] ?? 'center';
    $padding = $props['padding'] ?? '0 24px';
@endphp
@if ($src)
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;">
    <tr>
        <td style="padding:{{ $padding }};text-align:{{ $align }};" align="{{ $align }}">
            @if ($link)
                <a href="{{ $link }}" target="_blank" style="text-decoration:none;">
            @endif
            <img src="{{ $src }}" alt="{{ $alt }}" width="{{ str_replace('%', '', $width) }}" style="display:block;max-width:100%;width:{{ $width }};height:auto;border:0;outline:none;text-decoration:none;" />
            @if ($link)
                </a>
            @endif
        </td>
    </tr>
</table>
@endif
