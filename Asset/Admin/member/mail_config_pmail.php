<form id="formConfig" name="formConfig" method="post" action="../member/mail_ps.php">
    <div class="page-header js-affix">
        <h3><?= end($naviMenu->location); ?>
        </h3>
        <input type="submit" value="저장" class="btn btn-red">
    </div>
    <div class="table-title gd-help-manual">
        파워메일 등록정보 설정
    </div>
    <input type="hidden" name="mode" value="configPmail">
    <input type="hidden" name="userId" value="<?= $pMailConfig['userId'] ?>">

    <div class="form-inline">
        <table class="table-cols table">
            <colgroup>
                <col class="width-sm"/>
                <col/>
            </colgroup>
            <tr>
                <th class="require">이름</th>
                <td>
                    <input type="text" name="userNm" value="<?= $pMailConfig['userNm'] ?>"
                           class="form-control width-xs" />
                </td>
            </tr>
            <tr>
                <th class="require">이메일</th>
                <td>
                    <input type="text" id="mailId" name="mailId" value="<?= $pMailConfig['email'][0] ?>"
                           class="form-control"
                           size="40" /><span class="pdr10 pdl10">@</span>
                    <input type="text" id="mailDomain" name="mailDomain" value="<?= $pMailConfig['email'][1] ?>"
                           class="form-control"
                           size="40" />
                    <?= $mailDomainSelectBox; ?>
                </td>
            </tr>
            <tr>
                <th class="require">전화번호</th>
                <td>
                    <input type="text" class="form-control js-number width-xs" data-number="4,9999,4" name="tel[]"
                           value="<?= $pMailConfig['tel'][0] ?>" label="전화번호" /> -
                    <input type="text" class="form-control js-number width-xs" data-number="4,9999,4" name="tel[]"
                           value="<?= $pMailConfig['tel'][1] ?>" label="전화번호" /> -
                    <input type="text" class="form-control js-number width-xs" data-number="4,9999,4" name="tel[]"
                           value="<?= $pMailConfig['tel'][2] ?>" label="전화번호" />
                </td>
            </tr>
            <tr>
                <th class="require">휴대폰번호</th>
                <td>
                    <input type="text" class="form-control js-number width-xs" data-number="4,9999,4" name="mobile[]"
                           value="<?= $pMailConfig['mobile'][0] ?>" label="휴대폰" /> -
                    <input type="text" class="form-control js-number width-xs" data-number="4,9999,4" name="mobile[]"
                           value="<?= $pMailConfig['mobile'][1] ?>" label="휴대폰" /> -
                    <input type="text" class="form-control js-number width-xs" data-number="4,9999,4" name="mobile[]"
                           value="<?= $pMailConfig['mobile'][2] ?>" label="휴대폰" />
                </td>
            </tr>
        </table>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function () {

        $('#mail_site').change(function (e) {
            e.preventDefault();
            $('#mailDomain').val($(e.target).val());
        });

        $('#formConfig').validate({
            // onclick: false, // <-- add this option
            rules: {
                userNm : "required",
                mailId : "required",
                mailDomain : "required",
                "tel[]" : "required",
                "mobile[]" : "required",
            },
            messages: {
                userNm : {
                    required : "이름을 입력해 주세요.",
                },
                mailId : {
                    required : "이메일을 입력해 주세요.",
                },
                mailDomain : {
                    required : "이메일을 입력해 주세요.",
                },
                "tel[]" : {
                    required : "전화번호를 입력해 주세요.",
                },
                "mobile[]" : {
                    required : "핸드폰번호를 입력해 주세요.",
                },
            },
            submitHandler: function (form) {
                var params = $(form).serializeArray();
                params.push({name: "mode", value: "configPmail"});
                params.push({name: "email", value: $('#mailId').val() + '@' + $('#mailDomain').val()});

                post_with_reload('../member/mail_ps.php', params);
            }
        });
    });
</script>

