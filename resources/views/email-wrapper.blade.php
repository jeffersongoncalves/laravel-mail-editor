<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="x-apple-disable-message-reformatting" />
    <meta name="color-scheme" content="light dark" />
    <meta name="supported-color-schemes" content="light dark" />
    <title></title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:AllowPNG/>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style type="text/css">
        :root { color-scheme: light dark; }
        body { margin: 0; padding: 0; width: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table { border-collapse: collapse; border-spacing: 0; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
        a { text-decoration: none; }

        @media only screen and (max-width: 480px) {
            .email-container { width: 100% !important; max-width: 100% !important; }
            .email-col { width: 100% !important; display: block !important; }
        }

        @media (prefers-color-scheme: dark) {
            .email-body-bg { background-color: {{ $settings['dark_bg_color'] ?? '#1a1a1a' }} !important; }
            .email-container-bg { background-color: {{ $settings['dark_bg_color'] ?? '#1a1a1a' }} !important; }
            .email-dark-text { color: {{ $settings['dark_text_color'] ?? '#e0e0e0' }} !important; }
        }

        {!! $mediaQueries ?? '' !!}
    </style>
</head>
<body style="margin:0;padding:0;background-color:{{ $settings['bg_color'] ?? '#f8f9fa' }};font-family:{{ $settings['font_family'] ?? 'Arial, sans-serif' }};" class="email-body-bg">
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;background-color:{{ $settings['bg_color'] ?? '#f8f9fa' }};" class="email-body-bg">
        <tr>
            <td align="center" style="padding:0;">
                <!--[if mso]>
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600">
                <tr>
                <td>
                <![endif]-->
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" style="max-width:600px;width:100%;background-color:#ffffff;" class="email-container email-container-bg">
                    <tr>
                        <td>
                            {!! $content !!}
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
</body>
</html>
