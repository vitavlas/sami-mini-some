<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}

$userId = (int) $_SESSION['user_id'];
$errors = [];
$success = false;

// POST processing - update profile

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update-profile'])) {
    $errors = [];
    $success = false;

    // Validate form data
    $input_data = sanitizeInput([
        'name' => $_POST['name'] ?? '',
        'username' => $_POST['username'] ?? '',
        'email' => $_POST['email'] ?? '',
    ]);

    $name = $input_data['name'];
    $username = $input_data['username'];
    $email = $input_data['email'];

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
            'rule' => '/^[a-zA-Z0-9_]{3,50}$/',
            'message' => 'Käyttäjätunnuksen tulee olla 3–50 merkkiä pitkä ja sisältää kirjaimia, numeroita ja _- merkkejä',
        ],
        'email' => [
            'label' => 'Sähköposti',
            'type' => 'filter',
            'rule' => FILTER_VALIDATE_EMAIL,
            'message' => 'Virheellinen sähköpostiosoite',
        ],
    ];

    $validated = validateInput($patterns, $input_data);

    if (!$validated['isValid']) {
        $errors = $validated['errors'];
    } else {
        // Check if the username is unique
        $stmt = $conn->prepare('SELECT id FROM users WHERE username = ? AND id != ?');
        $stmt->bind_param("si", $username, $userId);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = 'Käyttäjätunnus on jo käytössä!';
        }

        $stmt->close();
    }

    // Update user profile data
    if (empty($errors)) {
        $stmt = $conn->prepare('UPDATE users SET name = ?, username = ?, email = ? WHERE id = ?');
        $stmt->bind_param("sssi", $name, $username, $email, $userId);

        if ($stmt->execute()) {
            $_SESSION['username'] = $username;

            $user['name'] = $name;
            $user['username'] = $username;
            $user['email'] = $email;

            $success = true;
        } else {
            $errors[] = 'Profiilin päivittäminen epäonnistui';
        }

        $stmt->close();
    }
}

// POST processing - change password

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change-password'])) {
    $errors = [];
    $success = false;

    $input_data = [
        'currentPassword' => $_POST['current-password'] ?? '',
        'newPassword' => $_POST['new-password'] ?? '',
        'confirmPassword' => $_POST['confirm-password'] ?? '',
    ];

    $patterns = [
        'password' => [
            'label' => 'Salasana',
            'type' => 'regex',
            'rule' => '/^(?=.*[a-zA-Z])(?=.*\d).{8,}$/',
            'message' => 'Salasanan tulee olla vähintään 8 merkkiä pitkä ja sisältää ainakin yksi kirjain ja yksi numero',
        ],
    ];

    // Check current password
    $stmt = $conn->prepare('SELECT password FROM users WHERE id = ?');
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $result = $stmt->get_result();
    $userPassword = $result->fetch_assoc();

    $stmt->close();

    if (!$userPassword || !password_verify($input_data['currentPassword'], $userPassword['password'])) {
        $errors[] = 'Nykyinen salasana on virheellinen.';
    }

    // Validate new password
    $validated = validateInput(
        $patterns,
        [
            'password' => $input_data['newPassword'],
            ]
        );

    if (!$validated['isValid']) {
        $errors = array_merge($errors, $validated['errors']);
    }

    // Check password match
    if ($input_data['newPassword'] !== $input_data['confirmPassword']) {
        $errors[] = 'Salasanat eivät täsmää';
    }

    // Update new password
    if (empty($errors)) {
        $hashedPassword = password_hash($input_data['newPassword'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
        $stmt->bind_param("si", $hashedPassword, $userId);

        if ($stmt->execute()) {
            $success = true;
        } else {
            $errors[] = 'Salasanan vaihtaminen epäonnistui';
        }

        $stmt->close();
    }
}

// Get current user data

$stmt = $conn->prepare('SELECT name, username, email FROM users WHERE id = ?');
$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();

// Post list of current user

$stmt = $conn->prepare('
    SELECT
        posts.id,
        posts.content,
        posts.user_id,
        posts.created_at,
        users.username AS author
    FROM posts
    INNER JOIN users ON posts.user_id = users.id
    WHERE posts.user_id = ?
    ORDER BY posts.created_at DESC
');
$stmt->bind_param("i", $userId);
$stmt->execute();

$postsResult = $stmt->get_result();

$stmt->close();
?>

<section class="post-form">
    <h2 class="section-title">Oma profiili</h2>

    <?php if ($user): ?>
        <p>Nimi: <?= htmlspecialchars($user['name']) ?></p>
        <p>Käyttäjätunnus: <?= htmlspecialchars($user['username']) ?></p>
        <p>Sähköposti: <?= htmlspecialchars($user['email']) ?></p>
    <?php else: ?>
        <p>Käyttäjää ei löytynyt.</p>
    <?php endif; ?>

    <h3 class="section-title">Muokkaa profiilia</h3>

    <?php if ($success && isset($_POST['update-profile'])): ?>
        <div class="alert alert-success">
            <p>Profiilin tiedot päivitetty onnistuneesti</p>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors) && isset($_POST['update-profile'])): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $error_msg): ?>
                <p><i class="fa-solid fa-circle-info"></i> <?= htmlspecialchars($error_msg) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="form-wrapper">
        <form action="<?= htmlspecialchars($_SERVER['SCRIPT_NAME']) ?>?page=profile" method="POST" name="update_profile">
            <div class="form-field">
                <label class="form-label" for="profile-name">Nimi</label>
                <input
                    class="form-input"
                    type="text"
                    id="profile-name"
                    name="name"
                    value="<?= htmlspecialchars($user['name']) ?>"
                    required>
            </div>

            <div class="form-field">
                <label class="form-label" for="profile-username">Käyttäjätunnus</label>
                <input
                    class="form-input"
                    type="text"
                    id="profile-username"
                    name="username"
                    value="<?= htmlspecialchars($user['username']) ?>"
                    required>
            </div>

            <div class="form-field">
                <label class="form-label" for="profile-email">Sähköposti</label>
                <input
                    class="form-input"
                    type="email"
                    id="profile-email"
                    name="email"
                    value="<?= htmlspecialchars($user['email']) ?>"
                    required>
            </div>

            <button class="form-button" type="submit" name="update-profile">
                Tallenna muutokset
            </button>
        </form>
    </div>

    <h3 class="section-title">Vaihda salasana</h3>

    <?php if ($success && isset($_POST['change-password'])): ?>
        <div class="alert alert-success">
            <p>Salasana vaihdettu onnistuneesti</p>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors) && isset($_POST['change-password'])): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $error_msg): ?>
                <p><i class="fa-solid fa-circle-info"></i> <?= htmlspecialchars($error_msg) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="form-wrapper">
        <form action="<?= htmlspecialchars($_SERVER['SCRIPT_NAME']) ?>?page=profile" method="POST" name="change_password">
            <div class="form-field">
                <label class="form-label" for="current-password">Nykyinen salasana</label>
                <input
                    class="form-input"
                    type="password"
                    id="current-password"
                    name="current-password"
                    required>
            </div>

            <div class="form-field">
                <label class="form-label" for="new-password">Uusi salasana</label>
                <input
                    class="form-input"
                    type="password"
                    id="new-password"
                    name="new-password"
                    required>
            </div>

            <div class="form-field">
                <label class="form-label" for="confirm-password">Vahvista uusi salasana</label>
                <input
                    class="form-input"
                    type="password"
                    id="confirm-password"
                    name="confirm-password"
                    required>
            </div>

            <button class="form-button" type="submit" name="change-password">
                Vaihda salasana
            </button>
        </form>
    </div>

    <h3 class="section-title">Omat julkaisut</h3>

    <div class="card-list">
        <?php if ($postsResult->num_rows > 0): ?>
            <?php while ($row = $postsResult->fetch_assoc()): ?>
                <?php include BASE_PATH . '/includes/card.php'; ?>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Et ole vielä julkaissut mitään.</p>
        <?php endif; ?>
    </div>
</section>