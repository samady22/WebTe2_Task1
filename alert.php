<?php
if (isset($_SESSION['msg'])) :
?>
    <!-- here is our notifion for our action which display when an action accure -->
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['msg']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

<?php
    unset($_SESSION['msg']);
endif;
?>