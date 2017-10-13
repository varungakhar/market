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
foreach ($orderData as $key => $data) {
    // translator load
    if (empty($translator) == false) {
        $translator[$key]->register();
    }
    $printCnt++;
?>
<div class="page-break">
    <div class="table-title gd-help-manual">
        <?= __('주문자 정보'); ?>
    </div>
    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col/>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tbody>
        <tr>
            <th><?= __('주문번호'); ?></th>
            <td>
                <?php echo $data['orderNo']; ?>
            </td>
            <th><?= __('주문일'); ?></td>
            <td><?php echo gd_date_format(__('Y년 m월 d일 H시 i분'), gd_isset($data['regDt'])); ?></td>
        </tr>
        <tr>
            <th><?= __('주문자명'); ?></th>
            <td>
                <span class="text_emphasis"><?php echo gd_isset($data['orderName']); ?></span>
                <?php
                if (empty($data['memInfo']) === true) {
                    echo '<span class="text_emphasis"> / ' . __('비회원') . '</span>';
                } else {
                    echo '<span class="text_emphasis"> / ' . gd_isset($data['memInfo']['memId']);
                    if (gd_isset($data['memInfo']['nickNm'])) {
                        echo ' (' . $data['memInfo']['nickNm'] . ')';
                    }
                }
                ?>
            </td>
            <th><?= __('이메일'); ?></th>
            <td><?php echo gd_isset($data['orderEmail']); ?></td>
        </tr>
        <tr>
            <th><?= __('전화번호'); ?></th>
            <td><?php echo gd_isset($data['orderPhone']); ?></td>
            <th><?= __('휴대폰번호'); ?></th>
            <td><?php echo gd_isset($data['orderCellPhone']); ?></td>
        </tr>
        </tbody>
    </table>


    <div class="table-title gd-help-manual"><?= __('상품정보'); ?></div>
    <table class="table table-rows">
        <thead>
        <tr>
            <th class="width3p"><?= __('번호'); ?></th>
            <th class="width5p"><?= __('상품'); ?><br/><?= __('주문번호'); ?></th>
            <?php if($data['orderPrint']['orderPrintOdCsImageDisplay'] === 'y'){ ?>
            <th class="width-3xs"><?= __('이미지'); ?></th>
            <?php } ?>
            <th><?= __('주문상품'); ?></th>
            <th class="width5p"><?= __('수량'); ?></th>
            <th class="width5p"><?= __('판매가'); ?></th>
            <th class="width5p"><?php if ($data['mallSno'] != '2') echo __('상품할인'); else echo __('상품') . '<br/>' . __('할인');?></th>
            <th class="width5p"><?php if ($data['mallSno'] != '2') echo __('회원할인') . '<br />(' . __('상품') . ')'; else echo __('회원') . '<br/>' . __('할인') . ' (' . __('상품') . ')';?></th>
            <th class="width5p"><?php if ($data['mallSno'] != '2') echo __('회원할인') . '<br />(' . __('배송비') . ')'; else echo __('회원') . '<br/>' . __('할인') . '(' . __('배송비') . ')';?></th>
            <th class="width5p"><?php if ($data['mallSno'] != '2') echo __('쿠폰할인'); else echo __('쿠폰') . '<br/>' . __('할인');?></th>
            <th class="width5p"><?php if ($data['mallSno'] != '2') echo __('결제금액'); else echo __('결제') . '<br/>' . __('금액');?></th>
            <th class="width5p"><?= __('배송비'); ?></th>
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
                        $statusMode = substr($val['orderStatus'], 0, 1);

                        // rowspan 처리
                        $orderAddGoodsRowSpan = $val['addGoodsCnt'] > 0 ? 'rowspan="' . ($val['addGoodsCnt'] + 1) . '"' : '';
                        $orderScmRowSpan = ' rowspan="' . ($data['cnt']['scm'][$sKey]) . '"';
                        $orderDeliveryRowSpan = ' rowspan="' . ($data['cnt']['delivery'][$dKey]) . '"';

                        // 기본 배송업체 설정
                        if (empty($val['deliverySno']) === true) {
                            $val['orderDeliverySno'] = $deliverySno;
                        }
                        ?>
                        <tr id="statusCheck_<?= $statusMode; ?>_<?= $key; ?>" class="text-center">
                            <td <?php echo $orderAddGoodsRowSpan; ?>><?= $sortNo ?></td>
                            <td <?php echo $orderAddGoodsRowSpan; ?>><?= $val['sno'] ?></td>
                            <?php if($data['orderPrint']['orderPrintOdCsImageDisplay'] === 'y'){ ?>
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
                                    <div class="goods_name hand text-primary" title="<?= __('상품명'); ?>" onclick="addgoods_register_popup('<?= $val['goodsNo']; ?>');">
                                        <span class="label label-default" title="<?= $val['sno'] ?>"><?= __('추가'); ?></span>
                                        <?= $val['goodsNm']; ?>
                                    </div>
                                <?php } else { ?>
                                    <div class="goods_name hand text-primary" title="<?= __('상품명'); ?>" onclick="goods_register_popup('<?= $val['goodsNo']; ?>');">
                                        <?= $val['goodsNm']; ?>
                                    </div>
                                <?php } ?>
                                <div class="info">
                                    <?php
                                    // 옵션 처리
                                    if (empty($val['optionInfo']) === false) {
                                        foreach ($val['optionInfo'] as $oKey => $oVal) {
                                            echo '<dl class="dl-horizontal" title="' . __('옵션명') . '">';
                                            echo '<dt>' . $oVal['optionName'] . ' :</dt>';
                                            echo '<dd>' . $oVal['optionValue'] . '</dd>';
                                            echo '</dl>';
                                        }
                                    }

                                    // 텍스트 옵션 처리
                                    if (empty($val['optionTextInfo']) === false) {
                                        foreach ($val['optionTextInfo'] as $oKey => $oVal) {
                                            echo '<ul class="list-unstyled" title="' . __('텍스트 옵션명') . '">';
                                            echo '<li>' . $oVal['optionName'] . ' :</li>';
                                            echo '<li>' . $oVal['optionValue'] . ' ';
                                            if ($oVal['optionTextPrice'] > 0) {
                                                echo '<span>(' . __('추가금') . ' ' . gd_global_order_currency_display(gd_isset($oVal['optionTextPrice']), $data['exchangeRate'], $data['currencyPolicy']) . ')</span>';
                                            }
                                            echo '</li>';
                                            echo '</ul>';
                                        }
                                    }
                                    ?>
                                </div>

                                <?php if($data['orderPrint']['orderPrintOdCsGoodsCode'] === 'y' && $data['orderPrint']['orderPrintOdCsSelfGoodsCode'] === 'y'){ ?>
                                    <div class="font-kor">(<?= $val['goodsNo']; ?> / <?= $val['goodsCd']; ?>)</div>
                                <?php } else if ($data['orderPrint']['orderPrintOdCsGoodsCode'] === 'y'){?>
                                    <div class="font-kor">(<?= $val['goodsNo']; ?>)</div>
                                <?php } else if ($data['orderPrint']['orderPrintOdCsSelfGoodsCode'] === 'y'){?>
                                    <div class="font-kor">(<?= $val['goodsCd']; ?>)</div>
                                <?php } else { } ?>
                            </td>
                            <td class="text-center"><?= number_format($val['goodsCnt']); ?></td>
                            <td class="text-right">
                                <?= gd_global_order_currency_display(($val['goodsPrice'] + $val['optionPrice'] + $val['optionTextPrice']) * $val['goodsCnt'], $data['exchangeRate'], $data['currencyPolicy']); ?>
                            </td>
                            <td <?php echo $orderAddGoodsRowSpan; ?> class="text-right"><?= gd_global_order_currency_display($val['goodsDcPrice'], $data['exchangeRate'], $data['currencyPolicy']); ?></td>
                            <td <?php echo $orderAddGoodsRowSpan; ?> class="text-right"><?= gd_global_order_currency_display($val['memberDcPrice'] + $val['memberOverlapDcPrice'], $data['exchangeRate'], $data['currencyPolicy']); ?></td>
                            <td <?php echo $orderAddGoodsRowSpan; ?> class="text-right"><?= gd_global_order_currency_display($val['totalMemberDeliveryDcPrice'], $data['exchangeRate'], $data['currencyPolicy']); ?></td>
                            <td <?php echo $orderAddGoodsRowSpan; ?> class="text-right"><?= gd_global_order_currency_display($val['couponGoodsDcPrice'] + $val['divisionCouponOrderDcPrice'], $data['exchangeRate'], $data['currencyPolicy']); ?></td>
                            <td <?php echo $orderAddGoodsRowSpan; ?> class="text-right"><?= gd_global_order_currency_display($settlePrice, $data['exchangeRate'], $data['currencyPolicy']); ?></td>
                            <?php if (($rowDelivery == 0 && $data['mallSno'] == DEFAULT_MALL_NUMBER) || ($rowAll == 0 && $data['mallSno'] > DEFAULT_MALL_NUMBER)) { ?>
                                <td <?=$orderDeliveryRowSpan?>><?= gd_global_order_currency_display($val['deliveryCharge'], $data['exchangeRate'], $data['currencyPolicy']); ?></td>
                            <?php } ?>
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
                                        <div class="goods_name one-line hand" title="<?= __('추가 상품명'); ?>" onclick="addgoods_register_popup('<?php echo $aVal['addGoodsNo']; ?>');"><?php echo gd_html_cut($aVal['goodsNm'], 46, '..'); ?>
                                            <small>(<?php echo gd_html_cut($aVal['optionNm'], 46, '..'); ?>)</small>
                                        </div>
                                    </td>
                                    <td class="goods_cnt">
                                        <span class="option_info bold" title="<?= __('상품 주문 수량'); ?>"><?php echo number_format($aVal['goodsCnt']); ?></span>
                                    </td>
                                    <td class="text-right">
                                        <span title="<?= __('상품 금액 : (상품단가+옵션금액) x 수량'); ?>"><?php echo gd_global_order_currency_display($aVal['goodsPrice'] * $aVal['goodsCnt'], $data['exchangeRate'], $data['currencyPolicy']); ?></span>
                                    </td>
                                </tr>
                                <?php
                            }
                        }

                        if (empty($val['orderDeliverySno']) === false) {
                            $escrowDelivery = $val['orderDeliverySno'];
                        }
                        if (empty($val['invoiceNo']) === false) {
                            $escrowInvoiceNo = $val['invoiceNo'];
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
            <td colspan="12" class="text-right">
                <strong><?= __('총 결제 금액'); ?></strong> :
                <?=gd_global_order_currency_display($data['totalGoodsPrice'], $data['exchangeRate'], $data['currencyPolicy'])?> +
                <?=gd_global_order_currency_display($data['totalDeliveryCharge'], $data['exchangeRate'], $data['currencyPolicy'])?><?= '(' . __('배송비') .')'; ?> - <?=gd_global_order_currency_display($data['totalSalePrice'] + $data['useMileage'] + $data['useDeposit'] + $data['totalMemberDeliveryDcPrice'], $data['exchangeRate'], $data['currencyPolicy'])?>(<?= __('할인'); ?>) =
                <strong><?=gd_global_order_currency_display($data['settlePrice'], $data['exchangeRate'], $data['currencyPolicy'])?></strong>
            </td>
        </tr>
        </tfoot>
    </table>

    <?php
    if (empty($data['gift']) === false) {
    ?>
        <div class="table-title gd-help-manual">
            <?= __('사은품 정보'); ?>
        </div>
        <table class="table table-rows">
            <thead>
            <tr>
                <th class="width30p"><?= __('사은품 지급조건명'); ?></th>
                <th class="width10p"><?= __('이미지'); ?></th>
                <th class="width30p"><?= __('사은품명'); ?></th>
                <th class="width10p"><?= __('수량'); ?></th>
                <th class="width30p"><?= __('사은품 설명'); ?></th>
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

    <?php if ($data['orderPrint']['orderPrintOdCsSettleInfoDisplay'] === 'y') { ?>
    <div class="row">
        <div class="col-xs-6">
            <div class="table-title gd-help-manual">
                <?= __('결제정보'); ?>
            </div>
            <table class="table table-cols">
                <colgroup>
                    <col class="width-md"/>
                    <col/>
                </colgroup>
                <tbody>
                <tr>
                    <th><?= __('상품 금액'); ?></th>
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
                    <th><?= __('할인금액'); ?></th>
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
                    <th><?= __('배송비'); ?></th>
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
                        <th><?= __('해외배송 보험료'); ?></th>
                        <td class="text-right">
                            <div class="text-primary">
                                (+) <?= gd_currency_display(gd_isset($data['totalDeliveryInsuranceFee'])); ?>
                                (<?= gd_global_order_currency_display(gd_isset($data['totalDeliveryInsuranceFee']), $data['exchangeRate'], $data['currencyPolicy']); ?>)
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <th><?= __('총 결제 금액'); ?></th>
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
                        <th><?= __('승인금액'); ?></th>
                        <td class="text-right">
                            <strong><?=$data['overseasSettleCurrency']?> <?= gd_isset($data['overseasSettlePrice']); ?></strong>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <th><?= __('총 적립 금액'); ?></th>
                    <td class="input_area right">
                        <span class="number_emphasis"><?php echo number_format(gd_isset($data['totalMileage'])); ?><?php echo $mileageUse['unit']?></span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="col-xs-6">
            <div class="table-title gd-help-manual">
                <?= __('결제수단'); ?>
            </div>
            <table class="table table-cols">
                <colgroup>
                    <col class="width-md"/>
                    <col/>
                </colgroup>
                <tbody>
                <tr>
                    <th><?= __('결제방법'); ?></th>
                    <td>
                        <span class="text_emphasis"><?php if (gd_isset($data['settle']['escrow']) == 'e') { ?><?= __('에스크로'); ?> <?php } ?><?php echo gd_isset($data['settle']['name']); ?></span>
                    </td>
                </tr>
                <?php if (gd_isset($data['settleKind']) == 'gb') { ?>
                    <tr>
                        <th><?= __('입금계좌'); ?></th>
                        <td><?php echo str_replace(STR_DIVISION, ' / ', gd_isset($data['bankAccount'])); ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?= __('입금자명'); ?></th>
                        <td><span id="bankSender"><?php echo gd_isset($data['bankSender']); ?></span></td>
                    </tr>
                <?php } else { ?>
                    <?php if (gd_isset($data['settle']['method']) == 'c') { ?>
                        <tr>
                            <th><?= __('카드사명'); ?></th>
                            <td><?php echo gd_isset($data['pgSettleNm'][0]); ?></td>
                        </tr>
                        <?php if (gd_isset($data['pgSettleCd'][0]) != '' && gd_isset($data['pgSettleCd'][0]) != '0' && gd_isset($data['pgSettleCd'][0]) != '00') { ?>
                            <tr>
                                <th><?= __('할부개월'); ?></th>
                                <td><?php if (gd_isset($data['pgSettleCd'][1]) == '1') { ?>무이자 <?php } ?><?php echo gd_isset($data['pgSettleCd'][0]); ?><?= __('개월'); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else if (gd_isset($data['settle']['method']) == 'c') { ?>
                        <tr>
                            <th><?= __('이체은행'); ?></th>
                            <td><?php echo gd_isset($data['pgSettleNm'][0]); ?></td>
                        </tr>
                    <?php } else if (gd_isset($data['settle']['method']) == 'v') { ?>
                        <tr>
                            <th><?= __('입금계좌'); ?></th>
                            <td><?php echo gd_isset($data['pgSettleNm'][0]); ?> / <?php echo gd_isset($data['pgSettleNm'][1]); ?> / <?php echo gd_isset($data['pgSettleNm'][2]); ?></td>
                        </tr>
                        <tr>
                            <th><?= __('입금기한'); ?></th>
                            <td><?php echo gd_isset($data['pgSettleCd'][0]); ?></td>
                        </tr>
                    <?php } else if (gd_isset($data['settle']['method']) == 'h') { ?>
                        <tr>
                            <th><?= __('통신사'); ?></th>
                            <td><?php echo gd_isset($data['pgSettleNm'][0]); ?></td>
                        </tr>
                        <?php if (empty($data['pgSettleCd'][0]) === false) { ?>
                            <tr>
                                <th><?= __('휴대폰번호'); ?></th>
                                <td><?php echo gd_isset($data['pgSettleCd'][0]); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
                <tr>
                    <th><?= __('결제확인일'); ?></th>
                    <td><?php echo gd_isset($data['paymentDt']); ?></td>
                </tr>
                <?php if (empty($data['isDefaultMall']) === false) { ?>
                <tr>
                    <th><?= __('영수증 신청여부'); ?></th>
                    <td>
                        <?php
                        if (gd_isset($data['receiptFl']) == 'n') {
                            echo __('미신청');
                            // 현금영수증인 경우
                        } else if (gd_isset($data['receiptFl']) == 'r') {
                            echo __('현금영수증 신청');
                            // 세금계산서인 경우
                        } else if (gd_isset($data['receiptFl']) == 't') {
                            echo __('세금계산서 신청');
                        }
                        ?>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php } ?>

    <div class="table-title gd-help-manual">
        <?= __('배송정보'); ?>
    </div>
    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col/>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tbody>
        <tr>
            <th><?= __('수령자'); ?></th>
            <td><?php echo gd_isset($data['receiverName']); ?></td>
            <th><?= __('연락처'); ?></th>
            <td>
                <?php if (empty($data['isDefaultMall']) === true) { ?>
                    (+<?php echo gd_isset($data['receiverPhonePrefix']); ?>)
                <?php } ?>
                <?php echo gd_isset($data['receiverPhone']); ?> /
                <?php if (empty($data['isDefaultMall']) === true) { ?>
                    (+<?php echo gd_isset($data['receiverCellPhonePrefix']); ?>)
                <?php } ?>
                <?php echo gd_isset($data['receiverCellPhone']); ?></td>
        </tr>
        <tr>
            <th><?= __('송장번호'); ?></th>
            <td><?php echo gd_isset($data['invoiceNo']); ?></td>
            <th>
                <?php if (empty($data['isDefaultMall']) === true) echo __('배송일'); else echo __('배송일(출고일)');?>
            </th>
            <td>
                <?php echo gd_isset($data['deliveryDt']); ?> <br>
                <?php echo gd_isset($data['deliveryCompleteDt']); ?>
            </td>
        </tr>
        <tr>
            <th><?= __('배송지'); ?></th>
            <td colspan="3">
                <?php if (empty($data['isDefaultMall']) === true) { ?>
                    <div><?php echo gd_isset($data['receiverAddressSub']); ?>, <?php echo gd_isset($data['receiverAddress']); ?>, <?php echo gd_isset($data['receiverState']); ?>, <?php echo gd_isset($data['receiverCity']); ?>, <?php echo gd_isset($data['receiverCountry']); ?></div>
                <?php } else { ?>
                    <div><?php echo gd_isset($data['receiverAddress']); ?> <?php echo gd_isset($data['receiverAddressSub']); ?></div>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <th><?= __('배송메시지'); ?></th>
            <td colspan="3"><?php echo gd_isset($data['orderMemo']); ?></td>
        </tr>
        </tbody>
    </table>
    <div class="display-inline clear-both"></div>

    <?php if (empty($data['addFieldData']) === false) { ?>
        <div class="table-title gd-help-manual">
            <?= __('추가 정보'); ?>
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

    <div class="clear-both"></div>

    <!-- 관리자 메모 -->
    <?php if($data['orderPrint']['orderPrintOdCsAdminMemoDisplay'] === 'y'){ ?>
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
    <?php if($data['orderPrint']['orderPrintOdCsBottomInfo'] === 'y' && trim($data['orderPrint']['orderPrintOdCsBottomInfoText']) !== ''){ ?>
        <div class="row">
            <div class="col-xs-12">
                <div style="padding: 3px;"><?=nl2br($data['orderPrint']['orderPrintOdCsBottomInfoText'])?></div>
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
