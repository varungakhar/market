<div class="page-header js-affix">
    <h3><?= end($naviMenu->location); ?></h3>
</div>

<form id="frmSearchBase" method="get" class="js-form-enter-submit">
    <input type="hidden" name="pageNum" value="<?= Request::get()->get('pageNum', 10) ?>"/>

    <div class="table-title gd-help-manual">휴면회원 검색</div>
    <div class="search-detail-box form-inline">
        <table class="table table-cols">
            <colgroup>
                <col class="width-sm"/>
                <col/>
            </colgroup>
            <tbody>
            <?php if ($gGlobal['isUse']) { ?>
                <tr>
                    <th>상점</th>
                    <td colspan="3">
                        <label class="radio-inline">
                            <input type="radio" name="mallSno"
                                   value="" <?= gd_isset($checked['mallSno']['']); ?>/>
                            전체
                        </label>
                        <?php foreach ($gGlobal['useMallList'] as $item) { ?>
                            <label class="radio-inline">
                                <input type="radio" name="mallSno"
                                       value="<?= $item['sno']; ?>" <?= gd_isset($checked['mallSno'][$item['sno']]); ?>/>
                                <span class="flag flag-16 flag-<?= $item['domainFl']; ?>"></span><?= $item['mallName']; ?>
                            </label>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <th>검색어</th>
                <td>
                    <?= gd_select_box('key', 'key', $combineSearch, null, gd_isset($search['key']), '=통합검색='); ?>
                    <input type="text" name="keyword" value="<?= gd_isset($search['keyword']); ?>"
                           class=""/>
                </td>
            </tr>
            <tr>
                <th>휴면회원 전환일</th>
                <td>
                    <div class="input-group js-datepicker">
                        <input type="text" class="" placeholder="" name="sleepDt[]"
                               value="<?= gd_isset($search['sleepDt'][0]); ?>">
                        <span class="input-group-addon">
                            <span class="btn-icon-calendar">
                            </span>
                        </span>
                    </div>
                    <div class="input-group js-datepicker">
                        <input type="text" class="" placeholder="" name="sleepDt[]"
                               value="<?= gd_isset($search['sleepDt'][1]); ?>">
                        <span class="input-group-addon">
                            <span class="btn-icon-calendar">
                            </span>
                        </span>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="table-btn">
        <input type="submit" value="검색" class="btn btn-lg btn-black js-search-button"/>
    </div>
</form>

<form id="frmList" action="" method="get" target="ifrmProcess">
    <input type="hidden" name="mode" value="">

    <div class="table-header">
        <div class="pull-left">
            <?= gd_display_search_result($page->recode['total'], $page->recode['amount'], '명'); ?>
        </div>
        <div class="pull-right">
            <?= gd_select_box_by_page_view_count(Request::get()->get('pageNum', 10)); ?>
        </div>
    </div>

    <div class="form-inline">
        <table class="table table-rows">
            <colgroup>
                <col class="width-xs"/>
                <col class="width-xs"/>
                <?php if ($gGlobal['isUse']) { ?>
                    <col/>
                <?php } ?>
                <col/>
                <col/>
                <col/>
                <col/>
                <col/>
                <col/>
                <col/>
                <col/>
                <col/>
            </colgroup>
            <thead>
            <tr>
                <th class="width-2xs">
                    <input type="checkbox" id="chk_all" class="js-checkall" data-target-name="chk"/>
                </th>
                <th>휴면회원 전환일</th>
                <?php if ($gGlobal['isUse']) { ?>
                    <th>상점 구분</th>
                <?php } ?>
                <th>아이디</th>
                <th>이름</th>
                <th>회원등급</th>
                <th>마일리지</th>
                <th>예치금</th>
                <th>이메일</th>
                <th>휴대폰번호</th>
                <th>전화번호</th>
                <th>회원가입일</th>
                <th>휴면해제</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (gd_isset($data)) {
                foreach ($data as $val) {
                    $sleepDt = (substr($val['sleepDt'], 2, 8) != date('y-m-d')) ? substr($val['sleepDt'], 2, 8) : '<span class="">' . substr($val['sleepDt'], 11) . '</span>';
                    ?>
                    <tr class="center">
                        <td>
                            <input type="checkbox" name="chk[]" value="<?= $val['sleepNo']; ?>" data-mem-no="<?= $val['memNo']; ?>"/>
                        </td>
                        <td class="font-date"><?= substr($val['sleepDt'], 2, 8); ?></td>
                        <?php if ($gGlobal['isUse']) { ?>
                            <td class="">
                                <span class="flag flag-16 flag-<?= gd_isset($gGlobal['mallList'][$val['mallSno']]['domainFl'], 'kr'); ?>"></span><?= gd_isset($gGlobal['mallList'][$val['mallSno']]['mallName'], '기준몰'); ?>
                            </td>
                        <?php } ?>
                        <td><?= $val['memId']; ?></td>
                        <td><?= $val['memNm']; ?></td>
                        <td><?= gd_isset($groups[$val['groupSno']]); ?></td>
                        <td class="font-num"><?= number_format($val['mileage']); ?></td>
                        <td class="font-num"><?= number_format($val['deposit']); ?></td>
                        <td><?= $val['email']; ?></td>
                        <td><?= $val['cellPhone']; ?></td>
                        <td><?= $val['phone']; ?></td>
                        <td class="font-date"><?= substr($val['entryDt'], 2, 8); ?></td>
                        <td>
                            <button type="button" class="btn btn-gray btn-sm btnWake" data-no="<?= $val['sleepNo']; ?>">해제</button>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                if ($page->recode['amount'] == 0) {
                    echo '<tr class="center">';
                    echo '<td colspan="12" class="no-data">휴면회원으로 전환된 회원이 없습니다.</td>';
                    echo '</tr>';
                } else {
                    echo '<tr class="center">';
                    echo '<td colspan="12" class="no-data">검색된 정보가 없습니다.</td>';
                    echo '</tr>';
                }
            }
            ?>
            </tbody>
        </table>
    </div>

    <div class="table-action">
        <div class="pull-left">
            <button type="button" class="btn btn-white " id="checkDelete">선택 탈퇴처리</button>
        </div>
        <div class="pull-right">
            <button type="button" class="btn btn-white " id="checkAllDelete">전체 탈퇴처리</button>
        </div>
    </div>

    <div class="center"><?= $page->getPage(); ?></div>
</form>


<script type="text/javascript">
    $(document).ready(function () {
        var $formList = $('#frmList');
        // 출력수
        $('select[name=\'pageNum\']').change(function () {
            $('input[name=\'pageNum\']').val($(this).val());
            $('#frmSearchBase').submit();
        });

        // 해제
        $('.btnWake', $formList).on('click', function (e) {
            BootstrapDialog.confirm({
                title: "휴면해제",
                message: "선택한 회원을 휴면회원 상태에서 해제하시겠습니까? 해제 시 해당 회원은 다시 서비스를 정상적으로 이용하실 수 있습니다.",
                btnOKLabel: "해제",
                callback: function (result) {
                    if (result) {
                        var data = $formList.serializeArray();
                        data.push({name: "mode", value: "wake_member"});
                        data.push({name: "sleepNo", value: member.get_member_attribute(e)});
                        post_with_reload('../member/member_sleep_ps.php', data);
                    }
                }
            });
            e.preventDefault();
        });

        // 삭제
        $('#checkDelete', $formList).click(function (e) {
            e.preventDefault();

            if ($(':checkbox:checked').length == 0) {
                alert('선택된 회원이 없습니다.');
                return;
            }

            BootstrapDialog.confirm({
                title: "휴면회원 선택삭제",
                message: "휴면회원을 탈퇴처리하시겠습니까? 탈퇴처리 후에는 취소가 불가능하며, 탈퇴처리된 회원은 탈퇴처리 후 회원탈퇴안내메일이 발송됩니다.",
                btnOKLabel: "탈퇴처리",
                callback: function (result) {
                    if (result) {
                        var data = $formList.serializeArray();
                        data.push({name: "mode", value: "delete_sleep_member"});
                        post_with_reload('../member/member_sleep_ps.php', data);
                    }
                }
            });
        });

        $('#checkAllDelete', $formList).click(function (e) {
            e.preventDefault();

            BootstrapDialog.confirm({
                title: "휴면회원 전체삭제",
                message: "전체 <?= $page->recode['amount']; ?>건의 휴면회원을 탈퇴처리하시겠습니까? 탈퇴처리 후에는 취소가 불가능하며, 탈퇴처리된 회원은 탈퇴처리 후 회원탈퇴안내메일이 발송됩니다.",
                btnOKLabel: "삭제",
                callback: function (result) {
                    if (result) {
                        var data = $formList.serializeArray();
                        data.push({name: "mode", value: "delete_sleep_member_all"});
                        post_with_reload('../member/member_sleep_ps.php', data);
                    }
                }
            });
        });
    });
</script>
