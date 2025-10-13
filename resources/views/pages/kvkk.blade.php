<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KVKK Aydınlatma Metni</title>
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
            line-height: 1.6;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            color: white;
            margin-bottom: 40px;
            padding: 30px 20px;
        }

        .header-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
        }

        .header h1 {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header p {
            font-size: 18px;
            opacity: 0.95;
        }

        .main-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            padding: 40px;
            border-bottom: 2px solid #f0f0f0;
        }

        .card-body {
            padding: 40px;
        }

        .section {
            margin-bottom: 40px;
            padding-bottom: 40px;
            border-bottom: 2px solid #f0f0f0;
        }

        .section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .section-header {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 20px;
        }

        .section-number {
            min-width: 50px;
            height: 50px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 700;
            color: #667eea;
        }

        .section-title {
            flex: 1;
        }

        .section-title h3 {
            font-size: 22px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 5px;
        }

        .section-content {
            padding-left: 70px;
        }

        .section-content p {
            color: #4a5568;
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 15px;
        }

        .section-content p:last-child {
            margin-bottom: 0;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .feature-item {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            padding: 20px;
            border-radius: 12px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            border-color: #667eea;
            transform: translateY(-2px);
        }

        .feature-item-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }

        .check-icon {
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .feature-item p {
            color: #2d3748;
            font-size: 15px;
            margin: 0;
            font-weight: 500;
        }

        .rights-list {
            margin-top: 20px;
        }

        .right-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 16px;
            margin-bottom: 12px;
            background: #f7fafc;
            border-radius: 10px;
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }

        .right-item:hover {
            background: rgba(102, 126, 234, 0.08);
            transform: translateX(5px);
        }

        .arrow-icon {
            color: #667eea;
            font-size: 20px;
            flex-shrink: 0;
        }

        .right-item p {
            margin: 0;
            color: #2d3748;
            font-size: 15px;
            font-weight: 500;
        }

        .contact-box {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-radius: 16px;
            padding: 30px;
            margin-top: 20px;
            border: 2px solid rgba(102, 126, 234, 0.2);
        }

        .contact-box-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .envelope-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .contact-box-content h4 {
            color: #1a202c;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .contact-box-content p {
            color: #4a5568;
            font-size: 14px;
            margin: 0;
        }

        .contact-info {
            margin-top: 15px;
            padding: 15px;
            background: white;
            border-radius: 10px;
        }

        .contact-info p {
            color: #2d3748;
            font-size: 15px;
            margin: 0;
        }

        .contact-info strong {
            color: #667eea;
        }

        .footer {
            background: #f7fafc;
            padding: 25px 40px;
            text-align: center;
            border-top: 2px solid #e2e8f0;
        }

        .footer-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: #718096;
            font-size: 14px;
        }

        .calendar-icon {
            color: #667eea;
            font-size: 18px;
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

            .card-header,
            .card-body {
                padding: 30px 25px;
            }

            .section-content {
                padding-left: 0;
            }

            .section-header {
                flex-direction: column;
                gap: 15px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .contact-box {
                padding: 25px 20px;
            }

            .footer {
                padding: 20px 25px;
            }

            .footer-content {
                flex-direction: column;
                gap: 5px;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 28px;
            }

            .header-icon {
                width: 60px;
                height: 60px;
            }

            .card-header,
            .card-body {
                padding: 25px 20px;
            }

            .section-title h3 {
                font-size: 20px;
            }

            .section-content p {
                font-size: 15px;
            }

            .main-card {
                border-radius: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-icon">
                🛡️
            </div>
            <h1>KVKK Aydınlatma Metni</h1>
            <p>6698 Sayılı Kişisel Verilerin Korunması Kanunu Uyarınca</p>
        </div>

        <div class="main-card">
            <div class="card-header">
                <p style="color: #4a5568; font-size: 16px; line-height: 1.8; margin: 0;">
                    Bu aydınlatma metni, kişisel verilerinizin işlenmesi hakkında sizi bilgilendirmek amacıyla 6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") kapsamında hazırlanmıştır.
                </p>
            </div>

            <div class="card-body">
                <!-- Section 1 -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-number">01</div>
                        <div class="section-title">
                            <h3>Veri Sorumlusu</h3>
                        </div>
                    </div>
                    <div class="section-content">
                        <p>
                            [Şirket Adı], 6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") uyarınca veri sorumlusu sıfatıyla hareket etmektedir. Kişisel verilerinizin işlenmesi ile ilgili tüm süreçler veri sorumlusu tarafından yönetilmekte ve sorumluluğu taşınmaktadır.
                        </p>
                    </div>
                </div>

                <!-- Section 2 -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-number">02</div>
                        <div class="section-title">
                            <h3>Kişisel Verilerin İşlenme Amacı</h3>
                        </div>
                    </div>
                    <div class="section-content">
                        <p>Kişisel verileriniz aşağıdaki amaçlarla işlenmektedir:</p>
                        <div class="features-grid">
                            <div class="feature-item">
                                <div class="feature-item-header">
                                    <div class="check-icon">✓</div>
                                    <p>Hizmet sunumu ve iyileştirme</p>
                                </div>
                            </div>
                            <div class="feature-item">
                                <div class="feature-item-header">
                                    <div class="check-icon">✓</div>
                                    <p>Müşteri ilişkileri yönetimi</p>
                                </div>
                            </div>
                            <div class="feature-item">
                                <div class="feature-item-header">
                                    <div class="check-icon">✓</div>
                                    <p>Yasal yükümlülüklerin yerine getirilmesi</p>
                                </div>
                            </div>
                            <div class="feature-item">
                                <div class="feature-item-header">
                                    <div class="check-icon">✓</div>
                                    <p>İletişim faaliyetlerinin yürütülmesi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3 -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-number">03</div>
                        <div class="section-title">
                            <h3>Kişisel Verilerin Toplanma Yöntemi</h3>
                        </div>
                    </div>
                    <div class="section-content">
                        <p>
                            Kişisel verileriniz, internet sitemiz, mobil uygulamamız, e-posta ve telefon gibi çeşitli kanallar aracılığıyla elektronik veya fiziksel ortamda toplanmaktadır. Toplanan veriler, belirtilen amaçlar doğrultusunda güvenli bir şekilde saklanmakta ve işlenmektedir.
                        </p>
                    </div>
                </div>

                <!-- Section 4 -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-number">04</div>
                        <div class="section-title">
                            <h3>Kişisel Verilerin Aktarılması</h3>
                        </div>
                    </div>
                    <div class="section-content">
                        <p>
                            Kişisel verileriniz, KVKK'nın öngördüğü şartlarda ve yasal sınırlar dahilinde yurt içinde veya yurt dışında bulunan üçüncü kişilere, iş ortaklarımıza ve hizmet sağlayıcılarımıza aktarılabilir. Aktarım işlemleri her zaman yasal çerçevede ve güvenli yöntemlerle gerçekleştirilmektedir.
                        </p>
                    </div>
                </div>

                <!-- Section 5 -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-number">05</div>
                        <div class="section-title">
                            <h3>Kişisel Veri Sahibinin Hakları</h3>
                        </div>
                    </div>
                    <div class="section-content">
                        <p>KVKK'nın 11. maddesi uyarınca aşağıdaki haklara sahipsiniz:</p>
                        <div class="rights-list">
                            <div class="right-item">
                                <div class="arrow-icon">→</div>
                                <p>Kişisel verilerinizin işlenip işlenmediğini öğrenme</p>
                            </div>
                            <div class="right-item">
                                <div class="arrow-icon">→</div>
                                <p>Kişisel verileriniz işlenmişse buna ilişkin bilgi talep etme</p>
                            </div>
                            <div class="right-item">
                                <div class="arrow-icon">→</div>
                                <p>Kişisel verilerin işlenme amacını ve bunların amacına uygun kullanılıp kullanılmadığını öğrenme</p>
                            </div>
                            <div class="right-item">
                                <div class="arrow-icon">→</div>
                                <p>Yurt içinde veya yurt dışında kişisel verilerin aktarıldığı üçüncü kişileri bilme</p>
                            </div>
                            <div class="right-item">
                                <div class="arrow-icon">→</div>
                                <p>Kişisel verilerin eksik veya yanlış işlenmiş olması hâlinde bunların düzeltilmesini isteme</p>
                            </div>
                            <div class="right-item">
                                <div class="arrow-icon">→</div>
                                <p>Kişisel verilerin silinmesini veya yok edilmesini isteme</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 6 -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-number">06</div>
                        <div class="section-title">
                            <h3>İletişim</h3>
                        </div>
                    </div>
                    <div class="section-content">
                        <p>
                            Yukarıda belirtilen haklarınızı kullanmak için aşağıdaki iletişim kanallarımızdan bizimle iletişime geçebilirsiniz:
                        </p>
                        <div class="contact-box">
                            <div class="contact-box-header">
                                <div class="envelope-icon">✉️</div>
                                <div class="contact-box-content">
                                    <h4>Başvuru Yöntemleri</h4>
                                    <p>Haklarınızı kullanmak için başvurularınızı aşağıdaki yöntemlerle iletebilirsiniz</p>
                                </div>
                            </div>
                            <div class="contact-info">
                                <p>
                                    <strong>E-posta:</strong> [Telofon Dünyası]<br>
                                    <strong>Telefon:</strong> [0 547 722 92 92]<br>
                                    <strong>Adres:</strong> [Afyonkarahisar]
                                </p>
                            </div>
                        </div>
                        <p style="margin-top: 20px;">
                            Başvurularınız en kısa sürede ve en geç 30 gün içinde değerlendirilecek ve tarafınıza bilgilendirme yapılacaktır.
                        </p>
                    </div>
                </div>
            </div>

            <div class="footer">
                <div class="footer-content">
                    <span class="calendar-icon">📅</span>
                    <span>Son Güncelleme: <strong id="currentDate"></strong></span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Set current date
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        const currentDate = new Date().toLocaleDateString('tr-TR', options);
        document.getElementById('currentDate').textContent = currentDate;
    </script>
</body>
</html>
