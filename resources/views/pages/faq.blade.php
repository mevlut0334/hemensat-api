<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İletişim</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            color: white;
            margin-bottom: 40px;
            padding: 20px;
        }

        .header h1 {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .header p {
            font-size: 18px;
            opacity: 0.95;
        }

        .contact-container {
            background: white;
            border-radius: 20px;
            padding: 40px 35px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .intro-text {
            text-align: center;
            font-size: 17px;
            color: #4a5568;
            line-height: 1.7;
            margin-bottom: 35px;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }

        .contact-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px solid transparent;
            border-radius: 16px;
            padding: 35px 25px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .contact-card:hover {
            transform: translateY(-5px);
            border-color: #667eea;
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.15);
        }

        .contact-icon {
            font-size: 48px;
            display: block;
            margin-bottom: 15px;
        }

        .contact-card h3 {
            font-size: 22px;
            color: #1a202c;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .contact-card .detail {
            font-size: 18px;
            color: #4a5568;
            margin-bottom: 25px;
            word-break: break-all;
        }

        .contact-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 14px 30px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .contact-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .note-section {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border-radius: 12px;
            padding: 28px 30px;
            text-align: center;
            margin-top: 35px;
        }

        .note-section h3 {
            font-size: 20px;
            color: #1a202c;
            margin-bottom: 8px;
        }

        .note-section p {
            color: #4a5568;
            font-size: 15px;
            line-height: 1.7;
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            .header h1 {
                font-size: 32px;
            }

            .header p {
                font-size: 16px;
            }

            .contact-container {
                padding: 30px 20px;
            }

            .contact-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .contact-card {
                padding: 28px 20px;
            }

            .note-section {
                padding: 22px 20px;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 28px;
            }

            .contact-container {
                padding: 25px 15px;
            }

            .contact-card .detail {
                font-size: 16px;
            }

            .contact-button {
                font-size: 14px;
                padding: 12px 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📞 İletişim</h1>
            <p>Sorularınız için bize ulaşın, size yardımcı olalım</p>
        </div>

        <div class="contact-container">
            <p class="intro-text">
                Hesap işlemleri, ilanlar ve teklifler hakkında aklınıza takılan
                her konuda telefon veya e-posta yoluyla bizimle iletişime geçebilirsiniz.
            </p>

            <div class="contact-grid">
                <div class="contact-card">
                    <span class="contact-icon">📱</span>
                    <h3>Telefon</h3>
                    <p class="detail">0 547 722 92 92</p>
                    <a href="tel:05477229292" class="contact-button">
                        <span>📞</span>
                        <span>Hemen Ara</span>
                    </a>
                </div>

                <div class="contact-card">
                    <span class="contact-icon">✉️</span>
                    <h3>E-posta</h3>
                    <p class="detail">hemensatapp@gmail.com</p>
                    <a href="mailto:hemensatapp@gmail.com" class="contact-button">
                        <span>📧</span>
                        <span>E-posta Gönder</span>
                    </a>
                </div>
            </div>

            <div class="note-section">
                <h3>Size nasıl yardımcı olabiliriz?</h3>
                <p>
                    Şifre sıfırlama, hesap bilgisi güncelleme ve hesap silme
                    gibi işlemler için bizimle iletişime geçmeniz yeterlidir.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
