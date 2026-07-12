
<?php

declare(strict_types=1);

if (!defined('APP_NAME')) {
    exit('Direct access not allowed.');
}
?>

    </main>

    <footer class="footer">

        <div class="container">

            <p>
                &copy; <?= date('Y') ?>
                <?= e(APP_NAME) ?>.
                All Rights Reserved.
            </p>

        </div>

    </footer>

</div>

<script src="<?= APP_URL ?>/assets/js/app.js"></script>

</body>
</html>
