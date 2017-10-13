<?php
/**
 * 공통 상품주문번호별 간편 리스트 레이아웃
 * 결제완료|상품준비중|배송중|배송완료|구매확정 리스트에서 사용
 *
 * !주의! CRM 주문내역, 주문상세, 클레임접수 리스트, 환불상세 모두 동시에 수정되어야 하며, layout_order_goods.php 반드시 확인이 필요하다.
 *
 * @author Jong-tae Ahn <qnibus@godo.co.kr>
 */
use Component\Naver\NaverPay;

?>

<div class="table-responsive">
    <table class="table table-rows order-list">
        <thead>
        <tr>
            <th class="width3p">
                <input type="checkbox" value="y" class="js-checkall" data-target-name="statusCheck"/>
            </th>
            <th class="width3p">번호</th>
            <th class="width5p">상점 구분</th>
            <th class="width5p">주문일시</th>
            <?php if ($isUserHandle) { ?>
                <th class="width5p">접수일시</th>
                <th class="width7p">사유</th>
            <?php } else { ?>
                <?php if ($currentStatusCode === 'o') { ?>
                    <th class="width5p">경과일자</th>
                <?php } elseif ($currentStatusCode === 'p') { ?>
                    <th class="width5p">입금일시</th>
                <?php } ?>
            <?php } ?>
            <th class="width10p">주문번호</th>
            <th class="width7p">주문자</th>
            <th colspan="2">주문상품</th>
            <th class="width3p">수량</th>
            <th class="width7p">금액</th>
            <?php if (!$isProvider) { ?>
                <?php if (!$isUserHandle) { ?>
                    <th class="width7p">총 결제금액</th>
                <?php } ?>
                <th class="width5p">결제방법</th>
                <th class="width5p">수령자</th>
            <?php } ?>
            <?php if ($currentStatusCode === '') { ?>
            <th class="width7p">처리상태</th>
            <?php } ?>
            <?php if (in_array(substr($currentStatusCode, 0, 1), ['g','d','s'])) { ?>
                <th class="width10p">송장번호</th>
            <?php } ?>
            <?php if (!$isProvider) { ?>
                <th class="width10p">공급사</th>
                <?php if ($currentStatusCode == 'o') { ?>
                    <th class="width5p">입금자</th>
                    <th class="width10p">입금계좌</th>
                <?php } else { ?>
                    <?php if ($currentStatusCode == 'c') { ?>
                        <th class="width5p">취소일자</th>
                        <th class="width3p">취소수량</th>
                    <?php } elseif ($currentStatusCode == 'e') { ?>
                        <th class="width5p">접수일시</th>
                        <th class="width3p">교환수량</th>
                        <th class="width10p">사유</th>
                    <?php } elseif ($currentStatusCode == 'b') { ?>
                        <th class="width5p">접수일시</th>
                        <th class="width3p">반품수량</th>
                        <th class="width10p">사유</th>
                    <?php } elseif ($currentStatusCode == 'r') { ?>
                        <th class="width5p">접수일시</th>
                        <th class="width3p">환불수량</th>
                        <th class="width10p">사유</th>
                    <?php } ?>
                <?php } ?>
            <?php } else { ?>
                <?php if (!in_array($currentStatusCode, ['c','e','b','r'])) { ?>
                    <th class="width3p">수령자</th>
                <?php } ?>
                <th class="width7p">배송메시지</th>
            <?php } ?>
            <?php if ($currentStatusCode == 'r') { ?>
                <th class="width5p">환불수단</th>
                <?php if (!$isProvider) { ?>
                    <th class="width10p">환불처리</th>
                <?php } ?>
            <?php } ?>
            <?php if ($isUserHandle) { ?>
                <?php if ($currentUserHandleMode == 'e') { ?>
                    <th class="width3p">교환수량</th>
                <?php } elseif ($currentUserHandleMode == 'b') { ?>
                    <th class="width3p">반품수량</th>
                <?php } elseif ($currentUserHandleMode == 'r') { ?>
                    <th class="width3p">환불수량</th>
                <?php } ?>
                <th class="width10p">메모</th>
            <?php } ?>
            <?php if (!$isProvider) { ?>
            <th class="width5p">관리자메모</th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php
        $naverPay = new NaverPay();
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
                            if ($val['orderChannelFl'] == 'naverpay') {
                                $checkoutData = json_decode($val['checkoutData'], true);
                                if ($naverPay->getStatusText($checkoutData)) {
                                    $naverImg = sprintf("<img src='%s' > ", \UserFilePath::adminSkin('gd_share', 'img', 'channel_icon', 'naverpay.gif')->www());
                                    $val['orderStatusStr'] .= '<br>(' . $naverImg . $naverPay->getStatusText($checkoutData) . ')';
                                }
                            }
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
                                $checkDisabled = '';
                            }

                            //배송업체가 설정되어 있지 않을시 기본 배송업체 select
                            $selectInvoiceCompanySno = $val['invoiceCompanySno'];
                            if((int)$selectInvoiceCompanySno < 1){
                                $selectInvoiceCompanySno = $deliverySno;
                            }

                            // rowspan 처리
                            $orderGoodsRowSpan = $rowChk === 0 && $rowCnt > 1 ? 'rowspan="' . $rowCnt . '"' : '';
                            $orderAddGoodsRowSpan = $val['addGoodsCnt'] > 0 ? 'rowspan="' . ($val['addGoodsCnt'] + 1) . '"' : '';
                            $orderScmRowSpan = ' rowspan="' . ($orderData['cnt']['scm'][$sKey]) . '"';
                            $orderDeliveryRowSpan = ' rowspan="' . ($orderData['cnt']['delivery'][$dKey]) . '"';
                            ?>
                            <tr class="text-center" data-mall-sno="<?=$val['mallSno']?>">
                                <?php if (in_array($currentStatusCode, $statusListCombine)) { ?>
                                    <?php if ($rowChk === 0) { ?>
                                        <td <?= $orderGoodsRowSpan; ?>>
                                            <input type="checkbox" name="statusCheck[<?= $val['statusMode'] ?>][]" <?= $checkDisabled ?> value="<?= $checkBoxCd; ?>"/>
                                            <input type="hidden" name="orderStatus[<?= $val['statusMode'] ?>][]" value="<?= $val['orderStatus']; ?>"/>
                                            <input type="hidden" name="escrowCheck[<?= $val['statusMode'] ?>][]" <?= $checkDisabled ?> value="<?= $val['escrowFl'] . $val['escrowDeliveryFl']; ?>"/>
                                            <?php if (in_array($currentStatusCode, ['r', 'e', 'b'])) { ?>
                                                <input type="hidden" name="handleSno[<?= $val['statusMode'] ?>][]" value="<?= $val['handleSno']; ?>"/>
                                                <input type="hidden" name="beforeStatus[<?= $val['statusMode'] ?>][]" value="<?= $val['beforeStatus']; ?>"/>
                                            <?php } ?>
                                        </td>
                                    <?php } ?>
                                <?php } else { ?>
                                    <td <?= $orderAddGoodsRowSpan ?>>
                                        <input type="checkbox" name="statusCheck[<?= $val['statusMode'] ?>][]" <?= $checkDisabled ?> value="<?= $checkBoxCd; ?>"/>
                                        <input type="hidden" name="orderStatus[<?= $val['statusMode'] ?>][]" value="<?= $val['orderStatus']; ?>"/>
                                        <input type="hidden" name="escrowCheck[<?= $val['statusMode'] ?>][]" <?= $checkDisabled ?> value="<?= $val['escrowFl'] . $val['escrowDeliveryFl']; ?>"/>
                                        <?php if (in_array($currentStatusCode, ['r', 'e', 'b'])) { ?>
                                            <input type="hidden" name="handleSno[<?= $val['statusMode'] ?>][]" value="<?= $val['handleSno']; ?>"/>
                                            <input type="hidden" name="beforeStatus[<?= $val['statusMode'] ?>][]" value="<?= $val['beforeStatus']; ?>"/>
                                        <?php } ?>
                                    </td>
                                <?php } ?>
                                <td <?= $orderAddGoodsRowSpan ?> class="font-num">
                                    <small><?= $page->idx--; ?></small>
                                </td>
                                <?php if ($rowChk === 0) { ?>
                                    <td <?= $orderGoodsRowSpan; ?> class="font-kor"><span class="flag flag-16 flag-<?=$val['domainFl']?>"></span><?=$val['mallName']?></td>
                                    <td <?= $orderGoodsRowSpan; ?> class="font-date nowrap"><?= str_replace(' ', '<br>', gd_date_format('Y-m-d H:i', $val['regDt'])); ?></td>
                                <?php } ?>
                                <?php if ($isUserHandle) { ?>
                                    <td <?= $orderAddGoodsRowSpan ?> class="font-date nowrap"><?= str_replace(' ', '<br>', gd_date_format('Y-m-d H:i', $val['userHandleRegDt'])); ?></td>
                                    <td <?= $orderAddGoodsRowSpan ?>><?= $val['userHandleReason']; ?></td>
                                <?php } else { ?>
                                    <?php if ($rowChk === 0) { ?>
                                        <?php if ($currentStatusCode === 'o') { ?>
                                            <td <?= $orderGoodsRowSpan ?> class="font-date nowrap"><?=gd_interval_day($val['regDt'], date('Y-m-d H:i:s'));?>일</td>
                                        <?php } elseif ($currentStatusCode === 'p') { ?>
                                            <td <?= $orderGoodsRowSpan ?> class="font-date nowrap"><?= str_replace(' ', '<br>', gd_date_format('Y-m-d H:i', $val['paymentDt'])); ?></td>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                                <?php if ($rowChk === 0) { ?>
                                    <td <?= $orderGoodsRowSpan; ?> class="order-no">
                                        <?php if ($val['firstSaleFl'] == 'y') { ?>
                                            <p class="mgb0"><img src="<?=PATH_ADMIN_GD_SHARE?>img/order/icon_firstsale.png" alt="첫주문" /></p>
                                        <?php } ?>
                                        <a href="./order_view.php?orderNo=<?= $orderNo; ?>" target="_blank" title="주문번호" class="font-num<?=$isUserHandle ? ' js-link-order' : ''?>" data-order-no="<?=$orderNo?>" data-is-provider="<?= $isProvider ? 'true' : 'false' ?>"><?= $orderNo; ?></a>
                                        <?php if ($val['orderChannelFl'] == 'naverpay') { ?>
                                            <p>
                                                <a href="./order_view.php?orderNo=<?= $orderNo; ?>" target="_blank" title="주문번호" class="font-num<?=$isUserHandle ? ' js-link-order' : ''?>" data-order-no="<?=$orderNo?>" data-is-provider="<?= $isProvider ? 'true' : 'false' ?>"><img
                                                        src="<?= UserFilePath::adminSkin('gd_share', 'img', 'channel_icon', 'naverpay.gif')->www() ?>"/> <?= $val['apiOrderNo']; ?></a>
                                            </p>
                                        <?php } else if($val['orderChannelFl'] == 'payco') { ?>
                                            <img src="<?= UserFilePath::adminSkin('gd_share', 'img', 'channel_icon', 'payco.gif')->www() ?>"/>
                                        <?php } ?>
                                    </td>
                                    <td <?= $orderGoodsRowSpan; ?> class="js-member-info" data-member-no="<?= $val['memNo'] ?>" data-member-name="<?= $val['orderName'] ?>"
                                                                   data-cell-phone="<?= $val['smsCellPhone'] ?>">
                                        <?= $val['orderName'] ?>
                                        <p class="mgb0">
                                            <?php if (!$val['memNo']) { ?>
                                                <?php if (!$val['memNoCheck']) { ?>
                                                    <span class="font-kor">(비회원)</span>
                                                <?php } else { ?>
                                                    <span class="font-kor">(탈퇴회원)</span>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <?php if (!$isProvider) { ?>
                                                    <button type="button" class="btn btn-link font-eng js-layer-crm" data-member-no="<?= $val['memNo'] ?>">(<?= $val['memId'] ?>/<?=$val['groupNm']?>)
                                                <?php } else { ?>
                                                    (<?= $val['memId'] ?>/<?=$val['groupNm']?>)
                                                <?php } ?>
                                                </button>
                                            <?php } ?>
                                        </p>
                                    </td>
                                <?php } ?>
                                <td class="text-left border-right-none" style="width: 40px;">
                                    <?php if ($val['goodsType'] === 'addGoods') { ?>
                                        <?= gd_html_add_goods_image($val['goodsNo'], $val['addImageName'], $val['addImagePath'], $val['addImageStorage'], 30, $val['goodsNm'], '_blank'); ?>
                                    <?php } else { ?>
                                        <?= gd_html_goods_image($val['goodsNo'], $val['imageName'], $val['imagePath'], $val['imageStorage'], 30, $val['goodsNm'], '_blank'); ?>
                                    <?php } ?>
                                </td>
                                <td class="text-left">
                                    <?php if ($val['goodsType'] === 'addGoods') { ?>
                                        <span class="label label-default" title="<?= $val['sno'] ?>">추가</span>
                                        <a href="javascript:void();" class="one-line bold mgb5" title="추가상품명"
                                           onclick="addgoods_register_popup('<?= $val['goodsNo']; ?>', <?= $isProvider ? 'true' : 'false' ?>);"><?= gd_html_cut($val['goodsNm'], 46, '..'); ?></a>
                                    <?php } else { ?>
                                        <a href="javascript:void();" class="one-line bold mgb5" title="상품명"
                                           onclick="goods_register_popup('<?= $val['goodsNo']; ?>', <?= $isProvider ? 'true' : 'false' ?>);"><?= gd_html_cut($val['goodsNm'], 46, '..'); ?></a>
                                    <?php } ?>
                                    <?php
                                    // 옵션 처리
                                    if (empty($val['optionInfo']) === false) {
                                        echo '<div class="option_info" title="상품 옵션">';
                                        foreach ($val['optionInfo'] as $option) {
                                            $tmpOption[] = $option[0] . ':' . $option[1];
                                        }
                                        echo implode(', ', $tmpOption);
                                        echo '</div>';
                                        unset($tmpOption);
                                    }

                                    // 텍스트 옵션 처리
                                    if (empty($val['optionTextInfo']) === false) {
                                        echo '<div class="option_info" title="텍스트 옵션">';
                                        foreach ($val['optionTextInfo'] as $option) {
                                            $tmpOption[] = $option[0] . ':' . $option[1];
                                        }
                                        echo implode(', ', $tmpOption);
                                        echo '</div>';
                                        unset($tmpOption);}
                                    ?>
                                </td>
                                <td class="goods_cnt"><strong><?= number_format($val['goodsCnt']); ?></strong></td>
                                <td><?= gd_currency_display($goodsPrice); ?></td>
                                <?php if (!$isProvider) { ?>
                                    <?php if ($rowChk === 0 && !$isUserHandle) { ?>
                                        <td <?= $orderGoodsRowSpan; ?>><?= gd_currency_symbol() ?><?= gd_money_format($val['totalSettlePrice']); ?></span><?= gd_currency_string() ?></td>
                                    <?php } ?>
                                    <?php if ($rowChk === 0) { ?>
                                        <td <?= $orderGoodsRowSpan; ?>>
                                            <?php if (is_file(UserFilePath::adminSkin('gd_share', 'img', 'settlekind_icon', 'icon_settlekind_' . $val['settleKind'] . '.gif'))) { ?>
                                                <?= gd_html_image(UserFilePath::adminSkin('gd_share', 'img', 'settlekind_icon', 'icon_settlekind_' . $val['settleKind'] . '.gif')->www(), $val['settleKindStr']); ?>
                                            <?php } ?>
                                            <?php if ($val['divisionUseDeposit'] > 0) { ?>
                                                <?= gd_html_image(UserFilePath::adminSkin('gd_share', 'img', 'settlekind_icon', 'icon_settlekind_gd.gif')->www(), $val['settleKindStr']); ?>
                                            <?php } ?>
                                            <?php if ($val['divisionUseMileage'] > 0) { ?>
                                                <?= gd_html_image(UserFilePath::adminSkin('gd_share', 'img', 'settlekind_icon', 'icon_settlekind_gm.gif')->www(), $val['settleKindStr']); ?>
                                            <?php } ?>
                                            <?php if ($val['receiptFl'] != 'n') {
                                                //echo gd_html_image(PATH_ADMIN_GD_SHARE . 'image/ico_receipt_' . $val['receiptFl'] . '.gif', null);
                                            } ?>
                                        </td>
                                    <?php } ?>
                                    <td><?= $val['receiverName'] ?></td>
                                <?php } ?>
                                <?php if ($currentStatusCode === '') { ?>
                                    <?php if (in_array($currentStatusCode, $statusListCombine)) { ?>
                                        <?php if ($rowChk === 0) { ?>
                                            <td <?= $orderGoodsRowSpan; ?>>
                                                <div title="주문 상품별 주문 상태"><?= $val['orderStatusStr']; ?></div>
                                                <?php if ($val['statusMode'] == 'o') { ?>
                                                    <div class="mgt5">
                                                        <input type="button" onclick="status_process_payment('<?= $orderNo; ?>');" value="입금확인" class="btn btn-sm btn-black"/>
                                                    </div>
                                                <?php } ?>
                                            </td>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <td <?= $orderAddGoodsRowSpan ?>>
                                            <?php if ($currentStatusCode == 'r') { ?>
                                                <div class="text-muted" title="이전 상품별 주문 상태"><?= $val['beforeStatusStr']; ?> &gt;</div>
                                            <?php } ?>
                                            <div title="주문 상품별 주문 상태"><?= $val['orderStatusStr']; ?></div>
                                            <?php if (!in_array(substr($currentStatusCode, 0, 1), ['g','d','s'])) { ?>
                                                <?php if (empty($val['invoiceCompanySno']) === false && empty($val['invoiceNo']) === false) { ?>
                                                    <div class="delivery-trace">
                                                        <input type="button" onclick="delivery_trace('<?= $val['invoiceCompanySno']; ?>', '<?= $val['invoiceNo']; ?>');" value="배송추적" class="btn btn-sm btn-black">
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>
                                        </td>
                                    <?php } ?>
                                <?php } ?>
                                <?php if (in_array(substr($currentStatusCode, 0, 1), ['g','d','s'])) { ?>
                                    <td <?= $orderAddGoodsRowSpan; ?>>
                                    <?php if ($currentStatusCode == 'g') { ?>
                                        <?= gd_select_box(null, 'invoiceCompanySno[' . $val['statusMode'] . '][' . $val['sno'] . ']', $deliveryCom, null, $selectInvoiceCompanySno, null); ?>
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
                                <?php if (!$isProvider) { ?>
                                    <?php if ($rowScm === 0) { ?>
                                        <td <?= $orderScmRowSpan; ?>><?= $val['companyNm'] ?></td>
                                    <?php } ?>
                                    <?php if ($rowChk === 0) { ?>
                                        <?php if ($currentStatusCode == 'o') {//입금대기에서 입금자/입금계좌가 표시되도록 처리 ?>
                                            <td <?= $orderGoodsRowSpan; ?>>
                                                <?php
                                                if ($val['statusMode'] == 'o' && $val['settleKind'] == 'gb') {        // 주문 접수의 경우 입금자명
                                                    echo '<span title="입금자명">' . $val['bankSender'] . '</span>';
                                                }
                                                ?>
                                            </td>
                                            <td <?= $orderGoodsRowSpan; ?>><?= str_replace(STR_DIVISION, ' / ', gd_isset($val['bankAccount'])); ?></td>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if ($currentStatusCode != 'o') { ?>
                                        <?php if ($currentStatusCode == 'c') { ?>
                                            <td <?= $orderAddGoodsRowSpan; ?> class="font-date nowrap"><?= str_replace(' ', '<br>', gd_date_format('Y-m-d H:i', $val['cancelDt'])); ?></td>
                                            <td <?= $orderAddGoodsRowSpan; ?>><?=$val['goodsCnt'];?></td>
                                        <?php } elseif ($currentStatusCode == 'e') { ?>
                                            <td <?= $orderAddGoodsRowSpan; ?> class="font-date nowrap"><?= str_replace(' ', '<br>', gd_date_format('Y-m-d H:i', $val['handleRegDt'])); ?></td>
                                            <td <?= $orderAddGoodsRowSpan; ?>><?=number_format($val['goodsCnt'])?></td>
                                            <td <?= $orderAddGoodsRowSpan; ?>><?=$val['handleReason']?></td>
                                        <?php } elseif ($currentStatusCode == 'b') { ?>
                                            <td <?= $orderAddGoodsRowSpan; ?> class="font-date nowrap"><?= str_replace(' ', '<br>', gd_date_format('Y-m-d H:i', $val['handleRegDt'])); ?></td>
                                            <td <?= $orderAddGoodsRowSpan; ?>><?=number_format($val['goodsCnt'])?></td>
                                            <td <?= $orderAddGoodsRowSpan; ?>><?=$val['handleReason']?></td>
                                        <?php } elseif ($currentStatusCode == 'r') { ?>
                                            <td <?= $orderAddGoodsRowSpan; ?> class="font-date nowrap"><?= str_replace(' ', '<br>', gd_date_format('Y-m-d H:i', $val['handleRegDt'])); ?></td>
                                            <td <?= $orderAddGoodsRowSpan; ?>><?=number_format($val['goodsCnt'])?></td>
                                            <td <?= $orderAddGoodsRowSpan; ?>><?=$val['handleReason']?></td>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } else { ?>
                                    <?php if ($rowChk === 0) { ?>
                                        <?php if (!in_array($currentStatusCode, ['c','e','b','r'])) { ?>
                                            <td <?= $orderGoodsRowSpan; ?>><?= $val['receiverName'] ?></td>
                                        <?php } ?>
                                        <td <?= $orderGoodsRowSpan; ?>><?= $val['orderMemo'] ?></td>
                                    <?php } ?>
                                <?php } ?>
                                <?php if ($currentStatusCode == 'r') { ?>
                                    <td <?= $orderAddGoodsRowSpan ?>><?= $val['refundMethod'] ?></td>
                                    <?php if (!$isProvider) { ?>
                                        <td <?= $orderAddGoodsRowSpan ?>>
                                            <?php if ($val['orderStatus'] == 'r1') { ?>
                                                <button type="button" class="btn btn-sm btn-gray js-order-refund"  data-order-goods-no="<?=$val['sno']?>" data-channel="<?=$val['orderChannelFl']?>" data-order-no="<?= $val['orderNo'] ?>" data-handle-sno="<?= $val['handleSno'] ?>" data-mall-sno="<?=$val['mallSno']?>">환불처리
                                                </button>
                                            <?php } else if($val['orderStatus'] == 'r2' ){
                                                echo '처리중';
                                            }
                                            elseif ($val['orderStatus'] == 'r3' ) { ?>
                                                <?php if($val['orderChannelFl'] == 'naverpay') {?>
                                                    처리완료
                                                <?php } else {?>
                                                    <button type="button" class="btn btn-sm btn-gray js-order-refund-detail" data-order-no="<?= $val['orderNo'] ?>"
                                                            data-handle-sno="<?= $val['handleSno'] ?>" data-mall-sno="<?=$val['mallSno']?>">상세내역
                                                    </button>
                                                <?php } }?>
                                        </td>
                                    <?php } ?>
                                <?php } ?>
                                <?php if ($isUserHandle) { ?>
                                    <td <?= $orderAddGoodsRowSpan; ?>><?=number_format($val['goodsCnt'])?></td>
                                    <td <?= $orderAddGoodsRowSpan ?> data-order-no="<?= $val['orderNo'] ?>" data-handle-sno="<?= $val['userHandleSno'] ?>"
                                                                     data-status-mode="<?= $currentUserHandleMode ?>">
                                        <p class="memo">
                                            <button type="button" class="btn btn-sm btn-<?= $val['userHandleDetailReason'] != 'white' ? 'gray' : 'default' ?> js-user-memo">고객
                                            </button>
                                        </p>
                                        <p class="memo">
                                            <button type="button" class="btn btn-sm btn-<?= $val['adminHandleReason'] != '' ? 'gray' : 'white' ?> js-admin-memo">운영사</button>
                                        </p>
                                    </td>
                                <?php } ?>
                                <?php if (!$isProvider) { ?>
                                    <?php if ($rowChk === 0) { ?>
                                    <td <?= $orderGoodsRowSpan; ?> class="text-center" data-order-no="<?= $val['orderNo'] ?>" data-reg-date="<?= $val['regDt'] ?>">
                                        <button type="button" class="btn btn-sm btn-<?= $val['adminMemo'] != '' ? 'gray js-html-popover' : 'white' ?> js-super-admin-memo" title="관리자메모" data-placement="left" data-content="<?=nl2br($val['adminMemo'])?>">보기</button>
                                    </td>
                                    <?php } ?>
                                <?php } ?>
                            </tr>
                            <?php
                            if ($val['addGoodsCnt'] > 0) {
                                foreach ($val['addGoods'] as $aVal) {
                                    ?>
                                    <tr class="text-center add-goods">
                                        <td class="text-left"><span class="label label-default" title="<?= $aVal['sno'] ?>">추가</span></td>
                                        <td class="text-left">
                                            <?= gd_html_add_goods_image($aVal['addGoodsNo'], $aVal['imageNm'], $aVal['imagePath'], $aVal['imageStorage'], 30, $aVal['goodsNm'], '_blank'); ?>
                                            <a href="javascript:void()" class="one-line" title="추가 상품명"
                                               onclick="addgoods_register_popup('<?= $aVal['addGoodsNo']; ?>', <?= $isProvider ? 'true' : 'false' ?>);"><?= gd_html_cut($aVal['goodsNm'], 46, '..'); ?>
                                                <small>(<?= gd_html_cut($aVal['optionNm'], 46, '..'); ?>)</small>
                                            </a>
                                        </td>
                                        <td class="goods_cnt"><?= number_format($aVal['goodsCnt']); ?></td>
                                        <td><?= gd_currency_display($aVal['goodsPrice'] * $aVal['goodsCnt']); ?></td>
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
</div>

