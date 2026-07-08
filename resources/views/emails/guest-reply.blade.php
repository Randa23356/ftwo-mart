<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balasan dari {{ config('app.name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 8px 8px;
            border: 1px solid #e0e0e0;
        }
        .message-content {
            background: white;
            padding: 20px;
            border-radius: 6px;
            border-left: 4px solid #667eea;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            color: #666;
            font-size: 14px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">{{ config('app.name') }}</div>
        <p>Balasan untuk pesan Anda</p>
    </div>

    <div class="content">
        @if($guestName)
            <p>Halo {{ $guestName }},</p>
        @else
            <p>Halo,</p>
        @endif

        <p>Terima kasih telah menghubungi kami melalui form kontak. Berikut adalah balasan dari tim kami:</p>

        <div class="message-content">
            {!! nl2br(e($messageContent)) !!}
        </div>

        <p>Jika Anda memiliki pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami kembali melalui:</p>
        
        <ul>
            <li><strong>Website:</strong> <a href="{{ config('app.url') }}">{{ config('app.url') }}</a></li>
            <li><strong>Email:</strong> {{ config('mail.from.address') }}</li>
            @if(isset($settings['phone']) && $settings['phone']->value)
                <li><strong>Telepon:</strong> {{ $settings['phone']->value }}</li>
            @endif
        </ul>

        <p>Terima kasih atas kepercayaan Anda kepada {{ config('app.name') }}.</p>

        <p>Salam hangat,<br>
        <strong>Tim {{ config('app.name') }}</strong></p>
    </div>

    <div class="footer">
        <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>