<?php
// Check does user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? (int) $_SESSION['user_id'] : 0;

// Response data preparation
$success = false;
$canDelete = false;

$errors = [];
$postId = 0;

// Process request
if (!$isLoggedIn) {
    $errors[] = 'Kirjaudu sisään poistaaksesi julkaisun.';
} else {
    $requestId = $_SERVER['REQUEST_METHOD'] === 'POST'
        ? ($_POST['post-id'] ?? '')
        : ($_GET['post-id'] ?? '');

    $postId = (int) $requestId;

    // Check ownership
    $stmt = $conn->prepare(
        'SELECT id FROM posts WHERE id = ? AND user_id = ?'
    );

    $stmt->bind_param(
        "ii",
        $postId,
        $userId
    );

    $stmt->execute();
    $result = $stmt->get_result();

    $canDelete = $result->num_rows > 0;

    $stmt->close();

    if (!$canDelete) {
        $errors[] = 'Julkaisua ei löytynyt tai sinulla ei ole oikeutta poistaa sitä.';
    }
}

// Delete only after ownership check
if ($isLoggedIn && $canDelete && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare(
        'DELETE FROM posts WHERE id = ? AND user_id = ?'
    );

    $stmt->bind_param(
        "ii",
        $postId,
        $userId
    );

    if ($stmt->execute()) {
        $success = $stmt->affected_rows === 1;

        if (!$success) {
            $errors[] = 'Julkaisua ei poistettu.';
        }
    } else {
        $errors[] = 'Julkaisun poistaminen epäonnistui. Yritä uudelleen.';
    }

    $stmt->close();
    $canDelete = false;
}
?>


<section class="post-form">
    <h2 class="section-title">Julkaisun Poistaminen</h2>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <p>Julkaisu poistettu onnistuneesti.</p>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>

        <div class="alert alert-error">
            <?php foreach ($errors as $error_msg): ?>
                <p><i class="fa-solid fa-circle-info"></i> <?= htmlspecialchars($error_msg) ?></p>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

    <?php if ($canDelete): ?>

    <!-- Form -->
    <div class="form-wrapper">
        <form action="<?= htmlspecialchars($_SERVER['SCRIPT_NAME']) ?>?page=delete-post&post_id=<?= $postId ?>" method="POST" name="delete_post">
            <p>Haluatko varmasti poistaa julkaisun?</p>

            <input type="hidden" name="post-id" value="<?= $postId ?>">

            <button class="form-button form-button--alert" type="submit">Poista</button>
        </form>
    </div>

    <?php endif; ?>

</section>