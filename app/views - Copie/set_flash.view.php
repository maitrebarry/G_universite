<?php if (isset($_SESSION['notification']['message'])): ?>
    <div class="container">
        <div class="alert <?=$_SESSION['notification']['class']?> alert-dismissible mb-2" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="d-flex align-items-center">
                <i class="<?=$_SESSION['notification']['icon']?>"></i>
                <span>
                    <?=$_SESSION['notification']['message']?>
                </span>
            </div>
        </div>
    </div>
    <?php $_SESSION['notification'] = []; ?>
<?php endif; ?>
