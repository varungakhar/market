<form id="frmRegister" name="frmRegister" action="./order_ps.php" method="post">
	<input type="hidden" name="mode" value="register_frequency" />
    <input type="hidden" name="sno" value="<?= gd_isset($data['sno']); ?>" />
    <input type="hidden" name="groupNm" value="<?= gd_isset($data['groupNm']); ?>" />
    <table class="table table-cols no-title-line">
        <colgroup>
            <col class="width-sm"/>
            <col/>
        </colgroup>
        <tr>
            <th class="require">그룹</th>
            <td>
                <div class="radio">
                    <label>
                        <input type="radio" name="tmpCheckedGroup" value="old" class="js-order-same" /> 기존그룹
                    </label>
                    <label>
                        <?= gd_select_box('selectGroupNm', 'selectGroupNm', $groups, null, $data['groupNm'], null);?>
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="tmpCheckedGroup" value="new" class="js-order-same" /> 신규그룹
                    </label>
                    <label>
                        <input type="text" name="inputGroupNm" value="" class="form-control" />
                    </label>
                </div>
            </td>
        </tr>
        <tr>
            <th class="require">이름</th>
            <td>
                <input type="text" name="name" value="<?= gd_isset($data['name']); ?>" class="form-control width-sm"/>
            </td>
        </tr>
        <tr>
            <th class="require">주소</th>
            <td>
                <div class="form-inline">
                    <input type="text" name="zonecode" value="<?= gd_isset($data['zonecode']); ?>" size="5" class="form-control"/>
                    <input type="hidden" name="zipcode" value="<?= gd_isset($data['zipcode']); ?>"/>
                    <span id="zipcodeText" class="number <?php if (strlen($data['zipcode']) != 7) { echo 'display-none';} ?>">(<?php echo $data['zipcode'];?>)</span>
                    <input type="button" onclick="postcode_search('zonecode', 'address', 'zipcode');" value="우편번호찾기" class="btn btn-sm btn-gray"/>
                </div>
                <div class="mgt5">
                    <input type="text" name="address" value="<?= gd_isset($data['address']); ?>" class="form-control"/>
                </div>
                <div class="mgt5">
                    <input type="text" name="addressSub" value="<?= gd_isset($data['addressSub']); ?>" class="form-control"/>
                </div>
            </td>
        </tr>
        <tr>
            <th>이메일</th>
            <td>
                <div class="form-inline js-email-select" data-target-name="email[]" data-origin-data="<?= $data['email'][1] ?>">
                    <input type="text" name="email[]" value="<?= $data['email'][0] ?>" class="form-control width-sm">
                    <label class="control-label">@</label>
                    <input type="text" name="email[]" value="<?= $data['email'][1] ?>" class="form-control width-sm">
                    <?= gd_select_box_by_mail_domain(null, null, null, $data['email'][1], '직접입력'); ?>
                </div>
            </td>
        </tr>
        <tr>
            <th>전화번호</th>
            <td>
                <div class="form-inline">
                    <input type="text" name="phone" value="<?= implode("-",$data['phone']) ?>" maxlength="12" class="form-control js-number-only width-md"/>
                </div>
            </td>
        </tr>
        <tr>
            <th>휴대폰번호</th>
            <td>
                <div class="form-inline">
                    <input type="text" name="cellPhone" value="<?= implode("-",$data['cellPhone']) ?>" maxlength="12" class="form-control js-number-only width-md"/>
                </div>
            </td>
        </tr>
        <tr>
            <th>메모</th>
            <td>
                <textarea name="memo" rows="3" class="form-control"><?= gd_isset($data['memo']); ?></textarea>
            </td>
        </tr>
    </table>
    <div class="table-btn">
        <button type="button" class="btn btn-white btn-lg js-layer-close">취소</button>
        <input type="submit" class="btn btn-lg btn-black" value="저장" />
    </div>
</form>

<script type="text/javascript">
    <!--
    $(function() {
        // 폼체크
        $('#frmRegister').validate({
            submitHandler: function (form) {
                form.target = 'ifrmProcess';
                form.submit();
                layer_close();
            },
            rules: {
                tmpCheckedGroup: 'required',
                groupNm: 'required',
                name: 'required',
                address: 'required',
                addressSub: 'required',
                'phone[]': {
                    number: true,
                },
            },
            messages: {
                tmpCheckedGroup: '그룹을 선택해주세요.',
                groupNm: '그룹을 선택 혹은 입력해주세요.',
                name: '이름을 입력해주세요.',
                address: '주소를 입력해주세요.',
                addressSub: '주소를 입력해주세요.',
                'phone[]': {
                    number: '숫자만 입력해주세요.'
                },
            }
        });

        // 그룹선택 라디오
        $('input[name=tmpCheckedGroup]').click(function(e){
            $('input[name=groupNm]').val('<?=gd_isset($data['groupNm'])?>');
            if ($(this).val() == 'old') {
                $('select[name=selectGroupNm]').prop('disabled', false);
                $('input[name=inputGroupNm]').prop('disabled', true);
                $('select[name=selectGroupNm]').trigger('change');
            } if ($(this).val() == 'new') {
                $('select[name=selectGroupNm]').prop('disabled', true);
                $('input[name=inputGroupNm]').prop('disabled', false);
            }
        });
        $('input[name=tmpCheckedGroup]').eq(0).trigger('click');

        // 기존그룹 선택시 데이터 할당
        $('select[name=selectGroupNm]').change(function(e){
            var value = '';
            if ($(this).val() != '0') {
                value = $(this).val();
            }
            $('input[name=groupNm]').val(value);
        });

        // 신규그룹 글 작성 후
        $('input[name=inputGroupNm]').blur(function(e){
            var value = $(this).val();
            $('input[name=groupNm]').val(value);
        });

        // 자주쓰는 주소 삭제
        $('.js-btn-delete').click(function (e) {
            $.validator.setDefaults({dialog: false});
            if ($('input[name="sno[]"]:checked').length > 0) {
                BootstrapDialog.confirm({
                    type: BootstrapDialog.TYPE_DANGER,
                    title: '주문삭제',
                    message: '선택된 ' + $('input[name="sno[]"]:checked').length + '개의 자주쓰는 주소를 정말로 삭제 하시겠습니까?<br />삭제 시 정보는 복구 되지 않습니다.',
                    closable: false,
                    callback: function(result) {
                        if (result) {
                            $('#frmList').submit();
                        }
                    }
                });
            } else {
                $('#frmList').submit();
            }
        });
    });
    // 숫자만 입력하기 원하는 경우 ,(쉼표) .(콤마) -(마이너스) 입력안됨
    if ($('input.js-number-only').length > 0) {
        $('input.js-number-only').each(function () {
            $(this).number_only("d");
        });
    }
    //-->
</script>
