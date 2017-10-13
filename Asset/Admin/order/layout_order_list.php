<?php
/**
 * 공통 주문번호별 리스트 레이아웃
 * 주문통합|입금대기 리스트에서 사용
 *
 * !주의! CRM 주문내역, 주문상세, 클레임접수 리스트, 환불상세 모두 동시에 수정되어야 한다.
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
           <?php if ($currentStatusCode === 'o') { ?>
                <th class="width5p">경과일자</th>
            <?php } ?>
            <th class="width7p">주문번호</th>
            <th class="width7p">주문자</th>
            <th>주문상품</th>
            <th class="width5p">총 금액</th>
            <?php if (!$isProvider) { ?>
                <th class="width5p">총 결제금액</th>
                <th class="width3p">결제방법</th>
            <?php } ?>
            <th class="width5p">수령자</th>
            <?php if ($currentStatusCode !== 'o') { ?>
                <th class="width5p">결제상태</th>
            <?php } ?>
            <?php if ($currentStatusCode === 'o') { ?>
                <?php if (!$isProvider) { ?>
                <th class="width5p">입금자</th>
                <th class="width10p">입금계좌</th>
                <?php } ?>
            <?php } else { ?>
            <th class="width3p">미배송</th>
            <th class="width3p">배송중</th>
            <th class="width3p">배송완료</th>
            <th class="width3p">취소</th>
            <th class="width3p">교환</th>
            <th class="width3p">반품</th>
            <th class="width3p">환불</th>
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
                $totalCnt++; // 주문서 수량
                foreach ($orderData['goods'] as $sKey => $sVal) {
                    foreach ($sVal as $dKey => $dVal) {
                        foreach ($dVal as $key => $val) {
                            if ($key > 0) {
                                continue;
                            }
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
                            ?>
                            <tr class="text-center" data-mall-sno="<?=$val['mallSno']?>">
                                <?php if (in_array($currentStatusCode, $statusListCombine)) { ?>
                                    <td>
                                        <input type="checkbox" name="statusCheck[<?= $val['statusMode'] ?>][]" <?= $checkDisabled ?> value="<?= $checkBoxCd; ?>"/>
                                        <input type="hidden" name="orderStatus[<?= $val['statusMode'] ?>][]" value="<?= $val['orderStatus']; ?>"/>
                                        <input type="hidden" name="escrowCheck[<?= $val['statusMode'] ?>][]" <?= $checkDisabled ?> value="<?= $val['escrowFl'] . $val['escrowDeliveryFl']; ?>"/>
                                        <?php if (in_array($currentStatusCode, ['r', 'e', 'b'])) { ?>
                                            <input type="hidden" name="handleSno[<?= $val['statusMode'] ?>][]" value="<?= $val['handleSno']; ?>"/>
                                            <input type="hidden" name="beforeStatus[<?= $val['statusMode'] ?>][]" value="<?= $val['beforeStatus']; ?>"/>
                                        <?php } ?>
                                    </td>
                                <?php } else { ?>
                                    <td>
                                        <input type="checkbox" name="statusCheck[<?= $val['statusMode'] ?>][]" <?= $checkDisabled ?> value="<?= $checkBoxCd; ?>"/>
                                        <input type="hidden" name="orderStatus[<?= $val['statusMode'] ?>][]" value="<?= $val['orderStatus']; ?>"/>
                                        <input type="hidden" name="escrowCheck[<?= $val['statusMode'] ?>][]" <?= $checkDisabled ?> value="<?= $val['escrowFl'] . $val['escrowDeliveryFl']; ?>"/>
                                        <?php if (in_array($currentStatusCode, ['r', 'e', 'b'])) { ?>
                                            <input type="hidden" name="handleSno[<?= $val['statusMode'] ?>][]" value="<?= $val['handleSno']; ?>"/>
                                            <input type="hidden" name="beforeStatus[<?= $val['statusMode'] ?>][]" value="<?= $val['beforeStatus']; ?>"/>
                                        <?php } ?>
                                    </td>
                                <?php } ?>
                                <td class="font-num">
                                    <small><?= $page->idx--; ?></small>
                                </td>
                                <td class="font-kor">
                                    <span class="flag flag-16 flag-<?=$val['domainFl']?>"></span>
                                    <?=$val['mallName']?>
                                </td>
                                <td class="font-date nowrap"><?= str_replace(' ', '<br>', gd_date_format('Y-m-d H:i', $val['regDt'])); ?></td>
                                <?php if ($currentStatusCode === 'o') { ?>
                                    <td class="font-date nowrap"><?=gd_interval_day($val['regDt'], date('Y-m-d H:i:s'));?>일</td>
                                <?php } ?>
                                <td class="order-no">
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
                                <td class="js-member-info" data-member-no="<?= $val['memNo'] ?>" data-member-name="<?= $val['orderName'] ?>"
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
                                <td class="text-left"><?= $val['orderGoodsNm'] ?></td>
                                <td><?= gd_currency_display($val['totalGoodsPrice']); ?></td>
                                <?php if (!$isProvider) { ?>
                                    <td><?= gd_currency_symbol() ?><?= gd_money_format($val['totalSettlePrice']); ?></span><?= gd_currency_string() ?></td>
                                    <td>
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
                                <?php if (!in_array($currentStatusCode, ['o'])) { ?>
                                    <td>
                                        <div title="주문 상품별 주문 상태">
                                        <?php if (in_array(substr($val['orderStatus'], 0, 1), ['o','c'])) { ?>
                                            미결제
                                        <?php } elseif (in_array(substr($val['orderStatus'], 0, 1), ['f'])) { ?>
                                            <?=$val['orderStatusStr']?>
                                        <?php } else { ?>
                                            결제확인
                                        <?php } ?>
                                        </div>
                                    </td>
                                <?php } ?>
                                <?php if ($currentStatusCode === 'o') {//입금대기에서 입금자/입금계좌가 표시되도록 처리 ?>
                                    <?php if (!$isProvider) { ?>
                                    <td>
                                        <?php
                                        if ($val['statusMode'] == 'o' && $val['settleKind'] == 'gb') {        // 주문 접수의 경우 입금자명
                                            echo '<span title="입금자명">' . $val['bankSender'] . '</span>';
                                        }
                                        ?>
                                    </td>
                                    <td><?= str_replace(STR_DIVISION, ' / ', gd_isset($val['bankAccount'])); ?></td>
                                    <?php } ?>
                                <?php } else { ?>
                                <td class="font-num point1"><?=number_format($val['noDelivery'])?></td>
                                <td class="font-num point1"><?=number_format($val['deliverying'])?></td>
                                <td class="font-num point1"><?=number_format($val['deliveryed'])?></td>
                                <td class="font-num point1"><?=number_format($val['cancel'])?></td>
                                <td class="font-num point1"><?=number_format($val['exchange'])?></td>
                                <td class="font-num point1"><?=number_format($val['back'])?></td>
                                <td class="font-num point1"><?=number_format($val['refund'])?></td>
                                <?php } ?>
                                <?php if (!$isProvider) { ?>
                                <td class="text-center" data-order-no="<?= $val['orderNo'] ?>" data-reg-date="<?= $val['regDt'] ?>">
                                    <button type="button" class="btn btn-sm btn-<?= $val['adminMemo'] != '' ? 'gray js-html-popover' : 'white' ?> js-super-admin-memo" title="관리자메모" data-placement="left" data-content="<?=nl2br($val['adminMemo'])?>">보기</button>
                                </td>
                                <?php } ?>
                            </tr>
                            <?php
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

