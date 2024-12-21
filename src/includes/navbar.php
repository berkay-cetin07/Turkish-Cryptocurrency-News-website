<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            <a href="/?page=home">
                <span class="brand-icon">💰</span>
                <span class="brand-text">Better Be Rich</span>
            </a>
        </div>

        <div class="navbar-menu">
            <ul class="nav-links">
                <li>
                    <a href="/?page=home" class="nav-link">
                        <span class="nav-icon">🏠</span>
                        <span>Ana Sayfa</span>
                    </a>
                </li>
                <li>
                    <a href="/?page=news" class="nav-link">
                        <span class="nav-icon">📰</span>
                        <span>Haberler</span>
                    </a>
                </li>
                <li>
                    <a href="/?page=coin-values" class="nav-link">
                        <span class="nav-icon">📊</span>
                        <span>Coin Değerleri</span>
                    </a>
                </li>
                <li>
                    <a href="/?page=charts" class="nav-link">
                        <span class="nav-icon">📈</span>
                        <span>Grafikler</span>
                    </a>
                </li>
                <li>
                    <a href="/?page=price-checker" class="nav-link">
                        <span class="nav-icon">🔍</span>
                        <span>Fiyat Kontrolü</span>
                    </a>
                </li>
                <li>
                    <a href="/?page=exchange-status" class="nav-link">
                        <span class="nav-icon">🔄</span>
                        <span>Borsa Durumu</span>
                    </a>
                </li>
                <li>
                    <a href="/?page=redirect" class="nav-link">
                        <span class="nav-icon">↗️</span>
                        <span>Yönlendirme</span>
                    </a>
                </li>
                <li>
                    <a href="/?page=support" class="nav-link">
                        <span class="nav-icon">💬</span>
                        <span>Destek Portalı</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="navbar-right">
            <div class="navbar-search">
                <form method="get" action="/">
                    <input type="hidden" name="page" value="search-results">
                    <div class="search-container">
                        <input type="text" name="q" placeholder="Haber arayın..." required>
                        <button type="submit">
                            <span class="search-icon">🔍</span>
                        </button>
                    </div>
                </form>
            </div>
            
            <a href="/?page=logout" class="nav-link logout-link">
                <span class="nav-icon">🚪</span>
                <span>Çıkış Yap</span>
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
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border-bottom: 1px solid #e9ecef;
}

.navbar-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 15px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 65px;
}

.navbar-brand a {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: #2563eb;
    font-size: 1.3em;
    font-weight: 600;
    white-space: nowrap;
}

.nav-links {
    display: flex;
    list-style: none;
    margin: 0;
    margin-left: 5px;
    padding: 0;
    gap: 2px;
}

.nav-link {
    display: flex;
    align-items: center;
    color: #4b5563;
    text-decoration: none;
    padding: 6px 8px;
    border-radius: 6px;
    transition: all 0.3s ease;
    font-weight: 500;
    white-space: nowrap;
    font-size: 0.9em;
}

.nav-link:hover {
    background: #f3f4f6;
    color: #2563eb;
}

.nav-icon {
    margin-right: 4px;
    font-size: 1em;
}

.navbar-right {
    display: flex;
    align-items: center;
    gap: 10px;
}

.logout-link {
    background: #f3f4f6;
    padding: 6px 12px;
}

.logout-link:hover {
    background: #e5e7eb;
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
    width: 140px;
    background: transparent;
    border: none;
    padding: 6px 10px;
    color: #4b5563;
    font-size: 0.9em;
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


@media (max-width: 1024px) {
    .navbar-menu {
        display: none;
        position: absolute;
        top: 70px;
        left: 0;
        right: 0;
        background: #ffffff;
        padding: 20px;
        border-bottom: 1px solid #e9ecef;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .navbar-menu.active {
        display: block;
    }

    .nav-links {
        flex-direction: column;
    }

    .mobile-menu-button {
        display: block;
    }

    .navbar-search, .logout-link {
        display: none;
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

    // Add active class to current page link
    const currentPage = window.location.search.split('page=')[1] || 'home';
    const currentLink = document.querySelector(`a[href*="${currentPage}"]`);
    if (currentLink) {
        currentLink.classList.add('active');
    }
});
</script>
