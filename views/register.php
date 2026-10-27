<section class="post-form">

    <h2 class="section-title">Rekisteröidy</h2>

    <div class="form-wrapper">
        <form class="form" method="POST" action="<?= htmlspecialchars($_SERVER['SCRIPT_NAME']) ?>?page=register">
            <div class="form-field">
                <label class="form-label" for="name">Nimi</label>
                <input class="form-input" type="text" id="name" name="name" placeholder="Juzu Kvanttinen" required>
            </div>

            <div class="form-field">
                <label class="form-label" for="email">Sähköposti</label>
                <input class="form-input" type="email" id="email" name="email" placeholder="kvant@email.com" required>
            </div>

            <div class="form-field">
                <label class="form-label" for="username">Käyttäjätunnus</label>
                <input class="form-input" type="text" id="username" name="username" placeholder="Keksi joku kiva nimimerkki" autocomplete="username" required>
            </div>

            <div class="form-field">
                <label class="form-label" for="password">Salasana</label>
                <input class="form-input" type="password" id="password" name="password" placeholder="Käytä vahvaa salasanaa" autocomplete="current-password" required>
            </div>

            <button class="form-button" type="submit">Rekisteröidy</button>
        </form>
    </div>

    <p>
        Onko sinulla jo tili?
        <a href="index.php?page=login">Kirjaudu sisään</a>
    </p>
</section>