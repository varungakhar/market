<form id="frmBankSender">
    <table class="table table-cols no-title-line">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tr>
            <th>입금계좌</th>
            <td>
                <?= gd_select_box('bankAccountSelector', 'bankAccountSelector', $bankData, null, null, '=입금 계좌 선택='); ?>
            </td>
        </tr>
        <tr>
            <th>입금자명</th>
            <td>
                <input type="text" name="bankSenderSelector" id="bankSenderSelector" value="" class="form-control width-md"/>
            </td>
        </tr>
    </table>
    <div class="text-center">
        <button type="button" class="btn btn-lg btn-white js-layer-close">취소</button>
        <button type="submit" class="btn btn-lg btn-black js-layer-close">저장</button>
    </div>
</form>

<script type="text/javascript">
    <!--
    $(document).ready(function () {
        $('#frmBankSender').validate({
            submitHandler: function (form) {
                // 본문내 입력폼에 입금계좌와 입금자명을 입력
                if ($('#bankAccountSelector').val() != '') {
                    $('input[name=\'order[bankAccount]\']').val($('#bankAccountSelector').val());
                    $('#bankAccount').html($('#bankAccountSelector option:selected').text());
                }
                if ($('#bankSenderSelector').val() != '') {
                    $('input[name=\'order[bankSender]\']').val($('#bankSenderSelector').val());
                    $('#bankSender').html($('#bankSenderSelector').val());
                }
                return false;
            },
            rules: {
                bankAccountSelector: 'required',
                bankSenderSelector: 'required'
            },
            messages: {
                bankAccountSelector: '입금계좌를 선택하세요.',
                bankSenderSelector: '입금자명을 입력하세요.'
            }
        });
    });
    //-->
</script>
