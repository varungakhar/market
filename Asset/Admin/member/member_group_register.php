<form id="frm" action="../member/member_group_ps.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="mode" value="<?= gd_isset($mode) ?>"/>
    <input type="hidden" id="sno" name="sno" value="<?= gd_isset($sno) ?>"/>
    <input type="hidden" name="groupSort" value="<?= gd_isset($data['groupSort']) ?>"/>

    <div class="page-header js-affix">
        <h3><?= end($naviMenu->location); ?></h3>
        <div class="btn-group">
            <input type="button" value="목록" class="btn btn-white btn-icon-list" onclick="goList('./member_group_list.php');"/>
            <input type="submit" value="저장" class="btn btn-red">
        </div>
    </div>
    <div class="table-title gd-help-manual">기본설정</div>
    <div class="form-inline">
        <table class="table table-cols">
            <colgroup>
                <col class="width-sm"/>
                <col/>
            </colgroup>
            <tr>
                <th class="">회원등급번호</th>
                <td><?= $sno; ?></td>
            </tr>
            <tr>
                <th class="require">회원등급이름</th>
                <td>
                    <input type="hidden" name="chkGroupNm" id="chkGroupNm" value="<?= gd_isset($data['groupNm']) ?>"/>
                    <label>
                        <input type="text" name="groupNm" class="form-control"
                               maxlength="20" value="<?= gd_isset($data['groupNm']) ?>"/>
                    </label>
                    <button type="button" class="btn btn-sm btn-gray" id="overlap_groupNm">등급이름 중복확인</button>
                </td>
            </tr>
            <tr>
                <th>등급표시</th>
                <td>
                    <input type="hidden" name="groupIcon" value="<?= gd_isset($data['groupIcon']) ?>"/>
                    <input type="hidden" name="groupIconUpload" value="<?= gd_isset($data['groupIconUpload']) ?>"/>
                    <div class="row">
                        <div class="col-xs-12">
                            <label class="radio-inline">
                                <input name="groupMarkGb" type="radio" <?= $checked['groupMarkGb']['text'] ?> value="text">
                                텍스트 : <?= gd_isset($data['groupNm'], '(회원등급이름에 등록된 텍스트 노출)') ?>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <label class="radio-inline mgb10">
                                <input name="groupMarkGb" type="radio" <?= $checked['groupMarkGb']['icon'] ?> value="icon">
                                기본 등급표시 아이콘
                            </label>
                            <br/>
                            <div class="mgl20">
                                <img class="img-thumbnail group-icon" src="<?= $data['groupIconHtml']; ?>" alt="선택된 등급 아이콘"/>
                                <button type="button" class="mgl5 groupIconDel btn btn-sm btn-gray">삭제</button>
                                <?php
                                $iconHtml = [];
                                $groupIcon = gd_get_group_icon_http_path();
                                foreach ($groupIcon as $src) {
                                    $iconHtml[] = '<img class="img-thumbnail mgl5 group-icon" src="' . $src . '" alt="" />';
                                }
                                echo join('', $iconHtml);
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <label class="radio-inline">
                                <input name="groupMarkGb" type="radio" <?= $checked['groupMarkGb']['upload'] ?> value="upload">
                                직접등록
                            </label>
                            <br/>
                            <div class="mgl20" id="divGroupMarkGb">
                                <input type="file" name="fileGroupIcon"/>
                                <?php if ($data['groupIconUpload'] != '') { ?>
                                    <img class="img-thumbnail group-icon" src="<?= $data['uploadGroupIconHtml']; ?>" alt="등록된 등급 아이콘"/>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="fileGroupIconDeleteFl" value="y">
                                        삭제
                                    </label>
                                <?php } ?>
                                <br/>
                                <span class="notice-info">jpg, jpeg, png, gif만 등록 가능하며, 기본 등급표시 아이콘은 16x16 pixel 입니다.</span>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>등급이미지</th>
                <td>
                    <input type="hidden" name="groupImage" value="<?= gd_isset($data['groupImage']) ?>"/>
                    <input type="hidden" name="groupImageUpload" value="<?= gd_isset($data['groupImageUpload']) ?>"/>
                    <div class="row">
                        <div class="col-xs-12">
                            <label class="radio-inline">
                                <input name="groupImageGb" type="radio" <?= $checked['groupImageGb']['none'] ?> value="none">
                                사용안함
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <label class="radio-inline mgb10">
                                <input name="groupImageGb" type="radio" <?= $checked['groupImageGb']['image'] ?> value="image"/>
                                기본 등급이미지
                            </label>
                            <br/>
                            <div class="mgl20">
                                <img class="img-thumbnail group-image" src="<?= $data['groupImageHtml']; ?>" alt="선택된 등급 이미지"/>
                                <button type="button" class="mgl5 groupImageDel btn btn-sm btn-gray">삭제</button>
                                <?php
                                $imgHtml = [];
                                $groupIcon = gd_get_group_image_http_path();
                                foreach ($groupIcon as $src) {
                                    $imgHtml[] = '<img class="img-thumbnail mgl5 group-image" src="' . $src . '" alt="" />';
                                }
                                echo join('', $imgHtml);
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <label class="radio-inline">
                                <input name="groupImageGb" type="radio" <?= $checked['groupImageGb']['upload'] ?> value="upload">
                                직접등록
                            </label>
                            <br/>
                            <div class="mgl20" id="divGroupImageGb">
                                <input type="file" name="fileGroupImage"/>
                                <?php if ($data['groupImageUpload'] != '') { ?>
                                    <img class="img-thumbnail group-image" src="<?= $data['uploadGroupImageHtml']; ?>" alt="등록된 등급 이미지"/>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="fileGroupImageDeleteFl" value="y">
                                        삭제
                                    </label>
                                <?php } ?>
                                <br/>
                                <span class="notice-info">jpg, jpeg, png, gif만 등록 가능하며, 기본 등급이미지는 70x70 pixel 입니다.</span>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <?php if (gd_get_default_group() !== $sno) { ?>
                <tr>
                    <th>
                        등급평가기준치
                    </th>
                    <td>
                        <span class="notice-info"><a href="../member/member_group_appraisal_config.php" target="_blank">[회원>회원관리>회원등급 평가방법 설정]</a>에서 설정한 기준에 따름 <?= Request::request()->has('sno') ? '' : '(회원등급 등록 이후 설정 가능)' ?></span>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <th>사용가능한 결제수단</th>
                <td>
                    <?= gd_radio_box('settleGb', $settleGbData, $data['settleGb']); ?>
                    <div class="pdt5">
                        <span class="notice-info">회원등급별 사용가능한 결제수단 설정과 상관없이 마일리지/예치금은 결제 시 사용 가능합니다.</span>
                    </div>
                    <div class="pdt5">
                        <span class="notice-info">회원등급별 사용가능한 결제수단과 상품>상품관리>상품등록 화면에서 상품별로 개별설정된 결제수단이 일치하지 않는 경우 해당 상품은 구매할 수 없으므로 설정 시 유의 바랍니다.</span>
                    </div>
                    <?php if ($gGlobal['isUse']) {
                        ?>
                        <div class="pdt5">
                            <span class="notice-info">해외몰에서는 회원등급별 사용가능한 수단 설정과 상관없이 해외PG로만 결제가 가능합니다.</span>
                        </div>
                        <?php
                    }
                    ?>
                </td>
            </tr>
        </table>
    </div>

    <div class="table-title gd-help-manual">혜택설정</div>
    <div class="form-inline">
        <table class="table table-cols" id="tableBenefit">
            <colgroup>
                <col class="width-sm"/>
                <col/>
            </colgroup>
            <tr>
                <th>정률(%) 할인/적립 시<br/>구매금액 기준</th>
                <td>
                    <div class="form-inline">
                        판매가&nbsp;+&nbsp;(&nbsp;
                        <?= gd_check_box('fixedRateOption[]', $fixedRateOptionData, $data['fixedRateOption']); ?>
                        &nbsp;)&nbsp;
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    할인시 절사기준
                </th>
                <td>
                    <div class="form-inline">
                        <span><?php echo gd_trunc_display('member_group'); ?></span>

                        <div class="notice-info">※ <a href="../policy/base_currency_unit.php">[기본설정>기본정책>금액/단위 기준설정]</a>에서 설정한 기준에 따름
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    할인/적립 시 적용금액 기준
                </th>
                <td>
                    <div class="form-inline">
                        <?= gd_radio_box('fixedRatePrice', $fixedRatePriceData, $data['fixedRatePrice']); ?>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    추가 할인
                </th>
                <td>
                    <?php
                    $dcType = 'percent';
                    $dcName = $dcType == 'percent' ? 'dcPercent' : 'dcPrice';
                    $dcValue = $dcType == 'percent' ? gd_isset($data['dcPercent']) : gd_isset($data['dcPrice']);
                    ?>
                    <?= gd_select_box('fixedOrderTypeDc', 'fixedOrderTypeDc', $fixedOrderTypeData, null, $data['fixedOrderTypeDc']); ?> 구매금액이
                    <input type="text" class="form-control js-number" name="dcLine" value="<?= gd_currency_display(gd_isset($data['dcLine'])); ?>"/>
                    원 이상인 경우 해당상품
                    <input type="text" name="<?= $dcName ?>" value="<?= $dcValue ?>" class="form-control js-number width-3xs" data-number="4, 100, 10"/>
                    <input type="hidden" name="dcType" value="percent"/>
                    % 추가 할인
                </td>
            </tr>
            <tr>
                <th>추가 할인<br/>적용제외</th>
                <td>
                    <?php
                    $dcExOptionJson = $data['dcExOption'];
                    echo gd_check_box('dcExOption[]', $dcOptionData, gd_isset($dcExOptionJson));
                    ?>
                </td>
            </tr>
            <tr>
                <th>
                    중복 할인
                </th>
                <td>
                    <?php
                    $overlapDcType = 'percent';
                    $overlapDcName = $overlapDcType == 'percent' ? 'overlapDcPercent' : 'overlapDcPrice';
                    $overlapDcValue = $overlapDcType == 'percent' ? gd_isset($data['overlapDcPercent']) : gd_isset($data['overlapDcPrice']);
                    ?>
                    <?= gd_select_box('fixedOrderTypeOverlapDc', 'fixedOrderTypeOverlapDc', $fixedOrderTypeData, null, $data['fixedOrderTypeOverlapDc']); ?> 구매금액이
                    <input type="text" class="form-control js-number" name="overlapDcLine" value="<?= gd_currency_display(gd_isset($data['overlapDcLine'])); ?>"/>
                    원 이상인 경우 해당 상품
                    <input type="text" name="<?= $overlapDcName ?>" value="<?= $overlapDcValue ?>" class="form-control js-number width-3xs" data-number="4, 100, 10"/>
                    <input type="hidden" name="overlapDcType" value="percent"/>
                    % 중복 할인
                </td>
            </tr>
            <tr>
                <th>중복 할인 적용</th>
                <td>
                    <?= gd_check_box('overlapDcOption[]', $dcOptionData, gd_isset($data['overlapDcOption'])); ?>
                </td>
            </tr>
            <tr>
                <th>추가 마일리지 적립</th>
                <td>
                    <?php
                    $mileageType = 'percent';
                    $mileageName = $mileageType == 'percent' ? 'mileagePercent' : 'mileagePrice';
                    $mileageValue = $mileageType == 'percent' ? gd_isset($data['mileagePercent']) : gd_isset($data['mileagePrice']);
                    ?>
                    <?= gd_select_box('fixedOrderTypeMileage', 'fixedOrderTypeMileage', $fixedOrderTypeData, null, $data['fixedOrderTypeMileage']); ?> 구매금액이
                    <input type="text" class="form-control js-number" name="mileageLine" value="<?= gd_currency_display(gd_isset($data['mileageLine'])); ?>"/>
                    원 이상인 경우
                    <input type="text" name="<?= $mileageName ?>" value="<?= $mileageValue ?>" class="form-control js-number width-3xs" data-number="4, 100, 10"/>
                    <input type="hidden" name="mileageType" value="percent"/>
                    % 추가적립
                </td>
            </tr>
            <tr>
                <th>
                    추가 적립시 절사기준
                </th>
                <td>
                    <div class="form-inline">
                        <span><?php echo gd_trunc_display('mileage'); ?></span>

                        <div class="notice-info">※ <a href="../policy/base_currency_unit.php">[기본설정>기본정책>금액/단위 기준설정]</a>에서 설정한 기준에 따름
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    배송비 혜택
                </th>
                <td>
                    <div class="form-inline">
                        <label><input type="checkbox" name="deliveryFree" value="y" <?php echo $checked['deliveryFree']['y']; ?>> 배송비 무료</label>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</form>
<script type="text/javascript">

    function load_group_icon() {
        var iconHtml = [];
        $.ajax({
            "url": "../member/member_group_ps.php",
            "method": "post",
            "data": {
                "mode": "groupIconHttpPath"
            }
        }).done(function (data) {
            $(data).each(function (idx, item) {
                iconHtml.push('<img class="img-thumbnail mgl5 group-icon" src="' + item + '" alt="" />');
            });
            $('#sliderIcon').html(iconHtml.join(''));
        });
    }

    function load_group_image() {
        var iconHtml = [];
        $.ajax({
            "url": "../member/member_group_ps.php",
            "method": "post",
            "data": {
                "mode": "groupImageHttpPath"
            }
        }).done(function (data) {
            $(data).each(function (idx, item) {
                iconHtml.push('<img class="img-thumbnail mgl5 group-icon" src="' + item + '" alt="" />');
            });
            $('#sliderIcon').html(iconHtml.join(''));
        });
    }

    var member_group = (function () {
        var $form;

        function init() {
            $form = $('#frm');
            // 추가적립제외, 중복할인 적용 데이터
            group_register.templateAddDiscountContents.scm.contents = <?= json_encode($data['dcExScm']);?>;
            group_register.templateAddDiscountContents.category.contents = <?= json_encode($data['dcExCategory']);?>;
            group_register.templateAddDiscountContents.brand.contents = <?= json_encode($data['dcExBrand']);?>;
            group_register.templateAddDiscountContents.goods.contents = <?= json_encode($data['dcExGoods']);?>;
            group_register.templateOverlapDcOptionContents.scm.contents = <?= json_encode($data['overlapDcScm']);?>;
            group_register.templateOverlapDcOptionContents.category.contents = <?= json_encode($data['overlapDcCategory']);?>;
            group_register.templateOverlapDcOptionContents.brand.contents = <?= json_encode($data['overlapDcBrand']);?>;
            group_register.templateOverlapDcOptionContents.goods.contents = <?= json_encode($data['overlapDcGoods']);?>;

            var $groupMarkGb = $(':radio[name=groupMarkGb]');
            $groupMarkGb.change(function () {
                $($groupMarkGb).each(function (idx, item) {
                    if (idx == 2 && item.checked) {
                        $('#divGroupMarkGb *').not(':file, :text').removeProp('disabled');
                    } else {
                        $('#divGroupMarkGb *').not(':file, :text').prop('disabled', true);
                    }
                });
            }).filter(':checked').trigger('change');

            var $groupImageGb = $(':radio[name=groupImageGb]');
            $groupImageGb.change(function () {
                $($groupImageGb).each(function (idx, item) {
                    if (idx == 2 && item.checked) {
                        $('#divGroupImageGb *').not(':file, :text').removeProp('disabled');
                    } else {
                        $('#divGroupImageGb *').not(':file, :text').prop('disabled', true);
                    }
                });
            }).filter(':checked').trigger('change');
        }

        var templateDiscountAddEvent = function (e) {
            var $target = $(e.target);
            var targetValue = $target.val();
            var groupRegister = e.data.contents;
            var appendContents = null;
            switch (targetValue) {
                case 'scm' :
                    appendContents = groupRegister.scm;
                    break;
                case 'category' :
                    appendContents = groupRegister.category;
                    break;
                case 'brand' :
                    appendContents = groupRegister.brand;
                    break;
                case 'goods' :
                    appendContents = groupRegister.goods;
                    break;
            }
            appendContents.layerFormID = appendContents.child + "Layer";
            appendContents.parentFormID = appendContents.child + "Row";
            appendContents.dataFormID = appendContents.child + "Id";
            appendContents.dataInputNm = appendContents.child;
            var compiled = _.template($('#templateAddDiscountExclude').html());
            var afterTarget = $target.closest('tr');
            compiled = compiled(appendContents);
            afterTarget.after(compiled);
        };

        var templateDiscountRemoveEvent = function (e) {
            var $target = $(e.target);
            var targetValue = $target.val();
            var $removeTarget = $('tr[data-handler="' + targetValue + '"]', $form);
            if ($removeTarget.length == 1) {
                $removeTarget.remove();
            } else {
                if ($target.attr('name').indexOf('overlap') > -1) {
                    $removeTarget.eq(1).remove();
                } else {
                    $removeTarget.eq(0).remove();
                }
            }
        };

        /**
         * 추가할인 적용제외 체크 이벤트에 따른 템플릿 추가제거
         */
        function templateDiscountEvent(e) {
            try {
                if ($(e.target).prop('checked')) {
                    templateDiscountAddEvent(e);
                } else {
                    templateDiscountRemoveEvent(e);
                }
            } catch (error) {
                console.log(error);
            }
        }

        /**
         * 등급아이콘출력
         */
        function print_group_icon(isUpload) {
            var $groupIcon = $('.group-icon:first');
            if (isUpload === true) {
                $groupIcon = $('.group-icon:last');
            }
            $groupIcon.attr('src', '<?= UserFilePath::icon('group_icon')->www() ?>/' + $('input[name="groupIcon"]').val());
            $groupIcon.on("error", function () {
                this.src = '<?= UserFilePath::data('commonimg')->www() . '/ico_noimg_16.gif' ?>';
            });
        }

        /**
         * 등급 이미지 출력
         */
        function print_group_image(isUpload) {
            var $groupImage = $('.group-image:first');
            if (isUpload === true) {
                $groupImage = $('.group-image:last');
            }
            $groupImage.attr('src', '<?= UserFilePath::icon('group_image')->www() ?>/' + $('input[name="groupImage"]').val());
            $groupImage.on("error", function () {
                this.src = '<?= UserFilePath::data('commonimg')->www() . '/ico_noimg_75.gif' ?>';
            });
        }

        /**
         * 등급이미지 롤링 이벤트 바인딩 및 프린트
         */
        function group_image_event() {
            var $groupImage = $('.group-image:gt(0)');
            $groupImage.click(function () {
                var filenm = $(this).attr('src').substr($(this).attr('src').lastIndexOf('/') + 1);
                $('input[name=\'groupImage\']').val(filenm);
                $('.group-image:first').attr('src', '<?= UserFilePath::icon('group_image')->www() . '/' ?>' + filenm);
            });
            $groupImage.css('cursor', 'pointer');
            var $groupImageDel = $('.groupImageDel');
            $groupImageDel.click(function () {
                $('input[name=\'groupImage\']').val('');
                print_group_image();
            });
            var $fileGroupImageDeleteFl = $(':checkbox[name=fileGroupImageDeleteFl]');
            $fileGroupImageDeleteFl.change(function () {
                if (this.checked && this.value === 'y') {
                    print_group_image(true);
                }
            });
            $groupImageDel.css('cursor', 'pointer');
            var $groupMark = $(':radio[name="groupMarkGb"]');
            $groupMark.click(function () {
                onClickGroupMarkGb(this);
            });

            function onClickGroupMarkGb(radio) {
                if (radio.checked && radio.value == 'text') {
                    $('input[name=\'groupImage\']').val('');
                    print_group_image();
                    $('input[name=\'groupIcon\']').val('');
                    print_group_icon();
                }
            }
        }

        /**
         * 등급아이콘 롤링 이벤트 바인딩 및 프린트
         */
        function group_icon_event() {
            var $groupIcon = $('.group-icon:gt(0)');
            $groupIcon.click(function () {
                var filenm = $(this).attr('src').substr($(this).attr('src').lastIndexOf('/') + 1);
                $('input[name=\'groupIcon\']').val(filenm);
                $('.group-icon:first').attr('src', '<?= UserFilePath::icon('group_icon')->www() . '/' ?>' + filenm);
            });
            $groupIcon.css('cursor', 'pointer');
            var $groupIconDel = $('.groupIconDel');
            $groupIconDel.click(function () {
                $('input[name=\'groupIcon\']').val('');
                print_group_icon();
            });
            var $fileGroupIconDeleteFl = $(':checkbox[name=fileGroupIconDeleteFl]');
            $fileGroupIconDeleteFl.change(function () {
                if (this.checked && this.value === 'y') {
                    print_group_icon(true);
                }
            });
            $groupIconDel.css('cursor', 'pointer');
        }

        /**
         * 등급이름 표시
         */
        function group_name_event() {
            $('input[name=\'groupNm\']', $form).change(function () {
                $('.groupNm').text($(this).val());
            });
            $('.groupNm').text($('input[name=\'groupNm\']', $form).val());
        }

        $(document).ready(function () {
            init();
            group_name_event();
            group_icon_event();
            group_image_event();

            /**
             * 이벤트 바인딩
             */
            $('#overlap_groupNm', $form).click(function () {
                var _groupNm = $('input[name="groupNm"]').val();
                ajax_with_layer(group_register.url, {
                    mode: "overlapGroupNm",
                    groupNm: _groupNm,
                    sno: $('#sno').val()
                }, function (data) {
                    $('input[name="chkGroupNm"]').val(_groupNm);
                    member.dialog({message: data});
                })
            });

            $form.on('click', 'button[name=btnSelectDcEx]', group_register.layer_register);
            $form.on('change', 'input[name="apprFigureOrderPriceFl"]', member.checkbox_yn);
            $form.on('change', 'input[name="apprFigureOrderRepeatFl"]', member.checkbox_yn);
            $form.on('change', 'input[name="apprFigureReviewRepeatFl"]', member.checkbox_yn);
            $form.on('change', ':checkbox[name="dcExOption[]"]', {contents: group_register.templateAddDiscountContents}, $.proxy(templateDiscountEvent));
            $form.on('change', ':checkbox[name="overlapDcOption[]"]', {contents: group_register.templateOverlapDcOptionContents}, $.proxy(templateDiscountEvent));
            $(':checkbox[name="dcExOption[]"]:checked').trigger('change');
            $(':checkbox[name="overlapDcOption[]"]:checked').trigger('change');

            /**
             * 폼 검증
             */
            $form.validate({
                //            debug: true,
                ignore: "",
                rules: {
                    groupNm: {
                        required: true, equalTo: "#chkGroupNm"
                    }, apprFigureOrderPriceMore: {
                        required: "[name=apprFigureOrderPriceFl]:checked"
                    }, apprFigureOrderPriceBelow: {
                        required: "[name=apprFigureOrderPriceFl]:checked"
                    }, apprFigureOrderRepeat: {
                        required: "[name=apprFigureOrderRepeatFl]:checked"
                    }, apprFigureReviewRepeat: {
                        required: "[name=apprFigureReviewRepeatFl]:checked"
                    }
                }, messages: {
                    groupNm: {
                        required: "회원등급명은 필수 입니다.", equalTo: "등급이름 중복확인을 해주세요."
                    }
                }, submitHandler: function (form) {
                    // 추가할인 + 중복할인의 합이 100%가 넘지 않도록 수정
                    //                    if (parseFloat($('input[name=dcPercent]').val()) + parseFloat($('input[name=overlayDcPercent]').val()) > 100) {
                    //                        alert('구매 금액의 100%를 초과할 수 없습니다.');
                    //                        return false;
                    //                    }

                    form.target = 'ifrmProcess';
                    form.submit();
                }
            });
        });
    })();
    /**
     * 회원등급등록
     */

    var categoryHeaderHtml = "" +
        "<table class=\"table table-cols active mgt10 mgb0\">" +
        "<thead>" +
        "<tr>" +
        "<th class=\"width10p no-border\">번호</th>" +
        "<th class=\"width80p no-border\">카테고리</th>" +
        "<th class=\"width10p no-border\">삭제</th>" +
        "</tr>" +
        "</thead>" +
        "</table>";

    var brandHeaderHtml = "" +
        "<table class=\"table table-cols active mgt10 mgb0\">" +
        "<thead>" +
        "<tr>" +
        "<th class=\"width10p no-border\">번호</th>" +
        "<th class=\"width80p no-border\">브랜드</th>" +
        "<th class=\"width10p no-border\">삭제</th>" +
        "</tr>" +
        "</thead>" +
        "</table>";

    var goodsHeaderHtml = "" +
        "<table class=\"table table-cols active mgt10 mgb0\" >" +
        "<thead>" +
        "<tr>" +
        "<th class=\"width10p no-border\">번호</th>" +
        "<th class=\"width10p no-border\">이미지</th>" +
        "<th class=\"width70p no-border\">상품명</th>" +
        "<th class=\"width10p no-border\">삭제</th>" +
        "</tr>" +
        "</thead>" +
        "</table>";

    var group_register = {
        url: '../member/member_group_ps.php',
        object: {},
        /**
         * 언더스코어 템플릿 컨텐츠
         */

        templateAddDiscountContents: {
            scm: {
                handler: "scm",
                th: "특정 공급사",
                button: "공급사 선택",
                td: "추가할인적용을 제외할 공급사를 선택해주세요.",
                tdBottom: "",
                child: "dcExScm",
                mode: "search"
            },
            category: {
                handler: "category",
                th: "특정 카테고리",
                button: "카테고리 선택",
                td: "추가할인적용을 제외할 카테고리를 선택해주세요." + categoryHeaderHtml,
                tdBottom: "<div><button type=\"button\" class=\"btn btn-gray btn-sm\" onclick=\"allDel('dcExCategoryRow');\">전체삭제</button></div>",
                child: "dcExCategory",
                mode: "simple"
            },
            brand: {
                handler: "brand",
                th: "특정 브랜드",
                button: "브랜드 선택",
                td: "추가할인적용을 제외할 브랜드를 선택해주세요." + brandHeaderHtml,
                tdBottom: "<div><button type=\"button\" class=\"btn btn-gray btn-sm\" onclick=\"allDel('dcExBrandRow');\">전체삭제</button></div>",
                child: "dcExBrand",
                mode: "simple"
            },
            goods: {
                handler: "goods",
                th: "특정 상품",
                button: "상품 선택",
                td: "추가할인적용을 제외할 상품를 선택해주세요." + goodsHeaderHtml,
                tdBottom: "<div><button type=\"button\" class=\"btn btn-gray btn-sm\" onclick=\"allDel('dcExGoodsRow');\">전체삭제</button></div>",
                child: "dcExGoods",
                mode: "simple"
            }
        },
        templateOverlapDcOptionContents: {
            scm: {
                handler: "scm",
                th: "특정 공급사",
                button: "공급사 선택",
                td: "중복할인을 적용할 공급사를 선택해주세요.",
                tdBottom: "",
                child: "overlapDcScm",
                mode: "search"
            },
            category: {
                handler: "category",
                th: "특정 카테고리",
                button: "카테고리 선택",
                td: "중복할인을 적용할 카테고리를 선택해주세요." + categoryHeaderHtml,
                tdBottom: "<div><button type=\"button\" class=\"btn btn-gray btn-sm\" onclick=\"allDel('overlapDcCategoryRow');\">전체삭제</button></div>",
                child: "overlapDcCategory",
                mode: "simple"
            },
            brand: {
                handler: "brand",
                th: "특정 브랜드",
                button: "브랜드 선택",
                td: "중복할인을 적용할 브랜드를 선택해주세요." + brandHeaderHtml,
                tdBottom: "<div><button type=\"button\" class=\"btn btn-gray btn-sm\" onclick=\"allDel('overlapDcBrandRow');\">전체삭제</button></div>",
                child: "overlapDcBrand",
                mode: "simple"
            },
            goods: {
                handler: "goods",
                th: "특정 상품",
                button: "상품 선택",
                td: "중복할인을 적용할 상품를 선택해주세요." + goodsHeaderHtml,
                tdBottom: "<div><button type=\"button\" class=\"btn btn-gray btn-sm\" onclick=\"allDel('overlapDcGoodsRow');\">전체삭제</button></div>",
                child: "overlapDcGoods",
                mode: "simple"
            }
        },
        /**
         * 추가할인 적용제외 레이어팝업
         * @param string 타입
         * @param string 예외 여부
         */
        layer_register: function (e) {
            var target = $(e.target);

            var typeStr = target.attr('data-handler');
            var childNm = target.attr('data-child');
            var addParam = {
                mode: target.attr('data-mode') || "checkbox",
                layerFormID: childNm + "Layer",
                parentFormID: childNm + "Row",
                dataFormID: childNm + "Id",
                dataInputNm: childNm,
                layerTitle: target.text(),
                childRow: $("#" + childNm + "Row tr").length
            };
            layer_add_info(typeStr, addParam);
        }
    }

    function allDel(delRow) {
        $("#" + delRow).empty();
    }
</script>
<script type="text/html" id="templateAddDiscountExclude">
    <tr data-handler="<%=handler%>">
        <th>
            <div><%=th%></div>
            <?php /*TODO->버튼의 text가 줄바꿈되면 레이어에서 페이징 오류남... 이후 변경할 것*/ ?>
            <button type="button" class="btn btn-sm btn-gray" name="btnSelectDcEx" data-handler="<%=handler%>" data-child="<%=child%>" data-mode="<%=mode%>"><%=button%></button>
        </th>
        <td>
            <%=td%>

            <% if (child.indexOf('Scm') != -1) { %>
            <div id="<%=child%>Row">
                <%}else{%>
                <table class="table table-cols active" id="<%=child%>Row">
                    <%}%>

                    <% if (child.indexOf('Scm') != -1 && (_.size(contents) > 0 && _.isArray(contents))) { %> <% _.each(contents, function (content) { %>
                    <div id="<%=dataFormID%>_<%=content.scmNo%>" class="btn-group btn-group-xs">
                        <input type="hidden" name="<%=dataInputNm%>[]" value="<%=content.scmNo%>">
                        <input type="hidden" name="<%=dataInputNm%>Nm[]" value="<%=content.companyNm%>">
                        <button type="button" class="btn btn-sm btn-gray"><%=content.companyNm %></button>
                        <button type="button" class="btn btn-sm btn-red" data-toggle="delete" data-target="#<%=dataFormID%>_<%=content.scmNo%>">삭제</button>
                    </div>
                    <%}); %> <% } else if (child.indexOf('Category') != -1 && (_.size(contents) > 0 && _.isArray(contents))) { %> <%_.each(contents, function (content, idx) {%>

                    <tr id="<%=dataFormID%>_<%=content.cateCd%>">
                        <td class="center"><%=(idx + 1)%>
                            <input type="hidden" name="<%=dataInputNm%>[]" value="<%=content.cateCd%>">
                            <input type="hidden" name="<%=dataInputNm%>Nm[]" value="<%=content.cateNm%>">
                        </td>
                        <td><%=_.unescape(content.cateNm)%></td>
                        <td class="center">
                            <button type="button" class="btn btn-gray btn-sm" data-toggle="delete" data-target="#<%=dataFormID%>_<%=content.cateCd%>">삭제</button>
                        </td>
                    </tr>
                    <%});%> <%} else if (child.indexOf('Brand') != -1 && (_.size(contents) > 0 && _.isArray(contents))) { %> <%_.each(contents, function (content, idx) { %>

                    <tr id="<%=dataFormID%>_<%=content.brandCd%>">
                        <td class="center"><%=(idx + 1)%>
                            <input type="hidden" name="<%=dataInputNm%>[]" value="<%=content.brandCd%>">
                            <input type="hidden" name="<%=dataInputNm%>Nm[]" value="<%=content.brandNm%>">
                        </td>
                        <td><%=_.unescape(content.brandNm)%></td>
                        <td class="center">
                            <button type="button" class="btn btn-gray btn-sm" data-toggle="delete" data-target="#<%=dataFormID%>_<%=content.brandCd%>">삭제</button>
                        </td>
                    </tr>
                    <%});%> <%} else if (child.indexOf('Goods') != -1 && (_.size(contents) > 0 && _.isArray(contents))) { %> <%_.each(contents, function (content, idx) { %>
                    <tr id="<%=dataFormID%>_<%=content.goodsNo%>">
                        <td class="center"><%=(idx + 1)%>
                            <input type="hidden" name="<%=dataInputNm%>[]" value="<%=content.goodsNo%>">
                            <input type="hidden" name="<%=dataInputNm%>Nm[]" value="<%=content.goodsNm%>">
                        </td>
                        <td class="center"><%=_.unescape(content.goodsImage)%></td>
                        <td><%=_.unescape(content.goodsNm)%></td>
                        <td class="center">
                            <button type="button" class="btn btn-gray btn-sm" data-toggle="delete" data-target="#<%=dataFormID%>_<%=content.goodsNo%>">삭제</button>
                        </td>
                    </tr>

                    <%});%> <%}%>

                    <% if (child.indexOf('Scm') != -1) { %>
            </div>
            <%}else{%></table><%}%>

            <%=tdBottom%>
        </td>
    </tr>
</script>
<style>
    /* 회원등급수정*/
    #dcExGoodsRow td:nth-child(1), #dcExGoodsRow td:nth-child(2), #dcExGoodsRow td:nth-child(4),
    #overlapDcGoodsRow td:nth-child(1), #overlapDcGoodsRow td:nth-child(2), #overlapDcGoodsRow td:nth-child(4) { width:10%; }

    #dcExBrandRow td:nth-child(1), #dcExBrandRow td:nth-child(3), #dcExCategoryRow td:nth-child(1), #dcExCategoryRow td:nth-child(3),
    #overlapDcCategoryRow td:nth-child(1), #overlapDcCategoryRow td:nth-child(3), #overlapDcBrandRow td:nth-child(1), #overlapDcBrandRow td:nth-child(3) { width:10%; height:43px; }

    #tableBenefit .no-border { border-bottom:1px; }

    .table-cols > tr > td {
        padding:8px 15px;
        font-size:12px;
        height:43px;
        border-bottom:1px solid #E6E6E6;
    }
</style>
