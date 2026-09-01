<section class="post-form">

<?php
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
    // Save new post to the DB
    $author = $validated["data"]["author"];
    $post_content = $validated["data"]["post"];
    $query = "INSERT INTO posts (author, content) VALUES ('$author', '$post_content')";

    if ($result = mysqli_query($conn, $query)):
        $_POST = [];
?>

    <div class="alert alert-success">
        <p>Julkaisu tallennettu onnistuneesti.</p>
    </div>

    <?php else: ?>

    <div class="alert alert-error">
        <p>Julkaisun tallennus epäonnistui. Yritä uudelleen.</p>
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

<!-- Form -->
    <div class="form-wrapper">
        <form action="<?= htmlspecialchars($_SERVER['SCRIPT_NAME']) ?>?page=new-post" method="POST" name="add_post">
            <div class="form-field">
                <label class="form-label" for="post-author" >Nimimerkki</label>
                <input 
                    class="form-input" type="text" id="post-author" name="post-author" 
                    value="<?= htmlspecialchars($_POST['post-author'] ?? '') ?>" placeholder="Juzu Kvanttinen"
                >
            </div>
    
            <div class="form-field">
                <label class="form-label" for="post-message" >Viesti</label>
                <textarea class="form-textarea" id="post-message" name="post-message" placeholder="Julkaisun sisältö..."
                ><?= htmlspecialchars($_POST['post-message'] ?? '') ?></textarea>
            </div>
    
            <button class="form-button" type="submit" >Julkaise</button>
        </form>
    </div>

</section>