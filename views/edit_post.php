<section class="post-form">
    <h2 class="section-title">Julkaisun Muokkaus</h2>

<?php
/* ===== UPDATE post data ===== */ 

if ($_SERVER['REQUEST_METHOD'] === 'POST'):
    // Validate form inputs
     $input_data = [
        'author' => $_POST['post-author'] ?: '',
        'post' => $_POST['post-message'] ?: '',
    ];

    $patterns = [
        'author' => [
            'label' => 'Nimimerkki',
            'type' => 'regex',
            'rule' => '/^[a-zA-Z0-9 \-]+$/',
            'message' => 'Kelvoton nimimerkki',
        ],
        'post' => [
            'label' => 'Viesti',
            'type' => 'regex',
            'rule' => '/^(?!\s*$).+/',
            'message' => 'Viesti ei voi olla tyhjä',
        ],
    ];

    $sanitized = sanitizeInput($input_data);
    $validated = validateInput($patterns, $sanitized);
?>

<!-- Form send status messages -->
<?php
if($validated['isValid']):
    // Update post
    $post_id = (int) $_POST['post-id'];
    $author = $validated["data"]["author"];
    $post_content = $validated["data"]["post"];

    $query = "UPDATE posts SET author = ?, content = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssi", $author, $post_content, $post_id);

    if (mysqli_stmt_execute($stmt)):
        $_POST = [];
?>

    <div class="alert alert-success">
        <p>Julkaisu päivitetty!</p>
    </div>

    <?php else: ?>

    <div class="alert alert-error">
        <p>Julkaisun päivittäminen epäonnistui. Yritä uudelleen.</p>
    </div>

    <?php endif; ?>

<?php else: ?>

    <?php if (!empty($validated['errors'])): ?>

    <div class="alert alert-error">
        <?php foreach ($validated['errors'] as $error_msg): ?>
        <p><i class="fa-solid fa-circle-info"></i> <?= htmlspecialchars($error_msg) ?></p>
        <?php endforeach; ?>
    </div>

    <?php endif; ?>
    
<?php endif; ?>
<?php endif; ?>

<?php
/* ===== DISPLAY post data ===== */ 

if ($_SERVER['REQUEST_METHOD'] === 'GET'):
    // Get requested post data
    $post_id = (int) $_GET['post-id'];
    
    $query = "SELECT * FROM posts WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $post_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)):
?>

<!-- Form -->
<div class="form-wrapper">
    <form action="<?= htmlspecialchars($_SERVER['SCRIPT_NAME']) ?>?page=update-post&post_id=<?= $post_id ?>" method="POST" name="update_post">
        <div class="form-field">
            <label class="form-label" for="post-author" >Nimimerkki</label>
            <input 
                class="form-input" type="text" id="post-author" name="post-author" 
                value="<?= htmlspecialchars($row['author']) ?>"
            >
        </div>

        <div class="form-field">
            <label class="form-label" for="post-message" >Viesti</label>
            <textarea class="form-textarea" id="post-message" name="post-message"
            ><?= htmlspecialchars($row['content']) ?></textarea>
        </div>

        <input type="hidden" name="post-id" value="<?= htmlspecialchars($row['id']) ?>">

        <button class="form-button" type="submit" >Päivitä</button>
    </form>
</div>

<?php
endif;
endif;
?>

</section>