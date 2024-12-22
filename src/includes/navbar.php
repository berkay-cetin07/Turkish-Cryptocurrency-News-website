<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-left">
            <div class="navbar-brand">
                <a href="/?page=home">
                    <span class="brand-icon">💰</span>
                    <span class="brand-text">Better Be Rich</span>
                </a>
            </div>
            <div class="navbar-menu">
                <ul class="nav-links">
                    <li><a href="/?page=home" class="nav-link"><span class="nav-icon">🏠</span>Ana Sayfa</a></li>
                    <li><a href="/?page=news" class="nav-link"><span class="nav-icon">📰</span>Haberler</a></li>
                    <li><a href="/?page=coin-values" class="nav-link"><span class="nav-icon">📊</span>Coin Değerleri</a></li>
                    <li><a href="/?page=charts" class="nav-link"><span class="nav-icon">📈</span>Grafikler</a></li>
                    <li><a href="/?page=price-checker" class="nav-link"><span class="nav-icon">🔍</span>Fiyat Kontrolü</a></li>
                    <li><a href="/?page=exchange-status" class="nav-link"><span class="nav-icon">🔄</span>Borsa Durumu</a></li>
                    <li><a href="/?page=redirect" class="nav-link"><span class="nav-icon">↗️</span>Yönlendirme</a></li>
                    <li><a href="/?page=support" class="nav-link"><span class="nav-icon">💬</span>Destek Portalı</a></li>
                    <li><a href="/?page=graph-generator" class="nav-link"><span class="nav-icon">⚙️</span>Grafik Oluşturucu</a></li>
                </ul>
            </div>
        </div>
        <div class="navbar-right">
            <div class="navbar-search">
                <form method="get" action="/">
                    <input type="hidden" name="page" value="search-results">
                    <div class="search-container">
                        <input type="text" name="q" placeholder="Haber arayın..." required>
                        <button type="submit"><span class="search-icon">🔍</span></button>
                    </div>
                </form>
            </div>
            <a href="/?page=logout" class="nav-link logout-link">
                <span class="nav-icon">🚪</span>Çıkış Yap
            </a>
        </div>
    </div>
</nav>

<style>
.navbar {
    background: #ffffff;
    padding: 0;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    border-bottom: 1px solid #e9ecef;
}

.navbar-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 15px;
    height: 60px;
}

.navbar-left {
    display: flex;
    align-items: center;
}

.navbar-brand {
    margin-right: 20px;
}

.navbar-brand a {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: #2563eb;
    font-size: 1em;
    font-weight: 600;
    white-space: nowrap;
}

.nav-links {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 10px;
}

.nav-link {
    display: flex;
    align-items: center;
    color: #4b5563;
    text-decoration: none;
    padding: 5px 8px;
    font-size: 0.85em;
    font-weight: 500;
}

.nav-link:hover {
    background: #f3f4f6;
    color: #2563eb;
    border-radius: 5px;
}

.nav-icon {
    margin-right: 4px;
}

.navbar-right {
    display: flex;
    align-items: center;
    gap: 15px;
}

.search-container {
    display: flex;
    align-items: center;
    background: #f3f4f6;
    border-radius: 16px;
    padding: 3px 10px;
    border: 1px solid #e5e7eb;
}

.navbar-search input {
    width: 120px;
    background: transparent;
    border: none;
    padding: 6px 10px;
    color: #4b5563;
    font-size: 0.85em;
}

.navbar-search input:focus {
    outline: none;
}

.navbar-search button {
    background: transparent;
    border: none;
    color: #4b5563;
    padding: 6px 10px;
    cursor: pointer;
}

.logout-link {
    background: #f3f4f6;
    padding: 5px 10px;
    font-size: 0.85em;
    color: #4b5563;
    border-radius: 5px;
    text-decoration: none;
}

.logout-link:hover {
    background: #e5e7eb;
}

/* Responsive Ayarlar */
@media (max-width: 768px) {
    .navbar-container {
        flex-direction: column;
        align-items: flex-start;
    }

    .nav-links {
        flex-direction: column;
    }

    .navbar-right {
        flex-direction: column;
    }
}
</style>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.querySelector('.mobile-menu-button');
    const navbarMenu = document.querySelector('.navbar-menu');

    mobileMenuButton.addEventListener('click', function() {
        navbarMenu.classList.toggle('active');
    });

    // Sayfa bağlantısını işaretle
    const currentPage = window.location.search.split('page=')[1] || 'home';
    const currentLink = document.querySelector(`a[href*="${currentPage}"]`);
    if (currentLink) {
        currentLink.classList.add('active');
    }
});
</script>
