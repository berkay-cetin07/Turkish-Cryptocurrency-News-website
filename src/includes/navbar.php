<nav class="navbar">
    <ul>
        <li><a href="/?page=home">Ana Sayfa</a></li>
        <li><a href="/?page=news">Haberler</a></li>
        <li><a href="/?page=coin-values">Coin Değerleri</a></li>
        <li><a href="/?page=charts">Grafikler</a></li>
    </ul>
    <form method="get" action="/">
        <input type="hidden" name="page" value="search-results">
        <input type="text" name="q" placeholder="Haber arayın..." required>
        <button type="submit">Ara</button>
    </form>
</nav>
