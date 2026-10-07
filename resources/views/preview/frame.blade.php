<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Email Preview</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #ffffff;
        }

        @php
            $client = $client ?? 'gmail';
        @endphp

        @if ($client === 'gmail')
        body { background-color: #ffffff; }
        .email-wrapper { max-width: 600px; margin: 0 auto; }
        @elseif ($client === 'outlook')
        body { background-color: #f5f5f5; }
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            font-family: Calibri, Arial, sans-serif;
        }
        img { -ms-interpolation-mode: bicubic; }
        table { border-collapse: collapse; }
        @elseif ($client === 'apple')
        body { background-color: #ffffff; color: #1a1a1a; }
        .email-wrapper { max-width: 600px; margin: 0 auto; }
        @elseif ($client === 'mobile')
        body { background-color: #ffffff; max-width: 375px; margin: 0 auto; }
        .email-wrapper { max-width: 375px; margin: 0 auto; }
        table { max-width: 100% !important; }
        img { max-width: 100% !important; height: auto !important; }
        .email-col, .two-col-td, .three-col-td { width: 100% !important; display: block !important; }
        @endif

        {!! $mediaQueries ?? '' !!}
    </style>
</head>
<body>
    <div class="email-wrapper">
        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: {{ $settings['bg_color'] ?? '#f8f9fa' }};">
            <tr>
                <td align="center" style="padding: 0;">
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" style="max-width: 600px; width: 100%; background-color: #ffffff;">
                        <tr>
                            <td>
                                {!! $content !!}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <script>
        window.addEventListener('message', function(event) {
            if (event.data && event.data.type === 'blocks-update') {
                window.location.reload();
            }
        });

        function notifyParentHeight() {
            var height = document.documentElement.scrollHeight;
            window.parent.postMessage({ type: 'iframe-height', height: height }, '*');
        }

        window.addEventListener('load', notifyParentHeight);
        new MutationObserver(notifyParentHeight).observe(document.body, { childList: true, subtree: true });
    </script>
</body>
</html>
