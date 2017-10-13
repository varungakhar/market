<?php
//펼침,닫힘 정보
$toggle = gd_policy('display.toggle');
$SessScmNo = Session::get('manager.scmNo');
?>
<script type="text/javascript">
    <!--
    $(document).ready(function () {
        initDepthToggle(<?=$SessScmNo?>);//4depth 메뉴 보임안보임처리
        mobile_same();
        set_type();
        set_soldOutDisplay('front');
        set_soldOutDisplay('mobile');

        $("#frmGoods").validate({
            submitHandler: function (form) {
                if (!$('input[name="displayField[]"]:checked').length) {
                    alert('노출항목을 선택하세요.');
                    return false;
                }
                if ($('input[name="same"]').prop('checked') === false && !$('input[name="mobileDisplayField[]"]:checked').length) {
                    alert('노출항목을 선택하세요.');
                    return false;
                }

                form.target = 'ifrmProcess';
                form.submit();
            }
        });
    });

    var mobile_same = function() {
        var checked = $('input[name="same"]').prop('checked');

        if (checked === true) {
            $('.mobile-config-area').hide();
        } else {
            $('.mobile-config-area').show();
        }
    };

    var set_soldOutDisplay = function(key) {
        var name = key == 'mobile' ? 'mobileSoldOut' : 'soldOut';
        var soldOutFl = $('input[name="' + name + 'Fl"]:checked').val();

        if(soldOutFl =='n') $('input[name="' + name + 'DisplayFl"]').attr('disabled',true);
        else $('input[name="' + name + 'DisplayFl"]').attr('disabled',false);

    };

    var set_type = function() {
        var type = $('input[name="type"]:checked').val();
        var msg = '상품 판매 데이터';

        switch (type) {
            case 'sell':
                break;
            case 'hit':
                msg = '상품 클릭수 데이터';
                break;
        }

        $('.msg-type').html(msg);
    };
    //-->
</script>
<form id="frmGoods" name="frmGoods" action="./goods_ps.php" method="post">
    <input type="hidden" name="mode" value="populate"/>
    <div class="page-header js-affix">
        <h3><?=end($naviMenu->location); ?></h3>
        <div class="btn-group">
            <input type="submit" value="저장" class="btn btn-red"/>
        </div>
    </div>

    <div class="table-title gd-help-manual">
        기본 설정
        <span class="depth-toggle"><button type="button" class="btn btn-sm btn-link bold depth-toggle-button" depth-name="defaultConfig"><span>닫힘</span></button></span>
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
                <th>순위타입 선택</th>
                <td>
                    <label class="radio-inline"><input type="radio" name="type" value="sell" <?=gd_isset($checked['type']['sell']);?> onclick="set_type();" />상품 판매 순위</label>
                    <label class="radio-inline"><input type="radio" name="type" value="hit" <?=gd_isset($checked['type']['hit']);?> onclick="set_type();" />상품 클릭수 순위</label>
                </td>
            </tr>
            <tr>
                <th>노출순위 선택</th>
                <td>
                    <div class="form-inline">
                        1위 ~ <?=gd_select_box('rank', 'rank', array_combine($rank = range(1, 100), $rank), null, $data['rank'], null); ?>
                    </div>
                </td>
            </tr>
            <tr>
                <th>갱신주기 선택</th>
                <td>
                    <div class="form-inline">
                        등록 시점을 기준으로 <?=gd_select_box('renewal', 'renewal', $renewal, null, $data['renewal'], null); ?>마다 순위를 갱신함
                    </div>
                </td>
            </tr>
            <tr>
                <th>수집기간 선택</th>
                <td>
                    <div class="form-inline">
                        갱신 시점을 기준으로 <?=gd_select_box('collect', 'collect', $collect, null, $data['collect'], null); ?>동안의 `<b><span class="msg-type">상품 판매 데이터</span></b>`를 수집하여 순위를 결정함
                    </div>
                </td>
            </tr>
            <tr>
                <th>템플릿 형태</th>
                <td>
                    <div class="form-inline">
                        <div  class="pd10" style="float:left">
                            <img src="<?=PATH_ADMIN_GD_SHARE?>img/open_type.png"><br/>
                            <label class="radio-inline mgt5"><input type="radio" name="template" value="01" <?=gd_isset($checked['template']['01']);?> />펼침형</label>
                        </div>
                        <div  class="pd10" style="float:left">
                            <img src="<?=PATH_ADMIN_GD_SHARE?>img/hover_type.png"><br/>
                            <label class="radio-inline mgt5"><input type="radio" name="template" value="02" <?=gd_isset($checked['template']['02']);?> />롤오버형</label>
                        </div>
                        <div class="notice-info" style="clear:both">
                            펼침형 : 타이틀 부분과 순위부분이 펼쳐진 상태로 고정되어 노출됩니다.<br />
                            롤오버형 : 타이틀 부분은 순위가 자동으로 돌아가면서 보여지며 타이틀에 마우스오버시 순위부분이 펼쳐보여집니다.
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>


    <div class="table-title gd-help-manual">
        PC 인기상품 페이지 설정
        <span class="depth-toggle"><button type="button" class="btn btn-sm btn-link bold depth-toggle-button" depth-name="frontConfig"><span>닫힘</span></button></span>
    </div>
    <input type="hidden" id="depth-toggle-hidden-frontConfig" value="<?=$toggle['frontConfig_'.$SessScmNo]?>">
    <div id="depth-toggle-line-frontConfig" class="depth-toggle-line display-none"></div>
    <div id="depth-toggle-layer-frontConfig">
        <table class="table table-cols">
            <colgroup>
                <col class="width-md"/>
                <col/>
                <col class="width-md"/>
                <col/>
            </colgroup>
            <tr>
                <th>적용범위</th>
                <td colspan="3">
                    <label class="checkbox-inline"><input type="checkbox" name="same" value="y" <?=gd_isset($checked['same']['y']);?> onclick="mobile_same();" />모바일 쇼핑몰 동일 적용</label>
                </td>
            </tr>
            <tr>
                <th>사용여부</th>
                <td colspan="3">
                    <label class="radio-inline"><input type="radio" name="useFl" value="y" <?=gd_isset($checked['useFl']['y']);?> />사용함</label>
                    <label class="radio-inline"><input type="radio" name="useFl" value="n" <?=gd_isset($checked['useFl']['n']);?> />사용안함</label>
                    <div class="notice-info">
                        인기상품의 상품 리스트페이지 사용여부를 설정할 수 있으며, "사용함" 설정시 [더보기] 버튼이 출력됩니다.<br />
                        버튼 클릭시 상품 리스트페이지로 이동되어 최대 100위까지의 인기상품을 확인할 수 있습니다.
                    </div>
                    <div class="notice-info">
                        쇼핑몰 도메인 끝에 "/goods/populate.php"를 입력하면 인기상품 템플릿을 노출하지 않아도 리스트페이지로 접근이 가능합니다.
                        <br />
                        예시) http://쇼핑몰 도메인/goods/populate.php
                        <a href="<?=URI_HOME?>goods/populate.php" target="_blank" class="text-info btn-link">[PC쇼핑몰 화면보기]</a>,
                        <a href="<?=URI_MOBILE?>goods/populate.php" target="_blank" class="text-info btn-link"> [모바일쇼핑몰 화면보기] </a>
                    </div>
                </td>
            </tr>
            <tr>
                <th>이미지설정</th>
                <td colspan="3">
                    <div class="form-inline">
                        <?php echo gd_select_box('image', 'image', $image, null, $data['image'], null); ?>
                        이미지는 <a href="../policy/goods_images.php" target="_blank" class="text-info btn-link">[기본설정>상품 정책>상품 이미지 사이즈 설정]</a> 에서 관리할 수 있습니다.
                    </div>
                </td>
            </tr>
            <tr>
                <th>품절상품 노출</th>
                <td>
                    <label class="radio-inline"><input type="radio" name="soldOutFl" value="y" <?=gd_isset($checked['soldOutFl']['y']);?> onclick="set_soldOutDisplay('front')"/>노출함</label>
                    <label class="radio-inline"><input type="radio" name="soldOutFl" value="n" <?=gd_isset($checked['soldOutFl']['n']);?> onclick="set_soldOutDisplay('front')"/>노출안함</label>
                </td>
                <th>품절상품 진열</th>
                <td>
                    <label class="radio-inline"><input type="radio" name="soldOutDisplayFl" value="y" <?=gd_isset($checked['soldOutDisplayFl']['y']);?>/>정렬 순서대로 보여주기</label>
                    <label class="radio-inline"><input type="radio" name="soldOutDisplayFl" value="n" <?=gd_isset($checked['soldOutDisplayFl']['n']);?>/>리스트 끝으로 보내기</label>
                </td>
            </tr>
            <tr>
                <th>품절 아이콘 노출</th>
                <td>
                    <label class="radio-inline"><input type="radio" name="soldOutIconFl" value="y" <?=gd_isset($checked['soldOutIconFl']['y']);?>/>노출함</label>
                    <label class="radio-inline"><input type="radio" name="soldOutIconFl" value="n" <?=gd_isset($checked['soldOutIconFl']['n']);?>/>노출안함</label>
                </td>
                <th>아이콘노출</th>
                <td>
                    <label class="radio-inline"><input type="radio" name="iconFl" value="y" <?=gd_isset($checked['iconFl']['y']);?>/>노출함</label>
                    <label class="radio-inline"><input type="radio" name="iconFl" value="n" <?=gd_isset($checked['iconFl']['n']);?>/>노출안함</label>
                </td>
            </tr>
            <tr>
                <th>노출항목 설정</th>
                <td colspan="3">
                    <?php foreach($themeDisplayField as $k => $v) { ?>
                        <label class="checkbox-inline"  title=""><input type="checkbox" name="displayField[]" value="<?=$k?>" <?php if(in_array($k,array_values($data['displayField']))) { echo "checked"; } ?>  >  <?=$v?></label>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <th>디스플레이 유형</th>
                <td colspan="3">
                    <?php foreach($themeDisplayType as $k => $v) { ?>
                        <div  class="pd10  display_ <?=$v['class']?> <?php if($v['mobile'] != 'y') { echo ' display_pc'; }?> " style="float:left"><span>
                                            <img src="<?=PATH_ADMIN_GD_SHARE?>img/display_theme_<?=$k?>.jpg"><br/>
                                            <label class="radio-inline mgt5" title="" ><input type="radio" name="displayType" value="<?=$k?>" <?=gd_isset($checked['displayType'][$k]);?> /><?=$v['name']?></label></span></div>
                    <?php } ?>
                </td>
            </tr>

            <tr>
                <th>치환코드</th>
                <td colspan="3">
                    <?php if ($checkPathFront === true) { ?>
                        <div class="form-inline">
                            {=includeWidget('proc/_populate.html')} <button type="button" title="복사" class="btn btn-white btn-sm js-clipboard" data-clipboard-text="{=includeWidget('proc/_populate.html')}">복사</button>
                        </div>
                        <div class="notice-danger">치환코드를 복사하여 PC와 모바일 각각의 “디자인" 메뉴에서 HTML소스에 삽입해야 인기상품 정보가 쇼핑몰에 노출됩니다.</div>
                    <?php } else { ?>
                        <div class="notice-danger">
                            "디자인" 메뉴의 사용 스킨과 작업 스킨에 인기상품 관련 페이지 파일(proc/_populate.html)이 존재하여야만 정상적으로 치환코드 노출 및 사용이 기능합니다.<br />
                            인기상품 노출 기능 사용을 위한 자세한 사항은 마이고도> 패치게시판에서 확인하시기 바랍니다.
                        </div>
                    <?php } ?>
                </td>
            </tr>

        </table>
    </div>

    <div class="mobile-config-area">
        <div class="table-title gd-help-manual">
            모바일 인기상품 페이지 설정
            <span class="depth-toggle"><button type="button" class="btn btn-sm btn-link bold depth-toggle-button" depth-name="mobileConfig"><span>닫힘</span></button></span>
        </div>
        <input type="hidden" id="depth-toggle-hidden-mobileConfig" value="<?=$toggle['mobileConfig_'.$SessScmNo]?>">
        <div id="depth-toggle-line-mobileConfig" class="depth-toggle-line display-none"></div>
        <div id="depth-toggle-layer-mobileConfig">
            <table class="table table-cols">
                <colgroup>
                    <col class="width-md"/>
                    <col/>
                    <col class="width-md"/>
                    <col/>
                </colgroup>
                <tr>
                    <th>사용여부</th>
                    <td colspan="3">
                        <label class="radio-inline"><input type="radio" name="mobileUseFl" value="y" <?=gd_isset($checked['mobileUseFl']['y']);?> />사용함</label>
                        <label class="radio-inline"><input type="radio" name="mobileUseFl" value="n" <?=gd_isset($checked['mobileUseFl']['n']);?> />사용안함</label>
                        <div class="notice-info">
                            인기상품의 상품 리스트페이지 사용여부를 설정할 수 있으며, "사용함" 설정시 [더보기] 버튼이 출력됩니다.<br />
                            버튼 클릭시 상품 리스트페이지로 이동되어 최대 100위까지의 인기상품을 확인할 수 있습니다.
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>이미지설정</th>
                    <td colspan="3">
                        <div class="form-inline">
                            <?php echo gd_select_box('mobileImage', 'mobileImage', $image, null, $data['mobileImage'], null); ?>
                            이미지는 <a href="../policy/goods_images.php" target="_blank" class="text-info btn-link">[기본설정>상품 정책>상품 이미지 사이즈 설정]</a> 에서 관리할 수 있습니다.
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>품절상품 노출</th>
                    <td>
                        <label class="radio-inline"><input type="radio" name="mobileSoldOutFl" value="y" <?=gd_isset($checked['mobileSoldOutFl']['y']);?> onclick="set_soldOutDisplay('mobile')"/>노출함</label>
                        <label class="radio-inline"><input type="radio" name="mobileSoldOutFl" value="n" <?=gd_isset($checked['mobileSoldOutFl']['n']);?> onclick="set_soldOutDisplay('mobile')"/>노출안함</label>
                    </td>
                    <th>품절상품 진열</th>
                    <td>
                        <label class="radio-inline"><input type="radio" name="mobileSoldOutDisplayFl" value="y" <?=gd_isset($checked['mobileSoldOutDisplayFl']['y']);?>/>정렬 순서대로 보여주기</label>
                        <label class="radio-inline"><input type="radio" name="mobileSoldOutDisplayFl" value="n" <?=gd_isset($checked['mobileSoldOutDisplayFl']['n']);?>/>리스트 끝으로 보내기</label>
                    </td>
                </tr>
                <tr>
                    <th>품절 아이콘 노출</th>
                    <td>
                        <label class="radio-inline"><input type="radio" name="mobileSoldOutIconFl" value="y" <?=gd_isset($checked['mobileSoldOutIconFl']['y']);?>/>노출함</label>
                        <label class="radio-inline"><input type="radio" name="mobileSoldOutIconFl" value="n" <?=gd_isset($checked['mobileSoldOutIconFl']['n']);?>/>노출안함</label>
                    </td>
                    <th>아이콘노출</th>
                    <td>
                        <label class="radio-inline"><input type="radio" name="mobileIconFl" value="y" <?=gd_isset($checked['mobileIconFl']['y']);?>/>노출함</label>
                        <label class="radio-inline"><input type="radio" name="mobileIconFl" value="n" <?=gd_isset($checked['mobileIconFl']['n']);?>/>노출안함</label>
                    </td>
                </tr>
                <tr>
                    <th>노출항목 설정</th>
                    <td colspan="3">
                        <?php foreach($themeDisplayField as $k => $v) { ?>
                            <label class="checkbox-inline"  title=""><input type="checkbox" name="mobileDisplayField[]" value="<?=$k?>" <?php if(in_array($k,array_values($data['mobileDisplayField']))) { echo "checked"; } ?>  >  <?=$v?></label>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th>디스플레이 유형</th>
                    <td colspan="3">
                        <?php foreach($themeDisplayType as $k => $v) { ?>
                            <div  class="pd10  display_ <?=$v['class']?> <?php if($v['mobile'] != 'y') { echo ' display_pc'; }?> " style="float:left"><span>
                                            <img src="<?=PATH_ADMIN_GD_SHARE?>img/display_theme_<?=$k?>.jpg"><br/>
                                            <label class="radio-inline mgt5" title="" ><input type="radio" name="mobileDisplayType" value="<?=$k?>" <?=gd_isset($checked['mobileDisplayType'][$k]);?> /><?=$v['name']?></label></span></div>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th>치환코드</th>
                    <td colspan="3">
                        <?php if ($checkPathMobile === true) { ?>
                            <div class="form-inline">
                                {=includeWidget('proc/_populate.html')} <button type="button" title="복사" class="btn btn-white btn-sm js-clipboard" data-clipboard-text="{=includeWidget('proc/_populate.html')}">복사</button>
                            </div>
                            <div class="notice-danger">치환코드를 복사하여 PC와 모바일 각각의 “디자인" 메뉴에서 HTML소스에 삽입해야 인기상품 정보가 쇼핑몰에 노출됩니다.</div>
                        <?php } else { ?>
                            <div class="form-inline">
                                <div class="notice-danger">
                                    "디자인" 메뉴의 사용 스킨과 작업 스킨에 인기상품 관련 페이지 파일(proc/_populate.html)이 존재하여야만 정상적으로 치환코드 노출 및 사용이 기능합니다.<br />
                                    인기상품 노출 기능 사용을 위한 자세한 사항은 마이고도> 패치게시판에서 확인하시기 바랍니다.
                                </div>
                            </div>
                        <?php } ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</form>
