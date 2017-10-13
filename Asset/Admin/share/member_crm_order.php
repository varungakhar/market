<?php
/**
 * This is commercial software, only users who have purchased a valid license
 * and accept to the terms of the License Agreement can install and use this
 * program.
 *
 * Do not edit or add to this file if you wish to upgrade Godomall5 to newer
 * versions in the future.
 *
 * @copyright ⓒ 2016, NHN godo: Corp.
 * @link http://www.godo.co.kr
 */
?>
<form id="frmSearchOrder" method="get">
    <input type="hidden" name="memNo" value="<?= $memberData['memNo']; ?>"/>
    <input type="hidden" name="navTabs" value="order"/>

    <div class="table-title">주문리스트</div>
    <table class="table table-cols">
        <colgroup>
            <col class="width-sm">
            <col>
            <col class="width-sm">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th>검색어</th>
            <td colspan="3">
                <div class="form-inline">
                    <?= gd_select_box('key', 'key', $search['combineSearch'], null, $search['key'], null, null, 'form-control input-sm'); ?>
                    <input type="text" name="keyword" value="<?= $search['keyword']; ?>" class="form-control"/>
                </div>
            </td>
        </tr>
        <tr>
            <th>기간검색</th>
            <td colspan="3">
                <div class="form-inline">
                    <?= gd_select_box('treatDateFl', 'treatDateFl', $search['combineTreatDate'], null, $search['treatDateFl'], null, null, 'form-control input-sm'); ?>
                    <div class="input-group js-datepicker">
                        <input type="text" name="treatDate[]" value="<?= $search['treatDate'][0]; ?>" class="form-control width-xs">
                                    <span class="input-group-addon">
                                        <span class="btn-icon-calendar">
                                        </span>
                                    </span>
                    </div>
                    ~
                    <div class="input-group js-datepicker">
                        <input type="text" name="treatDate[]" value="<?= $search['treatDate'][1]; ?>" class="form-control width-xs">
                                    <span class="input-group-addon">
                                        <span class="btn-icon-calendar">
                                        </span>
                                    </span>
                    </div>

                    <div class="btn-group js-dateperiod" data-toggle="buttons" data-target-name="treatDate[]">
                        <label class="btn btn-white btn-sm hand"><input type="radio" name="periodFl" value="0">오늘
                        </label>
                        <label class="btn btn-white btn-sm hand"><input type="radio" name="periodFl" value="7">7일
                        </label>
                        <label class="btn btn-white btn-sm hand"><input type="radio" name="periodFl" value="15">15일
                        </label>
                        <label class="btn btn-white btn-sm hand"><input type="radio" name="periodFl" value="30">1개월
                        </label>
                        <label class="btn btn-white btn-sm hand"><input type="radio" name="periodFl" value="90">3개월
                        </label>
                        <label class="btn btn-white btn-sm hand"><input type="radio" name="periodFl" value="365">1년
                        </label>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="table-btn">
        <input type="submit" value="검색" class="btn btn-lg btn-black">
    </div>

    <div class="table-header">
        <div class="pull-left">
            검색 <strong class="text-danger"><?= number_format(gd_isset($page->recode['total'], 0)); ?></strong>개 /
            전체 <strong class="text-danger"><?= number_format(gd_isset($page->recode['amount'], 0)); ?></strong>개
        </div>
        <div class="pull-right">
            <div class="form-inline">
                <?= gd_select_box('sort', 'sort', $search['sortList'], null, $search['sort']); ?>
                <?= gd_select_box('pageNum', 'pageNum', gd_array_change_key_value([10,20,30,40,50,60,70,80,90,100,200,300,500,]), '개 보기', $page->page['list']); ?>
            </div>
        </div>
    </div>
</form>

<table class="table table-rows">
    <thead>
    <tr>
        <th class="width3p">번호</th>
        <th class="width5p">상점 구분</th>
        <th class="width5p">주문일시</th>
        <th class="width10p">주문번호</th>
        <th>주문상품</th>
        <th class="width3p">수량</th>
        <th class="width7p">총 결제금액</th>
        <th class="width7p">처리상태</th>
        <th class="width10p">배송번호</th>
        <?php if (in_array(substr($currentStatusCode, 0, 1), ['g','d','s'])) { ?>
            <th class="width10p">송장번호</th>
        <?php } ?>
        <th class="width10p">공급사</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if (empty($data) === false && is_array($data)) {
        $sortNo = 1; // 번호 설정
        $totalCnt = 0; // 주문서 수량 설정
        $totalGoods = 0; // 주문서 수량 설정
        $totalPrice = 0; // 주문 총 금액 설정
        foreach ($data as $orderNo => $orderData) {
            $rowCnt = $orderData['cnt']['goods']['all']; // 한 주문당 상품주문 수량
            $rowChk = 0; // 한 주문당 첫번째 주문 체크용
            $rowAddChk = 0; //
            $totalCnt++; // 주문서 수량
            foreach ($orderData['goods'] as $sKey => $sVal) {
                $rowScm = 0;
                foreach ($sVal as $dKey => $dVal) {
                    $rowDelivery = 0;
                    foreach ($dVal as $key => $val) {
                        $goodsPrice = $val['goodsCnt'] * ($val['goodsPrice'] + $val['optionPrice'] + $val['optionTextPrice']); // 상품 주문 금액
                        $settlePrice = ($val['goodsCnt'] * ($val['goodsPrice'] + $val['optionPrice'] + $val['optionTextPrice'])) + $val['addGoodsPrice'] - $val['goodsDcPrice'] - $val['totalMemberDcPrice'] - $val['totalMemberOverlapDcPrice'] - $val['totalCouponGoodsDcPrice'] - $val['divisionCouponOrderDcPrice'];

                        $totalGoods++; // 상품 수량
                        if ($key === 0) {
                            $totalPrice = $totalPrice + $val['settlePrice']; // 주문 총 금액(누적)
                        }
                        if (in_array($val['statusMode'], $statusListCombine)) {
                            $checkBoxCd = $orderNo;
                        } else {
                            $checkBoxCd = $orderNo . INT_DIVISION . $val['sno'];
                        }

                        // 주문일괄처리 제외대상 비활성화
                        if ($isUserHandle) {
                            $checkDisabled = ($isUserHandle && $val['userHandleFl'] != 'r' ? 'disabled="disabled"' : '');
                        } else {
                            if (in_array($currentStatusCode, ['b', 'e', 'r'])) {
                                // 교환/반품/환불완료일 경우 체크 불가
                                $checkDisabled = (in_array($val['orderStatus'], ['b4', 'e5', 'r3']) === false ? '' : 'disabled="disabled"');
                            } else {
                                $checkDisabled = (!gd_isset($statusExcludeCd, []) || in_array($val['statusMode'], $statusExcludeCd) === false ? '' : 'disabled="disabled"');
                            }
                        }

                        // 테스트용으로 만듬 삭제 할 것
                        $checkDisabled = false;

                        // rowspan 처리
                        $orderGoodsRowSpan = $rowChk === 0 && $rowCnt > 1 ? 'rowspan="' . $rowCnt . '"' : '';
                        $orderAddGoodsRowSpan = $val['addGoodsCnt'] > 0 ? 'rowspan="' . ($val['addGoodsCnt'] + 1) . '"' : '';
                        $orderScmRowSpan = ' rowspan="' . ($orderData['cnt']['scm'][$sKey]) . '"';
                        $orderDeliveryRowSpan = ' rowspan="' . ($orderData['cnt']['delivery'][$dKey]) . '"';
                        ?>
                        <tr class="text-center">
                            <td <?=$orderAddGoodsRowSpan?> class="font-num">
                                <small><?= $page->idx--; ?></small>
                            </td>
                            <?php if ($rowChk === 0) { ?>
                                <td <?= $orderGoodsRowSpan; ?> class="font-kor"><?=$val['mallName']?></td>
                                <td <?= $orderGoodsRowSpan; ?> class="font-date"><?= str_replace(' ', '<br>', gd_date_format('Y-m-d H:i', $val['regDt'])); ?></td>
                            <?php } ?>
                            <?php if ($rowChk === 0) { ?>
                                <td <?= $orderGoodsRowSpan; ?>>
                                    <?php if ($val['firstSaleFl'] == 'y') { ?>
                                        <img src="<?=PATH_ADMIN_GD_SHARE?>img/order/icon_firstsale.png" alt="첫주문" />
                                    <?php } ?>
                                    <a href="#" title="주문번호" onclick="order_view_popup('<?= $orderNo; ?>');"><?= $orderNo; ?></a>
                                    <?php if ($val['orderChannelFl'] == 'naverpay') { ?>
                                        <p>
                                            <a href="./order_view.php?orderNo=<?= $orderNo; ?>" target="_blank" title="주문번호" class="font-num<?=$isUserHandle ? ' js-link-order' : ''?>" data-order-no="<?=$orderNo?>" data-is-provider="<?= $isProvider ? 'true' : 'false' ?>"><img
                                                    src="<?= UserFilePath::adminSkin('gd_share', 'img', 'channel_icon', 'naverpay.gif')->www() ?>"/> <?= $val['apiOrderNo']; ?></a>
                                        </p>
                                    <?php } else if($val['orderChannelFl'] == 'payco') { ?>
                                        <img src="<?= UserFilePath::adminSkin('gd_share', 'img', 'channel_icon', 'payco.gif')->www() ?>"/>
                                    <?php } ?>
                                </td>
                            <?php } ?>
                            <td class="text-left">
                                <a href="javascript:void();" class="one-line" title="주문 상품명" onclick="goods_register_popup('<?= $val['goodsNo']; ?>');"><strong><?= gd_html_cut($val['goodsNm'], 46, '..'); ?></strong></a>
                                <?php
                                // 옵션 처리
                                if (empty($val['optionInfo']) === false) {
                                    echo '<div class="option_info" title="상품 옵션">';
                                    foreach ($val['optionInfo'] as $option) {
                                        echo $option[0] . ':', $option[1] . ', ';
                                    }
                                    echo '</div>' . chr(10);

                                }

                                // 텍스트 옵션 처리
                                if (empty($val['optionTextInfo']) === false) {
                                    echo '<div class="option_info" title="텍스트 옵션">';
                                    foreach ($val['optionTextInfo'] as $option) {
                                        echo $option[0] . ':', $option[1] . ', ';
                                    }
                                    echo '</div>' . chr(10);
                                }
                                ?>
                            </td>
                            <td class="goods_cnt"><strong><?= number_format($val['goodsCnt']); ?></strong></td>
                            <?php if ($rowChk === 0 && !$isUserHandle) { ?>
                                <td <?= $orderGoodsRowSpan; ?>><?= gd_currency_symbol() ?><?= gd_money_format($val['totalSettlePrice']); ?></span><?= gd_currency_string() ?></td>
                            <?php } ?>
                            <?php if (in_array($currentStatusCode, $statusListCombine)) { ?>
                                <?php if ($rowChk === 0) { ?>
                                    <td <?= $orderGoodsRowSpan; ?>>
                                        <div title="주문 상품별 주문 상태"><?= $val['orderStatusStr']; ?></div>
                                        <?php if ($val['statusMode'] == 'o') { ?>
                                            <div>
                                                <input type="button" onclick="status_process_payment('<?= $orderNo; ?>');" value="입금확인" class="btn btn-sm btn-black"/>
                                            </div>
                                        <?php } ?>
                                    </td>
                                <?php } ?>
                            <?php } else { ?>
                                <td <?=$orderAddGoodsRowSpan?>>
                                    <?php if ($currentStatusCode == 'r') { ?>
                                        <div class="text-muted" title="이전 상품별 주문 상태"><?= $val['beforeStatusStr']; ?> &gt;</div>
                                    <?php } ?>
                                    <div title="주문 상품별 주문 상태"><?= $val['orderStatusStr']; ?></div>
                                    <?php if (empty($val['invoiceCompanySno']) === false && empty($val['invoiceNo']) === false) { ?>
                                        <div>
                                            <input type="button" onclick="delivery_trace('<?= $val['invoiceCompanySno']; ?>', '<?= $val['invoiceNo']; ?>');" value="배송추적" class="btn btn-sm btn-black">
                                        </div>
                                    <?php } ?>
                                </td>
                            <?php } ?>
                            <?php if ($rowDelivery === 0) { ?>
                                <td <?= $orderDeliveryRowSpan; ?> class="border-left font-num"><?= $val['deliverySno'] ?></td>
                            <?php } ?>
                            <?php if (in_array(substr($currentStatusCode, 0, 1), ['g','d','s'])) { ?>
                                <td <?= $orderAddGoodsRowSpan; ?>>
                                    <?php if ($currentStatusCode == 'g') { ?>
                                        <?= gd_select_box(null, 'invoiceCompanySno[' . $val['statusMode'] . '][' . $val['sno'] . ']', $deliveryCom, null, $val['invoiceCompanySno'], null); ?>
                                        <input type="text" name="invoiceNo[<?= $val['statusMode'] ?>][<?= $val['sno'] ?>]" value="<?= $val['invoiceNo']; ?>" class="form-control input-sm mgt5"/>
                                    <?php } else { ?>
                                        <?php if (empty($val['invoiceCompanySno']) === false && empty($val['invoiceNo']) === false) { ?>
                                            <small><?= $val['invoiceCompanyNm']; ?> / <?= $val['invoiceNo']; ?></small>
                                            <div class="delivery-trace">
                                                <input type="button" onclick="delivery_trace('<?= $val['invoiceCompanySno']; ?>', '<?= $val['invoiceNo']; ?>');" value="배송추적" class="btn btn-sm btn-black">
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </td>
                            <?php } ?>
                            <?php if ($rowScm === 0) { ?>
                                <td <?= $orderScmRowSpan; ?>><?= $val['companyNm'] ?></td>
                            <?php } ?>
                        </tr>
                        <?php
                        if ($val['addGoodsCnt'] > 0) {
                            foreach ($val['addGoods'] as $aVal) {
                                ?>
                                <tr class="text-center add-goods">
                                    <td class="text-left">
                                        <span class="label label-default" title="<?= $aVal['sno'] ?>">추가</span>
                                        <div class="goods_name one-line hand" title="추가 상품명" onclick="addgoods_register_popup('<?= $aVal['addGoodsNo']; ?>');"><?= gd_html_cut($aVal['goodsNm'], 46, '..'); ?>
                                            <small>(<?= gd_html_cut($aVal['optionNm'], 46, '..'); ?>)</small>
                                        </div>
                                    </td>
                                    <td class="goods_cnt"><?= number_format($aVal['goodsCnt']); ?></td>
<!--                                    <td>--><?//= gd_currency_display($aVal['goodsPrice'] * $aVal['goodsCnt']); ?><!--</td>-->
                                </tr>
                                <?php
                                $rowChk++;
                            }
                        } else {
                            $rowChk++;
                        }
                        $rowScm++;
                        $rowDelivery++;
                    }
                }
            }
        }
    } else {
        ?>
        <tr>
            <td colspan="20" class="no-data">
                검색된 주문이 없습니다.
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<div class="text-center"><?= $page->getPage(); ?></div>

<script type="text/javascript" src="<?=PATH_ADMIN_GD_SHARE?>script/orderList.js"></script>
<script type="text/javascript">
    <!--

    //-->
</script>
