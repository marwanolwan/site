<?php// filepath: C:/wamp64/www/site/views/layouts/footer.php?>
    </main>
    
    <footer class="main-footer">
        <div class="container">
            <div class="footer-grid">
<div class="footer-col footer-about">
<h3 class="footer-logo">Al-Safi</h3>
<p style="text-align: justify;">
    <?php
    if ($current_lang === 'ar') {
        echo 'شركة الصافي للاستيراد والتجارة المحدودة، خبرة وموثوقية تمتد عبر السنين، نواصل التوسع والابتكار لتقديم أفضل المنتجات والخدمات للسوق الفلسطينية.';
    } else {
        echo 'Al-Safi Trading & Investment Co. — A legacy of trust and growth, continuously expanding and innovating to deliver the finest products and services to the Palestinian market.';
    }
    ?>
</p>
</div>

                <div class="footer-col footer-links">
                    <h4><?php echo htmlspecialchars($translations['nav_quick_links'] ?? 'Quick Links'); ?></h4>
                    <ul>
                        <li><a href="<?php echo BASE_URL; ?>"><?php echo htmlspecialchars($translations['nav_home'] ?? 'Home'); ?></a> || 
                        <a href="<?php echo BASE_URL; ?>about"><?php echo htmlspecialchars($translations['nav_about'] ?? 'About Us'); ?></a> || 
                        <a href="<?php echo BASE_URL; ?>products"><?php echo htmlspecialchars($translations['nav_products'] ?? 'Products'); ?></a> || 
                        <a href="<?php echo BASE_URL; ?>recipes"><?php echo htmlspecialchars($translations['nav_recipes'] ?? 'Recipes'); ?></a> || 
                        <a href="<?php echo BASE_URL; ?>locations"><?php echo htmlspecialchars($translations['nav_locations'] ?? 'Our Locations'); ?></a> || 
                        <a href="<?php echo BASE_URL; ?>contact"><?php echo htmlspecialchars($translations['nav_contact'] ?? 'Contact'); ?></a></li>
                    </ul>
                </div>
                <div class="footer-col footer-social">
                    <h4><?php echo htmlspecialchars($translations['nav_follow_us'] ?? 'Follow Us'); ?></h4>
                    <div class="social-icons">
                        <?php if(!empty($settings['social_facebook'])): ?><a href="<?php echo htmlspecialchars($settings['social_facebook']); ?>" target="_blank" class="social-icon"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
                        <?php if(!empty($settings['social_instagram'])): ?><a href="<?php echo htmlspecialchars($settings['social_instagram']); ?>" target="_blank" class="social-icon"><i class="fab fa-instagram"></i></a><?php endif; ?>
                        <?php if(!empty($settings['social_twitter'])): ?><a href="<?php echo htmlspecialchars($settings['social_twitter']); ?>" target="_blank" class="social-icon"><i class="fab fa-twitter"></i></a><?php endif; ?>
                        <?php if(!empty($settings['social_linkedin'])): ?><a href="<?php echo htmlspecialchars($settings['social_linkedin']); ?>" target="_blank" class="social-icon"><i class="fab fa-linkedin-in"></i></a><?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© <?php echo date('Y'); ?> Al-Safi Company. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // --- تأثير الهيدر عند التمرير ---
        const mainHeader = document.querySelector('.main-header');
        if (mainHeader) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    mainHeader.classList.add('header-scrolled');
                } else {
                    mainHeader.classList.remove('header-scrolled');
                }
            });
        }

        // --- تهيئة الهيرو سلايدر مع تأثيرات النصوص ---
        const heroContainer = document.querySelector('#hero-swiper');
        if (heroContainer) {
            const speed = parseInt(heroContainer.dataset.speed) || 5000;
            const effect = heroContainer.dataset.effect || 'slide';
            const loop = heroContainer.dataset.loop === '1';
            const autoplay = heroContainer.dataset.autoplay === '1';

            const heroSwiper = new Swiper(heroContainer, {
                loop: loop, effect: effect, grabCursor: true,
                autoplay: autoplay ? { delay: speed, disableOnInteraction: false } : false,
                pagination: { el: '.swiper-pagination', clickable: true },
                navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                on: {
                    init: function () { this.slides[this.activeIndex].classList.add('slide-loaded'); },
                    slideChangeTransitionStart: function () { this.slides.forEach(slide => { slide.classList.remove('slide-loaded'); }); },
                    slideChangeTransitionEnd: function () { this.slides[this.activeIndex].classList.add('slide-loaded'); }
                }
            });
        }

        // --- تهيئة سلايدر الشركاء ---
        const partnersSwiper = new Swiper('.partners-slider', {
            loop: true,
            spaceBetween: 30,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
        
            breakpoints: {
                320: { slidesPerView: 2, spaceBetween: 20 },
                640: { slidesPerView: 3, spaceBetween: 30 },
                1024: { slidesPerView: 5, spaceBetween: 40 }
            }
        });

        // --- كود نظام التابات في صفحة "من نحن" ---
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');
        if (tabButtons.length > 0) {
            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const tabId = button.dataset.tab;
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));
                    button.classList.add('active');
                    document.getElementById(tabId).classList.add('active');
                });
            });
        }
        
        // --- كود عداد الأرقام المتحرك ---
        const counters = document.querySelectorAll('.counter-number');
        if (counters.length > 0) {
            const speed = 200;
            const animateCounters = () => {
                counters.forEach(counter => {
                    const updateCount = () => {
                        const target = +counter.getAttribute('data-target');
                        const count = +counter.innerText;
                        const increment = Math.max(1, target / speed);
                        if (count < target) {
                            counter.innerText = Math.ceil(count + increment);
                            setTimeout(updateCount, 15);
                        } else { counter.innerText = target; }
                    };
                    updateCount();
                });
            };
            const counterSection = document.querySelector('#counters');
            let hasAnimated = false;
            if (counterSection) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting && !hasAnimated) {
                            animateCounters();
                            hasAnimated = true;
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.5 });
                observer.observe(counterSection);
            }
        }
        
        // --- كود تأثير Parallax ---
        const parallaxImages = document.querySelectorAll('.dynamic-image-circle');
        if (parallaxImages.length > 0) {
            const parallaxSpeed = 0.3;
            window.addEventListener('scroll', function() {
                let scrollPosition = window.scrollY;
                parallaxImages.forEach(image => {
                    const section = image.closest('.clipped-section');
                    if (section) {
                        const sectionTop = section.offsetTop;
                        const sectionHeight = section.offsetHeight;
                        if (scrollPosition + window.innerHeight > sectionTop && scrollPosition < sectionTop + sectionHeight) {
                            let yPos = (sectionTop - scrollPosition) * parallaxSpeed;
                            image.style.transform = `translateY(${yPos}px)`;
                        }
                    }
                });
            });
        }

    });
    </script>
</body>
</html>