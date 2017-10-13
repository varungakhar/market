<style>
    .plus_review_widget {
        display: block;
        width: 100%;
    }

    .plus_review_widget_item {
        display: block;
        float: left;
        padding: 5px;
    }
</style>
<div class="table-title gd-help-manual">포토후기 게시판 위젯 미리보기 <span class="notice-info">설정된 포토후기 게시판 위젯은 아래와 같이 페이지에 삽입됩니다.</span></div>
<div class="plus_review_widget">
    <?php
    $i = 1;
    foreach ($data as $row) { ?>
        <div class="plus_review_widget_item">
            <img src="<?= $row['uploadHeadImage']['thumSquareSrc'] ?>" style="display:none">
        </div>
        <?php if ($i % $req['cols'] == 0) { ?>
            <div style="clear:both"></div>
        <?php } ?>
        <?php
        $i++;
    } ?>
    <div style="clear:both"></div>
</div>

<div class="table-title gd-help-manual" style="margin-top:10px">포토후기 게시판 위젯 소스코드
    <span class="notice-info">하단의 소소를 복사하여 쇼핑몰에 삽입해주세요.</span></div>
<div>
    <code><?= $code ?></code>
</div>
<div style="padding-top:20px;text-align: center">
    <button type="button" data-clipboard-text="<?= $code ?>" class="js-btn-copy btn btn-red" title="위젯 소스코드">소스복사</button>
</div>

<script>
    var thumSizeType = '<?=$req['thumSizeType']?>';
    $(document).ready(function () {
        setTimeout(function () {
            if (thumSizeType == 'menual') {
                $('.plus_review_widget_item img').css('width', '<?=$req['thumWidth']?>px').css('height', '<?=$req['thumWidth']?>px').show();
            }
            else {
                var containerWidth = ($('.plus_review_widget').width());
                var autoSize = Math.floor(containerWidth /<?=$req['cols']?>) - 10;
                $('.plus_review_widget_item').css('width', autoSize + 'px').css('height', autoSize + 'px');
                $('.plus_review_widget_item img').css('width', '100%').css('height', '100%');
                $('.plus_review_widget_item img').show();
            }
        }, 1);

        var agent = navigator.userAgent.toLowerCase();
        if ((navigator.appName == 'Netscape' && agent.indexOf('trident') != -1) || (agent.indexOf("msie") != -1)) {
            $('.js-btn-copy').bind('click', function () {
                code = $(this).data('clipboard-text');
                window.clipboardData.setData('Text', code);
                alert('[위젯 소스코드] 정보를 클립보드에 복사했습니다. <code>Ctrl+V</code>를 이용해서 사용하세요.');
            })
        }
        else {
            // https://clipboardjs.com
            var clipboard = new Clipboard('.js-btn-copy');
            clipboard.on('success', function (e){
                var title = $(e.trigger).attr('title') == undefined ? '' : $(e.trigger).attr('title');
                alert('[' + title + '] 정보를 클립보드에 복사했습니다.\n<code>Ctrl+V</code>를 이용해서 사용하세요.');
                e.clearSelection();
            });
            clipboard.on('error', function (e) {
                console.error('Action:', e.action);
                console.error('Trigger:', e.trigger);
            });
        }
    });
</script>
