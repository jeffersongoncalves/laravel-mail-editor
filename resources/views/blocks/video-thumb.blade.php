@php
    $thumbSrc = $props['thumb_src'] ?? '';
    $videoUrl = $props['video_url'] ?? '#';
    $alt = $props['alt'] ?? 'Watch video';
    $width = $props['width'] ?? '100%';
@endphp
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;">
    <tr>
        <td align="center" style="padding:12px 24px;">
            <a href="{{ $videoUrl }}" target="_blank" style="display:inline-block;position:relative;text-decoration:none;">
                @if ($thumbSrc)
                    <img
                        src="{{ $thumbSrc }}"
                        alt="{{ $alt }}"
                        width="{{ str_replace('%', '', $width) === '100' ? '552' : str_replace('px', '', $width) }}"
                        style="display:block;max-width:100%;width:{{ $width }};height:auto;border:0;border-radius:8px;"
                    />
                @else
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="552" style="width:100%;max-width:552px;background-color:#f0f0f0;border-radius:8px;">
                        <tr>
                            <td align="center" valign="middle" style="padding:60px 0;font-size:48px;color:rgba(0,0,0,0.3);">
                                &#9654;
                            </td>
                        </tr>
                    </table>
                @endif
            </a>
        </td>
    </tr>
</table>
