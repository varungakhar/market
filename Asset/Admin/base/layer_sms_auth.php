<?php if($superAlertMessage) {?>
    <script type="text/javascript">
        <!--
        $(function () {
            <?php if($messageType == "smsCharge") { ?>
                dialog_confirm("<?=$superAlertMessage?>", function (result) {
                    if (result) {
                        location.replace("../member/sms_charge.php");
                    } else {
                        window.location.reload();
                    }
                });
            <?php } else { ?>
                dialog_alert("<?=$superAlertMessage?>", '알림', {isReload: true});
            <?php } ?>
        });
        //-->
    </script>
<?php exit;
} else if($message) { ?>
    <script type="text/javascript">
        <!--
        $(function () {
            alert("<?=$message?>");
        });
        //-->
    </script>
<?php } ?>
<form name="frmAdminSmsAuth" id="frmAdminSmsAuth" method="post" action="./login_ps.php">
    <input type="hidden" name="mode" value="checkSmsNumber"/>
    <div id="adminSecuritySmsInformation">
        <?php if(count($securitySelect) > 1) { ?>
        <div class="text-center sms-auth-information">
            로그인 보안을 위한 인증번호가<br/>관리자 정보에 등록된 아래 인증수단으로 발송되었습니다.<br>

            <span class="text-center form-inline">
                <?php echo gd_select_box('key', 'key', $securitySelect, null, null, null, null, 'form-control data-type'); ?>
            </span>
            <span class="text-center send-phone-number data-security-smsReSend"><?= $cellPhone; ?></span>
            <span class="text-center send-phone-number data-security-emailSend display-none"><?= $email; ?></span>
            <button type="button" class="btn btn-sm btn-gray js-layer-resend first-send">인증번호 발송</button>
        </div>

        <div class="text-center div-sms-auth">
            <div>해당 인증번호를 아래 입력란에 입력 후<br/>보안인증을 진행하시면 로그인이 완료됩니다.</div>
            <div class="width-lg div-input-number">
                <input type="text" id="smsAuthNumber" name="smsAuthNumber" value="" placeholder="인증번호를 입력해 주세요."
                       class="width-lg" maxlength="8"/>
                <button type="button" class="btn btn-sm btn-gray js-layer-resend">인증번호 재전송</button>
            </div>
            <div class="div-time-count">인증번호 발송을 눌러주세요.<span id="m_timer"></span></div>
        </div>

        <?php } else {
            foreach($securitySelect as $key => $text) {
                ?>
                <input type="hidden" name="key" class="data-type" value="<?=$key?>" />
                <?php if($key == 'smsReSend') {?>
                    <div class="text-center sms-auth-information">
                        로그인 보안을 위한 인증번호가<br/> 관리자 정보에 등록된 아래 <?= $text ?> 번호로 발송되었습니다.
                        <p class="text-center send-phone-number"><?= $cellPhone; ?></p>
                    </div>
                <?php } ?>
                <?php if($key == 'emailSend') {?>
                    <div class="text-center sms-auth-information">
                        로그인 보안을 위한 인증번호가<br/> 관리자 정보에 등록된 아래 <?= $text ?>로 발송되었습니다.
                        <p class="text-center send-phone-number"><?= $email; ?></p>
                    </div>
                <?php } ?>

                <div class="text-center div-sms-auth">
                    <div>해당 인증번호를 아래 입력란에 입력 후<br/>보안인증을 진행하시면 로그인이 완료됩니다.</div>
                    <div class="width-lg div-input-number">
                        <input type="text" id="smsAuthNumber" name="smsAuthNumber" value="" placeholder="인증번호를 입력해 주세요."
                               class="width-lg" maxlength="8"/>

                    </div>
                    <span class="div-time-count">인증번호 재발송을 눌러주세요.<span id="m_timer"></span></span>
                    <button type="button" class="btn btn-sm btn-gray js-layer-resend">인증번호 재전송</button>
                </div>
            <?php } ?>
        <?php } ?>

        <div class="text-center div-sms-auth capcha <?php if($retry < 5) { echo'display-none'; } ?>">
            <div class="capcha-text">
                <p class="pre">자동입력 방지를 위해 아래 이미지의 문자 및 숫자를<br>순서대로 입력해 주세요.</p>
            </div>
            <span class="capcha-img"><img src="../base/captcha.php" align="absmiddle" id="captchaImg"/></span>
                <span class="div-auth-number">
                    <input type="text" id="capchaNumber" name="capchaNumber" value="" placeholder="자동등록방지문자" class="width-sm capchaNumber" maxlength="5"/>
                </span>
            <div class="capcha-reload"><a href="javascript:captchaReload();">새로고침</a></div>

        </div>

    </div>

    <div class="table-btn">
        <button type="button" class="btn btn-lg btn-white js-layer-close">취소</button>
        <button type="button" class="btn btn-lg btn-black js-layer-submit">인증완료</button>
    </div>
</form>

<script type="text/javascript">
    <!--
    function captchaReload() {
        $('#captchaImg').removeAttr('src');
        setTimeout(function () {
            var someDate = new Date();
            someDate = someDate.getTime();
            $('#captchaImg').attr('src', '../base/captcha.php?ch=' + someDate);
        }, 1);
    }

    $(function () {
        var retry = <?=$retry?>;
        var sendRetry = 0;

        $('#frmAdminSmsAuth').validate({
            submitHandler: function (form) {
                $('input:hidden[name="mode"]').val('checkSmsNumber');
                form.target = 'ifrmProcess';
                form.submit();
            },
            rules: {
                "smsAuthNumber": 'required'
            },
            messages: {
                "smsAuthNumber": '인증번호를 입력해주세요.'
            }
        });
        // 인증번호 체크
        $('.js-layer-submit').click(function (e) {
            // validate 레이어 에서 처리 시 현재 레이어가 변경되어지므로 별도 체크를 함
            if ($.trim($('#smsAuthNumber').val()) == '') {
                alert('인증번호를 입력해주세요.');
                return false;
            }

            if(retry > 4) {
                if ($.trim($('#capchaNumber').val()) == '') {
                    alert('자동입력 방지문자를 입력해주세요.');
                    return false;
                }
            }

            var params = $('#frmAdminSmsAuth').serialize();
            $.post('./login_ps.php', params, function (data) {
                if (data.error == 1) {
                    if (data.message == 'CAPTCHA_FAIL') {
                        dialog_alert('자동등록 방지문자가 맞지 않습니다.', '알림', {isReload: false});
                    } else if (data.message == 'AUTH_NUMBER_FAIL') {
                        if (data.retry > 4) {
                            dialog_alert('관리자 인증번호가 맞지 않습니다.', '알림', {isReload: true});
                        } else {
                            dialog_alert('관리자 인증번호가 맞지 않습니다.', '알림', {isReload: false});
                        }
                    }
                } else {
                    dialog_alert("관리자 접속이 승인되었습니다.", '알림', {location: data.link});
                }
            });
        });
        // 인증번호 재전송
        $('.js-layer-resend').click(function (e) {
            sendRetry += 1;
            <?php if(count($securitySelect) > 1) { ?>
            var params = {
                mode: $(".data-type :selected").val()
            };
            <?php } else { ?>
            var params = {
                mode: $(".data-type").val()
            };
            <?php } ?>

            $.post('./login_ps.php', params, function (data) {
                if (data.error == 1) {
                    if (data.message == 'SMS Point Fail') {
                        dialog_alert('SMS 포인트가 소진되어 인증수단이 이메일로 자동 전환됩니다.', '알림', {isReload: true});
                        $('input:hidden[name="mode"]').val('');
                        clearInterval(window['timer_Mm_timer']);
                        layer_close();
                        location.reload();
                    } else {
                        dialog_alert('SMS 인증번호 전송에 실패하였습니다. 다시 시도해 주세요.', '알림', {isReload: false});
                        $('input:hidden[name="mode"]').val('');
                        clearInterval(window['timer_Mm_timer']);
                        layer_close();
                        location.reload();
                    }
                } else if(data.error == 2) {
                    // 이메일 발송실패
                    dialog_alert(data.message, '알림', {isReload: false});
                } else if(data.error == 3) {
                    // 이메일 인증정보가 없는 경우
                    dialog_alert(data.message, '알림', {isReload: true});
                } else {
                    dialog_alert("인증번호가 발송되었습니다.", '알림', {isReload: false});
                    clearInterval(window['timer_Mm_timer']);
                    $('#smsAuthNumber').removeAttr('disabled');
                    smsAuthCountDown();
                }
            });
        });
        // 인증번호 취소
        $('.js-layer-close').click(function (e) {
            $('input:hidden[name="mode"]').val('');
            clearInterval(window['timer_Mm_timer']);
            layer_close();
            location.reload();
        });
        function smsAuthCountDown() {
            $(".div-time-count").html('남은 인증시간 : <span id="m_timer"></span>');

            $('#m_timer').countdowntimer({
                minutes: 3,
                size: "14px",
                borderColor: "#ddd",
                fontColor: "#f91d11",
                backgroundColor: "#ddd",
                tickInterval: 1,
                timeUp: authTimeOut
            });
        }
        function authTimeOut() {
            $('#smsAuthNumber').attr('disabled', 'disabled');
            alert('인증시간이 만료되었습니다. 재전송을 눌러주세요.');
            if(sendRetry > 4) {//자동입력방지
                $('.capcha').show();
            }
            return false;
        }

        <?php if(count($securitySelect) > 1) { ?>
        $(".data-type").change(function(e) {
            $(".send-phone-number").hide();
            $(".data-security-" + $(this).val()).show();
        });

        $(".data-security-" + $(".data-type :selected").val()).show();
        smsAuthCountDown();
        $(".first-send").hide();
        <?php } else if(isset($securitySelect['emailSend'])) { ?>
        $('.js-layer-resend').trigger("click");
        <?php } else if(isset($securitySelect['smsReSend'])) {?>
        smsAuthCountDown();
        $(".first-send").hide();
        <?php } ?>
    });
    //-->
</script>
