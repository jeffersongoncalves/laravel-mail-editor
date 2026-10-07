@php
    $height = $props['height'] ?? '24';
    $mobileHeight = $props['mobile_height'] ?? null;
    $spacerId = 'spacer-' . uniqid();
@endphp
@if ($mobileHeight)
<style>
    @media only screen and (max-width: 480px) {
        .{{ $spacerId }} {
            height: {{ $mobileHeight }}px !important;
        }
    }
</style>
@endif
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;">
    <tr>
        <td class="{{ $mobileHeight ? $spacerId : '' }}" style="height:{{ $height }}px;font-size:0;line-height:0;overflow:hidden;" height="{{ $height }}">
            &nbsp;
        </td>
    </tr>
</table>
