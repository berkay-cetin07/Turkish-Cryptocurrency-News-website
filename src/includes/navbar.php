


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
                    <a href="/?page=upload" class="nav-link">
                        <span class="nav-icon">📤</span>
                        <span>Destek Portalı</span>
                    </a>
                </li>
            </ul>
        </div>

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

        <!-- Logout Button -->
        <div class="navbar-logout">
            <?php if (isset($_SESSION['username'])): ?>
                <a href="/?page=logout" class="nav-link logout-btn">
                    <span class="nav-icon">🔒</span>
                    <span>Çıkış Yap</span>
                </a>
            <?php endif; ?>
        </div>

        <!-- Mobile menu button -->
        <button class="mobile-menu-button">
            <span class="menu-icon">☰</span>
        </button>
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
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 70px;
}

.navbar-brand a {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: #2563eb; /* Modern blue */
    font-size: 1.5em;
    font-weight: 600;
}

.brand-icon {
    margin-right: 10px;
    font-size: 1.2em;
}

.nav-links {
    display: flex;
    list-style: none;
    margin: 0;
    margin-left: 10px;
    padding: 0;
    gap: 5px;
}

.nav-link {
    display: flex;
    align-items: center;
    color: #4b5563; /* Dark gray */
    text-decoration: none;
    padding: 10px 15px;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-weight: 500;
}

.nav-link:hover {
    background: #f3f4f6; /* Light gray background */
    color: #2563eb; /* Modern blue */
    transform: translateY(-2px);
}

.nav-icon {
    margin-right: 8px;
    font-size: 1.1em;
}

.navbar-search {
    position: relative;
}

.search-container {
    display: flex;
    align-items: center;
    border-radius: 20px;
    padding: 5px;
}

.navbar-search input {
    background: transparent;
    border: none;
    padding: 8px 15px;
    color: #4b5563;
    width: 200px;
    font-size: 0.9em;
}

.navbar-search input::placeholder {
    color: #9ca3af;
}

.navbar-search button {
    background: transparent;
    border: none;
    color: #4b5563;
    padding: 8px 15px;
    cursor: pointer;
    border-radius: 0 20px 20px 0;
    transition: all 0.3s ease;
}

.navbar-search button:hover {
    color: #2563eb;
}

/* Logout button styling */
.navbar-logout {
    margin-left: 20px;
}

.navbar-logout .logout-btn {
    font-weight: 600;
    color: #4b5563; /* Dark gray */
    text-decoration: none;
    padding: 10px 15px;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-size: 0.9em;
}

.navbar-logout .logout-btn:hover {
    background: #f3f4f6; /* Light gray background */
    color: #2563eb; /* Modern blue */
    transform: translateY(-2px);
}

.mobile-menu-button {
    display: none;
    background: transparent;
    border: none;
    color: #4b5563;
    font-size: 1.5em;
    cursor: pointer;
    padding: 5px;
}

/* Active link styling */
.nav-link.active {
    background: #e0e7ff; /* Light blue background */
    color: #2563eb; /* Modern blue */
    font-weight: 500;
}

/* Responsive design */
@media (max-width: 1024px) {
    .navbar-search input {
        width: 150px;
    }
}

@media (max-width: 768px) {
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

    .nav-link {
        padding: 15px;
        border-radius: 8px;
    }

    .mobile-menu-button {
        display: block;
    }

    .navbar-search {
        display: none;
    }
}

/* Animations */
@keyframes slideDown {
    from {
        transform: translateY(-10px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.nav-link {
    animation: slideDown 0.3s ease;
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
