<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sıkça Sorulan Sorular</title>
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

        .faq-container {
            background: white;
            border-radius: 20px;
            padding: 40px 35px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .search-box {
            margin-bottom: 35px;
        }

        .search-input {
            width: 100%;
            padding: 16px 20px;
            font-size: 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.3s ease;
            outline: none;
        }

        .search-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .faq-categories {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .category-btn {
            padding: 10px 20px;
            background: #f7fafc;
            border: 2px solid #e2e8f0;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            color: #4a5568;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .category-btn:hover, .category-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
        }

        .faq-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .faq-item {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-item.active {
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }

        .faq-question {
            width: 100%;
            padding: 20px 25px;
            background: white;
            border: none;
            text-align: left;
            font-size: 16px;
            font-weight: 600;
            color: #1a202c;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .faq-question:hover {
            background: #f7fafc;
        }

        .faq-item.active .faq-question {
            color: #667eea;
        }

        .faq-icon {
            font-size: 20px;
            transition: transform 0.3s ease;
            color: #667eea;
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
            background: #f7fafc;
        }

        .faq-item.active .faq-answer {
            max-height: 500px;
            padding: 20px 25px;
        }

        .faq-answer p {
            color: #4a5568;
            line-height: 1.7;
            font-size: 15px;
        }

        .contact-section {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin-top: 40px;
        }

        .contact-section h3 {
            font-size: 22px;
            color: #1a202c;
            margin-bottom: 10px;
        }

        .contact-section p {
            color: #4a5568;
            margin-bottom: 20px;
            font-size: 15px;
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

        .no-results {
            text-align: center;
            padding: 40px 20px;
            color: #718096;
        }

        .no-results-icon {
            font-size: 64px;
            margin-bottom: 15px;
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

            .faq-container {
                padding: 30px 20px;
            }

            .faq-question {
                padding: 16px 20px;
                font-size: 15px;
            }

            .faq-item.active .faq-answer {
                padding: 16px 20px;
            }

            .contact-section {
                padding: 25px 20px;
            }

            .category-btn {
                font-size: 13px;
                padding: 8px 16px;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 28px;
            }

            .faq-container {
                padding: 25px 15px;
            }

            .faq-question {
                font-size: 14px;
                padding: 14px 16px;
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
            <h1>❓ Sıkça Sorulan Sorular</h1>
            <p>Merak ettiğiniz her şeyin cevabı burada</p>
        </div>

        <div class="faq-container">
            <div class="search-box">
                <input type="text" class="search-input" id="searchInput" placeholder="🔍 Soru ara...">
            </div>

            <div class="faq-categories">
                <button class="category-btn active" data-category="all">Tümü</button>
                <button class="category-btn" data-category="hesap">Hesap</button>
                <button class="category-btn" data-category="abonelik">Abonelik</button>
                <button class="category-btn" data-category="ilan">İlanlar</button>
                <button class="category-btn" data-category="teklif">Teklifler</button>
            </div>

            <div class="faq-list" id="faqList">
                <div class="faq-item" data-category="hesap">
                    <button class="faq-question">
                        <span>Nasıl kayıt olabilirim?</span>
                        <span class="faq-icon">▼</span>
                    </button>
                    <div class="faq-answer">
                        <p>Uygulamamızı indirdikten sonra ana sayfada "Kayıt Ol" butonuna tıklayarak hızlıca hesap oluşturabilirsiniz. E-posta adresiniz ve telefon numaranız ile kolayca kayıt olabilirsiniz. Kayıt işlemi sadece birkaç dakika sürer.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="abonelik">
                    <button class="faq-question">
                        <span>Premium üyelik ne gibi avantajlar sağlar?</span>
                        <span class="faq-icon">▼</span>
                    </button>
                    <div class="faq-answer">
                        <p>Premium üyelikle tüm ilan detaylarını görüntüleyebilir, ilanlara teklif verebilir ve diğer kullanıcılarla doğrudan iletişim kurabilirsiniz. Ayrıca öncelikli destek hizmetinden faydalanabilirsiniz. Ücretsiz üyelerde bu özellikler kısıtlıdır.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="abonelik">
                    <button class="faq-question">
                        <span>Aboneliğimi nasıl başlatabilirim?</span>
                        <span class="faq-icon">▼</span>
                    </button>
                    <div class="faq-answer">
                        <p>Premium üyelik için 0 547 722 92 92 numaralı telefondan bizimle iletişime geçebilirsiniz. Aylık 499 TL karşılığında tüm premium özelliklere erişim sağlayabilirsiniz.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="abonelik">
                    <button class="faq-question">
                        <span>Aboneliğimi nasıl iptal edebilirim?</span>
                        <span class="faq-icon">▼</span>
                    </button>
                    <div class="faq-answer">
                        <p>Aboneliğinizi iptal etmek için müşteri hizmetlerimizle 0 547 722 92 92 numaralı telefondan iletişime geçmeniz yeterlidir. Abonelik iptal işleminiz sonraki fatura döneminde geçerli olacaktır.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="ilan">
                    <button class="faq-question">
                        <span>Nasıl ilan verebilirim?</span>
                        <span class="faq-icon">▼</span>
                    </button>
                    <div class="faq-answer">
                        <p>Hem ücretsiz hem de premium üyeler ilan verebilir. Ana sayfada "Yeni İlan" butonuna tıklayarak ilan oluşturabilir, detaylarını ekleyebilir ve yayınlayabilirsiniz. İlanınızı yayınlamak tamamen ücretsizdir.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="ilan">
                    <button class="faq-question">
                        <span>İlan detaylarını görüntüleyebilir miyim?</span>
                        <span class="faq-icon">▼</span>
                    </button>
                    <div class="faq-answer">
                        <p>İlan detaylarını görüntülemek premium üyelik gerektirir. Ücretsiz üyeler sadece ilan başlıklarını görebilir ancak detaylara erişemez. Premium üye olarak tüm ilan bilgilerine sınırsız erişim sağlayabilirsiniz.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="ilan">
                    <button class="faq-question">
                        <span>İlanımı nasıl düzenleyebilirim?</span>
                        <span class="faq-icon">▼</span>
                    </button>
                    <div class="faq-answer">
                        <p>Profilinizdeki "İlanlarım" bölümünden yayınladığınız ilanları görüntüleyebilir  veya silebilirsiniz. </p>
                    </div>
                </div>

                <div class="faq-item" data-category="teklif">
                    <button class="faq-question">
                        <span>İlanlara nasıl teklif verebilirim?</span>
                        <span class="faq-icon">▼</span>
                    </button>
                    <div class="faq-answer">
                        <p>Teklif verme özelliği sadece premium üyelere özeldir. Premium üye olduktan sonra ilan detay sayfasından "Teklif Ver" butonuna tıklayarak teklifinizi gönderebilirsiniz.</p>
                    </div>
                </div>



                <div class="faq-item" data-category="teklif">
                    <button class="faq-question">
                        <span>Kaç tane teklif verebilirim?</span>
                        <span class="faq-icon">▼</span>
                    </button>
                    <div class="faq-answer">
                        <p>Premium üyeler sınırsız sayıda teklif verebilir. Her ilana tek bir teklif verebilirsiniz ancak teklifinizi güncelleyebilirsiniz.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="hesap">
                    <button class="faq-question">
                        <span>Şifremi unuttum, ne yapmalıyım?</span>
                        <span class="faq-icon">▼</span>
                    </button>
                    <div class="faq-answer">
                        <p>Şifrenizi unuttuysanız müşteri hizmetlerimizle 0 547 722 92 92 numaralı telefondan iletişime geçerek şifre sıfırlama talebinde bulunabilirsiniz. Kimlik doğrulaması yapıldıktan sonra şifreniz sıfırlanacaktır.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="hesap">
                    <button class="faq-question">
                        <span>Hesap bilgilerimi nasıl güncelleyebilirim?</span>
                        <span class="faq-icon">▼</span>
                    </button>
                    <div class="faq-answer">
                        <p>Müşteri hizmetleri ile iletişime geçerek hesap bilgilerinizi güncelleyebilirsiniz. </p>
                    </div>
                </div>

                <div class="faq-item" data-category="hesap">
                    <button class="faq-question">
                        <span>Hesabımı nasıl silebilirim?</span>
                        <span class="faq-icon">▼</span>
                    </button>
                    <div class="faq-answer">
                        <p>Hesabınızı silmek için müşteri hizmetlerimizle 0 547 722 92 92 numaralı telefondan iletişime geçmeniz gerekmektedir. Hesap silme işlemi geri alınamaz ve tüm verileriniz kalıcı olarak silinir.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="ilan">
                    <button class="faq-question">
                        <span>İlanlarım nerede?</span>
                        <span class="faq-icon">▼</span>
                    </button>
                    <div class="faq-answer">
                        <p>"İlanlarım" bölümünden ilanlarınızı görebilirsiniz. Bu özellik hem ücretsiz hem de premium üyelere açıktır.</p>
                    </div>
                </div>

                <div class="faq-item" data-category="teklif">
                    <button class="faq-question">
                        <span>İlanıma gelen teklifleri nasıl görürüm?</span>
                        <span class="faq-icon">▼</span>
                    </button>
                    <div class="faq-answer">
                        <p>"İlanlarım" bölümünden ilanınıza gelen tüm teklifleri görebilir. Teklif veren kullanıcıyla  iletişime geçebilirsiniz.</p>
                    </div>
                </div>
            </div>

            <div class="no-results" id="noResults" style="display: none;">
                <div class="no-results-icon">🔍</div>
                <h3>Sonuç bulunamadı</h3>
                <p>Aradığınız soruyu bulamadık. Lütfen farklı bir arama yapın.</p>
            </div>

            <div class="contact-section">
                <h3>Sorunuzu bulamadınız mı?</h3>
                <p>Destek ekibimiz size yardımcı olmak için burada</p>
                <a href="tel:05477229292" class="contact-button">
                    <span>📞</span>
                    <span>0 547 722 92 92</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        // FAQ accordion functionality
        const faqItems = document.querySelectorAll('.faq-item');

        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');

            question.addEventListener('click', () => {
                const isActive = item.classList.contains('active');

                // Close all items
                faqItems.forEach(i => i.classList.remove('active'));

                // Open clicked item if it wasn't active
                if (!isActive) {
                    item.classList.add('active');
                }
            });
        });

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const faqList = document.getElementById('faqList');
        const noResults = document.getElementById('noResults');

        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            let hasResults = false;

            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question span:first-child').textContent.toLowerCase();
                const answer = item.querySelector('.faq-answer p').textContent.toLowerCase();

                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    item.style.display = 'block';
                    hasResults = true;
                } else {
                    item.style.display = 'none';
                }
            });

            noResults.style.display = hasResults || searchTerm === '' ? 'none' : 'block';
            faqList.style.display = hasResults || searchTerm === '' ? 'flex' : 'none';
        });

        // Category filter
        const categoryBtns = document.querySelectorAll('.category-btn');

        categoryBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active class from all buttons
                categoryBtns.forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                btn.classList.add('active');

                const category = btn.dataset.category;

                faqItems.forEach(item => {
                    if (category === 'all' || item.dataset.category === category) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>
