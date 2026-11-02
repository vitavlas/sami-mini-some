<?php
// Check does user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? (int) $_SESSION['user_id'] : 0;

// Response data preparation
$success = false;
$canEdit = false;

$errors = [];
$row = null;

$postId = 0;
$postContent = '';

// Process request
if (!$isLoggedIn) {
    $errors[] = 'Kirjaudu sisään muokataksesi julkaisua.';
} else {
    $requestId = $_SERVER['REQUEST_METHOD'] === 'POST'
        ? ($_POST['post-id'] ?? '')
        : ($_GET['post-id'] ?? '');

    $postId = (int) $requestId;

    // Fetch post data for current user
    $stmt = $conn->prepare(
        'SELECT id, author, content FROM posts WHERE id = ? AND user_id = ?'
    );

    $stmt->bind_param(
        "ii",
        $postId,
        $userId
    );

    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    // Check does user have rights to edit the post
    $canEdit = ($row !== null);

    if (!$canEdit) {
        $errors[] = 'Julkaisua ei löytynyt tai sinulla ei ole oikeutta muokata sitä.';
    }
}

// POST processing
if ($isLoggedIn && $canEdit && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form input
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

    $errors = array_merge($errors, $validated['errors']);

    if (empty($errors)) {
        $postContent = $validated['data']['post'];

        $stmt = $conn->prepare('UPDATE posts SET content = ? WHERE id = ? AND user_id = ?');

        $stmt->bind_param(
            "sii",
            $postContent,
            $postId,
            $userId
        );

        if ($stmt->execute()) {
            $success = true;
            $row['content'] = $postContent;
        } else {
            $errors[] = 'Julkaisun päivittäminen epäonnistui. Yritä uudelleen.';
        }

        $stmt->close();
    }


    // Keep submitted content if validation fails
    if (!empty($errors)) {
        $row['content'] = $_POST['post-message'];
    }
}
?>


<section class="post-form">
    <h2 class="section-title">Julkaisun Muokkaus</h2>

     <?php if ($success): ?>
        <div class="alert alert-success">
            <p>Julkaisu päivitetty!</p>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>

    <div class="alert alert-error">
        <?php foreach ($errors as $error_msg): ?>
        <p><i class="fa-solid fa-circle-info"></i> <?= htmlspecialchars($error_msg) ?></p>
        <?php endforeach; ?>
    </div>

    <?php endif; ?>

    <?php if ($canEdit && $row !== null): ?>

    <!-- Form -->
    <div class="form-wrapper">
        <form action="<?= htmlspecialchars($_SERVER['SCRIPT_NAME']) ?>?page=update-post&post_id=<?= $postId ?>" method="POST" name="update_post">
            <div class="form-field">
                <label class="form-label" for="post-author" >Nimimerkki</label>
                <input 
                    class="form-input" type="text" id="post-author" name="post-author" 
                    value="<?= htmlspecialchars($row['author']) ?>"
                    disabled
                >
            </div>

            <div class="form-field">
                <label class="form-label" for="post-message" >Viesti</label>
                <textarea class="form-textarea" id="post-message" name="post-message"
                ><?= htmlspecialchars($row['content']) ?></textarea>
            </div>

            <input type="hidden" name="post-id" value="<?= $postId ?>">

            <button class="form-button" type="submit" >Päivitä</button>
        </form>
    </div>
    <?php endif; ?>

</section>