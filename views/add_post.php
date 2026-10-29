<?php
// Check does user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';

$success = false;
$postContent = '';

if ($isLoggedIn && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form inputs
    $input_data = [
        'post' => $_POST['post-message'] ?: '',
    ];

    $patterns = [
        'post' => [
            'label' => 'Viesti',
            'type' => 'regex',
            'rule' => '/^(?!\s*$).+/',
            'message' => 'Viesti ei voi olla tyhjä',
        ],
    ];

    $sanitized = sanitizeInput($input_data);
    $validated = validateInput($patterns, $sanitized);

    if ($validated['isValid']) {
        // Save new post to the DB
        $author = $_SESSION["username"];
        $user_id = (int) $_SESSION['user_id'];
        $post_content = $validated["data"]["post"];

        $stmt = $conn->prepare(
            'INSERT INTO posts (author, content, user_id) VALUES (?, ?, ?)'
        );

        $stmt->bind_param(
            "ssi",
            $author,
            $post_content,
            $user_id
        );

        if ($stmt->execute()) {
            $success = true;
            $postContent = '';
        } else {
            $validated['errors'][] = "Julkaisun tallennus epäonnistui. Yritä uudelleen.";
            $postContent = $post_content;
        }

        $stmt->close();
    } else {
        $postContent = $input_data['post'];
    }
}
?>


<section class="post-form">
    <h2 class="section-title">Luo Uusi Julkaisu</h2>

    <?php if (!$isLoggedIn): ?>

        <div class="alert alert-error">
            <p>Kirjaudu sisään julkaistaksesi.</p>
        </div>

        <p>
            <a href="index.php?page=login">Kirjaudu sisään</a>
        </p>

    <?php else: ?>

        <?php if (!empty($validated['errors'])): ?>
            <div class="alert alert-error">
                <?php foreach ($validated['errors'] as $error_msg): ?>
                    <p><i class="fa-solid fa-circle-info"></i> <?= htmlspecialchars($error_msg) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <p>Julkaisu tallennettu onnistuneesti.</p>
            </div>
        <?php endif; ?>



        <!-- Form -->
        <div class="form-wrapper">
            <form action="<?= htmlspecialchars($_SERVER['SCRIPT_NAME']) ?>?page=new-post" method="POST" name="add_post">
                <div class="form-field">
                    <label class="form-label" for="post-author">Nimimerkki</label>
                    <input
                        class="form-input" type="text" id="post-author" name="post-author"
                        value="<?= htmlspecialchars($username) ?>" disabled>
                </div>

                <div class="form-field">
                    <label class="form-label" for="post-message">Viesti</label>
                    <textarea class="form-textarea" id="post-message" name="post-message" placeholder="Julkaisun sisältö..."><?= htmlspecialchars($postContent) ?></textarea>
                </div>

                <button class="form-button" type="submit">Julkaise</button>
            </form>
        </div>

    <?php endif; ?>

</section>