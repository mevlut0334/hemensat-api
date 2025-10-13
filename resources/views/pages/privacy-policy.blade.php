<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gizlilik Politikası</title>
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

        .intro-box {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.08) 0%, rgba(118, 75, 162, 0.08) 100%);
            border-left: 4px solid #667eea;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 40px;
        }

        .intro-box p {
            color: #2d3748;
            font-size: 16px;
            line-height: 1.8;
            margin: 0;
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

        .info-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .info-card {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            padding: 25px;
            border-radius: 12px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            border-color: #667eea;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.15);
        }

        .info-card-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .info-card h4 {
            color: #1a202c;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .info-card p {
            color: #4a5568;
            font-size: 15px;
            line-height: 1.7;
            margin: 0;
        }

        .list-items {
            margin-top: 20px;
        }

        .list-item {
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

        .list-item:hover {
            background: rgba(102, 126, 234, 0.08);
            transform: translateX(5px);
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

        .list-item p {
            margin: 0;
            color: #2d3748;
            font-size: 15px;
            font-weight: 500;
        }

        .highlight-box {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-radius: 16px;
            padding: 30px;
            margin-top: 20px;
            border: 2px solid rgba(102, 126, 234, 0.2);
        }

        .highlight-box-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .shield-icon {
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

        .highlight-box-content h4 {
            color: #1a202c;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .highlight-box-content p {
            color: #4a5568;
            font-size: 14px;
            margin: 0;
        }

        .contact-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-top: 15px;
        }

        .contact-box p {
            color: #2d3748;
            font-size: 15px;
            margin: 0;
            line-height: 1.8;
        }

        .contact-box strong {
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

        .cookie-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            padding: 8px 16px;
            border-radius: 20px;
            margin-top: 15px;
        }

        .cookie-badge span {
            font-size: 20px;
        }

        .cookie-badge p {
            color: #667eea;
            font-weight: 600;
            font-size: 14px;
            margin: 0;
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

            .info-cards {
                grid-template-columns: 1fr;
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

            .intro-box,
            .highlight-box {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-icon">
                🔒
            </div>
            <h1>Gizlilik Politikası</h1>
            <p>Verileriniz güvende</p>
        </div>

        <div class="main-card">
            <div class="card-header">
                <div class="intro-box">
                    <p>
                        Bu gizlilik politikası, uygulamamızı kullanırken toplanan kişisel verilerinizin nasıl işlendiği, korunduğu ve kullanıldığı hakkında sizi bilgilendirmek amacıyla hazırlanmıştır. Gizliliğiniz bizim için önemlidir ve verilerinizi en yüksek güvenlik standartlarıyla korumaktayız.
                    </p>
                </div>
            </div>

            <div class="card-body">
                <!-- Section 1 -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-number">01</div>
                        <div class="section-title">
                            <h3>Toplanan Bilgiler</h3>
                        </div>
                    </div>
                    <div class="section-content">
                        <p>
                            Hizmetlerimizi kullanırken sizden aşağıdaki türde kişisel veriler toplayabiliriz:
                        </p>
                        <div class="info-cards">
                            <div class="info-card">
                                <div class="info-card-icon">👤</div>
                                <h4>Kimlik Bilgileri</h4>
                                <p>Ad, soyad, kullanıcı adı gibi temel kimlik bilgileriniz</p>
                            </div>
                            <div class="info-card">
                                <div class="info-card-icon">📧</div>
                                <h4>İletişim Bilgileri</h4>
                                <p>E-posta adresi, telefon numarası gibi iletişim bilgileriniz</p>
                            </div>
                            <div class="info-card">
                                <div class="info-card-icon">📊</div>
                                <h4>Kullanım Bilgileri</h4>
                                <p>Uygulama içi aktiviteleriniz ve kullanım alışkanlıklarınız</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2 -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-number">02</div>
                        <div class="section-title">
                            <h3>Bilgilerin Kullanımı</h3>
                        </div>
                    </div>
                    <div class="section-content">
                        <p>
                            Toplanan bilgiler, size daha iyi hizmet sunabilmek için aşağıdaki amaçlarla kullanılır:
                        </p>
                        <div class="list-items">
                            <div class="list-item">
                                <div class="check-icon">✓</div>
                                <p>Hizmetlerimizi geliştirmek ve kullanıcı deneyimini iyileştirmek</p>
                            </div>
                            <div class="list-item">
                                <div class="check-icon">✓</div>
                                <p>Teknik destek ve müşteri hizmetleri sağlamak</p>
                            </div>
                            <div class="list-item">
                                <div class="check-icon">✓</div>
                                <p>Sizinle iletişim kurmak ve önemli bilgilendirmeler yapmak</p>
                            </div>
                            <div class="list-item">
                                <div class="check-icon">✓</div>
                                <p>Hesap güvenliğinizi sağlamak ve dolandırıcılığı önlemek</p>
                            </div>
                            <div class="list-item">
                                <div class="check-icon">✓</div>
                                <p>Yasal yükümlülüklerimizi yerine getirmek</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3 -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-number">03</div>
                        <div class="section-title">
                            <h3>Bilgi Güvenliği</h3>
                        </div>
                    </div>
                    <div class="section-content">
                        <p>
                            Kişisel verilerinizin güvenliği bizim için en önemli önceliktir. Verilerinizi korumak için endüstri standardı güvenlik önlemleri uygulamaktayız.
                        </p>
                        <div class="highlight-box">
                            <div class="highlight-box-header">
                                <div class="shield-icon">🛡️</div>
                                <div class="highlight-box-content">
                                    <h4>Güvenlik Önlemlerimiz</h4>
                                    <p>Verilerinizi korumak için aldığımız kapsamlı güvenlik tedbirleri</p>
                                </div>
                            </div>
                            <div class="contact-box">
                                <p>
                                    • <strong>SSL/TLS Şifreleme:</strong> Tüm veri aktarımları şifrelenir<br>
                                    • <strong>Güvenli Sunucular:</strong> Veriler korumalı sunucularda saklanır<br>
                                    • <strong>Erişim Kontrolü:</strong> Yetkisiz erişimlere karşı korunma<br>
                                    • <strong>Düzenli Denetim:</strong> Güvenlik sistemleri sürekli güncellenir<br>
                                    • <strong>Yedekleme:</strong> Düzenli veri yedeği alınır
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4 -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-number">04</div>
                        <div class="section-title">
                            <h3>Çerezler (Cookies)</h3>
                        </div>
                    </div>
                    <div class="section-content">
                        <p>
                            Websitemiz ve uygulamamız, kullanıcı deneyimini geliştirmek, tercihlerinizi hatırlamak ve hizmetlerimizi optimize etmek için çerezler kullanmaktadır.
                        </p>
                        <div class="cookie-badge">
                            <span>🍪</span>
                            <p>Çerez Kullanımı Aktif</p>
                        </div>
                        <div class="list-items">
                            <div class="list-item">
                                <div class="check-icon">✓</div>
                                <p><strong>Zorunlu Çerezler:</strong> Sitenin temel işlevlerini sağlar</p>
                            </div>
                            <div class="list-item">
                                <div class="check-icon">✓</div>
                                <p><strong>Performans Çerezleri:</strong> Site performansını ölçer ve iyileştirir</p>
                            </div>
                            <div class="list-item">
                                <div class="check-icon">✓</div>
                                <p><strong>İşlevsellik Çerezleri:</strong> Tercihlerinizi hatırlar</p>
                            </div>
                        </div>
                        <p style="margin-top: 20px;">
                            Tarayıcı ayarlarınızdan çerezleri yönetebilir veya reddedebilirsiniz. Ancak bu durumda bazı özelliklerin düzgün çalışmaması söz konusu olabilir.
                        </p>
                    </div>
                </div>

                <!-- Section 5 -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-number">05</div>
                        <div class="section-title">
                            <h3>Üçüncü Taraf Paylaşımı</h3>
                        </div>
                    </div>
                    <div class="section-content">
                        <p>
                            Kişisel bilgileriniz, yasal zorunluluklar veya hizmet sağlayıcılarımızla çalışma gereklilikleri dışında üçüncü taraflarla paylaşılmaz. Paylaşım yapıldığı durumlarda, bilgileriniz gizlilik sözleşmeleriyle korunur.
                        </p>
                        <p style="margin-top: 15px;">
                            Hizmet sağlayıcılarımız yalnızca belirli görevleri yerine getirmek için gerekli olan bilgilere erişebilir ve bu bilgileri başka amaçlarla kullanamazlar.
                        </p>
                    </div>
                </div>

                <!-- Section 6 -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-number">06</div>
                        <div class="section-title">
                            <h3>Haklarınız</h3>
                        </div>
                    </div>
                    <div class="section-content">
                        <p>
                            Kişisel verilerinizle ilgili olarak aşağıdaki haklara sahipsiniz:
                        </p>
                        <div class="list-items">
                            <div class="list-item">
                                <div class="check-icon">✓</div>
                                <p>Verilerinize erişim talep etme</p>
                            </div>
                            <div class="list-item">
                                <div class="check-icon">✓</div>
                                <p>Yanlış verilerin düzeltilmesini isteme</p>
                            </div>
                            <div class="list-item">
                                <div class="check-icon">✓</div>
                                <p>Verilerinizin silinmesini talep etme</p>
                            </div>
                            <div class="list-item">
                                <div class="check-icon">✓</div>
                                <p>Veri işleme faaliyetlerine itiraz etme</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 7 -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-number">07</div>
                        <div class="section-title">
                            <h3>İletişim</h3>
                        </div>
                    </div>
                    <div class="section-content">
                        <p>
                            Gizlilik politikamız hakkında sorularınız, haklarınızı kullanmak istediğinizde veya herhangi bir konuda bizimle iletişime geçmek için:
                        </p>
                        <div class="highlight-box">
                            <div class="highlight-box-header">
                                <div class="shield-icon">📞</div>
                                <div class="highlight-box-content">
                                    <h4>İletişim Bilgileri</h4>
                                    <p>Size yardımcı olmak için buradayız</p>
                                </div>
                            </div>
                            <div class="contact-box">
                                <p>

                                    <strong>Telefon:</strong> [0 547 722 92 92]<br>
                                    <strong>Adres:</strong> [Afyonkarahisar]<br>
                                    <strong>Çalışma Saatleri:</strong> Hafta içi 09:00 - 18:00
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Updates Section -->
                <div class="section">
                    <div class="section-header">
                        <div class="section-number">08</div>
                        <div class="section-title">
                            <h3>Politika Güncellemeleri</h3>
                        </div>
                    </div>
                    <div class="section-content">
                        <p>
                            Bu gizlilik politikasını zaman zaman güncelleyebiliriz. Önemli değişiklikler olduğunda sizi bilgilendireceğiz. Politikayı düzenli olarak gözden geçirmenizi öneririz.
                        </p>
                        <p style="margin-top: 15px;">
                            Güncellemeler yayınlandığı tarihte yürürlüğe girer ve hizmetlerimizi kullanmaya devam etmeniz güncellenen politikayı kabul ettiğiniz anlamına gelir.
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
