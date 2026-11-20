/**
 * Fizyoterapist Evrim Şenol Akarca Özel Sağlık Meslek Hizmet Birimi
 * İstemci Tarafı Dinamik Fonksiyonlar (İkonsuz, Hızlı ve Güvenli)
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Tam Ekran Mobil Menü Aç/Kapa
    const fullScreenNav = document.getElementById('fullScreenMobileNav');
    const mobileMenuOpenBtn = document.getElementById('mobileMenuOpenBtn') || document.querySelector('.mobile-toggle');
    const mobileMenuCloseBtn = document.getElementById('mobileMenuCloseBtn');

    window.openFullScreenMobileNav = function() {
        if (fullScreenNav) {
            fullScreenNav.classList.add('is-open');
            fullScreenNav.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeFullScreenMobileNav = function() {
        if (fullScreenNav) {
            fullScreenNav.classList.remove('is-open');
            fullScreenNav.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }
    };

    if (mobileMenuOpenBtn) {
        mobileMenuOpenBtn.addEventListener('click', (e) => {
            e.preventDefault();
            window.openFullScreenMobileNav();
        });
    }

    if (mobileMenuCloseBtn) {
        mobileMenuCloseBtn.addEventListener('click', (e) => {
            e.preventDefault();
            window.closeFullScreenMobileNav();
        });
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            window.closeFullScreenMobileNav();
        }
    });

    // Yan Sabit İletişim Menüsü Aç/Kapa (Dock Toggle)
    const sideDock = document.getElementById('sideContactDock');
    const dockToggleBtn = document.getElementById('dockToggleBtn');
    if (sideDock && dockToggleBtn) {
        dockToggleBtn.addEventListener('click', () => {
            sideDock.classList.toggle('collapsed');
        });
    }

    // 2. Sıkça Sorulan Sorular (SSS) Akordeon
    const accordionHeaders = document.querySelectorAll('.accordion-header');
    accordionHeaders.forEach(header => {
        header.addEventListener('click', () => {
            const currentItem = header.parentElement;
            const toggleSign = header.querySelector('.accordion-toggle');
            const isActive = currentItem.classList.contains('active');

            // Diğer tüm açık akordeonları kapat
            document.querySelectorAll('.accordion-item').forEach(item => {
                item.classList.remove('active');
                const sign = item.querySelector('.accordion-toggle');
                if (sign) sign.textContent = '+';
            });

            if (!isActive) {
                currentItem.classList.add('active');
                if (toggleSign) toggleSign.textContent = '—';
            }
        });
    });

    // 3. Çerez (Cookie) Onay Bannerı Yönetimi
    const cookieBanner = document.getElementById('cookieBanner');
    const cookieTriggerBtn = document.getElementById('cookieTriggerBtn');
    const btnAcceptCookies = document.getElementById('btnAcceptCookies');
    const btnRejectCookies = document.getElementById('btnRejectCookies');

    const consentStatus = localStorage.getItem('fzt_cookie_consent');

    if (!consentStatus && cookieBanner) {
        cookieBanner.classList.add('show');
    }

    if (btnAcceptCookies && cookieBanner) {
        btnAcceptCookies.addEventListener('click', () => {
            localStorage.setItem('fzt_cookie_consent', 'accepted');
            cookieBanner.classList.remove('show');
        });
    }

    if (btnRejectCookies && cookieBanner) {
        btnRejectCookies.addEventListener('click', () => {
            localStorage.setItem('fzt_cookie_consent', 'rejected');
            cookieBanner.classList.remove('show');
        });
    }

    if (cookieTriggerBtn && cookieBanner) {
        cookieTriggerBtn.addEventListener('click', () => {
            cookieBanner.classList.toggle('show');
        });
    }

    // 4. KVKK ve Bilgi Modalları Açma / Kapama
    const modalTriggers = document.querySelectorAll('[data-open-modal]');
    const modalCloseButtons = document.querySelectorAll('[data-close-modal]');
    const modals = document.querySelectorAll('.modal-overlay');

    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            const modalId = trigger.getAttribute('data-open-modal');
            const targetModal = document.getElementById(modalId);
            if (targetModal) {
                targetModal.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
        });
    });

    modalCloseButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            modals.forEach(m => m.classList.remove('show'));
            document.body.style.overflow = '';
        });
    });

    modals.forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            }
        });
    });

    // 5. Asenkron İletişim & Randevu Formu Gönderimi
    const appointmentForm = document.getElementById('appointmentForm');
    if (appointmentForm) {
        appointmentForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitBtn = appointmentForm.querySelector('button[type="submit"]');
            const alertBox = document.getElementById('formAlertBox');
            const originalBtnText = submitBtn.textContent;
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'LÜTFEN BEKLEYİNİZ...';
            if (alertBox) {
                alertBox.style.display = 'none';
                alertBox.className = 'form-alert';
            }

            const formData = new FormData(appointmentForm);

            try {
                const response = await fetch(appointmentForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const result = await response.json();

                if (alertBox) {
                    alertBox.style.display = 'block';
                    if (result.success) {
                        alertBox.className = 'form-alert form-alert-success';
                        alertBox.textContent = result.message || 'Talebiniz başarıyla iletildi. En kısa sürede sizinle iletişime geçeceğiz.';
                        appointmentForm.reset();

                        // İsteğe bağlı WhatsApp ile de doğrudan mesaj başlatma seçeneği
                        if (result.whatsapp_url) {
                            setTimeout(() => {
                                if (confirm('Randevu talebinizi doğrudan WhatsApp üzerinden de iletmek ister misiniz?')) {
                                    window.open(result.whatsapp_url, '_blank');
                                }
                            }, 700);
                        }
                    } else {
                        alertBox.className = 'form-alert form-alert-danger';
                        alertBox.textContent = result.message || 'Bir hata oluştu, lütfen alanları kontrol ediniz.';
                    }
                }
            } catch (err) {
                if (alertBox) {
                    alertBox.style.display = 'block';
                    alertBox.className = 'form-alert form-alert-danger';
                    alertBox.textContent = 'Bağlantı hatası oluştu. Lütfen doğrudan telefon veya WhatsApp ile ulaşınız.';
                }
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalBtnText;
            }
        });
    }

    // 6. Header Kaydırma Efekti (Buzlu Cam & Gölge)
    const siteHeader = document.querySelector('.site-header');
    if (siteHeader) {
        const handleHeaderScroll = () => {
            if (window.scrollY > 40) {
                siteHeader.classList.add('scrolled');
            } else {
                siteHeader.classList.remove('scrolled');
            }
        };
        window.addEventListener('scroll', handleHeaderScroll, { passive: true });
        handleHeaderScroll();
    }

    // 7. Sayfa Kaydırma Animasyonları (Scroll Reveal & Kademeli Akış)
    if ('IntersectionObserver' in window) {
        const revealSelector = [
            '.section-header',
            '.section-title',
            '.editorial-card',
            '.service-card',
            '.home-blog-card',
            '.blog-card',
            '.recipe-card',
            '.approach-visual',
            '.approach-content',
            '.editorial-list-item',
            '.vm-box',
            '.accordion-item',
            '.contact-info-panel',
            '.contact-map-panel',
            '.biofeedback-feature-box',
            '.service-bullets-box',
            '.contact-band-card'
        ].join(', ');

        const revealElements = document.querySelectorAll(revealSelector);

        // Kademeli (stagger) animasyon gecikmesi belirleme
        const containers = document.querySelectorAll('.services-grid, .editorial-list, .recipes-grid, .vm-grid, .contact-grid, .accordion-container');
        containers.forEach(container => {
            const items = container.querySelectorAll('.service-card, .editorial-list-item, .recipe-card, .vm-box, .accordion-item');
            items.forEach((item, index) => {
                const delay = Math.min((index % 4) * 0.12, 0.45);
                item.style.setProperty('--reveal-delay', `${delay}s`);
            });
        });

        // Sol / Sağ yönlendirmeli geçişler (Banner altı kartlar ve karşılıklı paneller)
        document.querySelectorAll('.approach-visual, .contact-info-panel, .editorial-card.sage, .reveal-from-left').forEach(el => el.classList.add('reveal-left'));
        document.querySelectorAll('.approach-content, .contact-map-panel, .editorial-card.biofeedback-editorial-card, .reveal-from-right').forEach(el => el.classList.add('reveal-right'));

        const observerOptions = {
            threshold: 0.08,
            rootMargin: '0px 0px -30px 0px'
        };

        const revealObserver = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    obs.unobserve(entry.target);
                }
            });
        }, observerOptions);

        revealElements.forEach(el => {
            el.classList.add('scroll-reveal');
            const rect = el.getBoundingClientRect();
            // Sayfa açıldığında doğrudan görünür alanda olan üst öğeler anında açılsın
            if (rect.top < window.innerHeight && rect.bottom > 0) {
                setTimeout(() => {
                    el.classList.add('is-visible');
                }, 80);
            } else {
                revealObserver.observe(el);
            }
        });
    }

    // 8. Mobilde Çift Dokunma Zoomunu Önleme
    let lastTouchTime = 0;
    document.addEventListener('touchend', (e) => {
        const currentTime = Date.now();
        if (currentTime - lastTouchTime <= 300) {
            e.preventDefault();
        }
        lastTouchTime = currentTime;
    }, false);
});
