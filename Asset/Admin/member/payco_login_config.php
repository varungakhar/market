<form id="form" name="form" action="payco_login_config_ps.php" method="post">
    <input type="hidden" name="serviceName" value="<?= $data['serviceName']; ?>"/>
    <input type="hidden" name="serviceURL" value="<?= $data['serviceURL']; ?>"/>
    <input type="hidden" name="consumerName" value="<?= $data['consumerName']; ?>"/>
    <div class="page-header js-affix">
        <h3><?php echo end($naviMenu->location); ?></h3>
        <input type="submit" value="저장" class="btn btn-red"/>
    </div>

    <div class="table-title gd-help-manual">
        페이코 아이디 로그인 설정
    </div>
    <div class="panel pd10">
        <p><b>페이코 아이디 로그인이란?</b></p>
        <p>
            600만 페이코 이용자를 내 쇼핑몰 회원으로!<br/> 회원가입, 로그인, 결제까지 쉽고 빠르게 이어지는 페이코 아이디 로그인 서비스를 사용해보세요.<br/> [페이코 로그인 사용 신청] 버튼 클릭 한 번으로 바로 쇼핑몰에서 사용할 수 있습니다.
        </p>
        <p> 페이코 아이디로 회원가입 시 페이코의 회원정보를 불러와 쉽고 빠르게 가입할 수 있습니다.<br/>모바일 쇼핑몰에서는 더욱 간편하게 로그인할 수 있습니다.<br/>쇼핑몰에는 가입한 회원의 회원정보가 보관되므로 다른 쇼핑몰 회원과 같이 개인정보활용동의에 따른 프로모션을 할 수 있습니다.
        </p>
        <p>페이코 아이디 로그인으로 더 빠르게, 더 많이 쇼핑몰 회원을 늘리세요.
            <a href="/service/service_info.php?menu=member_payco_info" class="btn btn-gray btn-sm">서비스 자세히 보기</a>
        </p>
    </div>
    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tbody>
        <tr>
            <th>사용설정</th>
            <td>
                <label class="radio-inline">
                    <input type="radio" name="useFl" id="useFlY" value="y" <?= $checked['useFl']['y']; ?>>
                    사용함
                </label>
                <label class="radio-inline">
                    <input type="radio" name="useFl" id="useFlN" value="n" <?= $checked['useFl']['n']; ?>>
                    사용안함
                </label>
            </td>
        </tr>
        <tr>
            <th class="require">Client ID</th>
            <td>
                <label>
                    <input type="text" name="clientId" id="clientId" value="<?= $data['clientId']; ?>" class="form-control width-2xl useFl" disabled="disabled"/>
                </label>
                <button type="button" class="btn btn-gray btn-sm js-payco-login-request">페이코 로그인 사용 <?= $data['clientId'] == '' ? '신청' : '재신청' ?></button>
            </td>
        </tr>
        <tr>
            <th class="require">Client 시크릿코드</th>
            <td>
                <label>
                    <input type="text" name="clientSecret" id="clientSecret" value="<?= $data['clientSecret']; ?>" class="form-control width-2xl useFl" disabled="disabled"/>
                </label>
            </td>
        </tr>
        <tr>
            <th>페이코 로그인 사용<br/>신청정보</th>
            <td>
                <div class="pdb24">쇼핑몰 이름 : <?= $data['serviceName']; ?></div>
                <div class="pdb24">쇼핑몰 URL : <?= $data['serviceURL']; ?></div>
                <div class="pdb24">상호(회사)명 : <?= $data['consumerName']; ?></div>
                <div >신청된 정보가 다를 경우 <a href="#" class="js-payco-login-request notice-ref notice-sm btn-link">페이코 로그인 재신청</a>
                을 클릭하여 재신청 해주시기 바랍니다.</div>
            </td>
        </tr>
        </tbody>
    </table>
</form>
<script type="text/javascript">
    <!--
    $(document).ready(function () {
        $("#form").validate({
            dialog: false,
            ignore: '.ignore',
            rules: {
                clientId: {
                    required: function () {
                        return $(':radio[name="useFl"]:checked').val() == 'y';
                    }
                },
                clientSecret: {
                    required: function () {
                        return $(':radio[name="useFl"]:checked').val() == 'y';
                    }
                }
            },
            messages: {
                clientId: {
                    required: "Client ID를 입력해 주세요."
                },
                clientSecret: {
                    required: "Client 시크릿코드를 입력해주세요."
                }
            },
            submitHandler: function (form) {
                form.target = 'ifrmProcess';
                form.submit();
            }
        });

        $('.js-payco-login-request').click(function (e) {
            e.preventDefault();
            if ($(':radio[name="useFl"]:checked').val() == 'y') {
                var loadChk = 0;
                $.ajax({
                    url: '../member/layer_payco_login_request.php',
                    type: 'get',
                    async: false,
                    success: function (data) {
                        if (loadChk == 0) {
                            data = '<div id="layerPaycoLogin">' + data + '</div>';
                        }
                        BootstrapDialog.show({
                            title: '페이코 아이디 로그인 사용신청',
                            size: BootstrapDialog.SIZE_WIDE,
                            message: $(data),
                            closable: true
                        });
                    }
                });
            } else {
                alert('사용설정을 사용함으로 선택해 주시기 바랍니다.');
            }
        });
    });
    //-->
</script>
