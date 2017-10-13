<form id="frmManager" name="frmManager" action="manage_ps.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="mode" value="<?= $data['mode']; ?>"/>
    <input type="hidden" name="sno" value="<?= $data['sno']; ?>"/>
    <input type="hidden" name="isSuper" value="n"/>
    <div class="page-header js-affix">
        <h3><?php echo end($naviMenu->location); ?></h3>
        <div class="btn-group">
            <input type="button" value="목록" class="btn btn-white btn-icon-list" onclick="goList('./manage_list.php');"/>
            <button type="submit" class="btn btn-red">저장</button>
        </div>
    </div>

    <div class="table-title gd-help-manual">
        기본정보
    </div>

    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col class="width-3xl"/>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tbody>
        <?php if (gd_use_provider()){ ?>
        <?php if (!gd_is_provider()){ ?>
        <tr>
            <th class="require">공급사 구분</th>
            <td colspan="3" class="form-inline">
                <label class="radio-inline">
                    <input type="radio" name="scmFl" <?= gd_isset($disabled['scmFl']['n']) ?>
                           value="n" <?= gd_isset($checked['scmFl']['n']); ?>
                        <?= $disabled['modify'] ?>/>
                    본사
                </label>
                <label class="radio-inline">
                    <input type="radio" name="scmFl" value="y" <?= gd_isset($checked['scmFl']['y']); ?>
                           onclick="layer_register('scm','radio')" <?php if ($data['mode'] == 'modify') echo 'disabled' ?> />
                    공급사
                </label>
                <label>
                    <button type="button" class="btn btn-sm btn-gray" onclick="layer_register('scm','radio')" <?= $disabled['modify'] ?>>공급사 선택</button>
                </label>
                <div id="scmLayer" class="selected-btn-group <?= $data['scmNo'] != DEFAULT_CODE_SCMNO && $data['mode'] == 'modify' ? 'active' : '' ?>">
                    <h5>선택된 공급사 : </h5>
                    <div id="info_scm_<?= $data['scmNo'] ?>" class="btn-group btn-group-xs">
                        <input type="hidden" name="scmNo" value="<?= $data['scmNo'] ?>"/>
                        <span class="btn"><?= $data['companyNm'] ?></span>
                    </div>
                </div>
            </td>
        </tr>
        <?php } ?>
        <?php } ?>
        <tr>
            <th class="require">아이디</th>
            <td class="form-inline">
                <input type="hidden" name="chkManagerId" value="<?= $data['managerId']; ?>"/>
                <?php if ($data['mode'] == 'register') { ?>
                    <input type="text" name="managerId" class="form-control width-sm js-maxlength" maxlength="<?= $policy['memId']['maxlen'] ?>"/>
                    <button type="button" id="overlap_managerId" class="btn btn-sm btn-gray" style="margin-left:50px">중복확인</button>
                    <span id="managerId_msg" class="input_error_msg"></span>
                <?php } else { ?>
                    <input type="hidden" name="managerId" value="<?= $data['managerId']; ?>"/>
                    <strong><?= gd_isset($data['managerId']); ?></strong>
                <?php } ?>
            </td>
            <th class="require">비밀번호</th>
            <td class="form-inline">
                <?php if ($data['mode'] == 'register') { ?>
                    <dl class="dl-horizontal">
                        <dt style="width:100px">비밀번호</dt>
                        <dd class="mgl0"><input type="password" name="managerPw" class="form-control width-lg"/></dd>
                        <dt style="width:100px" class="mgt5">비밀번호확인</dt>
                        <dd class="mgt5 mgl0"><input type="password" name="managerPwRe" class="form-control width-lg"/></dd>
                    </dl>
                <?php } else { ?>
                    <label title="관리자 비밀번호를 변경하려면 클릭해주세요!" class="checkbox-inline">
                        <input type="checkbox" name="isModManagerPw" value="y"/>
                        변경
                    </label>
                    <div title="관리자 비밀번호를 입력해주세요!" id="mod_managerPw" class="display-none mgt5">
                        <dl class="dl-horizontal">
                            <dt style="width:100px">비밀번호</dt>
                            <dd class="mgl0"><input type="password" name="modManagerPw" class="form-control width-sm"/></dd>
                            <dt style="width:100px" class="mgt5">비밀번호확인</dt>
                            <dd class="mgt5 mgl0"><input type="password" name="modManagerPwRe" class="form-control width-sm"/></dd>
                        </dl>
                    </div>
                <?php } ?>
                <div class="notice-danger notice-info">
                    영문대문자/영문소문자/숫자/특수문자 중 2가지 이상 조합, 10~16자리 이하로 설정할 수 있습니다.
                </div>
            </td>
        </tr>
        <tr>
            <th class="require">이름</th>
            <td class="form-inline">
                <input type="text" name="managerNm" value="<?= gd_isset($data['managerNm']); ?>" class="form-control js-maxlength" maxlength="20"/>
            </td>
            <th>닉네임</th>
            <td class="form-inline">
                <input type="text" name="managerNickNm" value="<?= gd_isset($data['managerNickNm']); ?>" class="form-control js-maxlength" maxlength="20"/>
            </td>
        </tr>
        <?php if ($data['isSuper'] != 'y' || $data['scmNo'] != '1') { ?>
            <tr>
                <th>로그인제한</th>
                <td colspan="3" class="form-inline">
                    <label class="radio-inline">
                        <input type="radio" name="loginLimitFlag" value="n" <?= gd_isset($checked['loginLimitFlag']['n']); ?>/>
                        제한없음
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="loginLimitFlag" value="y" <?= gd_isset($checked['loginLimitFlag']['y']); ?>/>
                        접속제한
                    </label>
                    <span class="notice-info">운영자 로그인을 5회 이상 실패하여 접속이 제한될 경우 로그인이 제한됩니다.</span>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <th>이미지표시</th>
            <td colspan="3" class="form-inline">
                <?php
                if ($data['isDispImage'] == 'y' && $data['mode'] != 'register' && $data['dispImage']) {
                    echo '<div><img src="' . $data['dispImage'] . '" width="100"  />';
                    echo '&nbsp;<label title="이미지 삭제시 체크해 주세요!" class="radio-inline"><input type="checkbox" name="isImageDelete" value="y" /> 삭제</label><div>';
                }
                ?>
                <div class="pd5"></div>
                <input type="file" name="dispImage" class="width30p form-control"/>
                <input type="hidden" name="dispImage" value="<?= $data['dispImage'] ?>"/>
            </td>
        </tr>
        <tr>
            <th>직원여부</th>
            <td>
                <label class="radio-inline">
                    <input type="radio" name="employeeFl" value="y" <?= gd_isset($checked['employeeFl']['y']); ?> />
                    직원
                </label>
                <label class="radio-inline">
                    <input type="radio" name="employeeFl"
                           value="t" <?= gd_isset($checked['employeeFl']['t']); ?> />
                    비정규직
                </label>
                <label class="radio-inline">
                    <input type="radio" name="employeeFl"
                           value="p" <?= gd_isset($checked['employeeFl']['p']); ?> />
                    아르바이트
                </label>
                <label class="radio-inline">
                    <input type="radio" name="employeeFl"
                           value="d" <?= gd_isset($checked['employeeFl']['d']); ?> />
                    파견직
                </label>
                <label class="radio-inline">
                    <input type="radio" name="employeeFl"
                           value="r" <?= gd_isset($checked['employeeFl']['r']); ?> />
                    퇴사자
                </label>
            </td>
            <th>부서</th>
            <td class="form-inline">
                <?= gd_select_box('departmentCd', 'departmentCd', $department, null, gd_isset($data['departmentCd']), '=부서 선택='); ?>
            </td>
        </tr>
        <tr>
            <th>직급</th>
            <td class="form-inline">
                <?= gd_select_box('positionCd', 'positionCd', $position, null, gd_isset($data['positionCd']), '=직급 선택='); ?>
            </td>
            <th>직책</th>
            <td class="form-inline">
                <?= gd_select_box('dutyCd', 'dutyCd', $duty, null, gd_isset($data['dutyCd']), '=직책 선택='); ?>
            </td>
        </tr>
        <tr>
            <th>SMS 자동발송 <br>수신설정</th>
            <td colspan="3">
                <div class="radio">
                    <label title="SMS 자동발송 수신하지 않음" class="radio-inline">
                        <input type="radio" name="smsAutoFl" value="n" <?= gd_isset($checked['smsAutoFl']['n']); ?> />
                        SMS 자동발송 수신안함
                    </label>
                </div>
                <div class="radio">
                    <label title="SMS 자동발송 수신함" class="radio-inline">
                        <input type="radio" name="smsAutoFl" value="y" <?= gd_isset($checked['smsAutoFl']['y']); ?> />
                        SMS 자동발송 수신함
                    </label>
                    <span class="dp-auto-sms"> :
                        <?php
                        foreach ($smsAutoReceiveKind as $aKey => $aVal) {
                            echo '<label class="checkbox-inline"><input type="checkbox" name="' . $aKey . '" value="y" ' . gd_isset($checked[$aKey]['y']) . ' /> ' . $aVal . '</label>';
                        }
                        ?>
                    </span>
                </div>
            </td>
        </tr>
        <tr>
            <th>전화번호</th>
            <td class="form-inline">
                <label title="전화번호를 입력해 주세요!">
                    <input type="text" name="phone" value="<?= $data['phone']; ?>" maxlength="20" class="form-control js-number-only width-md"/>
                </label>
            </td>
            <th>내선번호</th>
            <td class="form-inline">
                <label title="내선번호가 있는 경우 입력해 주세요!">
                    <input type="text" class="form-control" size="4" name="extension"
                           value="<?= gd_isset($data['extension']); ?>"/>
                </label>
            </td>
        </tr>
        <tr>
            <th>휴대폰번호</th>
            <td class="form-inline" colspan="3">
                <label title="휴대폰 번호를 입력해 주세요!">
                    <input type="text" name="cellPhone" value="<?= $data['cellPhone']; ?>" maxlength="12" class="form-control js-number-only width-md"/>
                </label>
                <button type="button" class="btn btn-gray btn-sm js-request-authno">인증번호받기</button>
                <span id="smsAuthSendBox" class="<?php if (gd_isset($data['isSmsAuth']) == 'y') { ?>display-none<?php } ?>">
                <span class="bold" style="padding-left: 10px !important">인증번호 :</span>
                <input type="text" name="cellPhoneAuthNo" class="form-control"/>
                </span>

                <?php if (gd_isset($data['isSmsAuth']) == 'y') {
                    echo '(인증완료)';
                } else {
                    echo '(미인증)';
                } ?>
                <div class="notice-info">
                    인증이 완료된 휴대폰번호는 관리자페이지 보안로그인 및 화면보안접속 시 인증정보로 사용할 수 있습니다. <a href="/policy/manage_security.php" target="_blank" class="btn-link">운영보안설정 바로가기 ></a>
                </div>
                <div class="notice-info">
                    SMS는 잔여포인트가 있어야 발송됩니다. (잔여 포인트 : <strong class="font-num text-red"><?= gd_get_sms_point(); ?></strong>)
                    <button type="button" class="btn btn-gray btn-sm" onclick="show_popup('../member/sms_charge.php?popupMode=yes')">SMS 포인트 충전하기</button>
                </div>
            </td>
        </tr>

        <tr>
            <th>이메일</th>
            <td class="form-inline" colspan="3">
                <label title="이메일을 입력해 주세요!">
                    <input type="text" name="email[]" value="<?= gd_isset($data['email'][0]); ?>" class="form-control width-md"/>
                    @
                    <input type="text" id="email" name="email[]" value="<?= gd_isset($data['email'][1]); ?>" c lass="form-control width-md"/>
                    <?= gd_select_box('email_domain', null, $emailDomain, null, $data['email'][1]); ?>
                </label>

                <button type="button" class="btn btn-gray btn-sm js-request-authemail">인증번호받기</button>
                <span id="emailAuthSendBox" class="<?php if (gd_isset($data['isEmailAuth']) == 'y') { ?>display-none<?php } ?>">
                인증번호 :
                <input type="text" name="cellEmailAuth" class="form-control"/>
                </span>

                <?php if (gd_isset($data['isEmailAuth']) == 'y') {
                    echo '(인증완료)';
                } else {
                    echo '(미인증)';
                } ?>
                <div class="notice-info">
                    인증이 완료된 이메일은 관리자페이지 보안로그인 및 화면보안접속 시 인증정보로 사용할 수 있습니다.
                    <a href="/policy/manage_security.php" target="_blank" class="btn-link">운영보안설정 바로가기 ></a>
                </div>
            </td>
        </tr>

        <tr>
            <th>메모</th>
            <td colspan="3" class="form-inline">
                <textarea name="memo" rows="5" cols="100" class="form-control"><?= gd_isset($data['memo']); ?></textarea>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="table-title gd-help-manual">
        권한 설정
    </div>
    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <?php if ($useGodoPro === true && $data['scmNo'] == DEFAULT_CODE_SCMNO) { ?>
        <tbody id="workPermissionFl">
        <tr>
            <th>개발권한</th>
            <td class="form-inline">
                <label class="radio-inline">
                    <input type="radio" name="workPermissionFl" value="y"
                           <?= gd_isset($checked['workPermissionFl']['y']); ?> />
                    설정함
                </label>
                <label class="radio-inline">
                    <input type="radio" name="workPermissionFl" value="n"
                           <?= gd_isset($checked['workPermissionFl']['n']); ?> />
                    설정안함
                </label>
                <p class="notice-info">
                    개발권한 설정 시 관리자 상단의 [개발소스보기]가 활성화되어 쇼핑몰 개발소스를 확인/복사할 수 있습니다.
                </p>
            </td>
        </tr>
        </tbody>
        <tbody id="debugPermissionFl" <?=$data['workPermissionFl'] !== 'y' ? 'style="display:none;"' : ''?>>
        <tr>
            <th>디버그권한</th>
            <td class="form-inline">
                <label class="radio-inline">
                    <input type="radio" name="debugPermissionFl" value="y"
                        <?= gd_isset($checked['debugPermissionFl']['y']); ?> />
                    설정함
                </label>
                <label class="radio-inline">
                    <input type="radio" name="debugPermissionFl" value="n"
                        <?= gd_isset($checked['debugPermissionFl']['n']); ?> />
                    설정안함
                </label>
                <p class="notice-info">
                    디버그권한 설정 시 오류가 발생하면 오류페이지 템플릿 하단에 Exception 메시지가 별도로 출력됩니다.
                </p>
            </td>
        </tr>
        </tbody>
        <?php } ?>
        <tbody>
        <tr>
            <th>운영권한</th>
            <td class="form-inline">
                <label class="radio-inline">
                    <input type="radio" name="permissionFl" value="s"
                           onclick="display_toggle('permission','hide');" <?= gd_isset($checked['permissionFl']['s']); ?> <?php if ($data['isSuper'] == 'y') echo 'disabled' ?> />
                    전체권한
                </label>
                <label class="radio-inline">
                    <input type="radio" name="permissionFl" value="l"
                           onclick="display_toggle('permission','show');" <?= gd_isset($checked['permissionFl']['l']); ?> <?php if ($data['isSuper'] == 'y') echo 'disabled' ?> />
                    권한선택
                </label>
            </td>
        </tr>
        </tbody>
    </table>
    <div id="permission" class="display-none">
    </div>
</form>

<script type="text/javascript">
    <!--
    var isUseProvider = '<?=gd_use_provider()?>';
    $(document).ready(function () {
        var frmObj = $('#frmManager');
        frmObj.validate({
            debug: false,
            onclick: false,
            onfocusout: false,
            onkeyup: false,
            ignore: ':hidden',
            submitHandler: function (form) {
                if ($('input[name="permissionFl"][value="l"]').is(':checked')) {
                    var isChecked = false;
                    $("input:checkbox[name^=permission_3]").each(function () {
                        if ($(this).is(':checked')) {
                            isChecked = true;
                            return false;
                        }
                    });
                    if (isChecked === false) {
                        alert('화면 접근권한은 최소 1개 이상 설정하셔야 합니다.');
                        return false;
                    }
                }

                form.target = 'ifrmProcess';
                form.submit();
            },
            // onclick: false, // <-- add this option
            rules: {
                //                'sub_goods[]': {
                //                    required: function () {
                //                        return $('input[name="permissionFl"][value="l"]').is(':checked');
                //                    }
                //                },
                scmFl: {
                    required: true
                }
                ,
                managerId: {
                    required: true,
                    minlength: 4,
                    maxlength: 50,
                    pattern: /^[a-zA-Z0-9\-\_\.\!\#\$\%\&\+\/\=\?\[\]\^\`\{\|\}\~\@]*$/,
                    equalTo: 'input[name=chkManagerId]'
                },
                managerNm: {
                    required: true,
                    minlength: 2,
                    pattern: /^[가-힣ㅏ-ㅣa-zA-Z0-9]*$/,   //한글영문만
                    maxlength: 20,
                },
                managerPw: {
                    required: true,
                    minlength: 10,
                    maxlength: 16,
                    passwordCondition: true,
                    equalTo: "input[name=managerPwRe]"
                },
                modManagerPw: {
                    required: "input[name=isModManagerPw]:checked",
                    passwordCondition: "input[name=isModManagerPw]:checked",
                    equalTo: "input[name=modManagerPwRe]"
                },
                managerNickNm: {
                    maxlength: 20
                },
                memo: {
                    maxlength: 300
                },
                phone: {
                    number: true,
                    minlength: 7,
                    maxlength: 20
                },
                cellPhone: {
                    number: true,
                    minlength: 7,
                    maxlength: 20
                },
                <?php if (!gd_is_provider()){ ?>
                'functionAuth[orderReceiptProcess]': {
                    required: function () {
                        var required = false;
                        if ($('input:checkbox[name="functionAuth[orderState]"]').prop("checked") == true) {
                            required = true;
                        }
                        return required;
                    },
                }
                <?php } ?>
            },
            messages: {
                scmFl: {
                    required: '공급사 구분을 체크해주세요.'
                },
                managerId: {
                    required: '아이디를 입력해주세요.',
                    minlength: ' 입력된 아이디 길이가 너무 짧습니다. 아이디는 영문/숫자/특수문자를 사용하여 4~<?= $policy['memId']['maxlen'] ?>자까지 입력 가능합니다.',
                    maxlength: '최대 {0} 자 이하로 입력해주세요.',
                    equalTo: '아이디 중복체크 해주세요.',
                    pattern: '허용할 수 없는 아이디 입니다.(최대 20자 입력 가능, 한글/영문/숫자/특수문자 입력가능합니다.)',
                },
                managerNm: {
                    required: '이름을 입력해주세요.',
                    minlength: '입력된 이름 길이가 너무 짧습니다. 이름은 한글/영문을 사용하여 2~20자까지 입력 가능합니다.',
                    maxlength: '최대 {0} 자 이하로 입력해주세요.',
                    pattern: '허용할 수 없는 이름입니다.(이름)',
                },
                managerPw: {
                    required: "비밀번호를 입력해주세요",
                    minlength: '비밀번호는 영문대문자/영문소문자/숫자/특수문자 중 2가지 이상 조합, 10~16자리 이하로 설정할 수 있습니다.',
                    maxlength: '비밀번호는 영문대문자/영문소문자/숫자/특수문자 중 2가지 이상 조합, 10~16자리 이하로 설정할 수 있습니다.',
                    equalTo: "비밀번호 확인 값 비밀번호와 다릅니다."
                },
                modManagerPw: {
                    required: "비밀번호를 입력해주세요",
                    minlength: '최소 {0} 자 이상 입력해주세요.',
                    maxlength: '최대 {0} 자 이하로 입력해주세요.',
                    equalTo: "비밀번호 확인 값 비밀번호와 다릅니다."
                },
                managerNickNm: {
                    maxlength: '최대 {0}자 이하로 입력해주세요.',
                },
                memo: {
                    maxlength: '최대 {0}자 이하로 입력해주세요.',
                },
                phone: {
                    number: '전화번호가 정확하지 않습니다. 확인 후 다시 입력해주세요.',
                    minlength: '전화번호가 정확하지 않습니다. 확인 후 다시 입력해주세요.',
                    maxlength: '전화번호가 정확하지 않습니다. 확인 후 다시 입력해주세요.'
                },
                cellPhone: {
                    number: '전화번호가 정확하지 않습니다. 확인 후 다시 입력해주세요.',
                    minlength: '전화번호가 정확하지 않습니다. 확인 후 다시 입력해주세요.',
                    maxlength: '전화번호가 정확하지 않습니다. 확인 후 다시 입력해주세요.'
                },
                <?php if (!gd_is_provider()){ ?>
                'functionAuth[orderReceiptProcess]': {
                    required: "기능권한의 주문상태변경 권한은 현금영수증 처리(발급/거절/취소/삭제)를 체크하셔야 합니다.",
                }
                <?php } ?>
            },
        });

        $.validator.addMethod('passwordCondition', function (value, element, param) {
            var reg_uid = [];
            reg_uid[0] = /(?=.*[a-zA-Z])/; //10~30자 영문, 숫자 사용가능
            reg_uid[1] = /(?=.*[0-9])/; //10~30자 영문, 숫자 사용가능
            reg_uid[2] = /(?=.*[!@#$%^*+=-])/; //10~30자 영문, 숫자 사용가능

            if (value.length < 10 || value.length > 16) {
                return false;
            }

            var condition = 0;
            for (var i = 0; i < reg_uid.length; i++) {
                if (reg_uid[i].test(value) == true) {
                    condition++;
                }
            }

            if (condition < 2) {
                return false;
            }

            return true;
        }, '영문대문자/영문소문자/숫자/특수문자 중 2가지 이상 조합, 10~16자리 이하로 설정할 수 있습니다.');
        //숫자로 된곳은 무조건 입력받게
        $(".js-number").each(function () {
            $(this).rules('add', {
                    required: true,
                    messages: {
                        required: '입력해주세요.'
                    }
                }
            );
        });

        $('.js-request-authno').bind('click', function () {
            var cellPhone = '';
            var cellPhoneFlag = true;
            if ($('#smsAuthSendBox').is(':visible') == false) {
                $('#smsAuthSendBox').show();
            }

            if ($('input[name="cellPhone"]').val() == '') {
                alert('인증번호를 받을 휴대폰번호를 입력해주세요.');
                cellPhoneFlag = false;
                return false;
            }
            cellPhone = $('input[name="cellPhone"]').val();

            if (cellPhoneFlag === false) return false;

            $.post('manage_ps.php', {
                'mode': 'authSms',
                'cellPhone': cellPhone,
                dataType: 'text',
            }, function (data, status) {
                if (status == 'success') {
                    alert(data);
                }
                else {
                    alert('request fail. ajax status (' + data + ')');
                }
            });

        });

        $('.js-request-authemail').bind('click', function () {
            var email = '';
            var emailFlag = true;
            if ($('#emailAuthSendBox').is(':visible') == false) {
                $('#emailAuthSendBox').show();
            }

            var emailForm = $('input[name="email[]"]');
            if ($(emailForm[0]).val() == '' || $(emailForm[1]).val() == '') {
                alert('인증번호를 받을 이메일을 입력해주세요.');
                emailFlag = false;
                return false;
            }
            email = $(emailForm[0]).val();
            email += '@' + $(emailForm[1]).val();

            if (emailFlag === false) return false;

            $.post('manage_ps.php', {
                'mode': 'authEmail',
                'email': email,
                dataType: 'text',
            }, function (data, status) {
                if (data == 'success') {
                    alert('인증번호가 발송되었습니다.');
                }
                else {
                    alert(data);
                }
            });
        });

        // 관리자 아이디 중복확인
        $("#overlap_managerId").bind('click', function () {
            var managerId = $('input[name=managerId').val().trim();
            if (managerId == '') {
                alert('아이디를 입력해주세요.');
                return false;
            }
            $.ajax({
                method: "GET",
                cache: false,
                url: "./manage_ps.php",
                data: "mode=overlapManagerId&managerId=" + managerId,
                dataType: 'json'
            }).success(function (data) {
                alert(data['msg']);
                if (data['result'] == 'ok') {
                    $('input[name=chkManagerId]').val(managerId);
                }
                else {
                    $('input[name=chkManagerId]').val('');
                }
            }).error(function (e) {
                alert(e.responseText);
            });
        });

        // 비밀번호변경
        $('input[name=\'isModManagerPw\']').bind('click', function () {
            $('#mod_managerPw').hide();
            if (this.checked) {
                $('#mod_managerPw').show();
            }
        });

        // 이메일 도메인 선택
        $('#email_domain').change(function () {
            put_email_domain('email')
        });

        // SMS 자동발송 수신 여부
        $('input[name=\'smsAutoFl\']').bind('click', function () {
            if ($('input[name=\'smsAutoFl\']:checked').val() == 'y') {
                $('.dp-auto-sms').show();
            } else {
                $('.dp-auto-sms').hide();
            }
        });
        <?php
        if ($data['smsAutoFl'] === 'y') {
            echo '$(\'.dp-auto-sms\').show();';
        } else {
            echo '$(\'.dp-auto-sms\').hide();';
        }
        ?>

        // 숫자 체크
        $('input[name*=\'phone\']').number_only();
        $('input[name*=\'extension\']').number_only();
        $('input[name*=\'cellPhone\']').number_only();

        $('input[name=scmFl][value=n]').bind('click', function () {
            changePermissionLayout();
            permissionToggle();
        });
        changePermissionLayout();
        permissionToggle();

        // 본사 클릭시 처리
        $('input[name="scmFl"]:eq(0)').click(function (e) {
            var data = {};
            data.info = [{scmNo: 1}];
            developmentPermissionToggle(data);
        });

        // 개발권한 설정함 체크시
        $('input[name="workPermissionFl"]').click(function (e) {
            $('#debugPermissionFl').hide();
            if ($(this).val() === 'y') {
                $('#debugPermissionFl').show();
            }
        });
    });

    function changePermissionLayout(data) {
        if (!isUseProvider) {   //공급사앱을 사용하지 않으면
            scmFl = 'n';
            <?php  if ($data['scmNo'] != DEFAULT_CODE_SCMNO) {?>
            scmFl = 'y';
            <?php }?>

        } else {
            scmFl = ($('input[name=scmFl]:checked').val());
        }

        var scmNoParam = '';
        if (data && data.info[0].scmNo > 0) {
            var scmNoParam = '&scmNo=' + $('input:hidden[name="scmNo"]').val();
        }

        $.ajax({
            method: "GET",
            cache: false,
            url: "./manage_ps.php",
            data: "mode=getAuthLayer&scmFl=" + scmFl + "&sno=" + frmManager.sno.value + "&rMode=" + '<?= $data['mode']; ?>' + scmNoParam,
            dataType: 'html'
        }).success(function (data) {
            $("#permission").html(data);
        }).error(function (e) {
            alert(e.responseText);
        });
    }

    function permissionToggle() {
        <?php
        // 권한 설정
        if ($data['permissionFl'] == 'l') {
            echo '	display_toggle(\'permission\',\'show\')' . chr(10);

            $chkKey = 'permission';
            foreach ($data as $key => $val) {
                if (substr($key, 0, 10) == $chkKey && $key != $chkKey . 'Fl') {
                    if (!preg_match("/sub_/", $data[$key])) {
                        if (empty($data[$key]) == false) {
                            echo '	check_toggle(\'' . $key . '\',\'sub_' . $data[$key] . '\');' . chr(10);
                        }
                    }
                }
            }
        }
        ?>
    }

    /**
     * 공급사에 따라서 개발소스관리 여부 사용 여부 결정
     */
    function developmentPermissionToggle(data) {
        if ($('#workPermissionFl').length > 0) {
            if (data.info[0].scmNo == '1') {
                $('#workPermissionFl').show();
                $('#debugPermissionFl').show();
            } else {
                $('#workPermissionFl').hide();
                $('#debugPermissionFl').hide();
            }
        }
    }

    /**
     * 출력 여부
     *
     * @param string arrayID 해당 ID
     * @param string modeStr 출력 여부 (show or hide)
     */
    function display_toggle(thisID, modeStr) {
        if (modeStr == 'show') {
            $('#' + thisID).attr('class', 'display-block');
        } else if (modeStr == 'hide') {
            $('#' + thisID).attr('class', 'display-none');
        }
    }

    function layer_register(typeStr, mode) {
        var addParam = {
            "mode": mode,
        };

        if (typeStr == 'scm') {
            addParam['callFunc'] = 'setScmSelect';
            $('input:radio[name=scmFl]:input[value=y]').prop("checked", true);
            $('select[name="add_must_info_sel"]').html("<option>= 상품 필수 정보 선택 =</option>");
            $('select[name="add_goods_info_sel"]').html("<option>= 추가 상품 그룹  정보 선택 =</option>");
        }

        if (typeStr == 'relation') {
            addParam['layerFormID'] = 'layerRelationGoodsForm';
            addParam['parentFormID'] = 'relationGoodsInfo';
            addParam['dataFormID'] = 'relationGoods';
            addParam['dataInputNm'] = 'relationGoodsNo';
            typeStr = 'goods';
            addParam['callFunc'] = 'setRelation';
        }

        layer_add_info(typeStr, addParam);
    }

    function setScmSelect(data) {
        //공급사 값 세팅
        displayTemplate(data);
        developmentPermissionToggle(data);
        changePermissionLayout(data);
    }

    //-->
</script>
