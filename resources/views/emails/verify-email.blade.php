<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email</title>
</head>
<body style="margin:0; padding:0; background-color:#f5f5f4; font-family:Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f5f4; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background-color:#ffffff; border-radius:16px; overflow:hidden;">
                    {{-- Header logo --}}
                    <tr>
                        <td align="center" style="padding:32px 32px 8px 32px;">
                            <img src="{{ asset('logo/logo.png') }}" alt="POSAL" style="width:120px; height:auto;" />
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:8px 32px 24px 32px;">
                            <h1 style="margin:0 0 12px 0; font-size:22px; color:#1f2937; text-align:center;">Verifikasi Email Anda</h1>
                            <p style="margin:0 0 16px 0; font-size:14px; line-height:1.6; color:#6b7280; text-align:center;">
                                Halo <strong style="color:#111827;">{{ $user->name }}</strong>,
                                terima kasih telah mendaftar di POSAL.
                                Silakan klik tombol di bawah ini untuk memverifikasi alamat email:
                                <strong style="color:#111827;">{{ $user->email }}</strong>
                            </p>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $url }}" style="display:inline-block; background-color:#ea580c; color:#ffffff; font-size:15px; font-weight:bold; text-decoration:none; padding:14px 32px; border-radius:12px;">
                                            Verifikasi Email
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:0 0 12px 0; font-size:13px; line-height:1.6; color:#9ca3af; text-align:center;">
                                Jika Anda tidak membuat akun di POSAL, abaikan email ini.
                            </p>
                            <p style="margin:0; font-size:13px; line-height:1.6; color:#9ca3af; text-align:center;">
                                Tombol tidak berfungsi? Salin tautan berikut ke browser:
                                <a href="{{ $url }}" style="color:#ea580c; word-break:break-all;">{{ $url }}</a>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:16px 32px; background-color:#fff7ed; border-top:1px solid #fed7aa;">
                            <p style="margin:0; font-size:12px; color:#c2410c;">&copy; {{ date('Y') }} POSAL &mdash; Aplikasi Kasir Termudah</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>