<form id="form_search" method="get" class="content-form js-search-form">
    <input type="hidden" name="indicate" value="search"/>
    <input type="hidden" name="pageNum" value="<?= $search['pageNum']; ?>"/>
    <input type="hidden" name="memNo" id="memNo" value="<?= $memberData['memNo'] ?>"/>
    <input type="hidden" name="detailSearch" value="<?= gd_isset($search['detailSearch']); ?>"/>

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
                    <input type="text" name="keyword" value="<?= gd_isset($search['keyword']); ?>"
                           class="form-control"/>
                </td>
            </tr>
            <tr>
                <th>지급/차감일</th>
                <td>
                    <div class="input-group js-datepicker">
                        <input type="text" name="regDt[]" class="form-control width-xs" placeholder=""
                               value="<?= gd_isset($search['regDt'][0]); ?>">
                        <span class="input-group-addon">
                            <span class="btn-icon-calendar">
                            </span>
                        </span>
                    </div>
                    ~
                    <div class="input-group js-datepicker">
                        <input type="text" name="regDt[]" class="form-control width-xs" placeholder=""
                               value="<?= gd_isset($search['regDt'][1]); ?>">
                        <span class="input-group-addon">
                            <span class="btn-icon-calendar">
                            </span>
                        </span>
                    </div>
                    <div class="btn-group js-dateperiod" data-toggle="buttons" data-target-name="regDt">
                        <label class="btn btn-white btn-sm">
                            <input type="radio" value="0"
                                   name="regDtPeriod" <?= gd_isset($checked['regDtPeriod']['0']); ?>>
                            오늘
                        </label>
                        <label class="btn btn-white btn-sm">
                            <input type="radio" value="7"
                                   name="regDtPeriod" <?= gd_isset($checked['regDtPeriod']['7']); ?>>
                            7일
                        </label>
                        <label class="btn btn-white btn-sm">
                            <input type="radio" value="15"
                                   name="regDtPeriod" <?= gd_isset($checked['regDtPeriod']['15']); ?>>
                            15일
                        </label>
                        <label class="btn btn-white btn-sm">
                            <input type="radio" value="30"
                                   name="regDtPeriod"<?= gd_isset($checked['regDtPeriod']['30']); ?>>
                            1개월
                        </label>
                        <label class="btn btn-white btn-sm">
                            <input type="radio" value="90"
                                   name="regDtPeriod" <?= gd_isset($checked['regDtPeriod']['90']); ?>>
                            3개월
                        </label>
                        <label class="btn btn-white btn-sm">
                            <input type="radio" value="-1"
                                   name="regDtPeriod" <?= gd_isset($checked['regDtPeriod']['-1']); ?>>
                            전체
                        </label>
                    </div>
                </td>
                <th>지급/차감사유</th>
                <td>
                    <?= gd_select_box('reasonCd', 'reasonCd', $mileageReasons, null, $search['reasonCd'], '전체'); ?>
                    <div>
                        <input type="hidden" name="contents" class="form-control" value="<?= $search['contents']; ?>"/>
                    </div>
                </td>
            </tr>
            <tr>
                <th>지급/차감구분</th>
                <td>
                    <label>
                        <input type="radio" name="mode"
                               value="all" <?= gd_isset($checked['mode']['all']); ?>/>
                        전체
                    </label>
                    <label>
                        <input type="radio" name="mode"
                               value="add" <?= gd_isset($checked['mode']['add']); ?>/>
                        지급
                    </label>
                    <label>
                        <input type="radio" name="mode"
                               value="remove" <?= gd_isset($checked['mode']['remove']); ?>/>
                        차감
                    </label>
                </td>
                <th>금액범위</th>
                <td>
                    <input
                        type="text" name="mileage[]" value="<?= gd_isset($search['mileage'][0]); ?>"
                        class="form-control"/>
                    ~
                    <input type="text" name="mileage[]" value="<?= gd_isset($search['mileage'][1]); ?>"
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
            마일리지 지급/차감리스트 (검색결과
            <strong><?= $page->recode['total']; ?></strong>
            건/ 전체
            <strong><?= $page->recode['amount']; ?></strong>
            건)
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
        // @todo : 시스템 적립 건은 자동지급으로 표기해야함 자동지급일 경우 mangerId 확인 필요
        if (isset($data) && is_array($data)) {
            $listHtml = [];
            foreach ($data as $val) {
                $isMinusSign = strpos($val['mileage'], '-') === 0;
                $reasonContents = gd_isset($mileageReasons[$val['reasonCd']]);
                switch ($val['reasonCd']) {
                    case '01005001':
                    case '01005002':
                    case '01005003':
                    case '01005004':
                    case '01005008':
                        $reasonContents .= '<br/>(주문번호 : ';
                        $reasonContents .= '<a href="#" class="js-link-order" data-order-no="' . $val['handleCd'] . '">' . $val['handleCd'] . '</a>)';
                        break;
                    case '01005006':
                        $reasonContents .= '<br/>(' . $val['handleCd'] . ')';
                        break;
                    case '01005009':
                    case '01005010':
                        $reasonContents .= '<br/>(' . $boards[$val['handleCd']] . ')';
                        break;
                    case '01005011':
                        $reasonContents = $val['contents'];
                        break;
                }

                $listHtml[] = '<tr class="center" data-member-no="' . $val['memNo'] . '">';
                $listHtml[] = '<td class="font-num">' . $page->idx-- . '</td>';
                if ($isMinusSign) {
                    $listHtml[] = '<td class="font-num">-</td>';
                } else {
                    $listHtml[] = '<td class="font-num">(+)' . gd_money_format($val['mileage']) . gd_display_mileage_unit() . '</td>';
                }
                if ($isMinusSign) {
                    $listHtml[] = '<td class="font-num">(-)' . gd_money_format(substr($val['mileage'], 1)) . gd_display_mileage_unit() . '</td>';
                } else {
                    $listHtml[] = '<td class="font-num">-</td>';
                }
                $listHtml[] = '<td class="font-date">' . gd_date_format('Y-m-d', $val['regDt']) . '<br/>' . gd_date_format('H:i', $val['regDt']) . '</td>';
                $listHtml[] = '<td class="center">' . $val['managerId'] . '</td>';
                $listHtml[] = '<td>' . $reasonContents . '</td></tr>';
            }
            echo implode('', $listHtml);
        }
        ?>
        </tbody>
    </table>

    <div class="center"><?= $page->getPage(); ?></div>
</form>
<script type="text/javascript">
    var gd_batch_mileage_list = {
        reasonCd: <?= json_encode($mileageReasons); ?>
    };
    $(document).ready(function () {
        $('.js-search-form').on('change', ':radio[name=mode]:checked', function (e) {
            var $target = $(e.target);
            var $mileage = $(':text[name="mileage[]"]');
            var mileage1 = $mileage.eq(0).val();
            var mileage2 = $mileage.eq(1).val();
            switch ($target.val()) {
                case 'all':
                    $mileage.eq(0).val('');
                    $mileage.eq(1).val('');
                    break;
                case 'add':
                    mileage1 = mileage1.replace('-', '');
                    mileage2 = mileage2.replace('-', '');
                    if (mileage1 >= mileage2) {
                        $mileage.eq(0).val(mileage2);
                        $mileage.eq(1).val(mileage1);
                    }
                    break;
                case 'remove':
                    mileage1 = ('-' + mileage1) * 1;
                    mileage2 = ('-' + mileage2) * 1;
                    if (mileage1 >= mileage2) {
                        $mileage.eq(0).val(mileage2);
                        $mileage.eq(1).val(mileage1);
                    }
                    break;
            }
        }).on('change', 'select[name=reasonCd]', function (e) {
            var $target = $(e.target);
            var $option = $target.find(':selected');
            var $contents = $('input[name=contents]');

            if ('01005011' == $option.val()) {
                $contents.attr('type', 'text').focus();
                if (_.isEmpty($contents.val())) {
                    $contents.val(gd_batch_mileage_list.reasonCd[$option.val()]);
                }
            } else {
                $contents.attr('type', 'hidden');
                $contents.val(gd_batch_mileage_list.reasonCd[$option.val()]);
            }
        });
        $('.btn-register').click(function () {
            layer_member_mileage();
        });
        $('select[name=reasonCd]').trigger('change');
        $('select[name=\'pageNum\']').change({targetForm: '.js-search-form'}, member.page_number);
    });
</script>
