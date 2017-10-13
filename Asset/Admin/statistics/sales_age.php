<div class="page-header js-affix">
    <h3><?php echo end($naviMenu->location); ?></h3>
</div>

<form id="frmSearchBase" method="get">
    <div class="table-title gd-help-manual">매출 검색 <span class="notice-danger">통계 데이터는 2시간마다 집계되므로 주문데이터와 약 1시간~2시간의 데이터 오차가 있을 수 있습니다.</span></div>
    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tbody>
        <?php
        if ($tabName == 'month') {
            ?>
            <tr>
                <th>기간검색</th>
                <td>
                    <div class="form-inline">
                        <div class="input-group js-datepicker">
                            <input type="text" class="form-control width-xs" name="searchDate[]" value="<?= $searchDate[0]; ?>"/>
                            <span class="input-group-addon"><span class="btn-icon-calendar"></span></span>
                        </div>
                        ~
                        <div class="input-group js-datepicker">
                            <input type="text" class="form-control width-xs" name="searchDate[]" value="<?php echo $searchDate[1]; ?>"/>
                            <span class="input-group-addon"><span class="btn-icon-calendar"></span></span>
                        </div>

                        <div class="btn-group js-dateperiod" data-toggle="buttons" data-target-name="searchDate[]">
                            <label class="btn btn-white btn-sm hand <?= $active['searchPeriod']['29']; ?>">
                                <input type="radio" name="searchPeriod" value="29" <?= $checked['searchPeriod']['29']; ?> >1개월
                            </label>
                            <label class="btn btn-white btn-sm hand <?= $active['searchPeriod']['89']; ?>">
                                <input type="radio" name="searchPeriod" value="89" <?= $checked['searchPeriod']['89']; ?> >3개월
                            </label>
                            <label class="btn btn-white btn-sm hand <?= $active['searchPeriod']['179']; ?>">
                                <input type="radio" name="searchPeriod" value="179" <?= $checked['searchPeriod']['179']; ?> >6개월
                            </label>
                            <label class="btn btn-white btn-sm hand <?= $active['searchPeriod']['359']; ?>">
                                <input type="radio" name="searchPeriod" value="359" <?= $checked['searchPeriod']['359']; ?> >12개월
                            </label>
                        </div>
                    </div>
                </td>
            </tr>
        <?php } else { ?>
            <tr>
                <th>기간검색</th>
                <td>
                    <div class="form-inline">
                        <div class="input-group js-datepicker">
                            <input type="text" class="form-control width-xs" name="searchDate[]" value="<?= $searchDate[0]; ?>"/>
                            <span class="input-group-addon"><span class="btn-icon-calendar"></span></span>
                        </div>
                        ~
                        <div class="input-group js-datepicker">
                            <input type="text" class="form-control width-xs" name="searchDate[]" value="<?php echo $searchDate[1]; ?>"/>
                            <span class="input-group-addon"><span class="btn-icon-calendar"></span></span>
                        </div>

                        <div class="btn-group js-dateperiod" data-toggle="buttons" data-target-name="searchDate[]">
                            <label class="btn btn-white btn-sm hand <?= $active['searchPeriod']['0']; ?>">
                                <input type="radio" name="searchPeriod" value="0" <?= $checked['searchPeriod']['0']; ?> >오늘
                            </label>
                            <label class="btn btn-white btn-sm hand <?= $active['searchPeriod']['6']; ?>">
                                <input type="radio" name="searchPeriod" value="6" <?= $checked['searchPeriod']['6']; ?> >7일
                            </label>
                            <label class="btn btn-white btn-sm hand <?= $active['searchPeriod']['14']; ?>">
                                <input type="radio" name="searchPeriod" value="14" <?= $checked['searchPeriod']['14']; ?> >15일
                            </label>
                            <label class="btn btn-white btn-sm hand <?= $active['searchPeriod']['29']; ?>">
                                <input type="radio" name="searchPeriod" value="29" <?= $checked['searchPeriod']['29']; ?> >1개월
                            </label>
                            <label class="btn btn-white btn-sm hand <?= $active['searchPeriod']['89']; ?>">
                                <input type="radio" name="searchPeriod" value="89" <?= $checked['searchPeriod']['89']; ?> >3개월
                            </label>
                        </div>
                    </div>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <div class="table-btn">
        <button type="submit" class="btn btn-lg btn-black">검색</button>
    </div>
</form>

<ul class="nav nav-tabs mgb30" role="tablist">
    <li role="presentation" <?= $tabName == 'day' ? 'class="active"' : '' ?>>
        <a href="../statistics/sales_age_day.php<?= $queryString ?>">일별 매출현황</a>
    </li>
    <li role="presentation" <?= $tabName == 'hour' ? 'class="active"' : '' ?>>
        <a href="../statistics/sales_age_hour.php<?= $queryString ?>">시간대별 매출현황</a>
    </li>
    <li role="presentation" <?= $tabName == 'week' ? 'class="active"' : '' ?>>
        <a href="../statistics/sales_age_week.php<?= $queryString ?>">요일별 매출현황</a>
    </li>
    <li role="presentation" <?= $tabName == 'month' ? 'class="active"' : '' ?>>
        <a href="../statistics/sales_age_month.php<?= $queryString ?>">월별 매출현황</a>
    </li>
</ul>
<div class="table-dashboard">
    <table class="table table-cols">
        <colgroup>
            <col style="width:20%;"/>
            <col style="width:20%;"/>
            <col style="width:20%;"/>
            <col style="width:20%;"/>
            <col style="width:20%;"/>
        </colgroup>
        <tr>
            <th class="bln point">
                총 매출금액
            </th>
            <th>
                10대
            </th>
            <th>
                20대
            </th>
            <th>
                30대
            </th>
            <th>
                40대
            </th>
        </tr>
        <tr>
            <td rowspan="3" class="bln point">
                <strong><?= gd_money_format(array_sum($daySales)); ?></strong>원
            </td>
            <td>
                <strong><?php echo gd_money_format($daySales[10]); ?></strong>원
            </td>
            <td>
                <strong><?php echo gd_money_format($daySales[20]); ?></strong>원
            </td>
            <td>
                <strong><?php echo gd_money_format($daySales[30]); ?></strong>원
            </td>
            <td>
                <strong><?php echo gd_money_format($daySales[40]); ?></strong>원
            </td>
        </tr>
        <tr>
            <th class="left-rowspan">
                50대
            </th>
            <th>
                60대
            </th>
            <th>
                70대
            </th>
            <th>
                연령미확인
            </th>
        </tr>
        <tr>
            <td class="left-rowspan">
                <strong><?php echo gd_money_format($daySales[50]); ?></strong>원
            </td>
            <td>
                <strong><?php echo gd_money_format($daySales[60]); ?></strong>원
            </td>
            <td>
                <strong><?php echo gd_money_format($daySales[70]); ?></strong>원
            </td>
            <td>
                <strong><?php echo gd_money_format($daySales['etc']); ?></strong>원
            </td>
        </tr>
    </table>
</div>

<div class="table-action mgt30 mgb0">
    <div class="pull-right">
        <button type="button" class="btn btn-white btn-icon-excel btn-excel">엑셀 다운로드</button>
    </div>
</div>

<div class="code-html js-excel-data">
    <div id="grid"></div>
</div>

<script type="text/javascript" class="code-js">
    var grid = new tui.Grid({
        el: $('#grid'),
        autoNumbering: false,
        columnFixCount: 3,
        headerHeight: 39,
        displayRowCount: <?= $rowDisplay; ?>,
        minimumColumnWidth: 20,
        columnModelList: [
            {
                "title": "<b>날짜</b>",
                "columnName": "paymentDate",
                "align": "center",
                "width": 100,
                editOption: {
                    type: 'normal'
                }
            },
            {
                "title": "<b>구분</b>",
                "columnName": "orderDevice",
                "align": "center",
                "width": 100,
                editOption: {
                    type: 'normal'
                }
            },
            {
                "title": "<b>매출총액</b>",
                "columnName": "orderSalesPrice",
                "align": "right",
                "width": 100,
                editOption: {
                    type: 'normal'
                }
            },
            {
                "title": "<b>10대</b>",
                "columnName": "10",
                "align": "right",
                "width": 100,
                editOption: {
                    type: 'normal'
                }
            },
            {
                "title": "<b>20대</b>",
                "columnName": "20",
                "width": 100,
                "align": "right",
                "editOption": {
                    type: 'normal'
                }
            },
            {
                "title": "<b>30대</b>",
                "columnName": "30",
                "width": 100,
                "align": "right",
                "editOption": {
                    type: 'normal'
                }
            },
            {
                "title": "<b>40대</b>",
                "columnName": "40",
                "align": "right",
                "width": 100,
                editOption: {
                    type: 'normal'
                }
            },
            {
                "title": "<b>50대</b>",
                "columnName": "50",
                "width": 100,
                "align": "right",
                "editOption": {
                    type: 'normal'
                }
            },
            {
                "title": "<b>60대</b>",
                "columnName": "60",
                "width": 100,
                "align": "right",
                "editOption": {
                    type: 'normal'
                }
            },
            {
                "title": "<b>70대</b>",
                "columnName": "70",
                "width": 100,
                "align": "right",
                "editOption": {
                    type: 'normal'
                }
            },
            {
                "title": "<b>연령미확인</b>",
                "columnName": "etc",
                "align": "right",
                "width": 100,
                editOption: {
                    type: 'normal'
                }
            }
        ]
    });
    grid.setRowList(<?= $rowList; ?>);

    //    grid.use('Net', {
    //        el: $('#grid'),
    //        initialRequest: true,
    //        readDataMethod: 'GET',
    //        perPage: 500,
    //        enableAjaxHistory: true,
    //        api: {
    //            readData: '/sample',
    //            downloadExcel: '/download/excel',
    //            downloadExcelAll: '/download/excelAll'
    //        }
    //    });
    // 엑셀다운로드
    $('.btn-excel').click(function () {
        grid.setDisplayRowCount('<?=$orderCount?>');
        statistics_excel_download('연령별 ');
        grid.setDisplayRowCount('<?= $rowDisplay; ?>');
    });
</script>
<script type="text/javascript" src="<?=PATH_ADMIN_GD_SHARE?>script/statistics.js"></script>
