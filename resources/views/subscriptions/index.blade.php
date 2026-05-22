<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abonelik - Premium Üyelik</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            max-width: 900px;
            width: 100%;
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .header p {
            font-size: 16px;
            opacity: 0.95;
        }

        .content {
            padding: 40px 30px;
        }

        .comparison-section {
            margin-bottom: 40px;
        }

        .comparison-title {
            font-size: 24px;
            color: #1a202c;
            margin-bottom: 25px;
            font-weight: 600;
            text-align: center;
        }

        .comparison-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 40px;
        }

        .comparison-card {
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 25px;
            transition: all 0.3s ease;
        }

        .comparison-card.free {
            background: #f7fafc;
        }

        .comparison-card.premium {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border-color: #667eea;
        }

        .comparison-card h3 {
            font-size: 20px;
            margin-bottom: 15px;
            color: #1a202c;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .comparison-card h3 .icon {
            font-size: 24px;
        }

        .feature-list {
            list-style: none;
        }

        .feature-list li {
            padding: 10px 0;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: #4a5568;
            font-size: 15px;
            line-height: 1.5;
        }

        .feature-list li .check {
            color: #48bb78;
            font-weight: bold;
            flex-shrink: 0;
        }

        .feature-list li .cross {
            color: #f56565;
            font-weight: bold;
            flex-shrink: 0;
        }

        .pricing-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            padding: 35px;
            text-align: center;
            margin-bottom: 30px;
        }

        .pricing-card h2 {
            font-size: 28px;
            margin-bottom: 15px;
        }

        .price {
            font-size: 48px;
            font-weight: 700;
            margin: 20px 0;
        }

        .price-period {
            font-size: 18px;
            opacity: 0.9;
        }

        .contact-section {
            background: #f7fafc;
            border-radius: 16px;
            padding: 30px;
            text-align: center;
        }

        .contact-section h3 {
            font-size: 22px;
            color: #1a202c;
            margin-bottom: 15px;
        }

        .contact-section p {
            color: #4a5568;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .phone-number {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: white;
            padding: 15px 30px;
            border-radius: 12px;
            font-size: 24px;
            font-weight: 600;
            color: #667eea;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .phone-number:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        .phone-icon {
            font-size: 28px;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .header {
                padding: 30px 20px;
            }

            .header h1 {
                font-size: 24px;
            }

            .header p {
                font-size: 14px;
            }

            .content {
                padding: 30px 20px;
            }

            .comparison-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .comparison-title {
                font-size: 20px;
            }

            .comparison-card {
                padding: 20px;
            }

            .pricing-card {
                padding: 25px 20px;
            }

            .price {
                font-size: 36px;
            }

            .phone-number {
                font-size: 20px;
                padding: 12px 25px;
            }

            .contact-section {
                padding: 25px 20px;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 22px;
            }

            .comparison-title {
                font-size: 18px;
            }

            .comparison-card h3 {
                font-size: 18px;
            }

            .feature-list li {
                font-size: 14px;
            }

            .price {
                font-size: 32px;
            }

            .phone-number {
                font-size: 18px;
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌟 Premium Üyelik</h1>
            <p>Tüm özelliklerin keyfini çıkarın ve işinizi büyütün</p>
        </div>

        <div class="content">
            <div class="comparison-section">
                <h2 class="comparison-title">Ücretsiz vs Premium Üyelik</h2>

                <div class="comparison-grid">
                    <div class="comparison-card free">
                        <h3><span class="icon">👤</span> Ücretsiz Üyelik</h3>
                        <ul class="feature-list">
                            <li><span class="check">✓</span> İlan verebilirsiniz</li>
                            <li><span class="cross">✗</span> İlan detaylarını göremezsiniz</li>
                            <li><span class="cross">✗</span> Teklif veremezsiniz</li>
                            <li><span class="cross">✗</span> Diğer kullanıcılarla iletişim kuramazsınız</li>
                        </ul>
                    </div>

                    <div class="comparison-card premium">
                        <h3><span class="icon">⭐</span> Premium Üyelik</h3>
                        <ul class="feature-list">
                            <li><span class="check">✓</span> İlan verebilirsiniz</li>
                            <li><span class="check">✓</span> Tüm ilan detaylarını görüntüleyebilirsiniz</li>
                            <li><span class="check">✓</span> İlanlara teklif verebilirsiniz</li>
                            <li><span class="check">✓</span> Diğer kullanıcılarla iletişim kurabilirsiniz</li>
                            <li><span class="check">✓</span> Öncelikli destek</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="pricing-card">
                <h2>Premium Üyelik Paketi</h2>
                <div class="price">
                    ₺499<span class="price-period">/ay</span>
                </div>
                <p style="opacity: 0.95;">Tüm premium özelliklere sınırsız erişim</p>
            </div>

            <div class="contact-section">
                <h3>📞 Abonelik İçin İletişime Geçin</h3>
                <p>Premium üyeliğe geçmek ve tüm avantajlardan yararlanmak için hemen arayın</p>
                <a href="tel:05477229292" class="phone-number">
                    <span class="phone-icon">📱</span>
                    <span>0 547 722 92 92</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
