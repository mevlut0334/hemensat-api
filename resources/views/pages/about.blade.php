<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hakkımızda</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
        }

        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 20px;
            text-align: center;
        }

        .hero-section h1 {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .hero-section p {
            font-size: 20px;
            opacity: 0.95;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px;
        }

        .content-section {
            background: white;
            border-radius: 20px;
            padding: 50px 40px;
            margin-top: -40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .intro-text {
            font-size: 20px;
            color: #4a5568;
            line-height: 1.8;
            margin-bottom: 50px;
            text-align: center;
            padding: 0 20px;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 16px;
            padding: 35px 30px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .card:hover {
            transform: translateY(-5px);
            border-color: #667eea;
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.15);
        }

        .card-icon {
            font-size: 48px;
            margin-bottom: 20px;
            display: block;
        }

        .card h3 {
            font-size: 24px;
            color: #1a202c;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .card p {
            font-size: 16px;
            color: #4a5568;
            line-height: 1.7;
        }

        .values-section {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border-radius: 16px;
            padding: 40px 35px;
            margin-top: 30px;
        }

        .values-section h3 {
            font-size: 28px;
            color: #1a202c;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
        }

        .value-item {
            background: white;
            border-radius: 12px;
            padding: 25px 20px;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .value-item:hover {
            border-color: #667eea;
            transform: scale(1.05);
        }

        .value-icon {
            font-size: 36px;
            margin-bottom: 12px;
            display: block;
        }

        .value-item h4 {
            font-size: 18px;
            color: #1a202c;
            font-weight: 600;
        }

        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 25px;
            margin-top: 50px;
            padding-top: 50px;
            border-top: 2px solid #e2e8f0;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 42px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 16px;
            color: #4a5568;
            font-weight: 500;
        }

        .contact-cta {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 16px;
            padding: 40px 30px;
            text-align: center;
            margin-top: 50px;
        }

        .contact-cta h3 {
            font-size: 26px;
            margin-bottom: 15px;
        }

        .contact-cta p {
            font-size: 16px;
            margin-bottom: 25px;
            opacity: 0.95;
        }

        .contact-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: white;
            color: #667eea;
            padding: 15px 35px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .contact-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 40px 20px;
            }

            .hero-section h1 {
                font-size: 32px;
            }

            .hero-section p {
                font-size: 16px;
            }

            .content-section {
                padding: 35px 25px;
                margin-top: -30px;
            }

            .intro-text {
                font-size: 18px;
                padding: 0 10px;
            }

            .cards-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .card {
                padding: 25px 20px;
            }

            .values-section {
                padding: 30px 20px;
            }

            .values-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .stats-section {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
                margin-top: 30px;
                padding-top: 30px;
            }

            .stat-number {
                font-size: 32px;
            }

            .contact-cta {
                padding: 30px 20px;
            }

            .contact-cta h3 {
                font-size: 22px;
            }
        }

        @media (max-width: 480px) {
            .hero-section h1 {
                font-size: 28px;
            }

            .card h3 {
                font-size: 20px;
            }

            .values-section h3 {
                font-size: 24px;
            }

            .stats-section {
                grid-template-columns: 1fr;
            }

            .contact-button {
                font-size: 16px;
                padding: 12px 25px;
            }
        }
    </style>
</head>
<body>
    <div class="hero-section">
        <h1>Hakkımızda</h1>
        <p>İlan ve teklif platformunda güvenilir çözümler sunan, kullanıcı deneyimini ön planda tutan bir ekibiz</p>
    </div>

    <div class="container">
        <div class="content-section">
            <p class="intro-text">
                Platformumuz, ilan vermek isteyenler ile teklif verenler arasında köprü kurmak için tasarlandı.
                Kullanıcılarımıza güvenli, hızlı ve kolay bir deneyim sunmak için sürekli geliştirme yapıyoruz.
            </p>

            <div class="cards-grid">
                <div class="card">
                    <span class="card-icon">🎯</span>
                    <h3>Misyonumuz</h3>
                    <p>
                        Alıcı ve satıcıları bir araya getirerek, güvenli ve şeffaf bir ticaret ortamı sağlamak.
                        Kullanıcılarımızın işlerini kolaylaştıran, zaman kazandıran çözümler üretmek.
                    </p>
                </div>

                <div class="card">
                    <span class="card-icon">🚀</span>
                    <h3>Vizyonumuz</h3>
                    <p>
                        Türkiye'nin en güvenilir ilan ve teklif platformu olmak. Sektörde yenilikçi yaklaşımlarımızla
                        öncü olmak ve kullanıcı memnuniyetinde standartları yükseltmek.
                    </p>
                </div>

                <div class="card">
                    <span class="card-icon">💡</span>
                    <h3>Yaklaşımımız</h3>
                    <p>
                        Kullanıcı odaklı düşünüyoruz. Her özelliği, her güncellemeyi kullanıcılarımızın
                        ihtiyaçlarını göz önünde bulundurarak geliştiriyoruz. Basitlik ve etkinlik önceliğimiz.
                    </p>
                </div>
            </div>

            <div class="values-section">
                <h3>Değerlerimiz</h3>
                <div class="values-grid">
                    <div class="value-item">
                        <span class="value-icon">⭐</span>
                        <h4>Müşteri Memnuniyeti</h4>
                    </div>
                    <div class="value-item">
                        <span class="value-icon">🔥</span>
                        <h4>Yenilikçilik</h4>
                    </div>
                    <div class="value-item">
                        <span class="value-icon">🤝</span>
                        <h4>Güvenilirlik</h4>
                    </div>
                    <div class="value-item">
                        <span class="value-icon">💎</span>
                        <h4>Şeffaflık</h4>
                    </div>
                    <div class="value-item">
                        <span class="value-icon">📈</span>
                        <h4>Sürekli Gelişim</h4>
                    </div>
                    <div class="value-item">
                        <span class="value-icon">🛡️</span>
                        <h4>Güvenlik</h4>
                    </div>
                </div>
            </div>

            <div class="stats-section">
                <div class="stat-item">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">Aktif Kullanıcı</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">5000+</div>
                    <div class="stat-label">Yayınlanan İlan</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">3000+</div>
                    <div class="stat-label">Tamamlanan İşlem</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">%98</div>
                    <div class="stat-label">Memnuniyet Oranı</div>
                </div>
            </div>

            <div class="contact-cta">
                <h3>Sorularınız mı var?</h3>
                <p>Platformumuz hakkında daha fazla bilgi almak için bizimle iletişime geçin</p>
                <a href="tel:05477229292" class="contact-button">
                    <span>📞</span>
                    <span>0 547 722 92 92</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
