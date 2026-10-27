<section class="post-form">

    <h2 class="section-title">Kirjaudu sisään</h2>

    <div class="form-wrapper">
        <form class="form" method="post" action="<?= htmlspecialchars($_SERVER['SCRIPT_NAME']) ?>?page=login">
            <div class="form-field">
                <label class="form-label" for="username">Käyttäjätunnus</label>
                <input class="form-input" type="text" id="username" name="username" autocomplete="username" required>
            </div>

            <div class="form-field">
                <label class="form-label" for="password">Salasana</label>
                <input class="form-input" type="password" id="password" name="password" autocomplete="current-password" required>
            </div>

            <button class="form-button" type="submit">Kirjaudu sisään</button>
        </form>
    </div>
</section>