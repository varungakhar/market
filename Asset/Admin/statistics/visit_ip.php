<div class="page-header js-affix">
    <h3><?php echo end($naviMenu->location); ?></h3>
</div>

<div class="table-title gd-help-manual">방문자 IP 검색 <?php if ($noticeFl) { ?><span class="notice-danger">PC/모바일 구분 데이터가 2017년 2월23일부터 제공됨에 따라 2017년 2월23일 이전 데이터는 PC/모바일 구분 데이터가 제공되지 않습니다.</span><?php } ?></div>
<form id="frmSearchStatistics" method="get">
    <input type="hidden" name="searchDevice">
    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tbody>
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
        </tbody>
    </table>
    <div class="table-btn">
        <button type="submit" class="btn btn-lg btn-black">검색</button>
    </div>
</form>

<div class="table-action mgt30 mgb0">
    <div class="pull-left pdt5">
        데이터 노출형태 :
        <select name="deviceFl">
            <option value="all" <?= $checked['searchDevice']['all']; ?> >통합</option>
            <option value="pc" <?= $checked['searchDevice']['pc']; ?> >PC쇼핑몰</option>
            <option value="mobile" <?= $checked['searchDevice']['mobile']; ?> >모바일쇼핑몰</option>
        </select>
    </div>
    <div class="pull-right">
        <button type="button" class="btn btn-white btn-icon-excel btn-excel">엑셀 다운로드</button>
    </div>
</div>

<div class="code-html js-excel-data">
    <div id="grid"></div>
</div>

<script>
    <!--
    $(document).ready(function () {
        $('[name="deviceFl"]').change(function (e) {
            $('[name="searchDevice"]').val($('[name="deviceFl"]').val());
            $('#frmSearchStatistics').submit();
        });
        $('.btn-excel').click(function () {
//            grid.setDisplayRowCount('<?//=$visitCount?>//');
//            statistics_excel_download('<?//=$naviMenu->location[2]?>//');
//            grid.setDisplayRowCount('<?//= $rowDisplay; ?>//');

            var $form = $('<form></form>');
            $form.attr('action', './excel_ps.php');
            $form.attr('method', 'post');
            $form.attr('target', 'ifrmProcess');
            $form.appendTo('body');

            var mode = $('<input type="hidden" name="mode" value="visit_ip_excel_download">');
            var excel_name = $('<input type="hidden" name="excel_name" value="방문자IP분석">');
            var sDate = $('<input type="hidden" name="sDate" value="' + $('input[name="searchDate[]"]:eq(0)').val() + '">');
            var eDate = $('<input type="hidden" name="eDate" value="' + $('input[name="searchDate[]"]:eq(1)').val() + '">');

            $form.append(mode).append(excel_name).append(sDate).append(eDate);
            $form.submit();
        });
    });

    var grid = new tui.Grid({
        el: $('#grid'),
        autoNumbering: false,
        columnFixCount: 1,
        headerHeight: 50,
        displayRowCount: <?= $rowDisplay; ?>,
        minimumColumnWidth: 20,
        columnModelList : [
            {
                "title" : "<b>접속시간</b>",
                "columnName" : "regDt",
                "align" : "center",
                "width" : 100,
                editOption: {
                    type: 'normal'
                },
            },
            {
                "title" : "<b>IP</b>",
                "columnName" : "visitIP",
                "align" : "center",
                "width" : 100,
                editOption: {
                    type: 'normal'
                }
            },
            {
                "title" : "<b>운영체제</b>",
                "columnName" : "visitOS",
                "align" : "center",
                "width" : 100,
                editOption: {
                    type: 'normal'
                }
            },
            {
                "title" : "<b>브라우저</b>",
                "columnName" : "visitBrowser",
                "align" : "center",
                "width" : 100,
                editOption: {
                    type: 'normal'
                }
            },
            {
                "title" : "<b>페이지뷰</b>",
                "columnName" : "visitPageView",
                "align" : "center",
                "width" : 100,
                editOption: {
                    type: 'normal'
                }
            },
            {
                "title" : "<b>방문경로</b>",
                "columnName" : "visitReferer",
                "align" : "center",
                "width" : 600,
                editOption: {
                    type: 'normal'
                }
            }
        ]
    });
    grid.setRowList(<?= $rowList; ?>);
    //-->
</script>
<!--<script type="text/javascript" src="--><?//=PATH_ADMIN_GD_SHARE?><!--script/statistics.js"></script>-->
