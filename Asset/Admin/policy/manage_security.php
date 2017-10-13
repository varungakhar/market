<form id="frmManageSecurity" name="frmManageSecurity" action="manage_ps.php" method="post">
    <input type="hidden" name="mode" value="<?= $mode; ?>"/>
    <div class="page-header js-affix">
        <h3><?php echo end($naviMenu->location); ?></h3>
        <input type="submit" value="저장" class="btn btn-red"/>
    </div>

    <div class="table-title gd-help-manual">
        관리자 보안인증 설정
    </div>
    <table class="table table-cols mgb30">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tr>
            <th>인증수단</th>
            <td>
                <label class="checkbox-inline">
                    <input type="checkbox" name="smsSecurityFl" value="y" <?php if($dataSecurity['superCellPhoneFl'] === false) echo "disabled='disabled'"; ?> <?php echo gd_isset($checked['smsSecurityFl']['y']); ?> /> SMS인증
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox" name="emailSecurityFl" value="y" <?php if($dataSecurity['superEmailFl'] === false) echo "disabled='disabled'"; ?> <?php echo gd_isset($checked['emailSecurityFl']['y']); ?> > 이메일인증
                </label>

                <div class="pdt5">
                    <?php if($dataSecurity['superCellPhoneFl'] === false || $dataSecurity['superEmailFl'] === false) {?>
                        <div class="notice-danger">
                            대표운영자 정보에 인증정보(<?php echo implode(', ', $dataSecurity['superSecurityText']); ?>)가 없습니다. 인증정보를 먼저 등록해주세요. <b><a href="<?=$dataSecurity['superManagerModifyUrl']?>" target="_blank" class="btn-link">대표운영자 정보 수정하기 ></b></a><br>
                            대표운영자의 인증정보가 등록되지 않으면 보안로그인/화면보안접속 설정과 관계없이 보안인증화면이 노출되지 않습니다
                        </div>
                    <?php } ?>

                    <p class="notice-info">
                        인증번호 SMS는 잔여포인트가 있어야 발송됩니다. (잔여포인트 : <?php echo number_format($smsPoint) ?>)
                        <a href="../member/sms_charge.php" target="_blank" class="btn-link">SMS포인트 충전하기 ></a>
                    </p>
                    <p class="notice-info">
                        SMS잔여포인트가 없는 경우 자동으로 이메일이 인증수단으로 노출되며, 포인트 충전 이후 설정한 인증수단으로 노출됩니다.
                    </p>
                </div>
            </td>
        </tr>
        <tr>
            <th>보안로그인</th>
            <td>
                <label class="radio-inline">
                    <input type="radio" name="smsSecurity" value="y" class="js-security" <?php if ($smsAuthCount < 1) echo "disabled='disabled'"; ?> <?php echo gd_isset($checked['smsSecurity']['y']); ?> />사용함
                </label>
                <label class="radio-inline">
                    <input type="radio" name="smsSecurity" value="n" class="js-security" <?php echo gd_isset($checked['smsSecurity']['n']); ?> />사용안함
                </label>
            </td>
        </tr>
        <tr class="js-security">
            <th>화면보안접속</th>
            <td>
                <label class="radio-inline">
                    <input type="radio" name="screenSecurity" value="y" class="js-security" <?php if ($smsAuthCount < 1) echo "disabled='disabled'"; ?> <?php echo gd_isset($checked['screenSecurity']['y']); ?> />사용함
                </label>
                <label class="radio-inline">
                    <input type="radio" name="screenSecurity" value="n" class="js-security" <?php echo gd_isset($checked['screenSecurity']['n']); ?> />사용안함
                </label>
                <div class="pdt5">
                    <p class="notice-info">
                        화면보안접속 사용 시 보안접속 인증화면이 노출되는 화면<br>
                        - 기본설정>관리정책>운영자관리/운영자등록<br>
                        - 기본설정>관리정책>운영보안설정<br>
                        - 기본설정>결제정책>무통장입금은행관리<br>
                        - 회원>SMS관리>개별/전체 SMS 발송
                    </p>
                </div>
            </td>
        </tr>
    </table>

    <div class="table-title gd-help-manual">
        관리자 자동 로그아웃 설정
    </div>
    <table class="table table-cols mgb30">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tr>
            <th>관리자 자동 로그아웃</th>
            <td>
                <label class="radio hand">
                    <input type="radio" name="sessionLimitUseFl" value="n" <?php if($dataSecurity['sessionLimitUseFl'] === false) echo "disabled='disabled'"; ?> <?php echo gd_isset($checked['sessionLimitUseFl']['n']); ?> /> 제한없음
                </label>
                <label class="radio pdt10 hand">
                    <input type="radio" name="sessionLimitUseFl" value="y" <?php echo gd_isset($checked['sessionLimitUseFl']['y']); ?> > 로그인 후
                    <select name="sessionLimitTime">
                        <option value="1800" <?php echo gd_isset($selected['sessionLimitTime']['1800']); ?>>30</option>
                        <option value="3600" <?php echo gd_isset($selected['sessionLimitTime']['3600']); ?>>60</option>
                        <option value="5400" <?php echo gd_isset($selected['sessionLimitTime']['5400']); ?>>90</option>
                        <option value="7200" <?php echo gd_isset($selected['sessionLimitTime']['7200']); ?>>120</option>
                        <option value="10800" <?php echo gd_isset($selected['sessionLimitTime']['10800']); ?>>180</option>
                    </select> 분간 클릭이 없으면 자동 로그아웃
                </label>
            </td>
        </tr>
    </table>

    <div class="table-title gd-help-manual">
        IP 접속제한 설정
    </div>
    <table class="table table-cols mgb15">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tr>
            <th>관리자 IP 접속제한</th>
            <td>
                <label class="radio-inline">
                    <input type="radio" name="ipAdminSecurity" value="y" <?php echo gd_isset($checked['ipAdminSecurity']['y']); ?> />사용함
                </label>
                <label class="radio-inline">
                    <input type="radio" name="ipAdminSecurity" value="n" <?php echo gd_isset($checked['ipAdminSecurity']['n']); ?> />사용안함
                </label>
            </td>
        </tr>
        <tr class="ipAdmin">
            <th>관리자 접속가능 IP 등록</th>
            <td>
                <button type="button" class="btn btn-sm btn-white btn-icon-plus js-admin-add mgb10">추가</button>
                <span class="notice-info mgb10">등록된 IP만 관리자에 접속 가능합니다. (현재 접속 IP : <?= $remoteAddr; ?>)</span>
                <ul class="ipAdmin list-unstyled clear-both">
                    <?php if (is_array($dataSecurity['ipAdmin']) === true) {
                        foreach ($dataSecurity['ipAdmin'] as $key => $val) {
                            ?>
                            <li class="form-inline">
                                <input type="text" name="ipAdmin[]" value="<?php echo $val[0]; ?>" class="form-control width5p number" maxlength="3"/>
                                <input type="text" name="ipAdmin[]" value="<?php echo $val[1]; ?>" class="form-control width5p number" maxlength="3"/>
                                <input type="text" name="ipAdmin[]" value="<?php echo $val[2]; ?>" class="form-control width5p number" maxlength="3"/>
                                <input type="text" name="ipAdmin[]" value="<?php echo $val[3]; ?>" class="form-control width5p number" maxlength="3"/>
                                <span class="js-bandWidth<?php if(trim($dataSecurity['ipAdminBandWidth'][$key]) === ''){ ?> display-none<?php } ?>">
                                    ~
                                <input type="text" name="ipAdminBandWidth[]" value="<?php echo $dataSecurity['ipAdminBandWidth'][$key]; ?>" class="form-control width5p number js-bandWidthText" maxlength="3"/></span>
                                <input type="checkbox" name="ipAdminBandWidthFl[]" value="y" <?php echo gd_isset($checked['ipAdminBandWidthFl'][$key]); ?> />대역 지정
                                <button class="btn btn-sm btn-white btn-icon-minus js-admin-del">삭제</button>
                            </li>
                        <?php }
                    } ?>
                </ul>
            </td>
        </tr>
        <tr>
            <th>쇼핑몰 IP 접속제한</th>
            <td>
                <label class="radio-inline">
                    <input type="radio" name="ipFrontSecurity" value="y" <?php echo gd_isset($checked['ipFrontSecurity']['y']); ?> />사용함
                </label>
                <label class="radio-inline">
                    <input type="radio" name="ipFrontSecurity" value="n" <?php echo gd_isset($checked['ipFrontSecurity']['n']); ?> />사용안함
                </label>
            </td>
        </tr>
        <tr class="ipFront">
            <th>쇼핑몰 접속제한 IP 등록</th>
            <td>
                <button type="button" class="btn btn-sm btn-white btn-icon-plus js-front-add mgb10">추가</button>
                <span class="notice-danger mgb10">등록된 IP는 쇼핑몰에 접속할 수 없으므로 등록 시 주의하시기 바랍니다.</span>
                <ul class="ipFront list-unstyled clear-both">
                    <?php if (is_array($dataSecurity['ipFront']) === true) {
                        foreach ($dataSecurity['ipFront'] as $key => $val) {
                            ?>
                            <li class="form-inline">
                                <input type="text" name="ipFront[]" value="<?php echo $val[0]; ?>" class="form-control width5p number" maxlength="3"/>
                                <input type="text" name="ipFront[]" value="<?php echo $val[1]; ?>" class="form-control width5p number" maxlength="3"/>
                                <input type="text" name="ipFront[]" value="<?php echo $val[2]; ?>" class="form-control width5p number" maxlength="3"/>
                                <input type="text" name="ipFront[]" value="<?php echo $val[3]; ?>" class="form-control width5p number" maxlength="3"/>
                                <span class="js-bandWidth<?php if(trim($dataSecurity['ipFrontBandWidth'][$key]) === ''){ ?> display-none<?php } ?>">
                                ~
                                    <input type="text" name="ipFrontBandWidth[]" value="<?php echo $dataSecurity['ipFrontBandWidth'][$key]; ?>" class="form-control width5p number js-bandWidthText" maxlength="3"/></span>
                                <input type="checkbox" name="ipFrontBandWidthFl[]" value="y" <?php echo gd_isset($checked['ipFrontBandWidthFl'][$key]); ?> />대역 지정
                                <button class="btn btn-sm btn-white btn-icon-minus js-front-del">삭제</button>
                            </li>
                        <?php }
                    } ?>
                </ul>
            </td>
        </tr>
    </table>
    <div class="notice-danger mgl15">
        공인 IP에 대해서만 작동하며, 사설 IP 등록 시 작동하지 않습니다.<br> (사설 IP 대역 : 10.0.0.0 ~ 10.255.255.255, 172.16.0.0 ~ 172.31.255.255, 192.168.0.0 ~ 192.168.255.255)
    </div>
    <div class="notice-danger mgl15">
        유동적으로 변경되는 IP 등록 시 접속이 제한되실 수 있으니 주의바랍니다.
    </div>
    <div class="notice-danger mgl15 mgb15">
        IP접속제한 설정 시 잘못된 IP 등록으로 사이트 접속 및 운영에 문제가 생길 수 있으므로 주의 바랍니다.
    </div>
    <div class="linepd30"></div>

    <div class="table-title gd-help-manual">
        쇼핑몰 화면 보안 설정
    </div>
    <table class="table table-cols mgb15">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tr>
            <th>마우스 드래그 차단</th>
            <td class="form-inline">
                <label class="radio-inline">
                    <input type="radio" name="unDragFl" value="y" <?php echo gd_isset($checked['unDragFl']['y']); ?> />사용함
                </label>
                <label class="radio-inline">
                    <input type="radio" name="unDragFl" value="n" <?php echo gd_isset($checked['unDragFl']['n']); ?> />사용안함
                </label>
                <p class="notice-info">"사용함" 선택 시 마우스로 드래그하여 텍스트 내용을 선택할 수 없습니다.</p>
            </td>
        </tr>
        <tr>
            <th>오른쪽 마우스 차단</th>
            <td class="form-inline">
                <label class="radio-inline">
                    <input type="radio" name="unContextmenuFl" value="y" <?php echo gd_isset($checked['unContextmenuFl']['y']); ?> />사용함
                </label>
                <label class="radio-inline">
                    <input type="radio" name="unContextmenuFl" value="n" <?php echo gd_isset($checked['unContextmenuFl']['n']); ?> />사용안함
                </label>
                <p class="notice-info">"사용함" 선택 시 마우스 오른쪽 버튼을 클릭할 수 없습니다.</p>
            </td>
        </tr>
        <tr>
            <th>관리자 차단해제</th>
            <td class="form-inline">
                <label class="radio-inline">
                    <input type="radio" name="managerUnblockFl" value="y" <?php echo gd_isset($checked['managerUnblockFl']['y']); ?> />사용함
                </label>
                <label class="radio-inline">
                    <input type="radio" name="managerUnblockFl" value="n" <?php echo gd_isset($checked['managerUnblockFl']['n']); ?> />사용안함
                </label>
                <p class="notice-info">"사용함" 선택 시 관리자로 접속하면 '마우스 드래그 차단'과 '오른쪽 마우스 차단'을 해제합니다.</p>
            </td>
        </tr>
    </table>
    <div class="notice-info mgl15 mgb15">
        인터넷 익스플로러(IE) 외 기타 브라우저에서는 지원되지 않을 수 있습니다.
    </div>
    <div class="linepd30"></div>
</form>

<script type="text/javascript">
    <!--
    $(document).ready(function () {
        // 스크롤저장버튼
        $("#frmManageSecurity").validate({
            submitHandler: function (form) {
                //대역 IP 범위 체크
                $validateMessage = '';
                if($('input[name="ipAdminBandWidth[]"], input[name="ipFrontBandWidth[]"]').length > 0){
                    $.each($('input[name="ipAdminBandWidth[]"], input[name="ipFrontBandWidth[]"]'), function(){
                        if($.trim($(this).val()) !== ''){
                            if(parseInt($(this).closest('li').find('input[type="text"]').eq(3).val()) > parseInt($(this).val())){
                                $validateMessage = '정확한 IP 대역을 입력해주세요.';
                                return false;
                            }
                        }
                    });
                }

                if($.trim($validateMessage) === ''){
                    form.target = 'ifrmProcess';
                    form.submit();
                }
                else {
                    setTimeout(function(){
                        layer_close();
                    }, 500);
                    setTimeout(function(){
                        alert($validateMessage);
                    }, 1000);
                }
            },
        });

        $('input:radio[name="ipAdminSecurity"]').click(function (e) {
            changeIpAdminSecurity();
        });
        $('input:radio[name="ipFrontSecurity"]').click(function (e) {
            changeIpFrontSecurity();
        });
        $('.js-admin-add').click(function (e) {
            ipAdminAdd();
        });
        $('.js-admin-del').click(function (e) {
            $(this).closest('li').remove();
        });
        $('.js-front-add').click(function (e) {
            ipFrontAdd();
        });
        $('.js-front-del').click(function (e) {
            $(this).closest('li').remove();
        });
        $('input:checkbox[name="smsSecurityFl"],input:checkbox[name="emailSecurityFl"]').click(function (e) {
            changeSecurity();
        });
        $('input:radio[name="sessionLimitUseFl"]').click(function (e) {
            changeSessionLimit();
        });
        $(document).on("click", 'input[name="ipAdminBandWidthFl[]"],input[name="ipFrontBandWidthFl[]"]', function(){
            if($(this).prop('checked')) {
                $(this).siblings('.js-bandWidth').removeClass('display-none');
            }
            else {
                $(this).closest('li').find('.js-bandWidthText').val('');
                $(this).siblings('.js-bandWidth').addClass('display-none');
            }
        });

        changeIpAdminSecurity();
        changeIpFrontSecurity();
        changeSecurity();
        changeSessionLimit();
    });

    function changeIpAdminSecurity() {
        if ($('input:radio[name="ipAdminSecurity"]:checked').val() == 'y') {
            $('.ipAdmin').show();
        } else if ($('input:radio[name="ipAdminSecurity"]:checked').val() == 'n') {
            $('input:text[name^="ipAdmin"]').val('');
            $('ul.ipAdmin li').remove();
            $('.ipAdmin').hide();
        }
    }

    function changeIpFrontSecurity() {
        if ($('input:radio[name="ipFrontSecurity"]:checked').val() == 'y') {
            $('.ipFront').show();
        } else if ($('input:radio[name="ipFrontSecurity"]:checked').val() == 'n') {
            $('input:text[name^="ipFront"]').val('');
            $('ul.ipFront li').remove();
            $('.ipFront').hide();
        }
    }

    function changeSecurity() {
        if($('input:checkbox[name="emailSecurityFl"]').is(':checked') == false && $('input:checkbox[name="smsSecurityFl"]').is(':checked') == false) {
            $('.js-security').prop('disabled',true);
        } else {
            $('.js-security').prop('disabled',false);
        }
    }

    function changeSessionLimit() {
        if($('input:radio[name="sessionLimitUseFl"]:checked').val() == 'n') {
            $('select[name="sessionLimitTime"]').prop('disabled',true);
        } else {
            $('select[name="sessionLimitTime"]').prop('disabled',false);
        }
    }

    function ipAdminAdd() {
        var addHtml = '';
        addHtml += '<li class="form-inline">';
        addHtml += '	<input type="text" name="ipAdmin[]" value="" class="form-control width5p number" maxlength="3"/>';
        addHtml += '	<input type="text" name="ipAdmin[]" value="" class="form-control width5p number" maxlength="3"/>';
        addHtml += '	<input type="text" name="ipAdmin[]" value="" class="form-control width5p number" maxlength="3"/>';
        addHtml += '	<input type="text" name="ipAdmin[]" value="" class="form-control width5p number" maxlength="3"/>';
        addHtml += '    <span class="js-bandWidth display-none">';
        addHtml += '    ~';
        addHtml += '    <input type="text" name="ipAdminBandWidth[]" value="" class="form-control width5p number js-bandWidthText" maxlength="3"/>';
        addHtml += '    </span>';
        addHtml += '    <input type="checkbox" name="ipAdminBandWidthFl[]" value="y" />대역 지정';
        addHtml += '	<button class="btn btn-sm btn-white btn-icon-minus js-admin-del">삭제</button>';
        addHtml += '</li>';
        $('ul.ipAdmin').append(addHtml);
        $('.js-admin-del').on('click', function (e) {
            $(this).closest('li').remove();
        });
    }

    function ipFrontAdd() {
        var addHtml = '';
        addHtml += '<li class="form-inline">';
        addHtml += '	<input type="text" name="ipFront[]" value="" class="form-control width5p number" maxlength="3"/>';
        addHtml += '	<input type="text" name="ipFront[]" value="" class="form-control width5p number" maxlength="3"/>';
        addHtml += '	<input type="text" name="ipFront[]" value="" class="form-control width5p number" maxlength="3"/>';
        addHtml += '	<input type="text" name="ipFront[]" value="" class="form-control width5p number" maxlength="3"/>';
        addHtml += '    <span class="js-bandWidth display-none">';
        addHtml += '    ~';
        addHtml += '    <input type="text" name="ipFrontBandWidth[]" value="" class="form-control width5p number js-bandWidthText" maxlength="3"/>';
        addHtml += '    </span>';
        addHtml += '    <input type="checkbox" name="ipFrontBandWidthFl[]" value="y" />대역 지정';
        addHtml += '	<button class="btn btn-sm btn-white btn-icon-minus js-front-del">삭제</button>';
        addHtml += '</li>';
        $('ul.ipFront').append(addHtml);
        $('.js-front-del').on('click', function (e) {
            $(this).closest('li').remove();
        });
    }
    //-->
</script>
