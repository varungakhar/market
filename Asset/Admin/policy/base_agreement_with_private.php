<form id="formSetup" name="formSetup" action="../policy/base_agreement_with_private_ps.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="mode" value="<?php echo $mode; ?>"/>
    <input type="hidden" name="mallSno" value="<?php echo $mallSno; ?>"/>
    <div class="page-header js-affix">
        <h3><?= end($naviMenu->location); ?>
            <small></small>
        </h3>
        <input id="btnSubmit" type="button" value="저장" class="btn btn-red"/>
    </div>
    <div>
        <?php if ($mallCnt > 1) { ?>
            <ul class="multi-skin-nav nav nav-tabs" style="margin-bottom:20px;">
                <?php foreach ($mallList as $key => $mall) { ?>
                    <li class="js-popover <?php echo $mallSno == $mall['sno'] ? 'active' : 'passive'; ?>" data-html="true" data-content="<?php echo $mall['mallName']; ?>" data-placement="top">
                        <a data-type="mallSno" data-mall-sno="<?php echo $mall['sno']; ?>">
                            <span class="flag flag-16 flag-<?php echo $mall['domainFl']?>"></span>
                            <span class="mall-name"><?php echo $mall['mallName']; ?></span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>

        <ul class="nav nav-tabs mgb20">
            <li role="presentation" class="<?= $mode == "agreement" ? "active" : "" ?>">
                <a href="#agreement" aria-controls="agreement">이용약관</a></li>
            <li role="presentation" class="<?= $mode == "private" ? "active" : "" ?>">
                <a href="#private" aria-controls="private">개인정보처리방침</a></li>
            <li role="presentation" class="<?= $mode == "privateItem" ? "active" : "" ?>">
                <a href="#privateItem" aria-controls="privateItem">개인정보수집 동의항목 설정</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane <?= $mode == "agreement" ? "active" : "" ?>" id="agreement" data-status="none">
                <div class="table-title gd-help-manual mgt10">
                    이용약관 내용
                </div>
                <table class="table table-cols">
                    <colgroup>
                        <col class="width-md"/>
                        <col/>
                    </colgroup>
                    <tbody>
                    <tr>
                        <th>표준약관 사용여부</th>
                        <td>
                            <label class="radio-inline <?php echo $display; ?>" title="전자상거래(인터넷사이버몰) 표준약관을 사용시 체크해주세요!" for="agreementModeFlY">
                                <input id="agreementModeFlY" type="radio" name="agreementModeFl" value="y" <?= $agreement['checked']['modeFl']['y'] ?> />
                                표준 약관 사용
                            </label>
                            <label class="radio-inline" title="전자상거래(인터넷사이버몰) 표준약관을 수정하시거나 다른 내용을 넣을시 체크해주세요!" for="agreementModeFlN">
                                <input id="agreementModeFlN" type="radio" name="agreementModeFl" value="n" <?= $agreement['checked']['modeFl']['n'] ?> />
                                약관 직접입력
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            약관내용
                            <button type="button" class="btn btn-gray btn-sm mgt5" id="btnAgreementReplace" name="btnViewReplaceCode">치환코드 보기</button>
                        </th>
                        <td>
                            <div id="agreement_y" class="display-none">
                                <div class="bold">전자상거래(인터넷사이버몰) 표준약관 - 표준약관 제10023호</div>
                                <textarea id="agree_standard" rows="26" class="form-control" readonly="readonly"><?php include 'base_agreement.txt'; ?></textarea>
                            </div>
                            <div id="agreement_n" class="display-none">
                                <textarea id="agree_nonstandard" rows="26" class="form-control"><?= $agreement['content'] ?></textarea>
                            </div>
                        </td>
                    </tr>
                    <tr class="js-standard-display">
                        <th>약관적용일</th>
                        <td>
                            <label class="form-inline mgr5">
                                <input type="text" name="agreementDate[year]" maxlength="4" value="<?= $data['agreementDate']['year'] ?>"/>
                                년
                            </label>
                            <label class="form-inline mgr5">
                                <input type="text" name="agreementDate[month]" maxlength="2" value="<?= $data['agreementDate']['month'] ?>"/>
                                월
                            </label>
                            <label class="form-inline mgr5">
                                <input type="text" name="agreementDate[day]" maxlength="2" value="<?= $data['agreementDate']['day'] ?>"/>
                                일
                            </label>
                        </td>
                    </tr>
                    <tr class="js-standard-display" id="logoRow">
                        <th>공정거래위원회 로고<br/>하단푸터 노출여부</th>
                        <td>
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="logoDefault" class="radio-inline">
                                        <input type="radio" name="logoFl" id="logoDefault" value="default" <?= $agreement['checked']['logoFl']['default'] ?>>
                                        기본 로고 이미지 노출
                                    </label>
                                    <label for="logoUpload" class="radio-inline">
                                        <input type="radio" name="logoFl" id="logoUpload" value="upload" <?= $agreement['checked']['logoFl']['upload'] ?>>
                                        노출 이미지 직접등록
                                    </label>
                                    <label class="radio-inline" for="logoNo">
                                        <input type="radio" name="logoFl" id="logoNo" value="no" <?= $agreement['checked']['logoFl']['no'] ?>>
                                        노출 안함
                                    </label>
                                </div>
                            </div>
                            <div class="row mgt30 mgb20 display-none" id="divLogoDefault">
                                <div class="col-lg-12">
                                    <img src="<?= PATH_ADMIN_GD_SHARE . 'img/logo_kftc.png' ?>" alt="공정거래위원회 기본 로고">
                                </div>
                            </div>
                            <div class="row mgt30 mgb20 display-none" id="divLogoUpload">
                                <div class="col-lg-5">
                                    <input type="file" name="logoUploadFile" class="width70p">
                                    <span class="notice-info">gif, png, jpe, jpeg, jpg, tif 파일 등록 가능합니다.</span>
                                    <input type="hidden" name="logoUploadFileTmp" value="<?= $data['logoUploadFile'] ?>">
                                </div>
                                <div class="col-lg-7">
                                    <?php
                                    $hasUploadLogo = $data['logoUploadFile'] != '' && !is_array($data['logoUploadFile']);
                                    if ($hasUploadLogo) {
                                        echo '<img id="uploadPreview" src="' . UserFilePath::data('common', $data['logoUploadFile'])->www() . '"/>';
                                        echo '<label class="checkbox-inline"><input type="checkbox" name="uploadDeleteFl" value="y"/>삭제</label>';
                                    } else {
                                        echo '<img id="uploadPreview" src=""/>';
                                        echo '<label class="checkbox-inline"><input type="checkbox" name="uploadDeleteFl" value="y"/>삭제</label>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div role="tabpanel" class="tab-pane <?= $mode == "private" ? "active" : "" ?>" id="private">
                <div class="table-title gd-help-manual mgt10">
                    개인정보처리방침 내용
                </div>
                <table class="table table-cols">
                    <colgroup>
                        <col class="width-lg"/>
                        <col/>
                    </colgroup>
                    <tbody>
                    <tr>
                        <th>개인정보처리방침 내용
                            <button type="button" class="btn btn-gray btn-sm mgt5" id="btnReplacePrivate" name="btnViewReplaceCode">치환코드 보기</button>
                        </th>
                        <td>
                            <textarea name="privateContent" rows="20" class="form-control"><?= $private['content'] ?></textarea>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="table-title gd-help-manual">
                    개인정보 보호책임자 입력
                </div>
                <table class="table table-cols">
                    <colgroup>
                        <col class="width-md"/>
                        <col class="width-xl"/>
                        <col class="width-md"/>
                        <col/>
                    </colgroup>
                    <tr>
                        <th>보호 책임자 이름</th>
                        <td colspan="3">
                            <label title="">
                                <input type="text" name="personalInfoManager[privateNm]" value="<?= $private['personalInfoManager']['privateNm'] ?>" class="form-control width-md"/>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th>보호 책임자 직책</th>
                        <td>
                            <label title="">
                                <input type="text" name="personalInfoManager[privatePosition]" value="<?= $private['personalInfoManager']['privatePosition'] ?>" class="form-control width-md"/>
                            </label>
                        </td>
                        <th>보호 책임자 부서</th>
                        <td>
                            <label title="">
                                <input type="text" name="personalInfoManager[privateDepartment]" value="<?= $private['personalInfoManager']['privateDepartment'] ?>" class="form-control width-md"/>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th>보호 책임자 전화번호</th>
                        <td colspan="3">
                            <div class="form-inline">
                                <label title="">
                                    <input type="text" name="personalInfoManager[privatePhone]" value="<?= $private['personalInfoManager']['privatePhone']; ?>" maxlength="12" class="form-control js-number-only width-md"/>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>보호 책임자 이메일</th>
                        <td colspan="3">
                            <div class="form-inline">
                                <label title="">
                                    <input type="text" name="personalInfoManager[privateEmail][]" value="<?= $private['personalInfoManager']['privateEmail'][0] ?>" class="form-control width-lg"/>
                                    @
                                    <input type="text" id="privateEmail" name="personalInfoManager[privateEmail][]" value="<?= $private['personalInfoManager']['privateEmail'][1] ?>" class="form-control width-md"/>
                                    <?= gd_select_box('email_domain', null, $emailDomain, null, $private['personalInfoManager']['privateEmail'][1]); ?>
                                </label>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div role="tabpanel" class="tab-pane <?= $mode == "privateItem" ? "active" : "" ?>" id="privateItem">
                <div class="table-title gd-help-manual mgt10">
                    회원 대상 동의항목 설정
                </div>
                <table class="table table-cols">
                    <caption>
                        [필수] 개인정보 수집.이용 동의
                    </caption>
                    <colgroup>
                        <col class="width-md"/>
                        <col/>
                    </colgroup>
                    <tbody>
                    <tr>
                        <th>내용입력</th>
                        <td>
                            <textarea id="privateApproval" name="privateApproval" rows="12" class="form-control"><?= $privateApprovalContent ?></textarea>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <?= $privateApprovalTableOption ?>
                <?= $privateConsignTable ?>
                <?= $privateOfferTable ?>
                <div class="table-title gd-help-manual mgt10">
                    비회원 대상 동의항목 설정
                </div>
                <table class="table table-cols">
                    <caption>
                        [필수] 개인정보 수집ㆍ이용 동의 (비회원 주문 시)
                    </caption>
                    <colgroup>
                        <col class="width-md"/>
                        <col/>
                    </colgroup>
                    <tbody>
                    <tr>
                        <th>내용입력</th>
                        <td>
                            <textarea id="privateGuestOrder" name="privateGuestOrder" rows="12" class="form-control"><?= $privateGuestOrderContent ?></textarea>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table class="table table-cols">
                    <caption>
                        [필수] 개인정보 수집ㆍ이용 동의 (비회원 게시글 등록 시)
                    </caption>
                    <colgroup>
                        <col class="width-md"/>
                        <col/>
                    </colgroup>
                    <tbody>
                    <tr>
                        <th>내용입력</th>
                        <td>
                            <textarea id="privateGuestBoardWrite" name="privateGuestBoardWrite" rows="12" class="form-control"><?= $privateGuestBoardWriteContent ?></textarea>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table class="table table-cols">
                    <caption>
                        [필수] 개인정보 수집ㆍ이용 동의 (비회원 댓글 등록 시)
                    </caption>
                    <colgroup>
                        <col class="width-md"/>
                        <col/>
                    </colgroup>
                    <tbody>
                    <tr>
                        <th>내용입력</th>
                        <td>
                            <textarea id="privateGuestCommentWrite" name="privateGuestCommentWrite" rows="12" class="form-control"><?= $privateGuestCommentWriteContent ?></textarea>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <?php if (gd_use_provider()) { ?>
                    <div class="table-title gd-help-manual mgt10">
                        회원/비회원 주문 시 상품 공급사 개인정보 제공 동의
                    </div>
                    <table class="table table-cols">
                        <caption>
                            [필수] 회원/비회원 주문 시 상품 공급사 개인정보 제공 동의
                        </caption>
                        <colgroup>
                            <col class="width-md"/>
                            <col/>
                        </colgroup>
                        <tbody>
                        <tr>
                            <th>내용입력</th>
                            <td>
                                <textarea id="privateProvider" name="privateProvider" rows="12" class="form-control"><?= $privateProviderContent ?></textarea>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    <!--
    var changeCheckedValue = '';
    var removeSnoArray = [];
    var moveTab;
    var mallSno;
    $(document).ready(function () {
        var $formSetup = $('#formSetup');

        $.validator.addMethod("year", function (value) {
            var valid = moment(value, 'YYYY', true).isValid();
            var checked = $('#agreementModeFlY').prop('checked');
            var empty = value.length < 1;
            return empty || !checked ? true : valid && checked;
        }, "약관적용일 년도를 입력하시기 바랍니다.");
        $.validator.addMethod("month", function (value) {
            var valid = moment(value, 'M', true).isValid();
            var checked = $('#agreementModeFlY').prop('checked');
            var empty = value.length < 1;
            return empty || !checked ? true : valid && checked;
        }, "약관적용일 월을 입력하시기 바랍니다.");
        $.validator.addMethod("day", function (value) {
            var valid = moment(value, 'D', true).isValid();
            var checked = $('#agreementModeFlY').prop('checked');
            var empty = value.length < 1;
            return empty || !checked ? true : valid && checked;
        }, "약관적용일 일을 입력하시기 바랍니다.");

        var validation = {
            debug: true,
            rules: {
                "agreementDate[year]": {
                    "year": true
                },
                "agreementDate[month]": {
                    "month": true
                },
                "agreementDate[day]": {
                    "day": true
                }
            },
            submitHandler: function (form) {
                var $form = $(form);
                var data = $form.serializeArray();
                var activeTab = $('li[role=presentation].active a').attr('aria-controls');
                switch (activeTab) {
                    case 'agreement':
                        data.push({name: "mode", value: "agreement"});
                        if ($('[name="agreementModeFl"]:checked').val() == 'y') {
                            form.action = 'base_agreement_ps.php';
                            $('input[name="mode"]').val('agreement');
                            form.target = 'ifrmProcess';
                            form.submit();
                            if (moveTab) {
                                window.location.href = 'base_agreement_with_private.php?' + (mallSno ? 'mallSno=' + mallSno + '&': '') + 'mode=' + moveTab;
                            }
                        } else {
                            $.post('base_agreement_ps.php', data, function (data) {
                                if (data.error) {
                                    alert(data.error.message);
                                } else {
                                    layer_close();
                                    if (moveTab) {
                                        dialog_alert(data.message, undefined, {location: 'base_agreement_with_private.php?' + (mallSno ? 'mallSno=' + mallSno + '&': '') + 'mode=' + moveTab});
                                    } else {
                                        dialog_alert(data.message, undefined, {isReload: true});
                                    }
                                }
                            });
                        }
                        break;
                    case 'private':
                        data.push({name: "mode", value: "private"});
                        data.push({name: "removeSno", value: JSON.stringify(removeSnoArray)});
                        $.post($form.attr('action'), data, function (data) {
                            if (data.error) {
                                alert(data.error.message);
                            } else {
                                layer_close();
                                if (moveTab) {
                                    dialog_alert(data.message, undefined, {location: 'base_agreement_with_private.php?' + (mallSno ? 'mallSno=' + mallSno + '&': '') + 'mode=' + moveTab});
                                } else {
                                    dialog_alert(data.message, undefined, {isReload: true});
                                }
                            }
                        });
                        break;
                    case 'privateItem':
                        var titleNull = false;
                        var contentNull = false;
                        var agreeField = ['privateApprovalOption','privateConsign','privateOffer'];

                        $.each(agreeField, function(index, value){
                            if ($('input[name="' + value + 'ModeFl"]:checked').val() == 'y') {
                                if (!$('input[name="' + value + 'Title[]"]').length) {
                                    contentNull = true;
                                    return false;
                                }
                                $.each($('input[name="' + value + 'Title[]"]'), function(){
                                    if (!this.value) {
                                        titleNull = true;
                                        return false;
                                    }
                                });
                                if (titleNull === true) {
                                    return false;
                                }
                                $.each($('textarea[name="' + value + '[]"]'), function(){
                                    if (!this.value) {
                                        contentNull = true;
                                        return false;
                                    }
                                });
                                if (contentNull === true) {
                                    return false;
                                }
                            }
                        });
                        if (titleNull === true) {
                            alert('제목을 입력하셔야 해당 동의항목을 사용하실 수 있습니다.');
                            return false;
                        }
                        if (contentNull === true) {
                            alert('내용을 입력하셔야 해당 동의항목을 사용하실 수 있습니다.');
                            return false;
                        }

                        data.push({name: "mode", value: "privateItem"});
                        data.push({name: "removeSno", value: JSON.stringify(removeSnoArray)});
                        $.post($form.attr('action'), data, function (data) {
                            if (data.error) {
                                alert(data.error.message);
                            } else {
                                layer_close();
                                if (moveTab) {
                                    dialog_alert(data.message, undefined, {location: 'base_agreement_with_private.php?' + (mallSno ? 'mallSno=' + mallSno + '&': '') + 'mode=' + moveTab});
                                } else {
                                    dialog_alert(data.message, undefined, {isReload: true});
                                }
                            }
                        });
                        break;
                }
            }
        };
        $formSetup.validate(validation);

        $formSetup.on('click', '.js-add-row', function (e) {
            e.preventDefault();

            addRow(e);
        });

        $formSetup.on('click', '.js-remove-row', function (e) {
            removeRow(e);
        });

        $('#btnSubmit', $formSetup).click(function () {
            $formSetup.submit();
        });

        $('#btnAgreementReplace', $formSetup).click(function () {
            replace_code_popup('', ['{rc_mallNm}', '{rc_companyNm}']);
        });
        $('#btnReplacePrivate', $formSetup).click(function () {
            replace_code_popup('', ['{rc_mallNm}', '{rc_privateNm}', '{rc_privatePosition}', '{rc_privateDepartment}', '{rc_privatePhone}', '{rc_privateEmail}']);
        });

        $(':radio[name=agreementModeFl]', $formSetup).change(display_toggle_default_agreement).trigger('change');

        $(':radio[name=logoFl]').change(function () {
            var $tr = $(this).closest('tr');
            if (this.value == 'default') {
                $tr.find('div:gt(1)').addClass('display-none');
                $('#divLogoDefault').removeClass('display-none');
                $('#divLogoDefault .display-none').removeClass('display-none');
            } else if (this.value == 'upload') {
                $tr.find('div:gt(1)').addClass('display-none');
                $('#divLogoUpload').removeClass('display-none');
                $('#divLogoUpload').find('*.display-none').each(function (idx, item) {
                    $(item).removeClass('display-none');
                    if ($(item).has(':checkbox') && $('#uploadPreview').attr('src') === '') {
                        $(':checkbox[name=uploadDeleteFl]').closest('label').addClass('display-none');
                    }
                });
            } else {
                $tr.find('div:gt(1)').addClass('display-none');
            }
        });
        $(':radio[name=logoFl]:checked').trigger('change');

        $('input[name=logoUploadFile]').change(function () {
            var $img = $('#uploadPreview');
            var file = this.files[0];
            var reader = new FileReader();
            reader.addEventListener('load', function () {
                $img.attr('src', reader.result);
                $img.closest('div').children().removeClass('display-none');
            });
            if (file) {
                reader.readAsDataURL(file);
            }
        });
        $(':checkbox[name=uploadDeleteFl]').change(function () {
            if (this.value == 'y') {
                $('input[name=logoUploadFile]').val('');
                $('input[name=logoUploadFileTmp]').val('');
            }
        });

        var $tabs = $('.nav-tabs a');
        $tabs.click(function (e) {
            e.preventDefault();
            var $target = $(e.target);
            if ($(this).data('type') == 'mallSno') {
                mallSno = $(this).data('mall-sno');
                moveTab = $('#formSetup input[name="mode"]').val();
            } else {
                mallSno = $('input[name="mallSno"]').val();
                moveTab = $target.attr('aria-controls');
            }
            if ($('.tab-pane.ctive').data('status') == 'keyup' || changeCheckedValue !== '') {
                modalActiveTab();
            } else {
                location.href = '../policy/base_agreement_with_private.php?' + (mallSno ? 'mallSno=' + mallSno + '&': '') + 'mode=' + moveTab;
            }
        });

        var $tabsPanel = $('.tab-pane', $formSetup);

        $tabsPanel.find(':input').on('change', function (e) {
            e.preventDefault();

            var $target = $(e.target);
            $.each($target, function (idx, item) {
                changeCheckedValue = idx;
            });
        });

        $tabsPanel.find('textarea, input').on('keyup', function (e) {
            e.preventDefault();

            $('.tab-pane.active').data('status', 'keyup');
        });

        // 이메일 선택
        $('#email_domain').change(function () {
            var val = $(this).val() == 'self' ? '' : $(this).val();
            $(this).closest('td').find('#privateEmail').val(val);
        });
    });

    function display_toggle_default_agreement(e) {
        e.preventDefault();
        var $target = $(e.target);
        if ($target.prop('checked')) {
            var val = $target.val();
            $('div[id*=\'agreement_\']').attr('class', 'display-none');
            $('#agreement_' + val).attr('class', 'display-block');

            $('textarea[id*=\'agree_\']').attr('name', '');
            if (val == 'y') {
                $('#agree_standard').attr('name', 'agreement');
                $('.js-standard-display').removeClass('display-none');
            } else if (val == 'n') {
                $('#agree_nonstandard').attr('name', 'agreement');
                $('.js-standard-display').addClass('display-none');
            }
        }
    }

    /**
     *  약관 추가 버튼 함수
     * @param e 이벤트 객체
     */
    function addRow(e) {
        var $target = $(e.target);
        var targetName = $target.data('target');
        var $targetRow = $('div[name="' + targetName + '"]');
        var targetId = $targetRow.length + 1;
        _.templateSettings.variable = 'data';
        var template = _.template($('script#titleTextAreaRow').html());
        var templateData = {
            rowId: targetName + targetId,
            rowName: targetName,
            titleId: targetName + "Title" + targetId,
            titleName: targetName + "Title[]",
            buttonTarget: targetName + targetId,
            textAreaName: targetName + "[]",
            textAreaId: targetName + "TextArea" + targetId,
            textAreaIndex: targetId
        };
        $target.closest('tr').next().find('td').append(template(templateData));
    }

    /**
     * 약관 삭제 버튼 함수
     * @param e 이벤트 객체
     */
    function removeRow(e) {
        var $target = $(e.target);
        var targetId = $target.data('target');
        var $targetTextArea = $('#' + targetId);
        removeSnoArray.push($target.data('sno'));
        $targetTextArea.closest('div').remove();
    }

    function modalActiveTab() {
        member.confirm('확인', '저장되지 않은 내용이 있습니다.수정하신 내용을 저장하고 이동하시겠습니까?', function (result) {
            if (result) {
                $('#btnSubmit').trigger('click');
            } else {
                window.location.href = 'base_agreement_with_private.php?' + (mallSno ? 'mallSno=' + mallSno + '&': '') + 'mode=' + moveTab;
            }
        }, {
            btnCancelLabel: "저장하지 않음",
            btnOKLabel: "저장"
        });
    }
    //-->
</script>
<script type="text/template" class="template" id="titleTextAreaRow">
    <div class="js-title-textarea-row" id="<%=data.rowId%>" name="<%=data.rowName%>">
        <div>
            <input type="text" class="form-control width80p pull-left" id="<%=data.titleId%>" name="<%=data.titleName%>" value=""/>
            <button type="button" data-target="<%=data.buttonTarget%>" class="btn btn-sm btn-white btn-icon-minus js-remove-row pull-right">삭제</button>
        </div>
        <div>
            <textarea name="<%=data.textAreaName%>" id="<%=data.textAreaId%>" rows="5" class="pull-left form-control width80p mgt5" data-index="<%=data.textAreaIndex%>"></textarea>
        </div>
    </div>
</script>
