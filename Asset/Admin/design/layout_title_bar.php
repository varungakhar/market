<!-- Title Bar -->
<h4 class="sr-only"><?php echo $skinWorkName; ?> (<?php echo $currentWorkSkin; ?>)</h4>
<?php
if (isset($designInfo) === true) {
    if ($designInfo['dir']['text'] !== 'default') {
        if (empty($designInfo['dir']['text']) === false) {
            echo $designInfo['dir']['text'];
        } else {
            echo $designInfo['dir']['name'];
        }
        ?>
        <span class="ui-icon ui-icon-carat-1-e"></span>
        <?php
        if (empty($designInfo['file']['text']) === false) {
            echo $designInfo['file']['text'];
        } else {
            echo $designInfo['file']['name'];
        }
        ?>
        <span class="text-muted" title="<?php echo gd_isset($getPageID); ?>">&nbsp; | &nbsp;<?php echo gd_isset($getPageID); ?></span>
        <?php
        if (isset($commonFuncCode) || isset($commonVarCode) || isset($designCode)) {
        ?>
            <span class="design-code-btn"><button type="button" class="btn btn-white js-layer-close bold">치환코드 열기</button></span>
        <?php
        }
    }
}
?>
<!-- //Title Bar -->
