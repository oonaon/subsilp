<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> <?= substr(file_get_contents('../../.git/refs/heads/master'), 0, 7) ?>
    </div>
    <strong>Copyright &copy; 2014-<?= date('Y') ?> <a href="<?= Yii::$app->homeUrl ?>">SUBSILP SV</a>.</strong> All rights
    reserved.
</footer>