<footer class="bx-footer">
  <div class="container">
    <div class="footer-main-layout">
      <div class="footer-brand-area">
        <div class="about">
          <a href="index.php" class="f-logo"><img src="assets/img/businessx-logo.png?v=20261008" alt="BusinessX"></a>
          <p>The UK's leading business exchange network connecting verified businesses, startups, investors and mentors across the UK and 120+ countries.</p>
          <div class="social-icons">
            <a href="#" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.5c-2.857 0-3.992 1-4.5 3.5v4.5z"/></svg></a>
            <a href="#" aria-label="Twitter"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M23 4.9c-.8.4-1.7.6-2.6.8a4.5 4.5 0 0 0 2-2.5c-.9.5-1.9.9-2.9 1.1a4.5 4.5 0 0 0-7.7 4.1A12.8 12.8 0 0 1 2.5 3.7a4.5 4.5 0 0 0 1.4 6 4.4 4.4 0 0 1-2-.5v.1a4.5 4.5 0 0 0 3.6 4.4 4.6 4.6 0 0 1-2 .1 4.5 4.5 0 0 0 4.2 3.1A9 9 0 0 1 1 18.6a12.7 12.7 0 0 0 6.9 2c8.3 0 12.8-6.9 12.8-12.8v-.6c.9-.6 1.6-1.4 2.3-2.3z"/></svg></a>
            <a href="#" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><line x1="17.5" y1="6.5" x2="17.5" y2="6.5"/></svg></a>
            <a href="#" aria-label="LinkedIn"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5c0 1.381-1.119 2.5-2.5 2.5s-2.5-1.119-2.5-2.5 1.119-2.5 2.5-2.5 2.5 1.119 2.5 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.6 4.92-4.985 4.92 0v8.399h5.029v-9.965c0-7.879-8.435-7.589-9.95-3.96v-2.075z"/></svg></a>
            <a href="#" aria-label="YouTube"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.615 3.184c-3.179-.503-7.398-.503-10.577-.503h-.076c-3.179 0-7.398 0-10.577.503C-1.778 3.908-2.001 7.478-2.001 12c0 4.521.223 8.091 4.786 8.816 3.179.503 7.398.503 10.577.503h.076c3.179 0 7.398 0 10.577-.503 4.563-.725 4.786-4.295 4.786-8.816 0-4.521-.223-8.091-4.786-8.816zM9.745 16.211V7.789l6.526 4.211-6.526 4.211z"/></svg></a>
          </div>
        </div>
      </div>
      <section class="footer-category-browser" aria-labelledby="footer-category-heading">
        <h4 id="footer-category-heading">Browse Categories</h4>
        <div class="footer-category-tabs" role="tablist" aria-label="Browse profile categories">
          <button type="button" id="footer-tab-business" role="tab" aria-controls="footer-category-list" aria-selected="true" tabindex="0" data-category-type="business">Business</button>
          <button type="button" id="footer-tab-startup" role="tab" aria-controls="footer-category-list" aria-selected="false" tabindex="-1" data-category-type="startup">Startup</button>
          <button type="button" id="footer-tab-investor" role="tab" aria-controls="footer-category-list" aria-selected="false" tabindex="-1" data-category-type="investor">Investor</button>
        </div>
        <div class="footer-category-grid" id="footer-category-list" role="tabpanel" aria-labelledby="footer-tab-business" aria-live="polite"></div>
      </section>
    </div>
    <div class="bx-footer-bottom">
      <span>Copyright © 2025 - 2026 BusinessX. All rights reserved.</span>
      <div class="fb-links">
        <a href="#">Home</a>
        <a href="#">About Us</a>
        <a href="#">Disclaimer</a>
        <a href="#">Privacy Policy</a>
        <a href="#">Terms</a>
        <a href="#">Contact</a>
      </div>
    </div>
  </div>
</footer>

<script src="js/common.js"></script>
<script>
  const footerCategoryGroups = {
    business: [
      { name: 'Automobile', items: ['Automobile Accessories', 'Automobile parts', 'Automobile wash', 'Automobile Electric vehicles'] },
      { name: 'FMCG', items: ['Ayurvedic products', 'Beauty cosmetics', 'Computer hardware', 'Consumer electronics'] },
      { name: 'Education', items: ['Coaching & training institutes', 'Colleges', 'Day Care centres, Creches', 'Education Supplies'] },
      { name: 'Beauty, health & wellness', items: ['Ambulance healthcare service', 'Beauty equipments', 'Beauty Salons', 'Clinics & Nursing Homes'] },
      { name: 'Business services', items: ['Advertisement & media services', 'BPO', 'Broadcasting services', 'Book, magazine & newspaper publishing'] },
      { name: 'Food & beverage', items: ['Agriculture & farming', 'Agriculture products', 'Alcoholic beverages', 'Animal feed'] },
      { name: 'Fashion', items: ['Bags & luggage', 'Children clothing', 'Ethnical wear', 'Fabric'] },
      { name: 'Building construction & Home products', items: ['Bathroom fixtures', 'Brick & cement', 'Building contractors', 'Building maintenance'] }
    ],
    startup: [
      { name: 'Technology & software', items: ['AI & machine learning', 'SaaS', 'Cybersecurity', 'E-commerce'] },
      { name: 'Health & life sciences', items: ['Healthtech', 'Biotechnology', 'Medical devices', 'Digital health'] },
      { name: 'Education', items: ['Edtech', 'Learning platforms', 'Training & skills', 'Education services'] },
      { name: 'Climate & industry', items: ['Clean energy', 'AgriTech', 'Mobility', 'Advanced manufacturing'] }
    ],
    investor: [
      { name: 'Investment focus', items: ['Technology', 'Healthcare', 'Manufacturing', 'Consumer products'] },
      { name: 'Investor type', items: ['Angel investors', 'Venture capital', 'Private equity', 'Family offices'] },
      { name: 'Investment stage', items: ['Pre-seed & seed', 'Early stage', 'Growth capital', 'Buyouts'] },
      { name: 'Investment region', items: ['United Kingdom', 'Europe', 'North America', 'Asia Pacific'] }
    ]
  };

  const footerCategoryTabs = [...document.querySelectorAll('.footer-category-tabs [role="tab"]')];
  const footerCategoryList = document.getElementById('footer-category-list');

  function showFooterCategories(tab) {
    const profileType = tab.dataset.categoryType;
    footerCategoryTabs.forEach(item => {
      const selected = item === tab;
      item.setAttribute('aria-selected', String(selected));
      item.tabIndex = selected ? 0 : -1;
    });
    footerCategoryList.setAttribute('aria-labelledby', tab.id);
    footerCategoryList.innerHTML = footerCategoryGroups[profileType].map(group => `
      <section class="footer-category-group">
        <h5>${group.name}</h5>
        <ul>${group.items.map(item => `<li><a href="business-listing.php?type=${profileType}">${item}</a></li>`).join('')}</ul>
      </section>
    `).join('');
  }

  if (footerCategoryTabs.length && footerCategoryList) {
    footerCategoryTabs.forEach((tab, index) => {
      tab.addEventListener('click', () => showFooterCategories(tab));
      tab.addEventListener('keydown', event => {
        const nextIndex = event.key === 'ArrowRight' ? (index + 1) % footerCategoryTabs.length
          : event.key === 'ArrowLeft' ? (index - 1 + footerCategoryTabs.length) % footerCategoryTabs.length
          : event.key === 'Home' ? 0
          : event.key === 'End' ? footerCategoryTabs.length - 1
          : -1;

        if (nextIndex >= 0) {
          event.preventDefault();
          footerCategoryTabs[nextIndex].focus();
          showFooterCategories(footerCategoryTabs[nextIndex]);
        }
      });
    });

    showFooterCategories(footerCategoryTabs[0]);
  }
</script>
