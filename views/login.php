<?php
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_data = [
        'username' => $_POST['username'] ?? '',
        'password' => $_POST['password'] ?? '',
    ];

    $patterns = [
        'username' => [
            'label' => 'Nimimerkki',
            'type' => 'regex',
            'rule' => '/^[a-zA-Z0-9_-]{3,50}$/',
            'message' => 'Virheellinen käyttäjätunnus',
        ],
    ];

    $sanitized = sanitizeInput([
        'username' => $input_data['username'],
    ]);

    $sanitized['password'] = $input_data['password'];
    $validated = validateInput($patterns, $sanitized);

    // Password shold not be empty
    if ($validated['data']['password'] === '') {
        $validated['errors'][] = 'Täytä kaikki kentät.';
        $validated['isValid'] = false;
    }

    if ($validated['isValid']) {
        $username = $validated['data']['username'];
        $password = $validated['data']['password'];

        $stmt = $conn->prepare(
            'SELECT id, username, password FROM users WHERE username = ?'
        );

        $stmt->bind_param('s', $username);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            // New session ID
            session_regenerate_id(true);

            // Session metadata
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header('Location: index.php?page=home');
            exit;
        } else {
            $validated['errors'][] = 'Virheellinen käyttäjätunnus tai salasana.';
        }

        $stmt->close();
    }
}
?>


<section class="post-form">

    <h2 class="section-title">Kirjaudu sisään</h2>

    <?php if (!empty($validated['errors'])): ?>

        <div class="alert alert-error">
            <?php foreach ($validated['errors'] as $error_msg): ?>
                <p><i class="fa-solid fa-circle-info"></i> <?= htmlspecialchars($error_msg) ?></p>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

    <div class="form-wrapper">
        <form class="form" method="post" action="<?= htmlspecialchars($_SERVER['SCRIPT_NAME']) ?>?page=login">
            <div class="form-field">
                <label class="form-label" for="username">Käyttäjätunnus</label>
                <input class="form-input" type="text" id="username" name="username" maxlength="50" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" autocomplete="username" required>
            </div>

            <div class="form-field">
                <label class="form-label" for="password">Salasana</label>
                <input class="form-input" type="password" id="password" name="password" autocomplete="current-password" required>
            </div>

            <button class="form-button" type="submit">Kirjaudu sisään</button>
        </form>
    </div>
    <p>
        Ei vielä tiliä?
        <a href="index.php?page=register">Rekisteröidy</a>
    </p>
</section>