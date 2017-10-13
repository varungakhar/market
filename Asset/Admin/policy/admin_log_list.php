<div class="page-header js-affix">
    <h3><?= end($naviMenu->location); ?>
    </h3>
</div>
<div class="table-title gd-help-manual">
    개인정보접속기록 조회
</div>

<form name="frmSearch" id="frmSearch" action="admin_log_list.php" method="get" class="js-form-enter-submit">
    <div class="search-detail-box">
        <table class="table table-cols mgb15">
            <tr>
                <th class="width-md">운영자 아이디</th>
                <td><input type="text" class="form-control" name="managerId" value="<?=$req['managerId']?>"/></td>
            </tr>
            <tr>
                <th class="width-md">검색기간</th>
                <td>
                    <div class="form-inline">
                        <div class="input-group js-datepicker">
                            <input type="text" class="form-control width-xs" name="searchDate[]"
                                   value="<?= $req['searchDate'][0]; ?>">
                                    <span class="input-group-addon">
                                        <span class="btn-icon-calendar">
                                        </span>
                                    </span>
                        </div>
                        ~
                        <div class="input-group js-datepicker">
                            <input type="text" class="form-control width-xs" name="searchDate[]"
                                   value="<?= $req['searchDate'][1]; ?>">
                                    <span class="input-group-addon">
                                        <span class="btn-icon-calendar">
                                        </span>
                                    </span>
                        </div>
                        <?= gd_search_date(gd_isset($req['searchPeriod'], 7), 'searchDate',false) ?>
                        <span class="notice-info mgl15">최근 6개월 내 접속기록만 조회 가능합니다</span>
                    </div>
                </td>
            </tr>
        </table>

        <div class="notice-danger mgb0 mgl15 mgb15">개인정보의 안전성 확보에 필요한 조치에 관한 사항<br>
        개인정보의 기술적 관리적 보호조치 기준에 따라 정보통신서비스 제공자 등은 개인정보취급자가 개인정보처리시스템에 접속한 기록을<br>
        월 1회 이상 정기적으로 확인·감독하여야 하며,시스템 이상 유무의 확인 등을 위해 최소 6개월 이상 접속기록을 보존·관리하여야 합니다. </div>
        <div class="linepd30"></div>

        <div class="table-btn">
            <input type="submit" value="검색" class="btn btn-lg btn-black">
        </div>

        <div class="table-header">
            <div class="pull-right">
                <div class="form-inline">
                    <?= gd_select_box('sort', 'sort', $search['sortList'], null, $search['sort']); ?>
                    <?= gd_select_box_by_page_view_count(Request::get()->get('pageNum', 20)); ?>
                </div>
            </div>
        </div>
    </div>
</form>

<table class="table table-rows table-fixed">
    <colgroup>
        <col class="width15p">
        <col class="width15p">
        <col class="width15p">
        <col class="width15p">
        <col >
        <col class="width15p">
    </colgroup>
    <thead>
    <tr>
        <th>접속일시</th>
        <th>접속IP</th>
        <th>운영자 아이디</th>
        <th>메뉴구분</th>
        <th>접속페이지<br>(개인정보관련)</th>
        <th>수행업무</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($data['list']  as $row){
        if(!$row['page']) {
            $row['page'] = $row['baseUri'];
        }
        ?>
    <tr class="text-center">
        <td><?=$row['regDt']?></td>
        <td><?=$row['ip']?></td>
        <td><?=$row['managerId']?> <?=$row['deleteText']?></td>
        <td><?=$row['menu']?></td>
        <td><?=$row['page']?></td>
        <td><?=$row['action']?></td>
    </tr>
    <?php }?>
    </tbody>
</table>
<div align="center"><?=$data['page']; ?></div>

<script>
    $(document).ready(function(){
        $('select[name=\'pageNum\']').change(function () {
            $('#frmSearch').submit();
        });
    });
</script>
