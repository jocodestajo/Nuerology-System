<?php
if (isset($_SESSION['message'])) :
?>
    <div class="alert-background">
        <div class="alert alert-warning fade show" role="alert"  id="floatingAlert"> 
            <div class="notice">
                <strong>Notice!</strong>
                <span class="close">&times;</span>
            </div>    
            
            <div class="message">
                <span><?= $_SESSION['message']; ?></span>
            </div>

            <div id="message-footer">
                <button type="button" class="btn-close">Close</button>
            </div>
        </div>
    </div>
<?php
    unset($_SESSION['message']);
endif;
?>