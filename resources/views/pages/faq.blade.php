
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
            border: none;
            cursor: pointer;
        }

        .contact-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        /* İletişim formu */
        .contact-form-section {
            margin-top: 40px;
            padding: 35px;
            background: #f8f9fc;
            border-radius: 16px;
            border: 1px solid #edf0f7;
        }

        .contact-form-section h2 {
            text-align: center;
            font-size: 26px;
            color: #1a202c;
            margin-bottom: 10px;
        }

        .form-description {
            text-align: center;
            color: #718096;
            font-size: 15px;
            margin-bottom: 28px;
            line-height: 1.6;
        }

        .contact-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 600;
            color: #2d3748;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #dce1eb;
            border-radius: 10px;
            background: white;
            font-family: inherit;
            font-size: 15px;
            color: #2d3748;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.12);
        }

        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }

        .form-submit {
            grid-column: 1 / -1;
            display: flex;
            justify-content: center;
            margin-top: 5px;
        }

        .form-submit button {
            min-width: 200px;
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-submit button:hover {
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

            .contact-form-section {
                padding: 25px 20px;
            }

            .contact-form {
                grid-template-columns: 1fr;
            }

            .form-group.full-width,
            .form-submit {
                grid-column: 1;
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

            .contact-form-section h2 {
                font-size: 22px;
            }

            .form-submit button {
                width: 100%;
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

            <!-- İletişim Formu -->
            <div class="contact-form-section">
                <h2>Bize Mesaj Gönderin</h2>
                <p class="form-description">
                    Formu doldurun, mesajınızı e-posta yoluyla bize iletin.
                </p>

                <form class="contact-form" id="contactForm">
                    <div class="form-group">
                        <label for="name">Ad Soyad</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            placeholder="Adınız ve soyadınız"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="email">E-posta Adresiniz</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="ornek@mail.com"
                            required
                        >
                    </div>

                    <div class="form-group full-width">
                        <label for="subject">Konu</label>
                        <input
                            type="text"
                            id="subject"
                            name="subject"
                            placeholder="Mesajınızın konusu"
                            required
                        >
                    </div>

                    <div class="form-group full-width">
                        <label for="message">Mesajınız</label>
                        <textarea
                            id="message"
                            name="message"
                            placeholder="Mesajınızı buraya yazın..."
                            required
                        ></textarea>
                    </div>

                    <div class="form-submit">
                        <button type="submit">📨 Mesaj Gönder</button>
                    </div>
                </form>
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

    <script>
        document.getElementById('contactForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const subject = document.getElementById('subject').value.trim();
            const message = document.getElementById('message').value.trim();

            if (!name || !email || !subject || !message) {
                alert('Lütfen tüm alanları doldurun.');
                return;
            }

            const recipient = 'hemensatapp@gmail.com';

            const emailSubject = encodeURIComponent(subject);
            const emailBody = encodeURIComponent(
                'Ad Soyad: ' + name + '\n' +
                'Gönderen E-posta: ' + email + '\n\n' +
                'Mesaj:\n' + message
            );

            window.location.href =
                'mailto:' + recipient +
                '?subject=' + emailSubject +
                '&body=' + emailBody;
        });
    </script>
</body>
</html>
