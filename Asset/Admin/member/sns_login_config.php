<form id="form" name="form" action="sns_login_config_ps.php" method="post" target="ifrmProcess">
    <div class="page-header js-affix">
        <h3><?php echo end($naviMenu->location); ?></h3>
        <input type="submit" value="저장" class="btn btn-red"/>
    </div>
    <div class="design-notice-box mgb10">
        <!--@formatter:off-->
        ‘페이스북 로그인 사용설정'을 설정하여 사용하시는 도중에, <strong class="text-darkred">‘앱(APP) ID' 를 변경하시게 되면,</strong><br>
        <span class="text-darkblue">페이스북에서는 새로운 쇼핑몰로 인식하게 되어, <strong>기존 페이스북과 연동된 회원들의 연동이 해제</strong></span>되므로 변경 시 주의하여 주시기 바랍니다.<br>
        (<b>고객은 로그인이 불가하며, 다시 동일 계정으로 쇼핑몰에 재가입해야 하므로</b> 고객 클레임이 발생할 수 있습니다.)<br><br>
        또한, <strong class="text-darkred">"앱(APP) ID 변경 시", 기존 앱 ID 정보는 삭제되어 복구가 불가능</strong>합니다.
        <!--@formatter:on-->
    </div>
    <div class="table-title">
        페이스북 로그인 사용 설정
    </div>
    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tbody>
        <tr>
            <th>사용 여부</th>
            <td>
                <label class="radio-inline">
                    <input type="radio" class="js-radio-sns-login-use" name="snsLoginUse[facebook]" id="snsLoginUseFacebook_Y" value="y" <?= $checked['snsLoginUse']['facebook']['y']; ?>>
                    사용함
                </label>
                <?php if ($godoAppIdFl) { ?>
                    <label class="checkbox-inline mgl5 mgr20">
                        <input type="checkbox" value="y" id="useGodoAppId" name="useGodoAppId" <?= $checked['useGodoAppId']['y']; ?>>
                        간편설정
                    </label>
                <?php } ?>
                <label class="radio-inline">
                    <input type="radio" class="js-radio-sns-login-use" name="snsLoginUse[facebook]" id="snsLoginUseFacebook_N" value="n" <?= $checked['snsLoginUse']['facebook']['n']; ?>>
                    사용안함
                </label>
                <div class="notice notice-info">사용함으로 선택 시 쇼핑몰에 페이스북 로그인 영역이 노출되지 않으면 스킨패치를 진행하시기 바랍니다.</div>
                <?php if ($godoAppIdFl) { ?>
                    <div class="notice notice-info">간편설정을 해지 후 개별 AppID를 사용할 경우 기존 페이스북 회원은 페이스북 로그인 사용을 할 수 없습니다.</div>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <th>App ID</th>
            <td>
                <label>
                    <input type="text" name="appId[facebook]" id="appIdFacebook" value="<?= $appId['facebook']; ?>" class="form-control width-2xl useFl" disabled="disabled"/>
                </label>
            </td>
        </tr>
        <tr>
            <th>App Secret</th>
            <td>
                <label>
                    <input type="text" name="appSecret[facebook]" id="appSecretFacebook" value="<?= $appSecret['facebook']; ?>" class="form-control width-2xl useFl" disabled="disabled"/>
                </label>
            </td>
        </tr>
        </tbody>
    </table>
</form>
<script type="text/javascript">
    <!--
    $(document).ready(function () {
        var use_godo_app_id = $('#useGodoAppId');
        var sns_login_use = $('.js-radio-sns-login-use');
        sns_login_use.change(function () {
            var $table = $(this).closest('table.table');
            if (this.value == 'y' && this.checked) {
                $table.find('input:text:lt(2)').prop('disabled', false);
                $table.find('tr:gt(0)').removeClass('display-none');
                if (use_godo_app_id.length > 0) {
                    use_godo_app_id.prop('disabled', false);
                }
            } else {
                $table.find('input:text:lt(2)').prop('disabled', true);
                if (use_godo_app_id.length > 0) {
                    use_godo_app_id.prop('checked', false);
                    use_godo_app_id.prop('disabled', true);
                }
            }
        }).filter(':checked').trigger('change');

        use_godo_app_id.change(function () {
            var $table = $(this).closest('table.table');
            $table.find('input:text:lt(2)').prop('disabled', this.checked);
            if (this.checked) {
                $table.find('tr:gt(0)').addClass('display-none');
            } else {
                $table.find('tr:gt(0)').removeClass('display-none');
            }

        }).trigger('change');
    });
    //-->
</script>
