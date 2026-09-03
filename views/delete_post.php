<section class="post-form">
    <h2 class="section-title">Julkaisun Poistaminen</h2>

<?php
/* ===== DELETE post ===== */ 

if ($_SERVER['REQUEST_METHOD'] === 'POST'):
    $post_id = (int) $_POST['post-id'];
    $query = "DELETE FROM posts WHERE id = $post_id";

    if ($result = mysqli_query($conn, $query)):
?>

    <div class="alert alert-success">
        <p>Julkaisu poistettu onnistuneesti.</p>
    </div>

    <?php else: ?>

    <div class="alert alert-error">
        <p>Julkaisun poistaminen epäonnistui. Yritä uudelleen.</p>
    </div>

    <?php endif; ?>
    <?php endif; ?>


<?php
/* ===== DISPLAY alert message ===== */ 

if ($_SERVER['REQUEST_METHOD'] === 'GET'):
// Get requested post data
$post_id = $_GET['post-id'];

$query = "SELECT id FROM posts WHERE id = $post_id";
if ($result = mysqli_query($conn, $query)):
?>

    <!-- Form -->
    <div class="form-wrapper">
        <form action="<?= htmlspecialchars($_SERVER['SCRIPT_NAME']) ?>?page=delete-post&post_id=<?= $post_id ?>" method="POST" name="delete_post">
            <p>Haluatko varmasti poistaa julkaisun?</p>

            <input type="hidden" name="post-id" value="<?= htmlspecialchars($post_id) ?>">

            <button class="form-button form-button--alert" type="submit" >Poista</button>
        </form>
    </div>

    <?php
        else:
            echo "<p>Tapahtui tuntematon virhe. Yritä uudelleen.</p>";
        endif;
    ?>

    <?php endif; ?>

</section>