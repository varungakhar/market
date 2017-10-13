<script type="text/javascript">
    <!--
    $(document).ready(function () {
        $("#frmGoods").validate({
            submitHandler: function (form) {
                if (!$('input[name="range[]"]:checked').length) {
                    alert('상품범위를 선택하세요.');
                    return false;
                }
                if (!$('input[name="displayField[]"]:checked').length) {
                    alert('노출항목을 선택하세요.');
                    return false;
                }
                if ($('input[name="iconFl"]:checked').val() == 'n' && ((!$('input[name="iconFile"]').val() && !$('input[name="iconFileName"]').val()) || ($('input[name="iconFileName"]').val() && !$('input[name="iconFile"]').val() && $('input[name="delIcon"]').prop('checked') == true))) {
                    alert('사용자 아이콘 이미지를 등록하세요.');
                    return false;
                }

                form.target = 'ifrmProcess';
                form.submit();
            }
        });

        $('input[name="iconFile"]').click(function(){
            $('input[name="iconFl"][value="n"]').prop('checked', true);
        });

        setInterval(function(){
            pushPoint();
        },600);

        view_stock_table('<?php echo $data['stockFl']; ?>');
    });

    var view_stock_table = function(value){
        switch (value) {
            case 'y':
                $('.stock-table').removeClass('display-none');
                break;
            case 'n':
                $('.stock-table').addClass('display-none');
                break;
        }
    };

    function pushPoint() {
        if($('.bandpush_point').hasClass('on')) {
            $('.bandpush_point').removeClass('on');
        }else{
            $('.bandpush_point').addClass('on');
        }
    }
    //-->
</script>
<form id="frmGoods" name="frmGoods" action="./goods_ps.php" method="post" enctype="multipart/form-data" >
    <input type="hidden" name="mode" value="bandwagon_push"/>
    <div class="page-header js-affix">
        <h3><?=end($naviMenu->location); ?></h3>
        <div class="btn-group">
            <input type="submit" value="저장" class="btn btn-red"/>
        </div>
    </div>

    <div class="mgt10 design-notice-box mgb10">
        밴드왜건 푸시란?<br />
        현재 나의 쇼핑몰에 있는 고객이 관심을 가지고 있는 상품에 대해 타인의 구매 상품정보를 제공하면서<br />
        타겟화된 소비심리를 자극하여 구매력을 높일 수 있는 개인화 알림 서비스입니다.
    </div>

    <div class="table-title">
        기본 설정
    </div>
    <input type="hidden" id="depth-toggle-hidden-defaultConfig" value="<?=$toggle['defaultConfig_'.$SessScmNo]?>">
    <div id="depth-toggle-line-defaultConfig" class="depth-toggle-line display-none"></div>
    <div id="depth-toggle-layer-defaultConfig">
        <table class="table table-cols">
            <colgroup>
                <col class="width-md"/>
                <col/>
            </colgroup>
            <tr>
                <th>상품범위</th>
                <td>
                    <?php foreach ($range as $key => $value) { ?>
                        <label class="checkbox-inline"><input type="checkbox" name="range[]" value="<?php echo $key?>" <?php if(in_array($key,array_values($data['range']))) { echo "checked"; } ?> /><?php echo $value?></label>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <th>노출항목 설정</th>
                <td>
                    <?php foreach ($field as $key => $value) { ?>
                        <label class="checkbox-inline"><input type="checkbox" name="displayField[]" value="<?php echo $key?>" <?php if(in_array($key,array_values($data['displayField']))) { echo "checked"; } ?> /><?php echo $value?></label>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <th>수집기간 선택</th>
                <td>
                    <div class="form-inline">
                        상품범위 기준으로 <?=gd_select_box('term', 'term', $term, null, $data['term'], null); ?>동안의 '<b>상품 구매 데이터</b>'를 수집하여 출력함
                    </div>
                </td>
            </tr>
            <tr>
                <th>품절상품 노출</th>
                <td>
                    <label class="radio-inline"><input type="radio" name="soldOutFl" value="y" <?=gd_isset($checked['soldOutFl']['y']);?> />노출함</label>
                    <label class="radio-inline"><input type="radio" name="soldOutFl" value="n" <?=gd_isset($checked['soldOutFl']['n']);?> />노출안함</label>
                </td>
            </tr>
            <tr>
                <th>노출위치 선택</th>
                <td>
                    <div  class="pd10" style="float:left">
                        <img src="<?=PATH_ADMIN_GD_SHARE?>img/bandwagon_right.png"><br/>
                        <label class="radio-inline"><input type="radio" name="position" value="right" <?=gd_isset($checked['position']['right']);?> />우측</label>
                    </div>
                    <div  class="pd10" style="float:left">
                        <img src="<?=PATH_ADMIN_GD_SHARE?>img/bandwagon_left.png"><br/>
                        <label class="radio-inline"><input type="radio" name="position" value="left" <?=gd_isset($checked['position']['left']);?> />좌측</label>
                    </div>
                    <div  class="pd10" style="float:left">
                        <img src="<?=PATH_ADMIN_GD_SHARE?>img/bandwagon_center.png"><br/>
                        <label class="radio-inline"><input type="radio" name="position" value="center" <?=gd_isset($checked['position']['center']);?> />중간</label>
                    </div>
                    <div class="notice-info" style="clear:both">모바일쇼핑몰의 경우 선택된 노출위치와 상관없이 가로 100% 사이즈로 출력됩니다.</div>
                </td>
            </tr>
            <tr>
                <th>배경 색상 선택</th>
                <td>
                    <input type="text" name="background" value="<?php echo $data['background']; ?>" readonly class="form-control width-xs center color-selector"/>
                </td>
            </tr>
            <tr>
                <th>텍스트 색상 선택</th>
                <td>
                    <input type="text" name="color" value="<?php echo $data['color']; ?>" readonly class="form-control width-xs center color-selector"/>
                </td>
            </tr>
            <tr>
                <th>품절임박 아이콘</th>
                <td>
                    <div class="form-inline">
                        <label class="radio-inline"><input type="radio" name="stockFl" value="y" <?=gd_isset($checked['stockFl']['y']);?> onclick="view_stock_table('y')" />노출함</label>
                        <label class="radio-inline"><input type="radio" name="stockFl" value="n" <?=gd_isset($checked['stockFl']['n']);?> onclick="view_stock_table('n')" />노출안함</label>
                    </div>
                    <table class="table table-cols mgt10 stock-table display-none">
                        <colgroup>
                            <col class="width-md"/>
                            <col/>
                        </colgroup>
                        <tr>
                            <th>상품개수 선택</th>
                            <td>
                                <div class="form-inline">
                                    상품(옵션)의 재고가 <?=gd_select_box('stock', 'stock', array_combine($rank = range(1, 10), $rank), null, $data['stock'], null); ?>개 미만인 경우 품절임박 아이콘 노출함
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>아이콘 이미지</th>
                            <td>
                                <div class="form-inline">
                                    <label class="radio-inline"><input type="radio" name="iconFl" value="d" <?=gd_isset($checked['iconFl']['d']);?> />기본 아이콘</label> <span class="bandpush_point">품절임박</span>
                                    <label class="radio-inline"><input type="radio" name="iconFl" value="n" <?=gd_isset($checked['iconFl']['n']);?> />사용자 아이콘</label>
                                    <input type="file" name="iconFile" value="" class="form-control width60p"/>
                                    <?php if (empty($data['iconFile']) === false) { ?>
                                        <img src="<?php echo $imagePath . '/' . $data['iconFile']; ?>">
                                        <label>
                                            <input type="hidden" name="iconFileName" value="<?php echo $data['iconFile']; ?>">
                                            <input type="checkbox" name="delIcon" value="y"> 삭제
                                        </label>
                                    <?php } ?>
                                </div>
                                <div class="notice-info">아이콘 이미지 사이즈는 작게 해서 올려 주세요. 해당 이미지 크기 그대로 출력이 됩니다.</div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</form>

<style>
    .design-notice-box {width:100%}
    .bandpush_point {display: inline-block;width: 48px;height: 18px;line-height: 18px;font-size: 10px; color: #fff;opacity: 1;-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";text-align: center;background-color: #fa2828;font-weight: normal;margin: 0 0 0 5px;padding: 0 2px 0 2px;}
    .bandpush_point.on{background-color: #D53430;}
</style>
