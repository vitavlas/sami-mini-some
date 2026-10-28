<?php
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form inputs
    $input_data = [
        'name' => $_POST['name'] ?? '',
        'username' => $_POST['username'] ?? '',
        'email' => $_POST['email'] ?? '',
        'password' => $_POST['password'] ?? '',
    ];

    $patterns = [
        'name' => [
            'label' => 'Nimi',
            'type' => 'regex',
            'rule' => '/^(?=.{1,100}$)[\p{L}]+(?:[ -][\p{L}]+)*$/u',
            'message' => 'Nimi sisältää kiellettyjä merkkejä',
        ],
        'username' => [
            'label' => 'Nimimerkki',
            'type' => 'regex',
            'rule' => '/^[a-zA-Z0-9_-]{3,50}$/',
            'message' => 'Käyttäjätunnuksen tulee olla 3–50 merkkiä pitkä ja sisältää kirjaimia, numeroita ja _- merkkejä',
        ],
        'email' => [
            'label' => 'Sähköposti',
            'type' => 'filter',
            'rule' => FILTER_VALIDATE_EMAIL,
            'message' => 'Virheellinen sähköpostiosoite',
        ],
        'password' => [
            'label' => 'Salasana',
            'type' => 'regex',
            'rule' => '/^(?!\s*$).+/',
            'message' => 'Salasanan tulee olla vähintään 8 merkkiä pitkä ja sisältää ainakin yksi kirjain ja yksi numero',
        ],
    ];

    $sanitized = sanitizeInput([
        'name' => $input_data['name'],
        'username' => $input_data['username'],
        'email' => $input_data['email'],
    ]);

    $sanitized['password'] = $input_data['password'];

    $validated = validateInput($patterns, $sanitized);

    // Make useful data format
    if ($validated['isValid']) {
        $name = $validated['data']['name'];
        $username = $validated['data']['username'];
        $email = $validated['data']['email'];
        $password = $validated['data']['password'];

        // Check that username is unique
        $stmt = $conn->prepare(
            'SELECT id FROM users WHERE username = ?'
        );

        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $validated['errors'][] = 'Käyttäjätunnus on jo käytössä.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->close();

            // Add user into DB
            $stmt = $conn->prepare(
                'INSERT INTO users (name, username, email, password)
                VALUES (?, ?, ?, ?)'
            );

            $stmt->bind_param(
                'ssss',
                $name,
                $username,
                $email,
                $hashedPassword
            );

            if ($stmt->execute()) {
                $success = true;
            } else {
                $validated['errors'][] = 'Rekisteröinti epäonnistui.';
            }
        }

        $stmt->close();
    }
}
?>


<section class="post-form">

    <h2 class="section-title">Rekisteröidy</h2>

    <?php if ($success): ?>

        <div class="alert alert-success">
            <p>Rekisteröinti onnistui!</p>
            <p>
                <a href="index.php?page=login">Kirjaudu sisään</a>
            </p>
        </div>

    <?php else: ?>

        <?php if (!empty($validated['errors'])): ?>

            <div class="alert alert-error">
                <?php foreach ($validated['errors'] as $error_msg): ?>
                    <p><i class="fa-solid fa-circle-info"></i> <?= htmlspecialchars($error_msg) ?></p>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>

        <div class="form-wrapper">
            <form class="form" method="POST" action="<?= htmlspecialchars($_SERVER['SCRIPT_NAME']) ?>?page=register">
                <div class="form-field">
                    <label class="form-label" for="name">Nimi</label>
                    <input class="form-input" type="text" id="name" name="name" maxlength="100" value="<?= htmlspecialchars($validated['data']['name'] ?? '') ?>" placeholder="Juzu Kvanttinen" required>
                </div>

                <div class="form-field">
                    <label class="form-label" for="email">Sähköposti</label>
                    <input class="form-input" type="email" id="email" name="email" maxlength="255" value="<?= htmlspecialchars($validated['data']['email'] ?? '') ?>" placeholder="kvant@email.com" required>
                </div>

                <div class="form-field">
                    <label class="form-label" for="username">Käyttäjätunnus</label>
                    <input class="form-input" type="text" id="username" name="username" minlength="3" maxlength="50" value="<?= htmlspecialchars($validated['data']['username'] ?? '') ?>" placeholder="Keksi joku kiva nimimerkki" autocomplete="username" required>
                </div>

                <div class="form-field">
                    <label class="form-label" for="password">Salasana</label>
                    <input class="form-input" type="password" id="password" name="password" minlength="8" placeholder="Käytä vahvaa salasanaa" autocomplete="current-password" required>
                </div>

                <button class="form-button" type="submit">Rekisteröidy</button>
            </form>
        </div>

        <p>
            Onko sinulla jo tili?
            <a href="index.php?page=login">Kirjaudu sisään</a>
        </p>

    <?php endif; ?>
</section>