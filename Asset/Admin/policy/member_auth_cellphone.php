<form id="frmSetup" action="../policy/member_ps.php" method="post">
    <input type="hidden" name="mode" value="member_auth_cellphone"/>

    <div class="page-header js-affix">
        <h3><?php echo end($naviMenu->location); ?>
        </h3>
        <input type="submit" value="저장" class="btn btn-red"/>
    </div>

    <div class="table-title gd-help-manual">
        휴대폰 본인확인 사용 설정
    </div>

    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tr>
            <th>사용 설정</th>
            <td class="form-inline">
                <label class="radio-inline">
                    <input type="radio" name="useFl" value="y" <?php echo gd_isset($checked['useFl']['y']); ?> />
                    사용함
                </label>
                <label class="radio-inline">
                    <input type="radio" name="useFl" value="n" <?php echo gd_isset($checked['useFl']['n']); ?> />
                    사용안함
                </label>
                <p class="notice-info">
                    서비스 신청 전인 경우 먼저 서비스를 신청하세요.
                    <a href="/service/service_info.php?menu=member_auth_info" target="_blank" class="btn-link">서비스 자세히보기 ></a>
                </p>
            </td>
        </tr>
        <tr>
            <th>회원사 CODE</th>
            <td class="form-inline">
                <input type="text" name="cpCode" value="<?php echo gd_isset($data['cpCode']); ?>" class="form-control"/>
                <p class="notice-info">드림시큐리티에서 상점별로 발급되는 아이디 입니다. (<strong>&quot;<?php echo implode(',', (array) $dreamsecurityPrefix); ?>&quot;</strong>로 시작되어야 함)
                </p>
            </td>
        </tr>
    </table>
</form>

<script type="text/javascript">
    <!--
    $(document).ready(function () {
        $('#frmSetup').validate({
            submitHandler: function (form) {
                var params = $(form).serializeArray();
                post_with_reload('../policy/member_ps.php', params);
            }
        });
    });
    //-->
</script>
