<form id="formSearch" method="get" class="content-form js-search-form">
    <input type="hidden" name="indicate" value="search"/>
    <input type="hidden" name="memNo" id="memNo" value="<?= $requestGetParams['memNo']; ?>"/>
    <input type="hidden" name="navTabs" id="navTabs" value="<?= $requestGetParams['navTabs']; ?>"/>
    <input type="hidden" name="detailSearch" value="<?= $requestGetParams['detailSearch']; ?>"/>
    <input type="hidden" name="sort" value="<?= $requestGetParams['sort']; ?>"/>
    <input type="hidden" name="pageNum" value="<?= $requestGetParams['pageNum']; ?>"/>

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
                <th>등록자</th>
                <td>
                    <input type="hidden" name="key" value="managerNm"/>
                    <input type="text" name="keyword" value="<?= gd_isset($requestGetParams['keyword']); ?>"
                           class="form-control"/>
                </td>
                <th>기간검색</th>
                <td>
                    <div class="input-group js-datepicker">
                        <input type="text" class="form-control" placeholder="" name="regDt[]"
                               value="<?= $requestGetParams['regDt'][0]; ?>"/>
                        <span class="input-group-addon">
                        <span class="btn-icon-calendar">
                        </span>
                        </span>
                    </div>
                    ~
                    <div class="input-group js-datepicker">
                        <input type="text" class="form-control" placeholder="" name="regDt[]"
                               value="<?= $requestGetParams['regDt'][1]; ?>"/>
                        <span class="input-group-addon">
                        <span class="btn-icon-calendar">
                        </span>
                        </span>
                    </div>
                </td>
            </tr>
            <tr>
                <th>상담수단</th>
                <td>
                    <label>
                        <input type="radio" name="method"
                               value="" <?= $checked['method']['']; ?>/>
                        전체
                    </label>
                    <label>
                        <input type="radio" name="method"
                               value="p" <?= $checked['method']['p']; ?>/>
                        전화
                    </label>
                    <label>
                        <input type="radio" name="method"
                               value="m" <?= $checked['method']['m']; ?>/>
                        메일
                    </label>
                </td>
                <th>상담구분</th>
                <td><?= gd_select_box('kind', 'kind', $kinds, null, gd_isset($requestGetParams['kind']), '전체'); ?></td>
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
            상담 리스트 (검색결과<strong><?= $page->recode['total']; ?></strong>건/ 전체<strong><?= $page->recode['amount']; ?></strong>건)
        </div>
        <div class="pull-right">
            <?= gd_select_box('sort', 'sort', $sorts, '', Request::get()->get('sort')); ?>
            &nbsp;
            <?= gd_select_box_by_page_view_count(Request::get()->get('pageNum', 10)); ?>
        </div>
    </div>

    <table class="table table-rows">
        <colgroup>
            <col class="width-xs"/>
            <col class="width-xs"/>
            <col>
            <col>
            <col>
            <col>
            <col>
        </colgroup>
        <thead>
        <tr>
            <th>번호</th>
            <th>등록일</th>
            <th>등록자</th>
            <th>상담수단</th>
            <th>상담구분</th>
            <th>상담내용</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (count($list) > 0) {
            $listHtml = [];
            foreach ($list as $val) {
                $listHtml[] = '<tr class="center" data-sno="' . $val['sno'] . '">';
                $listHtml[] = '<td class="font-num">' . $page->idx-- . '</td>';
                $listHtml[] = '<td class="font-date">' . $val['regDt'] . '</td>';
                $listHtml[] = '<td>' . $val['managerNm'] . '</td>';
                $listHtml[] = '<td>' . $val['method'] . '</td>';
                $listHtml[] = '<td>' . $val['kind'] . '</td>';
                $listHtml[] = '<td>' . str_replace(array('\r\n', '\r', '\n'), '<br />', $val['contents']) . '</td>';
                $listHtml[] = '</tr>';
            }
            echo implode('', $listHtml);
        } else {
            echo '<tr> <td colspan="8" class="no-data">메일발송내역이 없습니다.</td> </tr>';
        }
        ?>
        </tbody>
    </table>

    <div class="center"><?= $page->getPage(); ?></div>
</form>
<script type="text/javascript">
    $('.btn-register').click(function () {
        member_counsel($('#memNo').val());
    });
    $('select[name=\'sort\']').change({targetForm: '#formSearch'}, member.page_sort);
    $('select[name=\'pageNum\']').change({targetForm: '#formSearch'}, member.page_number);
</script>
