<?php
$requestGetParams = Request::get()->all();
$groups = gd_member_groups();
$depositReasons = gd_code('01006');
?>
<form id="form_search" method="get" class="content-form js-search-form">
    <input type="hidden" name="indicate" value="search"/>
    <input type="hidden" name="pageNum" value="<?= $requestGetParams['pageNum']; ?>"/>
    <input type="hidden" name="memNo" id="memNo" value="<?= $requestGetParams['memNo']; ?>"/>
    <input type="hidden" name="detailSearch" value="<?= $requestGetParams['detailSearch']; ?>"/>

    <div class="form-inline search-detail-box">
        <table class="table table-cols">
            <colgroup>
                <col class="width-sm"/>
                <col/>
                <col class="width-sm"/>
                <col/>
            </colgroup>
            <tbody>
            <tr>
                <th>처리자</th>
                <td colspan="3">
                    <input type="hidden" name="key" value="managerId"/>
                    <input type="text" name="keyword" value="<?= $requestGetParams['keyword']; ?>"
                           class="form-control"/>
                </td>
            </tr>
            <tr>
                <th>지급/차감일</th>
                <td>
                    <div class="input-group js-datepicker">
                        <input type="text" name="regDt[]" class="form-control width-xs" placeholder="" value="<?= $requestGetParams['regDt'][0]; ?>">
                        <span class="input-group-addon">
                            <span class="btn-icon-calendar">
                            </span>
                        </span>
                    </div>
                    ~
                    <div class="input-group js-datepicker">
                        <input type="text" name="regDt[]" class="form-control width-xs" placeholder="" value="<?= $requestGetParams['regDt'][1]; ?>">
                        <span class="input-group-addon">
                            <span class="btn-icon-calendar">
                            </span>
                        </span>
                    </div>
                    <div class="btn-group js-dateperiod" data-toggle="buttons" data-target-name="regDt">
                        <label class="btn btn-white btn-sm">
                            <input type="radio" value="0"
                                   name="regDtPeriod" <?= $checked['regDtPeriod']['0']; ?>>
                            오늘
                        </label>
                        <label class="btn btn-white btn-sm">
                            <input type="radio" value="7"
                                   name="regDtPeriod" <?= $checked['regDtPeriod']['7']; ?>>
                            7일
                        </label>
                        <label class="btn btn-white btn-sm">
                            <input type="radio" value="15"
                                   name="regDtPeriod" <?= $checked['regDtPeriod']['15']; ?>>
                            15일
                        </label>
                        <label class="btn btn-white btn-sm">
                            <input type="radio" value="30"
                                   name="regDtPeriod"<?= $checked['regDtPeriod']['30']; ?>>
                            1개월
                        </label>
                        <label class="btn btn-white btn-sm">
                            <input type="radio" value="90"
                                   name="regDtPeriod" <?= $checked['regDtPeriod']['90']; ?>>
                            3개월
                        </label>
                        <label class="btn btn-white btn-sm">
                            <input type="radio" value="-1"
                                   name="regDtPeriod" <?= $checked['regDtPeriod']['-1']; ?>>
                            전체
                        </label>
                    </div>
                </td>
                <th>지급/차감사유</th>
                <td>
                    <?= gd_select_box('reasonCd', 'reasonCd', $depositReasons, null, $requestGetParams['reasonCd'], '전체'); ?>
                    <div>
                        <input type="hidden" name="contents" class="form-control" value="<?= $requestGetParams['contents']; ?>"/>
                    </div>
                </td>
            </tr>
            <tr>
                <th>지급/차감구분</th>
                <td>
                    <label>
                        <input type="radio" name="mode"
                               value="all" <?= $checked['mode']['all']; ?>/>
                        전체
                    </label>
                    <label>
                        <input type="radio" name="mode"
                               value="add" <?= $checked['mode']['add']; ?>/>
                        지급
                    </label>
                    <label>
                        <input type="radio" name="mode"
                               value="remove" <?= $checked['mode']['remove']; ?>/>
                        차감
                    </label>
                </td>
                <th>금액범위</th>
                <td>
                    <input
                        type="text" name="deposit[]" value="<?= $requestGetParams['deposit'][0]; ?>"
                        class="form-control"/>
                    ~
                    <input type="text" name="deposit[]" value="<?= $requestGetParams['deposit'][1]; ?>"
                           class="form-control"/>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="table-btn">
        <input type="submit" value="검색" class="btn btn-lg btn-black" id="btn_search">
    </div>
</form>

<form id="frmList" action="" method="get" target="ifrmProcess">
    <div class="table-header form-inline">
        <div class="pull-left">
            검색
            <strong><?= $page->recode['total']; ?></strong>
            건 / 전체
            <strong><?= $page->recode['amount']; ?></strong>
            건
        </div>
        <div class="pull-right">
            <div>
                <?php echo gd_select_box(
                    'pageNum', 'pageNum', gd_array_change_key_value(
                    [
                        10,
                        20,
                        30,
                        40,
                        50,
                        60,
                        70,
                        80,
                        90,
                        100,
                        200,
                        300,
                        500,
                    ]
                ), '개 보기', Request::get()->get('pageNum'), null
                ); ?>
            </div>
        </div>
    </div>

    <table class="table table-rows">
        <colgroup>
            <col class="width-xs"/>
            <col/>
            <col/>
            <col/>
            <col/>
            <col/>
        </colgroup>
        <thead>
        <tr>
            <th>번호</th>
            <th>지급액</th>
            <th>차감액</th>
            <th>지급/차감일</th>
            <th>처리자</th>
            <th>사유</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (!empty($depositList) && is_array($depositList)) {
            $listHtml = [];
            foreach ($depositList as $val) {
                $isMinusSign = strpos($val['deposit'], '-') === 0;
                $reasonContents = $depositReasons[$val['reasonCd']];
                switch ($val['reasonCd']) {
                    case '01006006':
                        $reasonContents = $val['contents'];
                        break;
                }

                $listHtml[] = '<tr class="center" data-member-no="' . $val['memNo'] . '">';
                $listHtml[] = '<td class="font-num">' . $page->idx-- . '</td>';
                if ($isMinusSign) {
                    $listHtml[] = '<td class="font-num">-</td>';
                } else {
                    $listHtml[] = '<td class="font-num">(+)' . gd_money_format($val['deposit']) . gd_display_deposit('unit') . '</td>';
                }
                if ($isMinusSign) {
                    $listHtml[] = '<td class="font-num">(-)' . gd_money_format(substr($val['deposit'], 1)) . gd_display_deposit('unit') . '</td>';
                } else {
                    $listHtml[] = '<td class="font-num">-</td>';
                }
                $listHtml[] = '<td class="font-date">' . gd_date_format('Y-m-d', $val['regDt']) . '<br/>' . gd_date_format('H:i', $val['regDt']) . '</td>';
                $listHtml[] = '<td class="center">' . $val['managerId'] . '</td>';
                $listHtml[] = '<td>' . $reasonContents . '</td></tr>';
            }
            echo implode('', $listHtml);
        } else {
            ?>
            <tr>
                <td colspan="10" class="no-data">예치금 내역이 없습니다.</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <div class="center"><?= $page->getPage(); ?></div>
</form>
<script type="text/javascript">
    var gd_batch_deposit_list = {
        reasonCd: <?= json_encode($depositReasons); ?>
    };
    $(document).ready(function () {
        $('.js-search-form').on('change', ':radio[name=mode]:checked', function (e) {
            var $target = $(e.target);
            var $deposit = $(':text[name="deposit[]"]');
            var deposit1 = $deposit.eq(0).val();
            var deposit2 = $deposit.eq(1).val();
            switch ($target.val()) {
                case 'all':
                    $deposit.eq(0).val('');
                    $deposit.eq(1).val('');
                    break;
                case 'add':
                    deposit1 = deposit1.replace('-', '');
                    deposit2 = deposit2.replace('-', '');
                    if (deposit1 >= deposit2) {
                        $deposit.eq(0).val(deposit2);
                        $deposit.eq(1).val(deposit1);
                    }
                    break;
                case 'remove':
                    deposit1 = ('-' + deposit1) * 1;
                    deposit2 = ('-' + deposit2) * 1;
                    if (deposit1 >= deposit2) {
                        $deposit.eq(0).val(deposit2);
                        $deposit.eq(1).val(deposit1);
                    }
                    break;
            }
        }).on('change', 'select[name=reasonCd]', function (e) {
            var $target = $(e.target);
            var $option = $target.find(':selected');
            var $contents = $('input[name=contents]');

            if ('01006006' == $option.val()) {
                $contents.attr('type', 'text').focus();
                if (_.isEmpty($contents.val())) {
                    $contents.val(gd_batch_deposit_list.reasonCd[$option.val()]);
                }
            } else {
                $contents.attr('type', 'hidden');
                $contents.val(gd_batch_deposit_list.reasonCd[$option.val()]);
            }
        });

        $('.btn-register').click(function () { // 온 타겟을 바꿔야함
            var loadChk = $('div#formMemberDeposit').length;
            $.get('../share/layer_member_deposit.php', {}, function (data) {
                if (loadChk === 0) {
                    data = '<div id="#formMemberDeposit">' + data + '</div>';
                }

                BootstrapDialog.show({
                    name: "layer_member_deposit",
                    title: "예치금 지급/차감",
                    size: BootstrapDialog.SIZE_WIDE,
                    message: $(data),
                    closable: true
                });
            });
        });
        $('select[name=reasonCd]').trigger('change');
        $('select[name=\'pageNum\']').change({targetForm: '.js-search-form'}, member.page_number);
    });
</script>
