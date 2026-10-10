<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confirm your BusinessX newsletter subscription</title>
</head>
<body style="margin:0;padding:32px 16px;background:#f4f6f9;font-family:Arial,sans-serif;color:#263449;">
  <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;margin:0 auto;background:#ffffff;border:1px solid #e3e8ef;border-radius:10px;">
    <tr>
      <td style="padding:26px 32px;border-bottom:3px solid #1769D2;">
        <img src="{{ asset('assets/img/businessx-logo.png?v=20261008') }}" alt="BusinessX" width="211" height="49" style="display:block;width:211px;height:auto;">
      </td>
    </tr>
    <tr>
      <td style="padding:32px;">
        <h1 style="margin:0 0 16px;color:#10284A;font-size:24px;">Confirm your subscription</h1>
        <p style="margin:0 0 16px;line-height:1.6;">Hi {{ $name }},</p>
        <p style="margin:0 0 24px;line-height:1.6;">Please confirm this email address to start receiving the BusinessX newsletter — market trends, new listings and funding opportunities.</p>
        <p style="margin:0 0 24px;">
          <a href="{{ $verifyUrl }}" style="display:inline-block;padding:13px 22px;border-radius:6px;background:#1769D2;color:#10284A;font-weight:bold;text-decoration:none;">Confirm subscription</a>
        </p>
        <p style="margin:0 0 12px;color:#5b6573;font-size:13px;line-height:1.6;">This link expires in 24 hours. If you did not request this, you can ignore this email.</p>
        <p style="margin:0;color:#5b6573;font-size:12px;line-height:1.6;">If the button does not work, copy and paste this address into your browser:<br><a href="{{ $verifyUrl }}" style="color:#2563eb;word-break:break-all;">{{ $verifyUrl }}</a></p>
      </td>
    </tr>
    <tr>
      <td style="padding:18px 32px;border-top:1px solid #e3e8ef;color:#778191;font-size:12px;">BusinessX · World Trade Council</td>
    </tr>
  </table>
</body>
</html>
