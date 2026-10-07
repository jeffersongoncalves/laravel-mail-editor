@php
    $items = $props['items'] ?? [];
    $type = $props['type'] ?? 'unordered';
    $bulletChar = $props['bullet_char'] ?? "\u{2022}";
    $bulletColor = $props['bullet_color'] ?? '#378ADD';
    $indent = $props['indent'] ?? 0;
    $color = $props['color'] ?? '#333333';
    $fontSize = $props['font_size'] ?? 14;
@endphp
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;">
    <tr>
        <td style="padding:12px 24px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;margin-left:{{ $indent }}px;">
                @foreach ($items as $index => $item)
                    @php
                        $text = is_array($item) ? ($item['text'] ?? '') : $item;
                        $link = is_array($item) ? ($item['link'] ?? '') : '';
                        $bullet = $type === 'ordered' ? ($index + 1) . '.' : $bulletChar;
                    @endphp
                    <tr>
                        <td width="24" valign="top" style="padding:4px 8px 4px 0;font-size:{{ $fontSize }}px;line-height:1.6;vertical-align:top;">
                            <span style="color:{{ $bulletColor }};font-weight:bold;">{{ $bullet }}</span>
                        </td>
                        <td valign="top" style="padding:4px 0;font-size:{{ $fontSize }}px;line-height:1.6;color:{{ $color }};vertical-align:top;">
                            @if ($link)
                                <a href="{{ $link }}" style="color:{{ $bulletColor }};text-decoration:underline;" target="_blank">{{ $text }}</a>
                            @else
                                {{ $text }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </td>
    </tr>
</table>
