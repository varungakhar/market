<style>
.permission-item > td {
    width:100%;
}

.permission-item > td > table > td {
    width:50%;
    text-align:center;
}
</style>
<form id="frmScm" name="frmScm" action="scm_ps.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="mode" value="<?= $getData['mode'] ?>"/>
    <input type="hidden" name="scmNo" value="<?= $getData['scmNo'] ?>"/>

    <div class="page-header js-affix">
        <h3><?php echo end($naviMenu->location); ?></h3>
        <div class="btn-group">
            <input type="button" value="목록" class="btn btn-white btn-icon-list" onclick="goList('./scm_list.php');" />
            <input type="submit" value="저장" class="btn btn-red"/>
        </div>
    </div>

    <div class="table-title">
        공급사 등록
    </div>
    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col/>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tbody>
        <?php
        if ($getData['mode'] == 'modifyScmModify') {
            ?>
            <tr>
                <th>
                    공급사 타입
                </th>
                <td colspan="3">
                    <?= $getData['scmKindName']; ?>
                </td>
            </tr>
            <?php
        } else {
            ?>
            <input type="hidden" name="scmKind" value="p" />
            <?php
        }
        ?>
        <tr>
            <th class="require">
                공급사명
            </th>
            <td>
                <input type="text" name="companyNm" value="<?= $getData['companyNm'] ?>" maxlength="20" class="form-control width-xl"/>
            </td>
            <th class="require">
                상태
            </th>
            <td>
                <label class="radio-inline">
                    <input type="radio" name="scmType" value="y" <?= $checked['scmType']['y'] ?> />운영
                </label>
                <label class="radio-inline">
                    <input type="radio" name="scmType" value="x" <?= $checked['scmType']['x'] ?> <?= $getData['scmTypeReadOnly'] ?> />탈퇴
                </label>
            </td>
        </tr>
        <tr>
            <th class="require">
                닉네임
            </th>
            <td class="form-inline">
                <?php
                if ($getData['mode'] == 'modifyScmModify') {
                    ?>
                    <div><?= $getData['managerNickNm'] ?></div>
                    운영자 닉네임 변경은 <a href="../policy/manage_list.php" class="btn-link">운영정책 > 관리 정책 > 운영자관리</a>에서 할 수 있습니다.
                    <?php
                } else {
                    ?>
                    <input type="text" name="managerNickNm" value="" maxlength="20" class="form-control width-sm"/>
                    <label class="checkbox-inline">
                        <input type="checkbox" name="scmSameNick" value="Y"/>공급사명과 동일
                    </label>
                    <?php
                }
                ?>
            </td>
            <th>
                이미지 표시
            </th>
            <td class="form-inline">
                <?php
                if ($getData['dispImage']) {
                    echo '<div><img src="' . $getData['dispImage'] . '" width="50"/>';
                    echo '<label title="이미지 삭제시 체크해 주세요!" class="radio-inline"><input type="checkbox" name="isImageDelete" value="y"/> 삭제</label><div>';
                }
                if ($getData['mode'] == 'modifyScmModify') {
                    echo '운영자 이미지 변경은 <a href="../policy/manage_list.php" class="btn-link">운영정책 > 관리 정책 > 운영자관리</a>에서 할 수 있습니다.';
                } else {
                    echo '<input type="file" name="scmImage" class="form-control width-lg "/>';
                }
                ?>
            </td>
        </tr>
        <tr>
            <th class="require">
                공급사 아이디
            </th>
            <td class="form-inline">
                <?php
                if ($getData['mode'] == 'modifyScmModify') {
                    echo $getData['managerId'];
                } else {
                    ?>
                    <input type="text" name="managerId" value="" maxlength="<?= $policy['memId']['maxlen'] ?>" class="form-control width-sm"/>
                    <input type="hidden" name="managerDuplicateId" value=""/>
                    <button type="button" id="overlap_managerId" class="btn btn-gray btn-sm">중복확인</button>
                <?php } ?>
            </td>
            <th class="require">
                공급사비밀번호
            </th>
            <td>
                <?php
                if ($getData['mode'] == 'modifyScmModify') {
                    echo '비밀번호 변경은 <a href="../policy/manage_list.php" class="btn-link">운영정책 > 관리 정책 > 운영자관리</a>에서 할 수 있습니다.';
                } else {
                    ?>
                    <input type="password" name="managerPw" value="" maxlength="16" class="form-control width-sm"/>
                <?php } ?>
                <div class="text-blue">
                    * 영문,숫자,특수문자 중 2가지 조합 : 10자 이상
                </div>
            </td>
        </tr>
        <tr>
            <th class="require">
                판매수수료
            </th>
            <td class="form-inline">
                <input type="text" name="scmCommission" value="<?= $getData['scmCommission'] ?>" class="form-control width-sm"/>%
            </td>
            <th class="require">
                배송비수수료
            </th>
            <td class="form-inline">
                <input type="text" name="scmCommissionDelivery" value="<?= $getData['scmCommissionDelivery'] ?>" class="form-control width-sm"/>%
            </td>
        </tr>
        <tr>
            <th>
                공급사코드
            </th>
            <td class="form-inline">
                <input type="text" name="scmCode" value="<?= $getData['scmCode'] ?>" class="form-control width-sm"/>
            </td>
            <th>
                이미지 저장소 기본값
            </th>
            <td class="form-inline">
                <?=gd_select_box('imageStorage', 'imageStorage', $conf['storage'], null, $getData['imageStorage'], null, null); ?>
            </td>
        </tr>
        <tr>
            <th>
                상품등록권한
            </th>
            <td colspan="3">
                <label class="radio-inline">
                    <input type="radio" name="scmPermissionInsert" value="a" <?= $checked['scmPermissionInsert']['a'] ?> />자동승인
                </label>
                <label class="radio-inline">
                    <input type="radio" name="scmPermissionInsert" value="c" <?= $checked['scmPermissionInsert']['c'] ?> />관리자승인
                </label>
            </td>
        </tr>
        <tr>
            <th>
                상품수정권한
            </th>
            <td colspan="3">
                <label class="radio-inline">
                    <input type="radio" name="scmPermissionModify" value="a" <?= $checked['scmPermissionModify']['a'] ?> />자동승인
                </label>
                <label class="radio-inline">
                    <input type="radio" name="scmPermissionModify" value="c" <?= $checked['scmPermissionModify']['c'] ?> />관리자승인
                </label>
            </td>
        </tr>
        <tr>
            <th>
                상품삭제권한
            </th>
            <td colspan="3">
                <label class="radio-inline">
                    <input type="radio" name="scmPermissionDelete" value="a" <?= $checked['scmPermissionDelete']['a'] ?> />자동승인
                </label>
                <label class="radio-inline">
                    <input type="radio" name="scmPermissionDelete" value="c" <?= $checked['scmPermissionDelete']['c'] ?> />관리자승인
                </label>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="table-title">
        공급사 정보
    </div>
    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col/>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tbody>
        <tr>
            <th class="require">
                대표자명
            </th>
            <td>
                <input type="text" name="ceoNm" value="<?= $getData['ceoNm'] ?>" maxlength="20" class="form-control width-sm"/>
            </td>
            <th class="require">
                사업자등록번호
            </th>
            <td>
                <input type="text" name="businessNo" value="<?= $getData['businessNo'] ?>" maxlength="20" class="form-control width-sm"/>
                <div class="notice-info">※사업자등록번호를 정확하게 입력하지 않을 경우 세금계산서 발행이 제한될 수 있습니다.</div>
            </td>
        </tr>
        <tr>
            <th>
                사업자등록증
            </th>
            <td colspan="3" class="form-inline">
                <?php
                if ($getData['businessLicenseImage']) {
                    echo '<div><img src="' . $getData['businessLicenseImage'] . '" width="100"  />';
                    echo '<label title="이미지 삭제시 체크해 주세요!" class="radio-inline"><input type="checkbox" name="isBusinessImageDelete" value="y" /> 삭제</label><div>';
                    echo '<input type="hidden" name="oldBusinessLicenseImage" value="' . $getData['businessLicenseImage'] . '"/>';
                }
                ?>
                <input type="file" name="businessLicenseImage" class="form-control width-lg"/>
            </td>
        </tr>
        <tr>
            <th class="require">
                업태
            </th>
            <td>
                <input type="text" name="service" value="<?= $getData['service'] ?>" class="form-control width-sm"/>
            </td>
            <th class="require">
                종목
            </th>
            <td>
                <input type="text" name="item" value="<?= $getData['item'] ?>" class="form-control width-sm"/>
            </td>
        </tr>
        <tr>
            <th class="require">
                사업장주소
            </th>
            <td colspan="3">
                <div class="form-inline">
                    <input type="text" name="zonecode" value="<?= gd_isset($getData['zonecode']) ?>" class="form-control width-sm" readonly="readonly"/>
                    <input type="hidden" name="zipcode" value="<?= gd_isset($getData['zipcode']) ?>"/>
                    <span id="zipcodeText" class="number <?php if (strlen($getData['zipcode']) != 7) {
                        echo 'display-none';
                    } ?>">(<?php echo $getData['zipcode']; ?>)</span>
                    <button type="button" id="btn_zipcode" class="btn btn-gray btn-sm">우편번호찾기</button>
                </div>
                <div>
                    <input type="text" name="address" class="form-control width-2xl" value="<?= gd_isset($getData['address']); ?>" readonly="readonly"/>
                    <input type="text" name="addressSub" class="form-control width-2xl" value="<?= gd_isset($getData['addressSub']); ?>"/>
                </div>
                <span id="zipcodeMsg" class="input_error_msg"></span>
                <span id="addressMsg" class="input_error_msg"></span>
                <span id="addressSubMsg" class="input_error_msg"></span>
            </td>
        </tr>
        <tr>
            <th>
                출고지 주소
            </th>
            <td colspan="3">
                <label class="radio-inline">
                    <input type="radio" name="chkSameUnstoringAddr" value="y" <?= $checked['chkSameUnstoringAddr']['y'] ?>/>사업장주소와 동일
                </label>
                <label class="radio-inline">
                    <input type="radio" name="chkSameUnstoringAddr" value="n" <?= $checked['chkSameUnstoringAddr']['n'] ?>/>주소등록
                </label>
                <div class="form-inline div_unstoring_addr">
                    <input type="text" name="unstoringZonecode" value="<?= gd_isset($getData['unstoringZonecode']) ?>" class="form-control width-sm" readonly="readonly"/>
                    <input type="hidden" name="unstoringZipcode" value="<?= gd_isset($getData['unstoringZipcode']) ?>"/>
                    <span id="unstoringZipcodeText" class="number <?php if (strlen($getData['unstoringZipcode']) != 7) {
                        echo 'display-none';
                    } ?>">(<?php echo $getData['unstoringZipcode']; ?>)</span>
                    <button type="button" id="btn_unstoring_zipcode">우편번호찾기</button>
                </div>
                <div class="div_unstoring_addr">
                    <input type="text" name="unstoringAddress" class="form-control width-2xl" value="<?= gd_isset($getData['unstoringAddress']); ?>" readonly="readonly"/>
                    <input type="text" name="unstoringAddressSub" class="form-control width-2xl" value="<?= gd_isset($getData['unstoringAddressSub']); ?>"/>
                </div>
                <div class="div_unstoring_addr">
                    <span id="unstoringZipcodeMsg" class="input_error_msg"></span>
                    <span id="unstoringAddressMsg" class="input_error_msg"></span>
                    <span id="unstoringAddressSubMsg" class="input_error_msg"></span>
                </div>
            </td>
        </tr>
        <tr>
            <th>
                반품/교환 주소
            </th>
            <td colspan="3">
                <label class="radio-inline">
                    <input type="radio" name="chkSameReturnAddr" value="y" <?= $checked['chkSameReturnAddr']['y'] ?>/>사업장주소와 동일
                </label>
                <label class="radio-inline">
                    <input type="radio" name="chkSameReturnAddr" value="x" <?= $checked['chkSameReturnAddr']['x'] ?>/>출고지주소와 동일
                </label>
                <label class="radio-inline">
                    <input type="radio" name="chkSameReturnAddr" value="n" <?= $checked['chkSameReturnAddr']['n'] ?>/>주소등록
                </label>
                <div class="form-inline div_return_addr">
                    <input type="text" name="returnZonecode" value="<?= gd_isset($getData['returnZonecode']) ?>" class="form-control width-sm" readonly="readonly"/>
                    <input type="hidden" name="returnZipcode" value="<?= gd_isset($getData['returnZipcode']) ?>"/>
                    <span id="returnZipcodeText" class="number <?php if (strlen($getData['returnZipcode']) != 7) {
                        echo 'display-none';
                    } ?>">(<?php echo $getData['returnZipcode']; ?>)</span>
                    <button type="button" id="btn_return_zipcode">우편번호찾기</button>
                </div>
                <div class="div_return_addr">
                    <input type="text" name="returnAddress" class="form-control width-2xl" value="<?= gd_isset($getData['returnAddress']); ?>" readonly="readonly"/>
                    <input type="text" name="returnAddressSub" class="form-control width-2xl" value="<?= gd_isset($getData['returnAddressSub']); ?>"/>
                </div>
                <div class="div_return_addr">
                    <span id="returnZipcodeMsg" class="input_error_msg"></span>
                    <span id="returnAddressMsg" class="input_error_msg"></span>
                    <span id="returnAddressSubMsg" class="input_error_msg"></span>
                </div>
            </td>
        </tr>
        <tr>
            <th class="require">
                대표번호
            </th>
            <td>
                <input type="text" name="phone" value="<?= $getData['phone'] ?>" class="form-control width-sm"/>
            </td>
            <th class="require">
                고객센터
            </th>
            <td>
                <input type="text" name="centerPhone" value="<?= $getData['centerPhone'] ?>" class="form-control width-sm"/>
            </td>
        </tr>
        </tbody>
    </table>

    <?php
    if ($layoutFunctionAuth) {
    ?>
    <div class="table-title">
        공급사 기능 권한
    </div>
    <?php include $layoutFunctionAuth; ?>
    <?php } ?>

    <div class="table-title">
        담당자 정보
    </div>
    <table id="table_staff" class="table table-cols">
        <colgroup>
            <col class="width-sm"/>
            <col class="width-sm"/>
            <col class="width-sm"/>
            <col class="width-sm"/>
            <col class="width-sm"/>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tbody>
        <tr>
            <th>
                담당
            </th>
            <th>
                이름
            </th>
            <th>
                전화번호
            </th>
            <th>
                휴대폰
            </th>
            <th>
                이메일
            </th>
            <th>
                추가/삭제
            </th>
        </tr>
        <?php
        if ($getData['staff']) {
            $staffNum = 2;
            foreach ($getData['staff'] as $key => $val) {
                $selected['staffType'][$key][$getData['staff'][$key]->staffType] = 'selected="selected"';
                $checked['chkSmsOrder'][$key][$getData['staff'][$key]->staffSmsOrder] = 'checked="checked"';
                $checked['chkSmsIncome'][$key][$getData['staff'][$key]->staffSmsIncome] = 'checked="checked"';
                ?>
                <tr id="tr_staff_<?= $staffNum ?>">
                    <td>
                        <?= gd_select_box('staffType[]', 'staffType[]', $department, null, gd_isset($getData['staff'][$key]->staffType), '=부서 선택='); ?>
                    </td>
                    <td>
                        <input type="text" name="staffName[]" value="<?= $getData['staff'][$key]->staffName ?>" class="form-control width-sm"/>
                    </td>
                    <td>
                        <input type="text" name="staffTel[]" value="<?= $getData['staff'][$key]->staffTel ?>" class="form-control width-sm"/>
                    </td>
                    <td>
                        <input type="text" name="staffPhone[]" value="<?= $getData['staff'][$key]->staffPhone ?>" class="form-control width-sm"/>
                    </td>
                    <td>
                        <input type="text" name="staffEmail[]" value="<?= $getData['staff'][$key]->staffEmail ?>" class="form-control width-sm"/>
                    </td>
                    <td>
                        <?php
                        if ($staffNum == 2) {
                            ?>
                            <button type="button" id="btn_staff_add">추가</button>
                            <?php
                        } else {
                            ?>
                            <button type="button" class="btn_staff_del">삭제</button>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
                <?php
                $staffNum++;
            }
        } else {
            ?>
            <tr id="tr_staff_2">
                <td>
                    <?= gd_select_box('staffType[]', 'staffType[]', $department, null, null, '=부서 선택='); ?>
                </td>
                <td>
                    <input type="text" name="staffName[]" value="" class="form-control width-sm"/>
                </td>
                <td>
                    <input type="text" name="staffTel[]" value="" class="form-control width-sm"/>
                </td>
                <td>
                    <input type="text" name="staffPhone[]" value="" class="form-control width-sm"/>
                </td>
                <td>
                    <input type="text" name="staffEmail[]" value="" class="form-control width-sm"/>
                </td>
                <td>
                    <button type="button" id="btn_staff_add" class="btn btn-gray btn-sm">추가</button>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <div class="text-center">
        <input type="submit" value="저장" class="btn btn-red btn-lg"/>
    </div>
</form>

<script type="text/javascript">
    <!--
    var trCount = $('#table_staff tr').length;
    $(document).ready(function () {
        $("#frmScm").validate({
            dialog: false,
            submitHandler: function (form) {
                <?php
                if ($getData['mode'] == 'modifyScmModify') {
                ?>
                if ($('input:radio[name="scmType"]:checked').val() == 'x') {
                    var scmNo = $('input:hidden[name="scmNo"]').val();
                    var chkMsg = '';
                    $.ajax({
                        method: "POST",
                        cache: false,
                        url: "../scm/scm_ps.php",
                        data: "mode=checkScmModify&scmNo=" + scmNo,
                    }).success(function (data) {
                        data = data + '<br/>탈퇴 처리 하시겠습니까?';
                        dialog_confirm(data, function (result) {
                            if (result) {
                                form.target = 'ifrmProcess';
                                form.submit();
                            } else {
                                return false;
                            }
                        });
                    }).error(function (e) {
                        alert(e.responseText);
                        return false;
                    });
                } else {
                    form.target = 'ifrmProcess';
                    form.submit();
                }
                <?php
                } else {
                ?>
                form.target = 'ifrmProcess';
                form.submit();
                <?php
                }
                ?>
            },
            rules: {
                companyNm: {
                    required: true,
                },
                scmType: {
                    required: true,
                },
                managerId: {
                    required: true,
                    minlength: 4,
                    maxlength: 50,
                    equalTo: 'input[name=managerDuplicateId]'
                },
                managerPw: {
                    required: true,
                    minlength: 10,
                    maxlength: 16,
                },
                scmCommission: {
                    required: true,
                },
                scmCommissionDelivery: {
                    required: true,
                },
                scmKind: {
                    required: true,
                },
                scmPermissionInsert: {
                    required: true,
                },
                scmPermissionModify: {
                    required: true,
                },
                scmPermissionDelete: {
                    required: true,
                },
                ceoNm: {
                    required: true,
                    maxlength: 20,
                },
                businessNo: {
                    required: true,
                    maxlength: 20,
                },
                service: {
                    required: true,
                },
                item: {
                    required: true,
                },
                phone: {
                    required: true,
                },
                zonecode: {
                    required: true,
                },
                address: {
                    required: true,
                },
                addressSub: {
                    required: true,
                }
            },
            messages: {
                companyNm: {
                    required: '공급사명을 입력해주세요.',
                },
                scmType: {
                    required: '공급사 상태를 선택해주세요.',
                },
                managerId: {
                    required: '공급사 로그인ID를 입력해주세요.',
                    minlength: '공급사 로그인ID는 최소 {0}자 이상 입력해주세요.',
                    maxlength: '공급사 로그인ID는 최대 {0}자 이하 입력해주세요.',
                    equalTo: '공급사 로그인ID 중복체크 해주세요.',
                },
                managerPw: {
                    required: '공급사 로그인비밀번호를 입력해주세요.',
                    minlength: '공급사 로그인비밀번호는 최소 {0}자 이상 입력해주세요.',
                    maxlength: '공급사 로그인비밀번호는 최대 {0}자 이하 입력해주세요.',
                },
                scmCommission: {
                    required: '판매수수료를 입력해주세요.',
                },
                scmCommissionDelivery: {
                    required: '배송수수료를 입력해주세요.',
                },
                scmKind: {
                    required: '공급사 타입을 선택해주세요.',
                },
                scmPermissionInsert: {
                    required: '상품등록권한을 선택해주세요.',
                },
                scmPermissionModify: {
                    required: '상품수정권한을 선택해주세요.',
                },
                scmPermissionDelete: {
                    required: '상품삭제권한을 선택해주세요.',
                },
                ceoNm: {
                    required: '대표자명을 입력해주세요.',
                    maxlength: '대표자명는 최대 {0}자 이하 입력해주세요.',
                },
                businessNo: {
                    required: '사업자등록번호를 입력해주세요.',
                    maxlength: '사업자등록번호는 최대 {0}자 이하 입력해주세요.',
                },
                service: {
                    required: '업태를 입력해주세요.',
                },
                item: {
                    required: '업종을 입력해주세요.',
                },
                phone: {
                    required: '대표번호를 입력해주세요.',
                },
                zonecode: {
                    required: '사업자 우편번호를 입력해주세요.',
                },
                address: {
                    required: '사업자 주소를 입력해주세요.',
                },
                addressSub: {
                    required: '사업자 주소를 입력해주세요.',
                }
            }
        });
        // 닉네임 공급사명과 동일체크
        $('input:checkbox[name^="scmSameNick"]').click(function (e) {
            changeSameNickName();
        });
        // 관리자 아이디 중복확인
        $("#overlap_managerId").click(function (e) {
            checkManageId(e);
        });
        // 사업자 주소
        $("#btn_zipcode").click(function (e) {
            postcode_search('zonecode', 'address', 'zipcode');
        });
        // 출고지 주소
        $("#btn_unstoring_zipcode").click(function (e) {
            postcode_search('unstoringZonecode', 'unstoringAddress', 'unstoringZipcode');
        });
        // 반품/교환 주소
        $("#btn_return_zipcode").click(function (e) {
            postcode_search('returnZonecode', 'returnAddress', 'returnAddressSub');
        });
        $('input:radio[name="chkSameUnstoringAddr"],input:radio[name="chkSameReturnAddr"]').click(function (e) {
            changeAddress();
        });
        // 주소 입력창 노출
        changeAddress();
        // 담당자 정보 추가
        $("#btn_staff_add").click(function (e) {
            staffAdd();
        });
        // 담당자 정보 삭제(정적 담당자 삭제)
        $(".btn_staff_del").click(function (e) {
            $(this).closest('tr').remove();
        });
        // input radio 값을 input text 에 넣기
        $(document).on('change', '#frmScm input:checkbox', function (e) {
            if ($(this).prop('checked') == true) {
                $(this).closest('td, th').children('input[type=hidden]').val('y');
            } else {
                $(this).closest('td, th').children('input[type=hidden]').val('');
            }
        });
    });
    function staffAdd() {
        var chkTrCount = ($('#table_staff tr').length) - 2; // + 추가 1개 - 기본 tr 2개
        trCount = trCount + 1;
        if ((chkTrCount + 1) >= <?php echo DEFAULT_LIMIT_SCM_STAFF;?>) {
            alert('담당자 정보는 <?php echo DEFAULT_LIMIT_SCM_STAFF;?>개가 제한 입니다.');
            return false;
        }
        var selectType = '<?= gd_select_box("staffType[]", "staffType[]", $department, null, null, "=부서 선택="); ?>';
        var addStaff = '<tr id="tr_staff_' + trCount + '">';
        addStaff += '<td>'+selectType+'</td>';
        addStaff += '<td><input type="text" name="staffName[]" value="" class="form-control width-sm"/></td>';
        addStaff += '<td><input type="text" name="staffTel[]" value="" class="form-control width-sm"/></td>';
        addStaff += '<td><input type="text" name="staffPhone[]" value="" class="form-control width-sm"/></td>';
        addStaff += '<td><input type="text" name="staffEmail[]" value="" class="form-control width-sm"/></td>';
        addStaff += '<td><button type="button" id="btn_staff_del_' + trCount + '" class="btn btn-gray btn-sm">삭제</button></td>';
        addStaff += '</tr>';
        $('#table_staff').append(addStaff);
        // 담당자 정보 삭제(동적 담당자 삭제)
        $('#btn_staff_del_' + trCount).on('click', function (e) {
            $(this).closest('tr').remove();
        });
    }
    function changeSameNickName(e) {
        if ($('input:checkbox[name="scmSameNick"]').prop("checked") == true) {
            $('input:text[name="managerNickNm"]').val($('input:text[name="companyNm"]').val());
        }
    }
    function checkManageId() {
        var managerId = $('input:text[name="managerId"]').val();
        $.ajax({
            method: "GET",
            cache: false,
            url: "../policy/manage_ps.php",
            data: "mode=overlapManagerId&managerId=" + managerId,
            dataType: 'json'
        }).success(function (data) {
            alert(data['msg']);
            if (data['result'] == 'ok') {
                $('input:hidden[name="managerDuplicateId"]').val(managerId);
            }
        }).error(function (e) {
            alert(e.responseText);
        });
    }
    function changeAddress() {
        if ($('input:radio[name="chkSameUnstoringAddr"]:checked').val() == 'n') {
            $('.div_unstoring_addr').show();
        } else {
            $('.div_unstoring_addr').hide();
            $('input:text[name="unstoringZonecode"]').val('');
            $('input:hidden[name="unstoringZipcode"]').val('');
            $('#unstoringZipcodeText').text('');
            $('input:text[name="unstoringAddress"]').val('');
            $('input:text[name="unstoringAddressSub"]').val('');
        }
        if ($('input:radio[name="chkSameReturnAddr"]:checked').val() == 'n') {
            $('.div_return_addr').show();
        } else {
            $('.div_return_addr').hide();
            $('input:text[name="returnZonecode"]').val('');
            $('input:hidden[name="returnZipcode"]').val('');
            $('#returnZipcodeText').text('');
            $('input:text[name="returnAddress"]').val('');
            $('input:text[name="returnAddressSub"]').val('');
        }
    }
    //-->
</script>
