<?php
if (isset($_SESSION['message'])) :
?>
    <div class="alert-background" id="floatingAlert">
        <span class="close-message close-floatingAlert">&times;</span>

        <div class="notice">
            <strong>Notice!</strong>
        </div>
        <div class="alert alert-warning fade show" role="alert" > 

            <div class="message">
                <span><?= $_SESSION['message']; ?></span>
            </div>

            <div id="message-footer">
                <button type="button" class="btn btn-close-footer close-floatingAlert">Close</button>
            </div>
        </div>
    </div>
<?php
    unset($_SESSION['message']);
endif;
?>