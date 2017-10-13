<div class="page-header js-affix">
    <h3><?php echo end($naviMenu->location); ?></h3>
    <div class="btn-group">
        <a href="coupon_offline_regist.php" class="btn btn-red-line">쿠폰 등록</a>
    </div>
</div>

<form id="frmSearchCoupon" method="get" class="js-form-enter-submit">
    <h5 class="table-title">페이퍼 쿠폰 검색</h5>
    <div class="search-detail-box">
        <table class="table table-cols">
            <colgroup>
                <col class="width-md">
                <col>
                <col class="width-md">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th>검색어</th>
                <td colspan="3">
                    <div class="form-inline">
                        <?= gd_select_box('key', 'key', $search['combineSearch'], null, $search['key']); ?>
                        <input type="text" name="keyword" value="<?php echo $search['keyword']; ?>" class="form-control"/>
                    </div>
                </td>
            </tr>
            <tr>
                <th>등록일 검색</th>
                <td colspan="3">
                    <div class="form-inline">
                        <div class="input-group js-datepicker">
                            <input type="text" class="form-control width-xs" name="searchDate[]" value="<?php echo $search['searchDate'][0]; ?>"/>
                            <span class="input-group-addon"><span class="btn-icon-calendar"></span></span>
                        </div>
                        ~
                        <div class="input-group js-datepicker">
                            <input type="text" class="form-control width-xs" name="searchDate[]" value="<?php echo $search['searchDate'][1]; ?>"/>
                            <span class="input-group-addon"><span class="btn-icon-calendar"></span></span>
                        </div>
                        <?= gd_search_date($search['searchPeriod']) ?>
                    </div>
                </td>
            </tr>
            <tr>
                <th>쿠폰유형</th>
                <td>
                    <label class="radio-inline">
                        <input type="radio" name="couponUseType" value="" <?= $checked['couponUseType']['']; ?>/>전체
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="couponUseType" value="product" <?= $checked['couponUseType']['product']; ?>/>상품적용쿠폰
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="couponUseType" value="order" <?= $checked['couponUseType']['order']; ?>/>주문적용쿠폰
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="couponUseType" value="delivery" <?= $checked['couponUseType']['delivery']; ?>/>배송비적용쿠폰
                    </label>
                </td>
                <th>발급방식</th>
                <td>
                    <label class="radio-inline">
                        <input type="radio" name="couponSaveType" value="" <?= $checked['couponSaveType']['']; ?>/>전체
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="couponSaveType" value="down" <?= $checked['couponSaveType']['down']; ?>/>회원다운로드
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="couponSaveType" value="auto" <?= $checked['couponSaveType']['auto']; ?>/>자동발급
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="couponSaveType" value="manual" <?= $checked['couponSaveType']['manual']; ?>/>수동발급
                    </label>
                </td>
            </tr>
            <tr>
                <th>사용범위</th>
                <td>
                    <label class="radio-inline">
                        <input type="radio" name="couponDeviceType" value="" <?= $checked['couponDeviceType']['']; ?>/>전체
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="couponDeviceType" value="all" <?= $checked['couponDeviceType']['all']; ?>/>PC+모바일
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="couponDeviceType" value="pc" <?= $checked['couponDeviceType']['pc']; ?>/>PC
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="couponDeviceType" value="mobile" <?= $checked['couponDeviceType']['mobile']; ?>/>모바일
                    </label>
                </td>
                <th>혜택구분</th>
                <td>
                    <label class="radio-inline">
                        <input type="radio" name="couponKindType" value="" <?= $checked['couponKindType']['']; ?>/>전체
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="couponKindType" value="sale" <?= $checked['couponKindType']['sale']; ?>/>상품할인
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="couponKindType" value="add" <?= $checked['couponKindType']['add']; ?>/>마일리지적립
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="couponKindType" value="delivery" <?= $checked['couponKindType']['delivery']; ?>/>배송비할인
                    </label>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="table-btn">
        <input type="submit" value="검색" class="btn btn-lg btn-black">
    </div>

    <div class="table-header">
        <div class="pull-left">
            검색 <strong><?= number_format($page->recode['total'], 0); ?></strong>건 /
            전체 <strong><?= number_format($page->recode['amount'], 0); ?></strong>건
        </div>
        <div class="pull-right">
            <div class="form-inline">
                <?= gd_select_box('sort', 'sort', $search['sortList'], null, $search['sort']); ?>
                <?php echo gd_select_box('pageNum', 'pageNum', gd_array_change_key_value([10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 200, 300, 500]), '개 보기', Request::get()->get('pageNum'), null); ?>
            </div>
        </div>
    </div>
</form>

<form id="frmCouponList" action="../promotion/coupon_ps.php" method="post">
    <input type="hidden" name="mode" value="deleteCouponOfflineList"/>
    <table class="table table-rows">
        <thead>
        <tr>
            <th><input type="checkbox" class="js-checkall" data-target-name="chkCoupon[]"/></th>
            <th>번호</th>
            <th>쿠폰명</th>
            <th>등록일</th>
            <th>등록자</th>
            <th>사용기간</th>
            <th>쿠폰유형</th>
            <th>사용범위</th>
            <th>혜택구분</th>
            <th>인증번호<br/>등록/관리</th>
            <th>발급수</th>
            <th>발급상태</th>
            <th>발급내역관리</th>
            <th>수정</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (empty($data) === false && is_array($data)) {
            foreach ($data as $key => $val) {
                $couponStartStopAction = '';
                $couponSaveAction = '';
                $couponAuthCode = '';
                if ($val['couponSaveType'] == 'manual') {
                    $couponSaveAction = '<button type="button" class="save-coupon-type btn btn-sm btn-gray" data-type="y" data-no="' . $val['couponNo'] . '">발급</button>';
                } else {
                    if ($val['couponType'] == 'y') {
                        $couponStartStopAction = '<button type="button" class="change-coupon-type btn btn-sm btn-gray" data-type="n" data-no="' . $val['couponNo'] . '">발급중지</button>';
                    } else {
                        $couponStartStopAction = '<button type="button" class="change-coupon-type btn btn-sm btn-gray" data-type="y" data-no="' . $val['couponNo'] . '">발급시작</button>';
                    }
                }
                if ($val['couponAuthType'] == 'y') {
                    $couponAuthCode = '<button type="button" class="layer-coupon-auth btn btn-sm btn-white" data-no="' . $val['couponNo'] . '">등록/관리</button>';
                } else {
                    $couponAuthCode = $convertArrData[$key]['couponAuthNumber'];
                }
                ?>
                <tr class="text-center">
                    <td>
                        <input type="checkbox" name="chkCoupon[]" value="<?= $val['couponNo'] ?>" <?= $countMemberCouponArrData[$val['couponNo']] > 0 ? 'disabled="disabled"' : '' ?>/>
                    </td>
                    <td><?= number_format($page->idx--); ?></td>
                    <td><?= $val['couponNm'] ?></td>
                    <td><?= gd_date_format('Y-m-d', $val['regDt']) ?></td>
                    <td><?= $val['couponInsertAdminId'].$val['deleteText'] ?></td>
                    <td><?= $convertArrData[$key]['useEndDate'] ?></td>
                    <td><?= $convertArrData[$key]['couponUseType'] ?></td>
                    <td><?= $convertArrData[$key]['couponDeviceType'] ?></td>
                    <td><?= $convertArrData[$key]['couponKindType'] ?>(<?= $convertArrData[$key]['couponBenefit']; ?>)</td>
                    <td><?= $couponAuthCode?></td>
                    <td><?= $countMemberCouponArrData[$val['couponNo']] ?></td>
                    <td>
                        <?= $convertArrData[$key]['couponType'] ?>
                        <?= $couponStartStopAction ?>
                    </td>
                    <td>
                        <a href="../promotion/coupon_offline_manage.php?couponNo=<?= $val['couponNo'] ?>&ypage=<?= $page->page['now'] ?>" class="btn btn-sm btn-white">관리</a>
                    </td>
                    <td>
                        <a href="coupon_offline_regist.php?couponNo=<?= $val['couponNo'] ?>&ypage=<?= $page->page['now'] ?>" class="btn btn-sm btn-white">수정</a>
                    </td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="12" class="no-data">
                    검색된 쿠폰이 없습니다.
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>

    <div class="table-action">
        <div class="pull-left">
            <button type="button" class="btn btn-white js-delete-coupon">선택 삭제</button>
        </div>
    </div>
</form>

<div class="center"><?= $page->getPage(); ?></div>
<div id="excelResult" style="visibility: hidden;"></div>
<script type="text/javascript">
    <!--
    $(document).ready(function () {
        $('#frmSearchCoupon').validate({
            submitHandler: function (form) {
                form.submit();
            }
//            ,
//            rules: {
//                'keyword': 'required'
//            },
//            messages: {
//                'keyword': {
//                    required: "검색어를 입력하세요.",
//                }
//            }
        });

        $('#frmCouponList').validate({
            submitHandler: function (form) {
                form.target = 'ifrmProcess';
                form.submit();
            },
            rules: {
                'chkCoupon[]': 'required'
            },
            messages: {
                'chkCoupon[]': {
                    required: "하나 이상 체크하세요.",
                }
            }
        });

        $('.change-coupon-type').click(function (e) {
            changeCouponType($(this).attr('data-no'), $(this).attr('data-type'));
        });
        $('.layer-coupon-auth').click(function (e) {
            layerCouponAuth($(this).attr('data-no'));
        });
        $('.save-coupon-type').click(function (e) {
            saveCoupon($(this).attr('data-no'));
        });
        $('.js-delete-coupon').click(function (e) {
            $('#frmCouponList').submit();
        });
        $('#pageNum').change(function (e) {
            $('#frmSearchCoupon').submit();
        });
    });
    function changeCouponType(couponNo, couponType) {
        $.ajax({
            method: "POST",
            cache: false,
            url: "../promotion/coupon_ps.php",
            data: "mode=modifyCouponOfflineList&couponNo=" + couponNo + "&couponType=" + couponType,
            dataType: 'json'
        }).success(function (data) {
            BootstrapDialog.alert({
                type: BootstrapDialog.TYPE_INFO,
                title: '안내',
                message: data['msg'],
                closable: false,
                callback: function (result) {
                    if (result) {
                        location.reload();
                    }
                }
            });
        }).error(function (e) {
            alert(e.responseText);
        });
    }
    function saveCoupon(couponNo) {
        location.href = '../promotion/coupon_save.php?couponNo=' + couponNo;
    }
    //-->
</script>
