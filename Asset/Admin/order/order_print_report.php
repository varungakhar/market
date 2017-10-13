<style>
    .table-rows > thead:first-child > tr:first-child > th {
        border-left: 1px solid #aeaeae;
    }
    .page-break {
        padding: 0 2px;
    }
</style>

<?php
$printCnt = 0;
foreach ($orderData as $data) {
    $printCnt++;
?>
<div class="page-break">
    <div class="panel panel-default">
        <div class="panel-heading">
            주문번호 : <span><?= $data['orderNo']; ?></span>
            <div class="pull-right">
                주문일시 : <?php echo gd_date_format('Y년 m월 d일 H시 i분', gd_isset($data['regDt'])); ?>
            </div>
        </div>
    </div>

    <div class="table-title gd-help-manual">
        주문내역
    </div>
    <table class="table table-rows">
        <thead>
        <tr>
            <th class="width3p">번호</th>
            <th class="width5p">상품<br/>주문번호</th>
            <?php if($data['orderPrint']['orderPrintOdImageDisplay'] === 'y'){ ?>
            <th class="width-3xs">이미지</th>
            <?php } ?>
            <th>주문상품</th>
            <th class="width5p">수량</th>
            <th class="width5p">판매가</th>
            <th class="width5p">상품할인</th>
            <th class="width5p">회원할인<br />(상품)</th>
            <th class="width5p">회원할인<br />(배송비)</th>
            <th class="width5p">쿠폰할인</th>
            <th class="width5p">결제금액</th>
            <?php if($data['orderPrint']['orderPrintOdScmDisplay'] === 'y'){ ?>
            <th class="width5p">공급사</th>
            <?php } ?>
            <th class="width5p">배송비</th>
            <th class="width7p">송장번호</th>
            <th class="width7p">처리상태</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (isset($data['goods']) === true) {
            $rowAll = 0;
            $sortNo = $data['cnt']['goods']['goods'];// 번호 설정
            $settlePrice = 0;// 상품가격
            foreach ($data['goods'] as $sKey => $sVal) {
                $rowScm = 0;
                foreach ($sVal as $dKey => $dVal) {
                    $rowDelivery = 0;
                    foreach ($dVal as $key => $val) {
                        // 결제금액
                        $settlePrice = ($val['goodsCnt'] * ($val['goodsPrice'] + $val['optionPrice'] + $val['optionTextPrice'])) - $val['goodsDcPrice'] - $val['memberDcPrice'] - $val['memberOverlapDcPrice'] - $val['couponGoodsDcPrice'] - $val['divisionCouponOrderDcPrice'] + $val['addGoodsPrice'];

                        // 주문상태 모드
                        $statusMode = substr($val['orderStatus'], 0, 1);

                        // 해외상점과 상관없이 상품명 한글로
                        if ($val['goodsNmStandard']) {
                            $val['goodsNm'] = $val['goodsNmStandard'];
                        }

                        // rowspan 처리
                        $orderAddGoodsRowSpan = $val['addGoodsCnt'] > 0 ? 'rowspan="' . ($val['addGoodsCnt'] + 1) . '"' : '';
                        $orderScmRowSpan = ' rowspan="' . ($data['cnt']['scm'][$sKey]) . '"';
                        $orderDeliveryRowSpan = ' rowspan="' . ($data['cnt']['delivery'][$dKey]) . '"';
                        ?>
                        <tr id="statusCheck_<?= $statusMode; ?>_<?= $key; ?>" class="text-center">
                            <td <?php echo $orderAddGoodsRowSpan; ?>><?= $sortNo ?></td>
                            <td <?php echo $orderAddGoodsRowSpan; ?>><?= $val['sno'] ?></td>
                            <?php if($data['orderPrint']['orderPrintOdImageDisplay'] === 'y'){ ?>
                            <td>
                                <?php if ($val['goodsType'] === 'addGoods') { ?>
                                    <?= gd_html_add_goods_image($val['goodsNo'], $val['addImageName'], $val['addImagePath'], $val['addImageStorage'], 40, $val['goodsNm'], '_blank'); ?>
                                <?php } else { ?>
                                    <?= gd_html_goods_image($val['goodsNo'], $val['imageName'], $val['imagePath'], $val['imageStorage'], 40, $val['goodsNm'], '_blank') ?>
                                <?php } ?>
                            </td>
                            <?php } ?>
                            <td class="text-left">
                                <?php if ($val['goodsType'] === 'addGoods') { ?>
                                    <div class="goods_name hand text-primary" title="상품명" onclick="addgoods_register_popup('<?= $val['goodsNo']; ?>');">
                                        <span class="label label-default" title="<?= $val['sno'] ?>">추가</span>
                                        <?= $val['goodsNm']; ?>
                                    </div>
                                <?php } else { ?>
                                    <div class="goods_name hand text-primary" title="상품명" onclick="goods_register_popup('<?= $val['goodsNo']; ?>');">
                                        <?= $val['goodsNm']; ?>
                                    </div>
                                <?php } ?>
                                <div class="info">
                                    <?php

                                    // 옵션 처리
                                    if (empty($val['optionInfo']) === false) {
                                        foreach ($val['optionInfo'] as $oKey => $oVal) {
                                            echo '<dl class="dl-horizontal" title="옵션명">';
                                            echo '<dt>' . $oVal['optionName'] . ' :</dt>';
                                            echo '<dd>' . $oVal['optionValue'] . '</dd>';
                                            echo '</dl>';
                                        }
                                    }

                                    // 텍스트 옵션 처리
                                    if (empty($val['optionTextInfo']) === false) {
                                        foreach ($val['optionTextInfo'] as $oKey => $oVal) {
                                            echo '<ul class="list-unstyled" title="텍스트 옵션명">';
                                            echo '<li>' . $oVal['optionName'] . ' :</li>';
                                            echo '<li>' . $oVal['optionValue'] . ' ';
                                            if ($oVal['optionTextPrice'] > 0) {
                                                echo '<span>(추가금 ' . gd_currency_display($oVal['optionTextPrice']) . ')</span>';
                                            }
                                            echo '</li>';
                                            echo '</ul>';
                                        }
                                    }
                                    ?>
                                </div>

                                <?php if($data['orderPrint']['orderPrintOdGoodsCode'] === 'y' && $data['orderPrint']['orderPrintOdSelfGoodsCode'] === 'y'){ ?>
                                    <div class="font-kor">(<?= $val['goodsNo']; ?> / <?= $val['goodsCd']; ?>)</div>
                                <?php } else if ($data['orderPrint']['orderPrintOdGoodsCode'] === 'y'){?>
                                    <div class="font-kor">(<?= $val['goodsNo']; ?>)</div>
                                <?php } else if ($data['orderPrint']['orderPrintOdSelfGoodsCode'] === 'y'){?>
                                    <div class="font-kor">(<?= $val['goodsCd']; ?>)</div>
                                <?php } else { } ?>
                            </td>
                            <td class="text-center"><?= number_format($val['goodsCnt']); ?></td>
                            <td class="text-right">
                                <?= gd_currency_display(($val['goodsPrice'] + $val['optionPrice'] + $val['optionTextPrice']) * $val['goodsCnt']); ?>
                            </td>
                            <td <?php echo $orderAddGoodsRowSpan; ?> class="text-right"><?= gd_currency_display($val['goodsDcPrice']); ?></td>
                            <td <?php echo $orderAddGoodsRowSpan; ?> class="text-right"><?= gd_currency_display($val['totalMemberDcPrice'] + $val['totalMemberOverlapDcPrice']); ?></td>
                            <td <?php echo $orderAddGoodsRowSpan; ?> class="text-right"><?= gd_currency_display($val['totalMemberDeliveryDcPrice']); ?></td>
                            <td <?php echo $orderAddGoodsRowSpan; ?> class="text-right"><?= gd_currency_display($val['totalCouponGoodsDcPrice'] + $val['totalCouponOrderDcPrice']); ?></td>
                            <td <?php echo $orderAddGoodsRowSpan; ?> class="text-right"><?= gd_currency_display($settlePrice); ?></td>
                            <?php if ($rowScm == 0 && $data['orderPrint']['orderPrintOdScmDisplay'] === 'y') { ?>
                                <td <?=$orderScmRowSpan?> class="text-center"><?= $val['companyNm']; ?></td>
                            <?php } ?>
                            <?php if (($rowDelivery == 0 && $data['mallSno'] == DEFAULT_MALL_NUMBER) || ($rowAll == 0 && $data['mallSno'] > DEFAULT_MALL_NUMBER)) { ?>
                                <td <?=$orderDeliveryRowSpan?>><?= gd_currency_display($val['deliveryCharge']); ?></td>
                            <?php } ?>
                            <td <?php echo $orderAddGoodsRowSpan; ?>>
                                <?php
                                if (empty($val['invoiceCompanySno']) === false) {
                                    echo $deliveryCom[$val['invoiceCompanySno']] . '<br/>' . $val['invoiceNo'];
                                }
                                ?>
                            </td>
                            <td <?php echo $orderAddGoodsRowSpan; ?> class="center">
                                <div><?= $val['orderStatusStr']; ?></div>
                                <div><?php if ($val['orderStatus'] == 'd1') {
                                        echo gd_date_format('m-d H:i', gd_isset($val['deliveryDt']));
                                    } else if ($val['orderStatus'] == 'd3') {
                                        echo gd_date_format('m-d H:i', gd_isset($val['finishDt']));
                                    } ?>
                                </div>
                            </td>
                        </tr>
                        <?php
                        if ($val['addGoodsCnt'] > 0) {
                            foreach ($val['addGoods'] as $aVal) {
                                ?>
                                <tr class="text-center add-goods">
                                    <td class="text-center">
                                        <span class="label label-default" title="<?= $aVal['sno'] ?>">추가</span></td>
                                    <td class="text-left">
                                        <?= gd_html_add_goods_image($aVal['addGoodsNo'], $aVal['imageNm'], $aVal['imagePath'], $aVal['imageStorage'], 30, $aVal['goodsNm'], '_blank'); ?>
                                        <div class="goods_name one-line hand" title="추가 상품명" onclick="addgoods_register_popup('<?php echo $aVal['addGoodsNo']; ?>');"><?php echo gd_html_cut($aVal['goodsNm'], 46, '..'); ?>
                                            <small>(<?php echo gd_html_cut($aVal['optionNm'], 46, '..'); ?>)</small>
                                        </div>
                                    </td>
                                    <td class="goods_cnt">
                                        <span class="option_info bold" title="상품 주문 수량"><?php echo number_format($aVal['goodsCnt']); ?></span>
                                    </td>
                                    <td class="text-right">
                                        <span title="상품 금액 : (상품단가+옵션금액) x 수량"><?php echo gd_currency_display($aVal['goodsPrice'] * $aVal['goodsCnt']); ?></span>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        $sortNo--;
                        $rowDelivery++;
                        $rowScm++;
                        $rowAll++;
                    }
                }
            }
        }
        ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="15" class="text-right">
                <strong>결제총액</strong> :
                <?=gd_currency_display($data['totalGoodsPrice'])?> +
                <?=gd_currency_display($data['totalDeliveryCharge'])?>(배송비) - <?=gd_currency_display($data['totalSalePrice'] + $data['useMileage'] + $data['useDeposit'] + $data['totalMemberDeliveryDcPrice'])?>(할인) =
                <strong><?=gd_currency_display($data['settlePrice'])?></strong>
            </td>
        </tr>
        </tfoot>
    </table>

    <?php
    if (empty($data['gift']) === false) {
    ?>
        <div class="table-title gd-help-manual">
            사은품 정보
        </div>
        <table class="table table-rows">
            <thead>
            <tr>
                <th class="width30p">사은품 지급조건명</th>
                <th class="width10p">이미지</th>
                <th class="width30p">사은품명</th>
                <th class="width10p">수량</th>
                <th class="width30p">사은품 설명</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($data['gift'] as $key => $val) {
                ?>
                <tr class="text-center">
                    <td><?= $val['presentTitle']; ?></td>
                    <td><?= html_entity_decode($val['imageUrl']); ?></td>
                    <td><?= $val['giftNm']; ?></td>
                    <td><?= $val['giveCnt']; ?></td>
                    <td><?= strip_tags(html_entity_decode($val['giftDescription'])); ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <?php
    }
    ?>

    <?php if ($data['orderPrint']['orderPrintOdSettleInfoDisplay'] === 'y') { ?>
    <div class="row">
        <div class="col-xs-6">
            <div class="table-title gd-help-manual">
                결제정보
            </div>
            <table class="table table-cols">
                <colgroup>
                    <col class="width-md"/>
                    <col/>
                </colgroup>
                <tbody>
                <tr>
                    <th>상품 금액</th>
                    <td class="input_area right">
                        <span class="font-num">
                            <?php echo gd_currency_display(gd_isset($data['totalGoodsPrice'])); ?>
                            <?php if (empty($data['isDefaultMall']) === true) { ?>
                                (<?= gd_global_order_currency_display(gd_isset($data['totalGoodsPrice']), $data['exchangeRate'], $data['currencyPolicy']); ?>)
                            <?php } ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>할인액</th>
                    <td class="input_area right text-danger">
                        <span class="font-num">
                            (-) <?php echo gd_currency_display($data['totalSalePrice'] + $data['useMileage'] + $data['useDeposit'] + $data['totalMemberDeliveryDcPrice']); ?>
                            <?php if (empty($data['isDefaultMall']) === true) { ?>
                                (<?= gd_global_order_currency_display(($data['totalSalePrice'] + $data['useMileage'] + $data['useDeposit'] + $data['totalMemberDeliveryDcPrice']), $data['exchangeRate'], $data['currencyPolicy']); ?>)
                            <?php } ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>배송비</th>
                    <td class="input_area right">
                        <div class="font-num text-primary">
                            (+) <?php echo gd_currency_display(gd_isset($data['totalDeliveryCharge'])); ?>
                            <?php if (empty($data['isDefaultMall']) === true) { ?>
                                (<?= gd_global_order_currency_display(($data['totalDeliveryCharge']), $data['exchangeRate'], $data['currencyPolicy']); ?>)
                            <?php } ?>
                        </div>
                    </td>
                </tr>
                <?php if (empty($data['isDefaultMall']) === true && $data['totalDeliveryInsuranceFee'] > 0) { ?>
                    <tr>
                        <th>해외배송 보험료</th>
                        <td class="text-right">
                            <div class="text-primary">
                                (+) <?= gd_currency_display(gd_isset($data['totalDeliveryInsuranceFee'])); ?>
                                (<?= gd_global_order_currency_display(gd_isset($data['totalDeliveryInsuranceFee']), $data['exchangeRate'], $data['currencyPolicy']); ?>)
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <th>결제 금액</th>
                    <td class="input_area right">
                        <span class="number_emphasis">
                            <?php echo gd_currency_display(gd_isset($data['settlePrice'])); ?>
                            <?php if (empty($data['isDefaultMall']) === true) { ?>
                                (<?= gd_global_order_currency_display(($data['settlePrice']), $data['exchangeRate'], $data['currencyPolicy']); ?>)
                            <?php } ?>
                        </span>
                    </td>
                </tr>
                <?php if (empty($data['isDefaultMall']) === true && substr($data['settleKind'], 0, 1) == 'o') { ?>
                    <tr>
                        <th>승인금액</th>
                        <td class="text-right">
                            <strong><?=$data['overseasSettleCurrency']?> <?= gd_isset($data['overseasSettlePrice']); ?></strong>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <th>적립 금액</th>
                    <td class="input_area right">
                        <span class="number_emphasis"><?php echo number_format(gd_isset($data['totalMileage'])); ?><?php echo $mileageUse['unit']?></span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="col-xs-6">
            <div class="table-title gd-help-manual">
                결제수단
            </div>
            <table class="table table-cols">
                <colgroup>
                    <col class="width-md"/>
                    <col/>
                </colgroup>
                <tbody>
                <tr>
                    <th>결제방법</th>
                    <td>
                        <span class="text_emphasis"><?php if (gd_isset($data['settle']['escrow']) == 'e') { ?>에스크로 <?php } ?><?php echo gd_isset($data['settle']['name']); ?></span>
                    </td>
                </tr>
                <?php if (gd_isset($data['settleKind']) == 'gb') { ?>
                    <tr>
                        <th>입금계좌</th>
                        <td><?php echo str_replace(STR_DIVISION, ' / ', gd_isset($data['bankAccount'])); ?>
                        </td>
                    </tr>
                    <tr>
                        <th>입금자명</th>
                        <td><span id="bankSender"><?php echo gd_isset($data['bankSender']); ?></span></td>
                    </tr>
                <?php } else { ?>
                    <?php if (gd_isset($data['settle']['method']) == 'c') { ?>
                        <tr>
                            <th>카드사명</th>
                            <td><?php echo gd_isset($data['pgSettleNm'][0]); ?></td>
                        </tr>
                        <?php if (gd_isset($data['pgSettleCd'][0]) != '' && gd_isset($data['pgSettleCd'][0]) != '0' && gd_isset($data['pgSettleCd'][0]) != '00') { ?>
                            <tr>
                                <th>할부개월</th>
                                <td><?php if (gd_isset($data['pgSettleCd'][1]) == '1') { ?>무이자 <?php } ?><?php echo gd_isset($data['pgSettleCd'][0]); ?>개월</td>
                            </tr>
                        <?php } ?>
                    <?php } else if (gd_isset($data['settle']['method']) == 'c') { ?>
                        <tr>
                            <th>이체은행</th>
                            <td><?php echo gd_isset($data['pgSettleNm'][0]); ?></td>
                        </tr>
                    <?php } else if (gd_isset($data['settle']['method']) == 'v') { ?>
                        <tr>
                            <th>입금계좌</th>
                            <td><?php echo gd_isset($data['pgSettleNm'][0]); ?> / <?php echo gd_isset($data['pgSettleNm'][1]); ?> / <?php echo gd_isset($data['pgSettleNm'][2]); ?></td>
                        </tr>
                        <tr>
                            <th>입금기한</th>
                            <td><?php echo gd_isset($data['pgSettleCd'][0]); ?></td>
                        </tr>
                    <?php } else if (gd_isset($data['settle']['method']) == 'h') { ?>
                        <tr>
                            <th>통신사</th>
                            <td><?php echo gd_isset($data['pgSettleNm'][0]); ?></td>
                        </tr>
                        <?php if (empty($data['pgSettleCd'][0]) === false) { ?>
                            <tr>
                                <th>휴대폰번호</th>
                                <td><?php echo gd_isset($data['pgSettleCd'][0]); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
                <tr>
                    <th>결제 확인일</th>
                    <td><?php echo gd_isset($data['paymentDt']); ?></td>
                </tr>
                <tr>
                    <th>영수증 신청여부</th>
                    <td>
                        <?php
                        if (gd_isset($data['receiptFl']) == 'n') {
                            echo '미신청';
                            // 현금영수증인 경우
                        } else if (gd_isset($data['receiptFl']) == 'r') {
                            echo '현금영수증 신청';
                            // 세금계산서인 경우
                        } else if (gd_isset($data['receiptFl']) == 't') {
                            echo '세금계산서 신청';
                        }
                        ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php } ?>

    <div class="row">
        <div class="col-xs-6">

            <div class="table-title gd-help-manual">
                주문자 정보
            </div>
            <table class="table table-cols">
                <colgroup>
                    <col class="width-md"/>
                    <col/>
                </colgroup>
                <tbody>
                <tr>
                    <th>주문자명</th>
                    <td>
                        <span class="text_emphasis"><?php echo gd_isset($data['orderName']); ?></span>
                        <?php if (empty($data['memInfo']) === true) { ?>
                            <?php if (empty($data['memNo']) === true) { ?>
                                / <span class="text-primary">비회원</span>
                            <?php } else { ?>
                                / <span class="text-primary">탈퇴회원</span>
                            <?php } ?>
                        <?php } else { ?>
                            / <span class="text-primary"><?= $data['memInfo']['memId'] ?></span>
                            / <span class="text-primary"><?= $data['memInfo']['groupNm'] ?></span>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th>회원등급</th>
                    <td>
                        <?php
                        if (empty($data['memInfo']) === false) {
                            echo gd_isset($data['memInfo']['groupNm']) . '</span>';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>구매자 IP</th>
                    <td class="font-num"><?=$data['orderIp']?></td>
                </tr>
                <tr>
                    <th>전화번호</th>
                    <td>
                        <?php if (empty($data['isDefaultMall']) === true) { ?>
                            (+<?php echo gd_isset($data['orderPhonePrefix']); ?>)
                        <?php } ?>
                        <?php echo gd_isset($data['orderPhone']); ?>
                    </td>
                </tr>
                <tr>
                    <th>휴대폰번호</th>
                    <td>
                        <?php if (empty($data['isDefaultMall']) === true) { ?>
                            (+<?php echo gd_isset($data['orderCellPhonePrefix']); ?>)
                        <?php } ?>
                        <?php echo gd_isset($data['orderCellPhone']); ?>
                    </td>
                </tr>
                <tr>
                    <th>이메일</th>
                    <td><?php echo gd_isset($data['orderEmail']); ?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-xs-6">
            <div class="table-title gd-help-manual">
                수령자 정보
            </div>
            <table class="table table-cols">
                <colgroup>
                    <col class="width-md"/>
                    <col/>
                </colgroup>
                <tbody>
                <tr>
                    <th>수령자명</th>
                    <td><?php echo gd_isset($data['receiverName']); ?></td>
                </tr>
                <tr>
                    <th>전화번호</th>
                    <td>
                        <?php if (empty($data['isDefaultMall']) === true) { ?>
                            (+<?php echo gd_isset($data['receiverPhonePrefix']); ?>)
                        <?php } ?>
                        <?php echo gd_isset($data['receiverPhone']); ?>
                    </td>
                </tr>
                <tr>
                    <th>휴대폰번호</th>
                    <td>
                        <?php if (empty($data['isDefaultMall']) === true) { ?>
                            (+<?php echo gd_isset($data['receiverCellPhonePrefix']); ?>)
                        <?php } ?>
                        <?php echo gd_isset($data['receiverCellPhone']); ?>
                    </td>
                </tr>
                <tr>
                    <th>주소</th>
                    <td>
                        <div><?php echo gd_isset($data['receiverZonecode']); ?></div>
                        <?php if (empty($data['isDefaultMall']) === true) { ?>
                        <div><?php echo gd_isset($data['receiverAddressSub']); ?>, <?php echo gd_isset($data['receiverAddress']); ?>, <?php echo gd_isset($data['receiverState']); ?>, <?php echo gd_isset($data['receiverCity']); ?>, <?php echo gd_isset($data['receiverCountry']); ?></div>
                        <?php } else { ?>
                        <div><?php echo gd_isset($data['receiverAddress']); ?> <?php echo gd_isset($data['receiverAddressSub']); ?></div>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th>배송 메세지</th>
                    <td><?php echo gd_isset($data['orderMemo']); ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="clear-both"></div>

    <?php if (empty($data['addFieldData']) === false) { ?>
        <div class="table-title gd-help-manual">
            추가 정보
        </div>
        <table class="table table-cols">
            <tbody>
            <?php
            foreach ($data['addFieldData'] as $addFieldKey => $addFieldVal) {
                if ($addFieldVal['process'] == 'goods') {
                    foreach ($addFieldVal['data'] as $addDataKey => $addDataVal) {
                        ?>
                        <tr>
                            <th><?= $addFieldVal['name']; ?> : <?= $addFieldVal['goodsNm'][$addDataKey]; ?></th>
                        </tr>
                        <tr>
                            <td><?= $addDataVal; ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <th><?= $addFieldVal['name']; ?></th>
                    </tr>
                    <tr>
                        <td><?= $addFieldVal['data']; ?></td>
                    </tr>
                    <?php
                }
            }
            ?>
            </tbody>
        </table>
        <div class="display-inline clear-both"></div>
    <?php } ?>

    <!-- 관리자 메모 -->
    <?php if($data['orderPrint']['orderPrintOdAdminMemoDisplay'] === 'y'){ ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="table-title gd-help-manual">
                    관리자 메모
                </div>
                <table class="table table-rows mgb5">
                    <colgroup>
                        <col />
                    </colgroup>
                    <thead>
                    <tr>
                        <th>작성내용</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="text-left"><?=nl2br($data['adminMemo'])?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="clear-both"></div>
    <?php } ?>
    <!-- 관리자 메모-->

    <!-- 하단 추가 정보 표시 -->
    <?php if($data['orderPrint']['orderPrintOdBottomInfo'] === 'y' && trim($data['orderPrint']['orderPrintOdBottomInfoText']) !== ''){ ?>
    <div class="row">
        <div class="col-xs-12">
            <div style="padding: 3px;"><?=nl2br($data['orderPrint']['orderPrintOdBottomInfoText'])?></div>
        </div>
    </div>
    <?php } ?>
    <!-- 하단 추가 정보 표시 -->

    <?php
    if ($printCnt != count($orderData)) {
        echo '<hr class="hidden-print" style="margin:20px 0px 20px 0px;  border-top:dashed 1px #000000;" />';
    }?>
</div>
<?php
}
?>
