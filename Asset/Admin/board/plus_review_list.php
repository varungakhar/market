<style>
    .js-contents-short {
        display: inline-block;
        width: 95%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
<div class="page-header js-affix">
    <h3><?= end($naviMenu->location); ?>
    <small>플러스리뷰에 등록된 게시글을 관리합니다.</small>
    </h3>
</div>
<div class="table-title gd-help-manual">플러스리뷰 게시글 관리</div>
<form name="frmSearch" id="frmSearch" action="plus_review_list.php" class="frmSearch js-form-enter-submit">
    <div class="search-detail-box">
        <table class="table table-cols">
            <tr>
                <th>게시판</th>
                <td colspan="3"><b>플러스리뷰 게시판</b></td>
            </tr>
            <tr>
                <th>일자</th>
                <td class="form-inline" colspan="3">
                    <select name="searchDateFl" class="form-control">
                        <option value="regDt" <?php if ($req['searchDateFl'] == 'regDt') echo 'selected' ?>>
                            등록일
                        </option>
                        <option value="modDt" <?php if ($req['searchDateFl'] == 'modDt') echo 'selected' ?>>
                            수정일
                        </option>
                    </select>

                    <div class="input-group js-datepicker">
                        <input type="text" class="form-control width-xs" name="rangDate[]"
                               value="<?= $req['rangDate'][0]; ?>">
                        <span class="input-group-addon">
                                        <span class="btn-icon-calendar">
                                        </span>
                                    </span>
                    </div>
                    ~
                    <div class="input-group js-datepicker">
                        <input type="text" class="form-control width-xs" name="rangDate[]"
                               value="<?= $req['rangDate'][1]; ?>">
                        <span class="input-group-addon">
                                        <span class="btn-icon-calendar">
                                        </span>
                                    </span>
                    </div>
                    <?= gd_search_date(gd_isset($req['searchPeriod'], 7), 'rangDate') ?>
                </td>
            </tr>
            <tr>
                <th class="width-md">검색어</th>
                <td class="form-inline" colspan="3">
                    <select class="form-control" name="searchField">
                        <option value="goodsNm" <?php if ($req['searchField'] == 'goodsNm') echo 'selected' ?>>상품명
                        </option>
                        <option value="contents" <?php if ($req['searchField'] == 'contents') echo 'selected' ?>>내용
                        </option>
                        <option value="writerNm" <?php if ($req['searchField'] == 'writerName') echo 'selected' ?>>이름
                        </option>
                        <option value="writerNick" <?php if ($req['searchField'] == 'writerNick') echo 'selected' ?>>닉네임
                        </option>
                        <option value="writerId" <?php if ($req['searchField'] == 'writerId') echo 'selected' ?>>아이디
                        </option>
                    </select>
                    <input name="searchWord" value="<?= gd_isset($req['searchWord']) ?>" class="form-control form-control width-3xl">
                </td>
            </tr>
            <tr>
                <th class="width-md">속성</th>
                <td class="form-inline">
                    <label class="radio-inline"><input type="radio" name="reviewType" value="" <?php if ($req['reviewType'] == '') echo 'checked' ?>>전체</label>
                    <label class="radio-inline"><input type="radio" name="reviewType" value="photo" <?php if ($req['reviewType'] == 'photo') echo 'checked' ?>>포토리뷰</label>
                    <label class="radio-inline"><input type="radio" name="reviewType" value="text" <?php if ($req['reviewType'] == 'text') echo 'checked' ?>>일반리뷰</label>
                </td>
                <th class="width-md">댓글여부</th>
                <td class="form-inline">
                    <label class="radio-inline"><input type="radio" name="isMemo" value="" <?php if ($req['isMemo'] == '') echo 'checked' ?>>전체</label>
                    <label class="radio-inline"><input type="radio" name="isMemo" value="y" <?php if ($req['isMemo'] == 'y') echo 'checked' ?>>댓글있음</label>
                    <label class="radio-inline"><input type="radio" name="isMemo" value="n" <?php if ($req['isMemo'] == 'n') echo 'checked' ?>>댓글없음</label>
                </td>
            </tr>
        </table>
        <div class="table-btn">
            <input type="submit" value="검색" class="btn btn-lg btn-black">
        </div>
</form>

<div class="table-header">
    <div class="pull-left">
        검색&nbsp;<strong><?= number_format($list['cnt']['search']) ?></strong>개/
        전체&nbsp;<strong><?= number_format($list['cnt']['total']) ?></strong>개
    </div>
    <div class="pull-right">
        <div class="form-inline">
            <?= gd_select_box('sort', 'sort', $list['sort'], null, $req['sort']); ?>
            <?= gd_select_box_by_page_view_count(Request::get()->get('pageNum', 10)); ?>
        </div>
    </div>
</div>

<form name="frmList" id="frmList" action="plus_review_ps.php" method="post">
    <input type="hidden" name="mode" value="delete">
    <table class="table table-rows table-fixed">
        <thead>
        <tr>
            <th class="width-2xs"><input type="checkbox" class="js-checkall" data-target-name="sno"></th>
            <th class="width-sm">번호</th>
            <th>내용</th>
            <th class="width-2xs">속성</th>
            <th class="width-2xs">댓글</th>
            <th class="width-2xs">평가</th>
            <th class="width-sm">작성자</th>
            <th class="width-sm">작성일</th>
            <th class="width-2xs">추천</th>
            <th class="width-sm">수정</th>
        </tr>
        </thead>
        <?php
        if (gd_array_is_empty($list['list']) === false) {
            foreach ($list['list'] as $val) {
                ?>
                <tr class="center">
                    <td><input name="sno[]" type="checkbox" value="<?= $val['sno'] ?>"></td>
                    <td class="font-num">
                        <?= $val['no'] ?>
                    </td>
                    <td align="left">
                        <a href="javascript:view(<?=$val['sno']?>)" class="js-contents-short">
                            <?php if($val['isFile'] == 'y') {?>
                                <img src="<?=PATH_ADMIN_GD_SHARE?>img/ico_bd_file.gif" />
                            <?php }?>
                            <?php if($val['isNew'] == 'y') {?>
                                <img src="<?=PATH_ADMIN_GD_SHARE?>img/ico_bd_new.gif" />
                            <?php }?>
                            <?= $val['listContents'] ?></a>
                    </td>
                    <td align="center">
                        <?= $val['reviewTypeText'] ?>
                    </td>
                    <td align="center">
                        <?= $val['memoCnt'] ?>
                    </td>
                    <td align="center">
                        <?= $val['goodsPt'] ?>
                    </td>
                    <td>
                        <?= $val['writer'] ?>
                    </td>
                    <td>
                        <?= $val['regDate'] ?>
                    </td>
                    <td>
                        <?= $val['recommend'] ?>
                    </td>

                    <td><input type="button" value="수정" class="btn btn-white" onclick="modify(<?= $val['sno'] ?>)"></td>
                </tr>

                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="7" height="50" class="no-data">검색된 정보가 없습니다.</td>
            </tr>
        <?php } ?>
    </table>

    <div class="table-action">
        <div class="pull-left form-inline">
            <span class="action-title">선택한 게시글</span>
            <button type="submit" class="btn btn-white js-btn-delete">삭제</button>
        </div>
    </div>
    <div class="center"><?= $list['pagination'] ?></div>
</form>
</div>

<script language="javascript">
    function modify(sno) {
        location.href = 'plus_review_register.php?sno=' + sno+"&mode=modify&<?=Request::getQueryString()?>";
    }

    function view(sno) {
        location.href = 'plus_review_view.php?sno=' + sno+"&<?=Request::getQueryString()?>";
    }

    $(document).ready(function () {
        $('select[name=\'pageNum\']').change(function () {
            $('#frmSearch').submit();
        });

        $('select[name=\'sort\']').change(function () {
            $('#frmSearch').submit();
        });

        $('#frmList').validate({
            ignore: ':hidden',
            dialog: false,
            submitHandler: function (form) {
                form.target = 'ifrmProcess';
                dialog_confirm('선택한 게시글을 삭제하시겠습니까?\n\r영구 삭제되어 복원 불가능합니다.', function (result) {
                    if (result) {
                        form.submit();
                    }
                });

            },
            rules: {
                'sno[]': {
                    required: true
                }
            },
            messages: {
                'sno[]': {
                    required: '선택된 게시글이 없습니다.'
                },

            },
        });
    });


</script>
