<form id="frmSetup" action="./member_ps.php" method="post">
    <div class="page-header js-affix">
        <h3><?= end($naviMenu->location); ?>
            <small></small>
        </h3>
        <input type="submit" value="저장" class="btn btn-red"/>
    </div>
    <div class="table-title gd-help-manual">
        가입 설정
    </div>
    <table class="table table-cols mgb15">
        <colgroup>
            <col class="width-lg"/>
            <col/>
        </colgroup>
        <tr>
            <th>가입승인 사용설정</th>
            <td>
                <div class="radio mgt0">
                    <label>
                        <input type="radio" name="appUseFl" value="n" <?= $checked['appUseFl']['n']; ?>>
                        승인 절차 없이 가입
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="appUseFl" value="y" <?= $checked['appUseFl']['y']; ?>>
                        승인 후 가입
                    </label>
                </div>
                <div class="radio mgb0">
                    <label>
                        <input type="radio" name="appUseFl" value="company" <?= $checked['appUseFl']['company']; ?>>
                        사업자회원만 승인 후 가입
                    </label>
                </div>
            </td>
        </tr>
        <tr>
            <th>가입연령제한 설정</th>
            <td>
                <div class="radio">
                    <label>
                        <input type="radio" name="under14Fl" value="n" <?= $checked['under14Fl']['n']; ?>>
                        제한 안함
                    </label>
                </div>
                <dl class="dl-horizontal">
                    <dt class="form-inline" style="width: 220px;">
                        만 <select name="limitAge" class="form-control" style="width:120px">
                            <option value="14" <?php if ($data['limitAge'] == 14) echo 'selected' ?>>14</option>
                            <option value="19" <?php if ($data['limitAge'] == 19) echo 'selected' ?>>19</option>
                        </select> 미만인 경우
                    </dt>
                    <dd style="margin-left: 230px;">
                        <div class="radio mgt0">
                            <label>
                                <input type="radio" name="under14Fl" value="y" <?= $checked['under14Fl']['y']; ?>>
                                운영자 승인 후 가입&nbsp;
                                <button type="button" class="btn btn-gray btn-sm" id="btnUnder14Download">법정대리인 동의서 샘플 다운로드</button>
                            </label>
                        </div>
                        <div class="radio mgb0" style="margin-top: -6px;">
                            <label>
                                <input type="radio" name="under14Fl" value="no" <?= $checked['under14Fl']['no']; ?>>
                                가입불가
                            </label>
                        </div>
                    </dd>
                </dl>
            </td>
        </tr>
    </table>

    <div class="notice-info notice-sm mgl15 mgb15">정보통신망법에 따라 만14세 미만의 아동은 법정대리인의 동의를 확인 후 회원가입 할 수 있습니다.<br>
    '운영자 승인 후 가입' 및 '가입불가'로 설정 시 본인확인인증서비스가 적용되어 있어야 하며,<br>
    본인확인인증서비스 미 사용 시에는 '생년월일'을 필수로 입력 받으셔야 합니다.<br>
    본인확인인증서비스 또는 생년월일 필수 설정이 없는 경우 만14세 미만 회원을 판단할 수 없으므로 '미승인'상태로 가입되거나(인증 후 가입 선택 시),<br>
    가입할 수 없으니(가입불가 선택 시) 주의해주시기 바랍니다.&nbsp;<a href="../policy/member_auth_cellphone.php" target="_blank" class="btn-link-underline">휴대폰본인확인관리></a>&nbsp;<a href="../policy/member_auth_ipin.php" target="_blank" class="btn-link-underline">아이핀관리></a></div>
    <div class="linepd30"></div>

    <div class="table-title gd-help-manual">
        탈퇴/재가입 설정
    </div>
    <table class="table table-cols mgb30">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tr>
            <th>재가입 기간제한</th>
            <td>
                <div class="radio form-inline">
                    <label>
                        <input type="radio" name="rejoinFl" value="y" <?= $checked['rejoinFl']['y']; ?>>
                        회원탈퇴/삭제 후
                        <input type="text" name="rejoin" size="4" class="input_int_m js-number" data-number="4" value="<?= $data['rejoin']; ?>">
                        일 동안 재가입 불가
                    </label>
                </div>
                <div class="radio">
                    <label class="radio-inline">
                        <input type="radio" name="rejoinFl" value="n" <?= $checked['rejoinFl']['n']; ?>>
                        사용안함
                    </label>
                </div>
            </td>
        </tr>
    </table>

    <div class="table-title gd-help-manual">
        가입불가 회원아이디
    </div>
    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tr>
            <th>가입불가 회원아이디</th>
            <td>
                <textarea name="unableid" rows="3" cols="" class="form-control"><?= $data['unableid']; ?></textarea>

                <div class="notice-info notice-sm">
                    회원가입을 제한할 아이디를 쉼표(,)로 구분하여 입력하세요.
                </div>
            </td>
        </tr>
    </table>
</form>

<script type="text/javascript">
    <!--
    $(document).ready(function () {
        $('input[name=rejoin]').focusout(function () {
            if (this.value < 1) {
                this.value = 1;
            }
        });

        // 재가입 기간제한
        $('input[name=\'rejoinFl\']').click(setRejoinFl);
        $('input[name=\'rejoinFl\']').each(function () {
            setRejoinFl.call(this);
        });

        $('#btnUnder14Download').click(function (e) {
            window.open('./member_ps.php?mode=under14Download');
            e.preventDefault();
        });

        var $frmSetup = $('#frmSetup');
        $frmSetup.validate({
            submitHandler: function (form) {
                if ($('[name=rejoinFl]:eq(0)', $frmSetup).prop('checked')) {
                    if ($('input[name="rejoin"]').val() < 1) {
                        alert('재가입 기간제한을 사용하지 않으시려면 사용하지 않음을 체크해주시기 바랍니다.');
                        return false;
                    }
                }

                var data = $(form).serializeArray();
                data.push({name: 'mode', value: 'member_join'});
                post_with_reload('./member_ps.php', data);
            }
        });
    });

    /**
     * 재가입 기간제한
     */
    function setRejoinFl() {
        if ($(this).prop('checked') === false) return;

        var thisVal = $('input[name=\'rejoinFl\']:checked').val();

        if (thisVal == 'y') {
            $('input[name=\'rejoin\']').prop('disabled', false);
        } else {
            $('input[name=\'rejoin\']').prop('disabled', true);
        }
    }
    //-->
</script>
