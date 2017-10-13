<!-- 프린트 출력을 위한 form -->
<form id="frmOrderPrint" name="frmOrderPrint" action="" method="post" class="display-none">
    <input type="checkbox" name="orderNo" value="<?= gd_isset($data['orderNo']); ?>" checked="checked"/>
    <input type="hidden" name="orderPrintCode" value=""/>
    <input type="hidden" name="orderPrintMode" value=""/>
</form>

<!-- // 프린트 출력을 위한 form -->

<!-- 주문상태 일괄 변경을 위한 form -->
<form id="frmOrderStatus" method="post" action="../order/order_ps.php"></form>
<!-- //주문상태 일괄 변경을 위한 form -->

<form id="frmOrder" name="frmOrder" action="./order_ps.php" method="post">
    <input type="hidden" name="mode" value="modify"/>
    <input type="hidden" name="orderNo" value="<?= $data['orderNo'] ?>"/>

    <div class="page-header js-affix">
        <h3><?= end($naviMenu->location); ?></h3>
        <input type="submit" value="저장" class="btn btn-red">
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <span class="flag flag-16 flag-<?= $data['domainFl']; ?>"></span>
            <?= $data['mallName']; ?>
            <?= str_repeat('&nbsp', 6); ?>

            주문번호 : <span><?= $data['orderNo']; ?></span>
            <?= str_repeat('&nbsp', 2); ?>
            <?php if ($data['orderChannelFl'] == 'naverpay') { ?>
                <span><img src="<?=UserFilePath::adminSkin('gd_share', 'img', 'channel_icon', 'naverpay.gif')->www()?>" /> <?= $data['apiOrderNo']; ?></span>
            <?php } ?>
            <?= str_repeat('&nbsp', 6); ?>
            주문일시 : <span><?= gd_date_format('Y년 m월 d일 H시 i분', gd_isset($data['regDt'])); ?></span>
            <div class="pull-right">
                <div class="form-inline">
                    <?= gd_select_box('orderPrintMode', null, ['report' => '주문내역서', 'customerReport' => '주문내역서 (고객용)', 'reception' => '간이영수증', 'particular' => '거래명세서', 'taxInvoice' => '세금계산서'], null, null, '=인쇄 선택=', null, 'form-control input-sm') ?>
                    <input type="button" onclick="order_print_popup($('#orderPrintMode').val(), 'frmOrderPrint', 'frmOrderPrint', 'orderNo', <?=$isProvider ? 'true' : 'false'?>);" value="인쇄" class="btn btn-sm btn-white"/>
                </div>
            </div>
        </div>
    </div>

    <?php if (!$isProvider && $data['orderChannelFl']!='naverpay') { ?>
        <div class="table-dashboard">
            <table class="table table-cols">
                <colgroup>
                    <col style="width: 33%;" />
                    <col style="width: 34%;" />
                    <col style="width: 33%;" />
                </colgroup>
                <thead>
                <tr>
                    <th>총 결제금액</th>
                    <th>총 취소금액</th>
                    <th>총 환불금액</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="bln">
                        <strong><?= gd_currency_display(gd_isset($data['dashBoardPrice']['settlePrice'])); ?></strong>
                        <ul class="list-unstyled">
                            <li><strong>결제 예정금액</strong><span><?= gd_currency_display(gd_isset($data['dashBoardPrice']['dueSettlePrice'])); ?></span></li>
                        </ul>
                    </td>
                    <td>
                        <strong><?= gd_currency_display(gd_isset($data['dashBoardPrice']['cancelPrice'])); ?></strong>
                        <ul class="list-unstyled">
                            <li><strong>&nbsp;</strong><span></span></li>
                        </ul>
                    </td>
                    <td>
                        <strong><?= gd_currency_display(gd_isset($data['dashBoardPrice']['refundPrice'])); ?></strong>
                        <ul class="list-unstyled">
                            <?php if (!$isProvider) { // 공급사인 경우 링크 제거?>
                                <li><strong><a href="./refund_view.php?orderNo=<?= $data['orderNo'] ?>&handleSno=<?= $data['handleSno'] ?>&isAll=1&statusFl=1" class="js-order-refund">환불 예정금액</a></strong><span><?= gd_currency_display(gd_isset($data['dashBoardPrice']['dueRefundPrice'])); ?></span>
                                </li>
                            <?php } else { ?>
                                <li><strong>환불 예정금액</strong><span><?= gd_currency_display(gd_isset($data['dashBoardPrice']['dueRefundPrice'])); ?></span>
                                </li>
                            <?php } ?>
                        </ul>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    <?php } ?>

    <div class="table-title">
        <span class="gd-help-manual mgt30">상품정보</span>
        <span class="btn-group mgl30">
            <button type="button" class="btn btn-sm btn-black active js-convert-exchange" data-use-mall="false">기준몰</button>
            <?php if ($gGlobal['isUse'] && $data['mallSno'] > 1) { ?>
                <button type="button" class="btn btn-sm btn-white js-convert-exchange" data-use-mall="true">해외상점</button>
            <?php } ?>
        </span>
    </div>
    <div id="tabOrderStatus" clear>
        <ul class="nav nav-tabs mgb30" role="tablist">
            <li role="presentation" class="<?=$data['normalGoods']['active'] == 'order' ? 'active' : ''?>">
                <a href="#tab-status-order" data-toggle="tab">주문내역 (<strong><?=number_format($data['normalGoods']['ordercnt']['orderGoodsCnt'])?></strong> 건)</strong></a>
            </li>
            <?php if (!$isProvider) { ?>
                <li role="presentation" class="<?=$data['normalGoods']['active'] == 'cancel' ? 'active' : ''?>">
                    <a href="#tab-status-cancel" data-toggle="tab">취소내역 (<strong><?=number_format($data['normalGoods']['cancelcnt']['orderGoodsCnt'])?></strong> 건)</strong></a>
                </li>
            <?php } ?>
            <li role="presentation" class="<?=$data['normalGoods']['active'] == 'exchange' ? 'active' : ''?>">
                <a href="#tab-status-exchange" data-toggle="tab">교환내역 (<strong><?=number_format($data['normalGoods']['exchangecnt']['orderGoodsCnt'])?></strong> 건)</strong></a>
            </li>
            <li role="presentation" class="<?=$data['normalGoods']['active'] == 'back' ? 'active' : ''?>">
                <a href="#tab-status-back" data-toggle="tab">반품내역 (<strong><?=number_format($data['normalGoods']['backcnt']['orderGoodsCnt'])?></strong> 건)</strong></a>
            </li>
            <li role="presentation" class="<?=$data['normalGoods']['active'] == 'refund' ? 'active' : ''?>">
                <a href="#tab-status-refund" data-toggle="tab">환불내역 (<strong><?=number_format($data['normalGoods']['refundcnt']['orderGoodsCnt'])?></strong> 건)</strong></a>
            </li>
            <?php if (!$isProvider) { ?>
                <li role="presentation" class="<?=$data['normalGoods']['active'] == 'fail' ? 'active' : ''?>">
                    <a href="#tab-status-fail" data-toggle="tab">결제 중단/실패 내역 (<strong><?=number_format($data['normalGoods']['failcnt']['orderGoodsCnt'])?></strong> 건)</strong></a>
                </li>
            <?php } ?>
        </ul>
        <div class="tab-content loading">
            <div role="tab-status-order" class="tab-pane <?=$data['normalGoods']['active'] == 'order' ? 'in active' : ''?>" id="tab-status-order"></div>
            <?php if (!$isProvider) { ?>
                <div role="tab-status-cancel" class="tab-pane <?=$data['normalGoods']['active'] == 'cancel' ? 'in active' : ''?>" id="tab-status-cancel"></div>
            <?php } ?>
            <div role="tab-status-exchange" class="tab-pane <?=$data['normalGoods']['active'] == 'exchange' ? 'in active' : ''?>" id="tab-status-exchange"></div>
            <div role="tab-status-back" class="tab-pane <?=$data['normalGoods']['active'] == 'back' ? 'in active' : ''?>" id="tab-status-back"></div>
            <div role="tab-status-refund" class="tab-pane <?=$data['normalGoods']['active'] == 'refund' ? 'in active' : ''?>" id="tab-status-refund"></div>
            <div role="tab-status-fail" class="tab-pane <?=$data['normalGoods']['active'] == 'fail' ? 'in active' : ''?>" id="tab-status-fail"></div>
        </div>
    </div>

    <?php
    $claimElementDisabled = '';
    if($data['orderChannelFl'] == 'naverpay' ) {
        $claimElementDisabled = 'disabled';
    }
    if (empty($data['gift']) === false) {
        ?>
        <table class="table table-cols">
            <colgroup>
                <col class="width-md"/>
                <col class="width-md"/>
                <col/>
                <col class="width-xl"/>
                <col class="width-md"/>
            </colgroup>
            <tbody>
            <tr>
                <th class="text-left" rowspan="<?=count($data['gift']) + 2;?>"><span class="gd-help-manual">사은품 정보</span></th>
                <th class="text-center">이미지</th>
                <th class="text-center">사은품명</th>
                <th class="text-center">사은품 지급조건명</th>
                <th class="text-center">수량</th>
            </tr>
            <?php
            $total = 0;
            foreach ($data['gift'] as $key => $val) {
                $total += $val['giveCnt'];
                ?>
                <tr class="text-center">
                    <td><?= html_entity_decode($val['imageUrl']); ?></td>
                    <td><?= $val['giftNm']; ?></td>
                    <td><?= $val['presentTitle']; ?></td>
                    <td><?= number_format($val['giveCnt']); ?></td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <th colspan="3" class="text-right">합계 수량</th>
                <th class="text-center"><?=number_format($total)?></th>
            </tr>
            </tbody>
        </table>
        <?php
    }
    ?>
    <?php if($data['orderChannelFl'] == 'naverpay'){?>
        <div class="notice-danger">
            환불/반품/교환 상세정보는 네이버페이센터에서 확인하기기 바랍니다.
            <a href="https://admin.pay.naver.com/" target="_blank">네이버페이센터 바로가기></a></div>
        <?php if($showNaverPayReload){?>
            <div class="notice-info">
                주문에 노출되지 않는 상품이 있는 경우 상품주문번호 조회를 해주세요. [
                <a href="javascript:void(0)" class="js-btn-naverpay-reload">네이버페이 상품주문번호 조회</a>]
            </div>
        <?php }?>
        <div style="height:50px"></div>
    <?php }?>
    <div class="table-title gd-help-manual">클래임 접수</div>
    <table class="table table-cols" id="orderStatusEach">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <thead>
        <tr>
            <th colspan="2" class="text-left">
                <?php if (!$isProvider) { ?>
                    <label class="radio-inline<?= gd_array_is_empty($data['claimGoods']['cancel']) ? ' disabled' : '' ?>">
                        <input type="radio" name="bundle[methodType]" value="cancel" <?= gd_array_is_empty($data['claimGoods']['cancel']) ? 'disabled="disabled"' : '' ?>/> 취소처리
                    </label>
                <?php } ?>
                <label class="radio-inline<?= gd_array_is_empty($data['claimGoods']['exchange']) ? ' disabled' : '' ?>">
                    <input type="radio" name="bundle[methodType]" value="exchange" <?= gd_array_is_empty($data['claimGoods']['exchange']) ? 'disabled="disabled"' : '' ?>/> 교환접수
                </label>
                <label class="radio-inline<?= gd_array_is_empty($data['claimGoods']['back']) ? ' disabled' : '' ?>">
                    <input type="radio" name="bundle[methodType]" value="back" <?= gd_array_is_empty($data['claimGoods']['back']) ? 'disabled="disabled"' : '' ?>/> 반품접수
                </label>
                <label class="radio-inline<?= gd_array_is_empty($data['claimGoods']['refund']) ? ' disabled' : '' ?>">
                    <input type="radio" name="bundle[methodType]" value="refund" <?= gd_array_is_empty($data['claimGoods']['refund']) ? 'disabled="disabled"' : '' ?>/> 환불접수
                </label>
                <button type="button" class="btn btn-sm btn-black js-no-method-type mgl10">초기화</button>
                <?php if ($data['mallSno'] > DEFAULT_MALL_NUMBER) { ?>
                    <span class="notice-danger" style="font-size:12px;">멀티상점 주문의 경우 PG부분 결제취소가 불가하므로 전체 반품/환불처리만 가능합니다.</span>
                <?php } ?>
                <?php if($data['orderChannelFl'] == 'naverpay') {?>
                    <?php if($data['statusMode'] == 'o') {?>
                        <span class="notice-danger">입금대기상태의 네이버페이 주문은 클래임 접수/주문상태변경이 불가능합니다.</span>
                    <?php }else if($data['statusMode'] == 'd') {?>
                        <span class="notice-danger">배송중/배송완료 상태의 네이버페이 주문은 클래임 중 반품접수만 가능합니다.</span>
                    <?php }else if($data['statusMode'] == 's') {?>
                        <span class="notice-danger">구매확정 상태의 네이버페이 주문은 클래임 접수/주문상태변경이 불가능합니다.</span>
                    <?php }?>
                <?php } elseif ($data['orderChannelFl'] == 'payco') {?>
                    <span class="payco-notice notice-danger display-none">페이코를 통한 바로이체 결제건의 부분취소는, 주문취소 상태만 연동되며 실제환불은 별도로 구매자에게 지급하셔야 합니다.</span>
                <?php }?>
            </th>
        </tr>
        </thead>
        <?php if (!gd_array_is_empty($data['claimGoods']['cancel']) && !$isProvider) { ?>
            <!-- 클래임접수 취소처리 -->
            <tbody id="orderStatusEach-cancel" class="display-none">
            <tr>
                <th>상품선택</th>
                <td>
                    <?php
                    $claimMode = 'cancel';
                    include $layoutOrderGoodsList;
                    ?>
                </td>
            </tr>
            <tr>
                <th>취소처리</th>
                <td>
                    <table class="table table-cols mgb10">
                        <colgroup>
                            <col class="width-sm"/>
                            <col class="width-2xl"/>
                            <col class="width-sm"/>
                            <col/>
                            <col class="width-sm"/>
                            <col class="width-lg"/>
                        </colgroup>
                        <tr>
                            <th>취소처리 상태</th>
                            <td>
                                <label>
                                    <?= gd_select_box(null, 'cancel[orderStatus]', $order->getOrderStatusList('c', null, ['c1','c4']), null, null, '=취소 종류 선택='); ?>
                                </label>
                            </td>
                            <th>취소사유</th>
                            <td>
                                <div class="form-inline">
                                    <?= gd_select_box(null, 'cancel[handleReason]', $refundReason, null, null, null); ?>
                                </div>
                            </td>
                            <th>처리 담당자</th>
                            <td><?= Session::get('manager.managerNm'); ?> (<?= Session::get('manager.managerId'); ?>)</td>
                        </tr>
                        <tr>
                            <th>상세사유</th>
                            <td colspan="5">
                                <textarea class="form-control" name="cancel[handleDetailReason]"></textarea>
                            </td>
                        </tr>
                    </table>
                    <p class="notice-info">취소처리된 주문은 <a href="order_list_cancel.php">[취소/교환/반품/환불 관리 > 취소 리스트]</a>에서 확인할 수 있습니다.</p>
                </td>
            </tr>
            </tbody>
            <!-- // 클래임접수 취소처리 -->
        <?php } ?>

        <?php if (!gd_array_is_empty($data['claimGoods']['refund'])) {
            ?>
            <!-- 클래임접수 환불처리 -->
            <tbody id="orderStatusEach-refund" class="display-none">
            <tr>
                <th>상품선택</th>
                <td>
                    <?php
                    $claimMode = 'refund';
                    include $layoutOrderGoodsList;
                    ?>
                </td>
            </tr>
            <tr>
                <th>환불처리</th>
                <td>
                    <table class="table table-cols mgb10">
                        <colgroup>
                            <col class="width-sm"/>
                            <col class="width-2xl"/>
                            <col class="width-sm"/>
                            <col/>
                            <col class="width-sm"/>
                            <col class="width-lg"/>
                        </colgroup>
                        <tr>
                            <th>환불사유</th>
                            <td class="form-inline">
                                <?= gd_select_box(null, 'refund[handleReason]', $refundReason, null, null, null); ?>
                            </td>
                            <th>환불수단</th>
                            <td class="form-inline">
                                <?= gd_select_box('refundMethod', 'refund[refundMethod]', $refundMethod, null, null, null,$claimElementDisabled); ?>
                            </td>
                            <th>처리 담당자</th>
                            <td><?= Session::get('manager.managerNm'); ?> (<?= Session::get('manager.managerId'); ?>)</td>
                        </tr>
                        <tr>
                            <th>상세사유</th>
                            <td colspan="5">
                                <textarea class="form-control" name="refund[handleDetailReason]" maxlength="500" <?=$claimElementDisabled?>></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>환불 계좌정보</th>
                            <td class="form-inline" colspan="5">
                                <?= gd_select_box(null, 'refund[refundBankName]', $bankNm, null, null, '= 은행 선택 =',$claimElementDisabled); ?>
                                <label class="control-label">계좌번호 :</label>
                                <input type="text" name="refund[refundAccountNumber]" <?=$claimElementDisabled?> value="" class="form-control width-lg js-number" maxlength="30"/>
                                <label class="control-label">예금주 :</label>
                                <input type="text" name="refund[refundDepositor]" <?=$claimElementDisabled?> value="<?= gd_isset($data['orderName']); ?>" class="form-control width-2xs"/>
                            </td>
                        </tr>
                    </table>
                    <?php if (empty($data['isDefaultMall']) === true) { ?>
                        <p class="notice-danger">멀티상점 주문의 경우 PG 부분 결제취소가 불가하므로 전체 반품/환불처리만 가능합니다.</p>
                    <?php } ?>
                    <p class="notice-info">환불접수된 주문은 <a href="order_list_refund.php">[취소/교환/반품/환불 관리 > 환불 리스트]</a>에서 확인할 수 있습니다.</p>
                    <p class="payco-notice-msg notice-danger display-none">페이코 결제에 대해 "기타 환불"을 선택하시면, 환불 처리 연동되지 않으므로 실제환불은 별도로 구매자에게 지급하셔야 합니다.</p>
                </td>
            </tr>
            </tbody>
            <!-- // 클래임접수 환불처리 -->
        <?php } ?>

        <?php if (!gd_array_is_empty($data['claimGoods']['back'])) { ?>
            <!-- 클래임접수 반품처리 -->
            <tbody id="orderStatusEach-back" class="display-none">
            <tr>
                <th>상품선택</th>
                <td>
                    <?php
                    $claimMode = 'back';
                    include $layoutOrderGoodsList;
                    ?>
                </td>
            </tr>
            <tr>
                <th>반품처리</th>
                <td>
                    <table class="table table-cols mgb10">
                        <colgroup>
                            <col class="width-sm"/>
                            <col class="width-2xl"/>
                            <col class="width-sm"/>
                            <col/>
                            <col class="width-sm"/>
                            <col class="width-lg"/>
                        </colgroup>
                        <tr>
                            <th>반품사유</th>
                            <td class="form-inline">
                                <?= gd_select_box(null, 'back[handleReason]', $backReason, null, null, null); ?>
                            </td>
                            <th>환불수단</th>
                            <td>
                                <div class="form-inline">
                                    <?= gd_select_box(null, 'back[refundMethod]', $refundMethod, null, null, null,$claimElementDisabled); ?>
                                </div>
                            </td>
                            <th>처리 담당자</th>
                            <td><?= Session::get('manager.managerNm'); ?> (<?= Session::get('manager.managerId'); ?>)</td>
                        </tr>
                        <tr>
                            <th>상세사유</th>
                            <td colspan="5">
                                <textarea class="form-control" name="back[handleDetailReason]" <?=$claimElementDisabled?>></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>환불 계좌정보</th>
                            <td class="form-inline" colspan="5">
                                <?= gd_select_box(null, 'back[refundBankName]', $bankNm, null, null, '= 은행 선택 =',$claimElementDisabled); ?>
                                <label class="control-label">계좌번호 :</label>
                                <input type="text" name="back[refundAccountNumber]" value="" class="form-control width-md" placeholder="계좌번호" <?=$claimElementDisabled?>/>
                                <label class="control-label">예금주 : </label>
                                <input type="text" name="back[refundDepositor]" value="" class="form-control width-2xs" <?=$claimElementDisabled?>/>
                            </td>
                        </tr>
                    </table>
                    <?php if (empty($data['isDefaultMall']) === true) { ?>
                        <p class="notice-danger">멀티상점 주문의 경우 PG 부분 결제취소가 불가하므로 전체 반품/환불처리만 가능합니다.</p>
                    <?php } ?>
                    <p class="notice-info">반품접수된 주문은 <a href="order_list_back.php">[취소/교환/반품/환불 관리 > 반품 리스트]</a>에서 확인할 수 있습니다.</p>
                </td>
            </tr>
            </tbody>
            <!-- // 클래임접수 반품처리 -->
        <?php } ?>

        <?php if (!gd_array_is_empty($data['claimGoods']['exchange'])) { ?>
            <!-- 클래임접수 교환처리 -->
            <tbody id="orderStatusEach-exchange" class="display-none">
            <tr>
                <th>상품선택</th>
                <td>
                    <?php
                    $claimMode = 'exchange';
                    include $layoutOrderGoodsList;
                    ?>
                </td>
            </tr>
            <tr>
                <th>교환처리</th>
                <td>
                    <table class="table table-cols mgb10">
                        <colgroup>
                            <col class="width-sm"/>
                            <col class="width-2xl"/>
                            <col class="width-sm"/>
                            <col/>
                        </colgroup>
                        <tr>
                            <th>교환사유</th>
                            <td class="input_area">
                                <div class="form-inline">
                                    <?= gd_select_box(null, 'exchange[handleReason]', $refundReason, null, null, null); ?>
                                </div>
                            </td>
                            <th>처리 담당자</th>
                            <td><?= Session::get('manager.managerNm'); ?> (<?= Session::get('manager.managerId'); ?>)</td>
                        </tr>
                        <tr>
                            <th>상세사유</th>
                            <td colspan="3">
                                <textarea class="form-control" name="exchange[handleDetailReason]"></textarea>
                            </td>
                        </tr>
                    </table>
                    <p class="notice-info">교환접수된 주문은 <a href="order_list_exchange.php">[취소/교환/반품/환불 관리 > 교환 리스트]</a>에서 확인할 수 있습니다.</p>
                </td>
            </tr>
            </tbody>
            <!-- // 클래임접수 교환처리 -->
        <?php } ?>
    </table>

    <div class="table-title gd-help-manual">클래임 정보</div>
    <div id="tabClaimStatus">
        <ul class="nav nav-tabs mgb30" role="tablist">
            <?php if (!$isProvider) { ?>
                <li role="presentation" class="<?=$data['claimGoods']['active'] == 'cancel' ? 'active' : ''?>">
                    <a href="#tab-claim-cancel" data-toggle="tab">취소정보 (<strong><?=number_format($data['claimGoods']['cancelcnt']['orderGoodsCnt'])?></strong> 건)</strong></a>
                </li>
            <?php } ?>
            <li role="presentation" class="<?=$data['claimGoods']['active'] == 'exchange' ? 'active' : ''?>">
                <a href="#tab-claim-exchange" data-toggle="tab">교환정보 (<strong><?=number_format($data['claimGoods']['exchangecnt']['orderGoodsCnt'])?></strong> 건)</strong></a>
            </li>
            <li role="presentation" class="<?=$data['claimGoods']['active'] == 'back' ? 'active' : ''?>">
                <a href="#tab-claim-back" data-toggle="tab">반품정보 (<strong><?=number_format($data['claimGoods']['backcnt']['orderGoodsCnt'])?></strong> 건)</strong></a>
            </li>
            <li role="presentation" class="<?=$data['claimGoods']['active'] == 'refund' ? 'active' : ''?>">
                <a href="#tab-claim-refund" data-toggle="tab">환불정보 (<strong><?=number_format($data['claimGoods']['refundcnt']['orderGoodsCnt'])?></strong> 건)</strong></a>
            </li>
        </ul>
        <div class="tab-content loading">
            <?php if (!$isProvider) { ?>
                <div role="tab-claim-cancel" class="tab-pane <?=$data['claimGoods']['active'] == 'cancel' ? 'in active' : ''?>" id="tab-claim-cancel"></div>
            <?php } ?>
            <div role="tab-claim-exchange" class="tab-pane <?=$data['claimGoods']['active'] == 'exchange' ? 'in active' : ''?>" id="tab-claim-exchange"></div>
            <div role="tab-claim-back" class="tab-pane <?=$data['claimGoods']['active'] == 'back' ? 'in active' : ''?>" id="tab-claim-back"></div>
            <div role="tab-claim-refund" class="tab-pane <?=$data['claimGoods']['active'] == 'refund' ? 'in active' : ''?>" id="tab-claim-refund"></div>
        </div>
    </div>

    <?php
    // 에스크로 배송등록
    if (gd_isset($settle['prefix']) == 'e' && in_array($data['statusMode'], $order->statusReceiptPossible) && $pgEscrowConf['delivery'] == 'y') {
        ?>
        <div class="table-title gd-help-manual">에스크로 진행 안내</div>
        <?php
        // 에스크로 구매확인 여부
        if (empty($data['escrowConfirmFl']) === true) {
            ?>
            <table class="table table-cols">
                <colgroup>
                    <col class="width-md"/>
                    <col/>
                </colgroup>
                <tr>
                    <th>에스크로 안내</th>
                    <td class="input_area note">
                        <?php
                        if ($data['escrowDeliveryFl'] == 'n') {
                            echo '<span class="notice-info"> 해당 주문은 에스크로 주문입니다. 배송처리시 자동으로 에스크로 배송등록이 됩니다.</span>';
                        } else {
                            echo '<span class="notice-info"> 에스크로 배송등록이 완료 되었습니다.</span>';
                        }
                        ?>
                    </td>
                </tr>
            </table>
            <?php
        } else {
            ?>
            <div class="table-title gd-help-manual">에스크로 구매결정</div>
            <div>
                <table class="table table-cols">
                    <colgroup>
                        <col class="width-md"/>
                        <col/>
                    </colgroup>
                    <tr>
                        <th>결정 여부</th>
                        <td class="input_area note">
                            <?php
                            if ($data['escrowConfirmFl'] == 'accept') {
                                echo '<span class="notice-info"> 에스크로 구매 결정이 승인 처리 되었습니다.</span>';
                            } else if ($data['escrowConfirmFl'] == 'reject') {
                                if ($data['escrowDenyFl'] == 'y') {
                                    echo '<span class="notice-info"> 에스크로 구매 거절 처리가 완료 되었습니다.</span>';
                                } else {
                                    echo '<span class="notice-info"> 고객이 에스크로 구매 거절을 하였습니다.</span>';
                                    if ($pgEscrowConf['deny'] == 'y') {
                                        echo '<span class="notice-danger notice-info"> 아래 &quot;에스크로 거절확인&quot;을 눌러 주세요. &quot;에스크로 거절확인&quot; 이후 배송 완료 처리 또는 반품 처리를 하시기 바랍니다.</span>';
                                    } else {
                                        echo '<span class="notice-danger notice-info"> PG 관리자 모드에서 처리하시기 바랍니다.</span>';
                                    }
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <?php if ($data['escrowConfirmFl'] == 'reject' && $data['escrowDenyFl'] != 'y' && $pgEscrowConf['deny'] == 'y') { ?>
                        <tr>
                            <th>에스크로 거절확인</th>
                            <td><input type="button" onclick="escrow_deny_register();" value="에스크로 거절확인"  class="btn btn-sm btn-gray"/></span></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
            <?php
        }
    }
    ?>

    <div class="row">
        <?php if (!$isProvider) { ?>
            <div class="col-xs-6">
                <div class="table-title gd-help-manual">
                    최초 결제정보
                    <?php if (empty($data['isDefaultMall']) === true) { ?>
                        <div class="pull-right"><small>(환율: <?=$data['currencyIsoCode']?> <?=$data['exchangeRate']?>)</small></div>
                    <?php } ?>
                </div>
                <div>
                    <table class="table table-cols table-toggle">
                        <colgroup>
                            <col class="width-md"/>
                            <col/>
                        </colgroup>
                        <tr>
                            <th>상품 판매금액</th>
                            <td class="text-right">
                                <strong>
                                    <?= gd_currency_display(gd_isset($data['totalGoodsPrice'])); ?>
                                    <?php if (empty($data['isDefaultMall']) === true) { ?>
                                        (<?= gd_global_order_currency_display(gd_isset($data['totalGoodsPrice']), $data['exchangeRate'], $data['currencyPolicy']); ?>)
                                    <?php } ?>
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <button type="button" class="btn btn-xs btn-link js-pay-toggle" data-number="<?= $data['totalDeliveryCharge']?>" data-target="toggleDelivery">보기</button>
                                총 배송비
                            </th>
                            <th class="th">
                                <div class="text-primary">
                                    (+) <?= gd_currency_display(gd_isset($data['totalDeliveryCharge'])); ?>
                                    <?php if (empty($data['isDefaultMall']) === true) { ?>
                                        (<?= gd_global_order_currency_display(gd_isset($data['totalDeliveryCharge']), $data['exchangeRate'], $data['currencyPolicy']); ?>)
                                    <?php } ?>
                                </div>
                            </th>
                        </tr>
                        <tr id="toggleDelivery">
                            <th style="display: none;"></th>
                            <td class="th" style="display: none;">
                                <ul class="list-unstyled">
                                    <?php if (empty($data['isDefaultMall']) === true) { ?>
                                        <li>
                                            <strong>총 무게</strong>
                                            <span>
                                            <?= number_format(gd_isset($data['deliveryWeightInfo']['total']), 2); ?>kg
                                            (상품<?= number_format(gd_isset($data['deliveryWeightInfo']['goods']), 2); ?>kg +
                                             박스<?= number_format(gd_isset($data['deliveryWeightInfo']['box']), 2); ?>kg)
                                        </span>
                                        </li>
                                    <?php } ?>
                                    <li>
                                        <strong>배송비</strong>
                                        <span>
                                            <?= gd_currency_display(gd_isset($data['totalDeliveryPolicyCharge'])); ?>
                                            <?php if (empty($data['isDefaultMall']) === true) { ?>
                                                (<?= gd_global_order_currency_display(gd_isset($data['totalDeliveryPolicyCharge']), $data['exchangeRate'], $data['currencyPolicy']); ?>)
                                            <?php } ?>
                                        </span>
                                    </li>
                                    <li>
                                        <strong>지역별 배송비</strong>
                                        <span>
                                            <?= gd_currency_display(gd_isset($data['totalDeliveryAreaCharge'])); ?>
                                            <?php if (empty($data['isDefaultMall']) === true) { ?>
                                                (<?= gd_global_order_currency_display(gd_isset($data['totalDeliveryAreaCharge']), $data['exchangeRate'], $data['currencyPolicy']); ?>)
                                            <?php } ?>
                                        </span>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <button type="button" class="btn btn-sm btn-link js-pay-toggle" data-number="<?= ($data['totalGoodsDcPrice'] + $data['totalMemberDcPrice'] + $data['totalMemberOverlapDcPrice'] + $data['totalCouponOrderDcPrice'] + $data['totalCouponGoodsDcPrice'] + $data['totalCouponDeliveryDcPrice'] + $data['totalMemberDeliveryDcPrice'])?>" data-target="toggleDcPrice">보기</button>
                                총 할인금액
                            </th>
                            <th class="th">
                                <?php if ($data['totalCouponOrderDcPrice'] + $data['totalCouponGoodsDcPrice'] + $data['totalCouponDeliveryDcPrice'] + $data['totalCouponGoodsMileage'] + $data['totalCouponOrderMileage'] > 0) { ?>
                                    <div class="pull-left" style="margin-top:1px;">
                                        <input type="button" value="쿠폰 정보 보기" class="btn btn-sm btn-gray js-order-coupon" />
                                    </div>
                                <?php } ?>
                                <div class="text-danger">
                                    (-) <?= gd_currency_display($data['totalGoodsDcPrice'] + $data['totalMemberDcPrice'] + $data['totalMemberOverlapDcPrice'] + $data['totalCouponOrderDcPrice'] + $data['totalCouponGoodsDcPrice'] + $data['totalCouponDeliveryDcPrice'] + $data['totalMemberDeliveryDcPrice']); ?>
                                    <?php
                                    if (empty($data['isDefaultMall']) === true) {
                                        $tmptotlaDcPrice = $data['totalGoodsDcPrice'] + $data['totalMemberDcPrice'] + $data['totalMemberOverlapDcPrice'] + $data['totalCouponOrderDcPrice'] + $data['totalCouponGoodsDcPrice'] + $data['totalCouponDeliveryDcPrice'];
                                        ?>
                                        (<?= gd_global_order_currency_display(gd_isset($tmptotlaDcPrice), $data['exchangeRate'], $data['currencyPolicy']); ?>)
                                    <?php } ?>
                                </div>
                            </th>
                        </tr>
                        <tr id="toggleDcPrice">
                            <th style="display: none;"></th>
                            <td class="th" style="display: none;">
                                <ul class="list-unstyled">
                                    <li>
                                        <strong>상품할인</strong>
                                        <span>
                                            <?= gd_currency_display(gd_isset($data['totalGoodsDcPrice'])); ?>
                                            <?php if (empty($data['isDefaultMall']) === true) { ?>
                                                (<?= gd_global_order_currency_display(gd_isset($data['totalGoodsDcPrice']), $data['exchangeRate'], $data['currencyPolicy']); ?>)
                                            <?php } ?>
                                        </span>
                                    </li>
                                    <li>
                                        <strong>회원할인(상품)</strong>
                                        <span>
                                            <?= gd_currency_display($data['totalMemberDcPrice'] + $data['totalMemberOverlapDcPrice']); ?>
                                            <?php if (empty($data['isDefaultMall']) === true) { ?>
                                                (<?= gd_global_order_currency_display(($data['totalMemberDcPrice'] + $data['totalMemberOverlapDcPrice']), $data['exchangeRate'], $data['currencyPolicy']); ?>)
                                            <?php } ?>
                                        </span>
                                    </li>
                                    <li>
                                        <strong>회원할인(배송비)</strong>
                                        <span>
                                            <?= gd_currency_display($data['totalMemberDeliveryDcPrice']); ?>
                                            <?php if (empty($data['isDefaultMall']) === true) { ?>
                                                (<?= gd_global_order_currency_display(gd_isset($data['totalMemberDeliveryDcPrice']), $data['exchangeRate'], $data['currencyPolicy']); ?>)
                                            <?php } ?>
                                        </span>
                                    </li>
                                    <li>
                                        <strong>쿠폰할인(상품)</strong>
                                        <span>
                                            <?= gd_currency_display($data['totalCouponGoodsDcPrice']); ?>
                                            <?php if (empty($data['isDefaultMall']) === true) { ?>
                                                (<?= gd_global_order_currency_display(gd_isset($data['totalCouponGoodsDcPrice']), $data['exchangeRate'], $data['currencyPolicy']); ?>)
                                            <?php } ?>
                                        </span>
                                    </li>
                                    <li>
                                        <strong>쿠폰할인(주문)</strong>
                                        <span>
                                            <?= gd_currency_display($data['totalCouponOrderDcPrice']); ?>
                                            <?php if (empty($data['isDefaultMall']) === true) { ?>
                                                (<?= gd_global_order_currency_display(gd_isset($data['totalCouponOrderDcPrice']), $data['exchangeRate'], $data['currencyPolicy']); ?>)
                                            <?php } ?>
                                        </span>
                                    </li>
                                    <li>
                                        <strong>쿠폰할인(배송비)</strong>
                                        <span>
                                            <?= gd_currency_display(gd_isset($data['totalCouponDeliveryDcPrice'])); ?>
                                            <?php if (empty($data['isDefaultMall']) === true) { ?>
                                                (<?= gd_global_order_currency_display(gd_isset($data['totalCouponDeliveryDcPrice']), $data['exchangeRate'], $data['currencyPolicy']); ?>)
                                            <?php } ?>
                                        </span>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <button type="button" class="btn btn-sm btn-link js-pay-toggle" data-number="<?=($data['useDeposit'] + $data['useMileage'])?>" data-target="toggleAddPrice">보기</button>
                                총 부가결제금액
                            </th>
                            <th class="th">
                                <div class="text-danger">
                                    (-) <?= gd_currency_display($data['useDeposit'] + $data['useMileage']); ?>
                                    <?php if (empty($data['isDefaultMall']) === true) { ?>
                                        (<?= gd_global_order_currency_display(($data['useDeposit'] + $data['useMileage']), $data['exchangeRate'], $data['currencyPolicy']); ?>)
                                    <?php } ?>
                                </div>
                            </th>
                        </tr>
                        <tr id="toggleAddPrice">
                            <th style="display: none;"></th>
                            <td class="th" style="display: none;">
                                <ul class="list-unstyled">
                                    <li>
                                        <strong><?= $depositUse['name'] ?></strong>
                                        <span>
                                            <?= gd_currency_display(gd_isset($data['useDeposit'])); ?>
                                            <?php if (empty($data['isDefaultMall']) === true) { ?>
                                                (<?= gd_global_order_currency_display(gd_isset($data['useDeposit']), $data['exchangeRate'], $data['currencyPolicy']); ?>)
                                            <?php } ?>
                                        </span>
                                    </li>
                                    <li>
                                        <strong><?= $mileageUse['name'] ?></strong>
                                        <span>
                                            <?= gd_currency_display(gd_isset($data['useMileage'])); ?>
                                            <?php if (empty($data['isDefaultMall']) === true) { ?>
                                                (<?= gd_global_order_currency_display(gd_isset($data['useMileage']), $data['exchangeRate'], $data['currencyPolicy']); ?>)
                                            <?php } ?>
                                        </span>
                                    </li>
                                </ul>
                            </td>
                        </tr>

                        <?php if($data['orderChannelFl'] == 'naverpay') {?>
                            <tr>
                                <th>네이버페이 할인금액</th>
                                <td class="text-right">
                                    <?=$data['naverpay']['discountInfo']?>
                                </td>
                            </tr>
                        <?php }?>

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
                            <th>실 결제금액</th>
                            <td class="text-right">
                                <?php if($data['orderChannelFl'] == 'naverpay') {?>
                                    <?=$data['naverpay']['priceInfo']?><br>
                                    <strong><?= gd_currency_display(($data['checkoutData']['orderData']['GeneralPaymentAmount'])); ?></strong>
                                <?php }
                                else {?>
                                    <strong>
                                        <?= gd_currency_display(gd_isset($data['settlePrice'])); ?>
                                        <?php if (empty($data['isDefaultMall']) === true) { ?>
                                            (<?= gd_global_order_currency_display(gd_isset($data['settlePrice']), $data['exchangeRate'], $data['currencyPolicy']); ?>)
                                        <?php } ?>
                                    </strong>
                                <?php }?>
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
                            <th>
                                <button type="button" class="btn btn-sm btn-link js-pay-toggle" data-number="<?= $data['totalMileage']?>" data-target="toggleSavingPrice">보기</button>
                                총 적립금액
                            </th>
                            <td class="th">
                                <div class="text-success"><?= number_format($data['totalMileage']); ?><?=$mileageUse['unit']?></div>
                            </td>
                        </tr>
                        <tr id="toggleSavingPrice">
                            <th style="display: none;"></th>
                            <td class="th" style="display: none;">
                                <ul class="list-unstyled">
                                    <?php if (gd_isset($data['totalGoodsMileage']) > 0) { ?>
                                        <li><strong>상품 <?= $mileageUse['name'] ?></strong><span><?= number_format($data['totalGoodsMileage']); ?><?=$mileageUse['unit']?></span></li>
                                    <?php } ?>
                                    <?php if (gd_isset($data['totalMemberMileage']) > 0) { ?>
                                        <li><strong>회원 <?= $mileageUse['name'] ?></strong><span><?= number_format($data['totalMemberMileage']); ?><?=$mileageUse['unit']?></span></li>
                                    <?php } ?>
                                    <?php if (gd_isset($data['totalCouponOrderMileage'], 0) + gd_isset($data['totalCouponGoodsMileage'], 0) > 0) { ?>
                                        <li><strong>쿠폰 <?= $mileageUse['name'] ?></strong><span><?= number_format($data['totalCouponOrderMileage'] + $data['totalCouponGoodsMileage']); ?><?=$mileageUse['unit']?></span></li>
                                    <?php } ?>
                                    <?php if (gd_isset($data['mileageGiveExclude']) == 'n' && $data['useMileage'] > 0) { ?>
                                        <div>※ <?= $mileageUse['name'] ?>을(를) 사용한 경우 적립이 되지 않습니다.</div>
                                    <?php } ?>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="table-title gd-help-manual">결제수단</div>
                <table class="table table-cols">
                    <colgroup>
                        <col class="width-md"/>
                        <col/>
                    </colgroup>
                    <tr>
                        <th>주문 채널</th>
                        <td>
                        <span class="text_emphasis">
                            <?=$data['orderChannelFl']?>
                        </span>
                        </td>
                    </tr>
                    <tr>
                        <th>결제 방법</th>
                        <td>
                        <span class="text_emphasis">

                            <?php if($data['orderChannelFl'] == 'naverpay'){
                                echo $data['checkoutData']['orderData']['PaymentMeans'];
                                if ($data['settleKind'] == 'fa') {
                                    echo  '(입금기한 : '.$data['checkoutData']['orderData']['PaymentDueDate'].')';
                                }
                            } else {?>
                                <?php if (gd_isset($settle['prefix']) == 'e') { ?>
                                    에스크로
                                <?php } ?>
                                <?php if (gd_isset($settle['prefix']) == 'f') { ?>
                                    간편결제
                                <?php } ?>
                                <?= gd_isset($settle['name']); ?>
                            <?php }?>
                        </span>
                        </td>
                    </tr>
                    <?php if (gd_isset($data['settleKind']) == 'gb') { ?>
                        <tr>
                            <th>입금계좌</th>
                            <td>
                                <span id="bankAccount"><?= str_replace(STR_DIVISION, ' / ', gd_isset($data['bankAccount'])); ?></span>
                                <input type="button" class="btn btn-sm btn-gray js-bank-change" value="입금 은행 변경"/>
                                <input type="hidden" name="order[bankAccount]" value="<?= gd_isset($data['bankAccount']); ?>"/>
                                <input type="hidden" name="order[bankSender]" value="<?= gd_isset($data['bankSender']); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th>입금자명</th>
                            <td><span id="bankSender"><?= gd_isset($data['bankSender']); ?></span></td>
                        </tr>
                    <?php } else { ?>
                        <?php if (empty($settle['settleReceipt']) === false) { ?>
                            <tr>
                                <th>전표 보기</th>
                                <td><input type="button" value="전표 보기" onclick="pg_receipt_view('<?= $settle['settleReceipt']; ?>','<?= $data['orderNo']; ?>');" class="btn btn-sm btn-gray"/></td>
                            </tr>
                        <?php } ?>
                        <?php if (gd_isset($settle['method']) == 'c') { ?>
                            <tr>
                                <th>카드사명</th>
                                <td><?= gd_isset($data['pgSettleNm'][0]); ?></td>
                            </tr>
                            <?php if (gd_isset($data['pgSettleCd'][0]) != '' && gd_isset($data['pgSettleCd'][0]) != '0' && gd_isset($data['pgSettleCd'][0]) != '00') { ?>
                                <tr>
                                    <th>할부개월</th>
                                    <td><?php if (gd_isset($data['pgSettleCd'][1]) == '1') { ?>무이자 <?php } ?><?= gd_isset($data['pgSettleCd'][0]); ?>개월</td>
                                </tr>
                            <?php } ?>
                        <?php } else if (gd_isset($settle['method']) == 'c') { ?>
                            <tr>
                                <th>이체은행</th>
                                <td><?= gd_isset($data['pgSettleNm'][0]); ?></td>
                            </tr>
                        <?php } else if (gd_isset($settle['method']) == 'v') { ?>
                            <tr>
                                <th>입금계좌</th>
                                <td><?= gd_isset($data['pgSettleNm'][0]); ?> / <?= gd_isset($data['pgSettleNm'][1]); ?> / <?= gd_isset($data['pgSettleNm'][2]); ?></td>
                            </tr>
                            <tr>
                                <th>입금기한</th>
                                <td><?= gd_isset($data['pgSettleCd'][0]); ?></td>
                            </tr>
                        <?php } else if (gd_isset($settle['method']) == 'h') { ?>
                            <tr>
                                <th>통신사</th>
                                <td><?= gd_isset($data['pgSettleNm'][0]); ?></td>
                            </tr>
                            <?php if (empty($data['pgSettleCd'][0]) === false) { ?>
                                <tr>
                                    <th>휴대폰번호</th>
                                    <td><?= gd_isset($data['pgSettleCd'][0]); ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                    <tr>
                        <th>주문확인일</th>
                        <td><span class="font-date"><?= gd_isset($data['regDt']); ?></span></td>
                    </tr>
                    <tr>
                        <th>결제확인일</th>
                        <td><span class="font-date"><?= gd_isset($data['paymentDt']); ?></span></td>
                    </tr>
                    <tr>
                        <th>현금영수증 신청여부</th>
                        <td class="form-inline">
                            <?php
                            if (gd_isset($data['receiptFl']) == 'n') {
                                if ($receipt['cashFl'] === 'n' && $receipt['taxFl'] === 'n') {
                                    echo '<span class="notice-danger">신청불가</span>';
                                } else {
                                    if ($receipt['cashFl'] === 'y') {
                                        if ($receipt['periodFl'] === 'y') {
                                            echo '<input type="button" class="btn btn-sm btn-gray" onclick="cash_receipt_register();" value="현금영수증 신청"/>';
                                        } else {
                                            echo '<span class="notice-info">현금영수증 발급불가 (결제완료 후 ' . $receipt['periodDay'] . ' 일 이내)</span>';
                                        }
                                    } else {
                                        echo '<span class="notice-info">현금영수증 사용안함</span>';
                                    }
                                }
                            }
                            // 현금영수증인 경우
                            else if (gd_isset($data['receiptFl']) == 'r') {
                                // 현금영수증 신청 정보
                                if ($data['cash']['statusFl'] == 'r') {
                                    echo '<input type="button" class="btn btn-sm btn-gray" onclick="cash_receipt_process(\'' . $data['orderNo'] . '\');" value="현금영수증 신청 정보"/>';
                                }
                                // 현금영수증 영수증 보기
                                else {
                                    echo '<input type="button" class="btn btn-sm btn-gray" onclick="pg_receipt_view(\'cash\', \'' . $data['orderNo'] . '\');" value="현금영수증 보기"/>';
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>세금계산서 신청여부</th>
                        <td class="form-inline">
                            <?php
                            if (gd_isset($data['receiptFl']) == 'n') {
                                if ($receipt['cashFl'] === 'n' && $receipt['taxFl'] === 'n') {
                                    echo '<span class="notice-danger">신청불가</span>';
                                } else {
                                    if ($receipt['taxFl'] === 'y') {
                                        echo '<input type="button" class="btn btn-sm btn-gray" onclick="tax_invoice_register();" value="세금계산서 신청"/>';
                                    } else {
                                        echo '<span class="notice-info">세금계산서 사용안함</span>';
                                    }
                                }
                            }
                            // 세금계산서인 경우
                            else if (gd_isset($data['receiptFl']) == 't') {
                                // 신청 단계인경우
                                if (gd_isset($data['tax']['statusFl']) == 'r') {
                                    ?>
                                    <input type="button" value="세금계산서 수정" class="btn btn-sm btn-red" onclick="tax_invoice_register('modify');"/>
                                    <?php
                                }
                                if (gd_isset($data['tax']['statusFl']) == 'y') {
                                    if ($taxInfo['godobill'] == 'y' && empty($data['tax']['godobillCd']) === false) {
                                        ?>
                                        고도빌 발급
                                        <?php
                                    } else {
                                        ?>
                                        <input type="button" value="세금계산서 출력" onclick="order_print_popup('taxInvoice', 'frmOrderPrint', 'frmOrderPrint', 'orderNo', <?=$isProvider ? 'true' : 'false'?>);"class="btn btn-gray btn-sm"/>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        <?php } else { ?>
        <div class="col-xs-12">
            <div class="table-title gd-help-manual">결제정보</div>
            <div>
                <table class="table table-cols">
                    <colgroup>
                        <col class="width-md"/>
                        <col class="width-md"/>
                        <col/>
                    </colgroup>
                    <tr>
                        <th>상품 판매금액</th>
                        <td class="text-right">
                            <strong><?= gd_currency_display(gd_isset($data['totalGoodsPrice'])); ?></strong>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>배송비</th>
                        <td class="text-right text-primary">
                            (+) <?= gd_currency_display(gd_isset($data['totalDeliveryPolicyCharge'])); ?>
                        </td>
                        <td class="input_area info_note" rowspan="2">
                            <?php
                            if (isset($data['delivery']) === true) {
                                $deliveryCharge = 0;
                                $deliveryGoodsCharge = 0;
                                if (empty($data['delivery']) === false) {
                                    foreach ($data['delivery'] as $key => $val) {
                                        echo '<div>● [' . $key . ']</div>';
                                        if ($val['deliveryConfFl'] == 'y') {
                                            echo '<div>' . nl2br($val['deliveryConfLog']) . '</div>';
                                        }
                                        if ($val['deliveryGoodsFl'] == 'y') {
                                            echo '<div>' . nl2br($val['deliveryGoodsLog']) . '</div>';
                                        }
                                        if ($val['deliveryFreeFl'] == 'y') {
                                            echo '<div>' . nl2br($val['deliveryFreeLog']) . '</div>';
                                        }
                                        if ($val['deliveryCollectFl'] == 'y') {
                                            echo '<div>' . nl2br($val['deliveryCollectLog']) . '</div>';
                                        }
                                    }
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>지역별 배송비</th>
                        <td class="text-right text-primary">
                            (+) <?= gd_currency_display(gd_isset($data['totalDeliveryAreaCharge'])); ?>
                        </td>
                    </tr>
                    <tr>
                        <th>합계금액</th>
                        <td class="text-right">
                            <strong><?= gd_currency_display($data['totalGoodsPrice'] + $data['totalDeliveryPolicyCharge'] + $data['totalDeliveryAreaCharge']); ?></strong>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
                <?php } ?>
            </div>

            <div class="row">
                <div class="col-xs-6">
                    <div class="table-title gd-help-manual">주문자 정보</div>
                    <input type="hidden" name="info[sno]" value="<?= gd_isset($data['infoSno']); ?>" class="form-control width100"/>
                    <table class="table table-cols">
                        <colgroup>
                            <col class="width-md"/>
                            <col/>
                        </colgroup>
                        <tr>
                            <th>주문자명</th>
                            <td>
                                <span class="text-primary"><?= gd_isset($data['orderName']); ?></span>
                                <?php if (empty($memInfo) === true) { ?>
                                    <?php if (empty($data['memNo']) === true) { ?>
                                        / <span class="text-primary">비회원</span>
                                    <?php } else { ?>
                                        / <span class="text-primary">탈퇴회원</span>
                                    <?php } ?>
                                <?php } else { ?>
                                    / <span class="text-primary"><?= $memInfo['memId'] ?></span>
                                    / <span class="text-primary"><?= $memInfo['groupNm'] ?></span>
                                    <?php if (!$isProvider) { ?>
                                        <button type="button" class="btn btn-sm btn-gray js-layer-crm" data-member-no="<?= $data['memNo'] ?>">CRM 보기</button>
                                    <?php } ?>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th>구매자 IP</th>
                            <td><span class="font-num"><?= gd_isset($data['orderIp']); ?></span></td>
                        </tr>
                        <tr>
                            <th>전화번호</th>
                            <td><span class="font-num"><?= gd_isset($data['orderPhone']); ?></span></td>
                        </tr>
                        <tr>
                            <th>휴대폰번호</th>
                            <td>
                                <?php if (empty($data['orderCellPhone']) === false) { ?>
                                    <?= gd_isset($data['orderCellPhone']) ?>
                                    <?php if (!$isProvider && empty($data['isDefaultMall']) === false) { ?>
                                        <a class="btn btn-sm btn-gray" onclick="member_sms('','<?= urlencode($data['orderName']); ?>','<?= $data['orderCellPhone']; ?>')">SMS 보내기</a>
                                    <?php } ?>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th>이메일</th>
                            <td><?= gd_isset($data['orderEmail']); ?></td>
                        </tr>
                        <?php if (empty($data['orderAddress']) === false) { ?>
                            <?php if (empty($data['isDefaultMall']) === true) { ?>
                                <tr>
                                    <th>주소</th>
                                    <td>
                                        <div>
                                            [<?= gd_isset($data['orderZonecode']); ?>]
                                        </div>
                                        <div>
                                            <?= gd_isset($data['orderAddressSub']); ?>,
                                            <?= gd_isset($data['orderAddress']); ?>,
                                            <?= gd_isset($data['orderState']); ?>,
                                            <?= gd_isset($data['orderCity']); ?>,
                                            <?= gd_isset($data['orderCountry']); ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <th>주소</th>
                                    <td>
                                        <div>
                                            [<?= gd_isset($data['orderZonecode']); ?>]
                                            <?php if (strlen($data['orderZipcode']) == 7) {
                                                echo '(' . gd_isset($data['orderZipcode']) . ')';
                                            } ?>
                                        </div>
                                        <div><?= gd_isset($data['orderAddress']); ?><?= gd_isset($data['orderAddressSub']); ?></div>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </table>
                </div>
                <div class="col-xs-6">
                    <div class="table-title gd-help-manual">수령자 정보</div>
                    <table class="table table-cols">
                        <colgroup>
                            <col class="width-md"/>
                            <col/>
                        </colgroup>
                        <tr>
                            <th>수령자명</th>
                            <td>
                                <input type="text" name="info[receiverName]" value="<?= gd_isset($data['receiverName']); ?>" class="form-control width-sm"/>
                            </td>
                        </tr>
                        <?php if (empty($data['isDefaultMall']) === true) { ?>
                            <!-- 멀티몰 수령지 정보 시작 -->
                            <tr>
                                <th>전화번호</th>
                                <td>
                                    <div class="form-inline">
                                        <?php if (empty($data['isDefaultMall']) === true) { ?>
                                            <p><?= gd_select_box('receiverPhonePrefixCode', 'info[receiverPhonePrefixCode]', $countryPhone, null, $data['receiverPhonePrefixCode'], null, null, 'form-control'); ?></p>
                                        <?php } ?>
                                        <input type="text" name="info[receiverPhone]" value="<?= gd_isset(implode("",$data['receiverPhone'])); ?>" maxlength="12" class="form-control js-number-only width-md"/>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>휴대폰번호</th>
                                <td>
                                    <div class="form-inline">
                                        <?php if (empty($data['isDefaultMall']) === true) { ?>
                                            <p><?= gd_select_box('receiverCellPhonePrefixCode', 'info[receiverCellPhonePrefixCode]', $countryPhone, null, $data['receiverCellPhonePrefixCode'], null, null, 'form-control '); ?></p>
                                            <input type="text" name="info[receiverCellPhone]" value="<?= gd_isset(implode("",$data['receiverCellPhone'])); ?>" maxlength="12" class="form-control js-number-only width-md"/>
                                        <?php } ?>
                                        <?php if (empty($data['receiverCellPhone'][1]) === false && empty($data['isDefaultMall']) === false) { ?>
                                            <a class="btn btn-sm btn-gray" onclick="member_sms('','<?= urlencode($data['receiverName']); ?>','<?= implode('-', $data['receiverCellPhone']); ?>')">SMS 보내기</a>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>주소</th>
                                <td>
                                    <div class="form-inline">
                                        <p><?= gd_select_box('receiverCountrycode', 'info[receiverCountrycode]', $countryAddress, null, $data['receiverCountryCode'], null, null, 'form-control'); ?></p>
                                        <input type="text" name="info[receiverZonecode]" value="<?= gd_isset($data['receiverZonecode']); ?>" size="5" class="form-control"/>
                                        <input type="hidden" name="info[receiverZipcode]" value="<?= gd_isset($data['receiverZipcode']); ?>"/>
                                    </div>
                                    <?php if (empty($data['isDefaultMall']) === true) { ?>
                                        <div class="mgt5">
                                            <input type="text" name="info[receiverCity]" value="<?= gd_isset($data['receiverCity']); ?>" class="form-control"/>
                                        </div>
                                        <div class="mgt5">
                                            <input type="text" name="info[receiverState]" value="<?= gd_isset($data['receiverState']); ?>" class="form-control"/>
                                        </div>
                                    <?php } ?>
                                    <div class="mgt5">
                                        <input type="text" name="info[receiverAddress]" value="<?= gd_isset($data['receiverAddress']); ?>" class="form-control"/>
                                    </div>
                                    <div class="mgt5">
                                        <input type="text" name="info[receiverAddressSub]" value="<?= gd_isset($data['receiverAddressSub']); ?>" class="form-control"/>
                                    </div>
                                </td>
                            </tr>
                            <!--// 멀티몰 수령지 정보 끝 -->
                        <?php } else { ?>
                            <!-- 기준몰 수령지 정보 시작 -->
                            <tr>
                                <th>전화번호</th>
                                <td>
                                    <div class="form-inline">
                                        <input type="text" name="info[receiverPhone]" value="<?= gd_isset(implode("",$data['receiverPhone'])); ?>" maxlength="12" class="form-control js-number-only width-md"/>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>휴대폰번호</th>
                                <td>
                                    <div class="form-inline">
                                        <input type="text" name="info[receiverCellPhone]" value="<?= gd_isset(implode("",$data['receiverCellPhone'])); ?>" maxlength="12" class="form-control js-number-only width-md"/>
                                        <?php if (empty($data['receiverCellPhone'][1]) === false) { ?>
                                            <a class="btn btn-sm btn-gray" onclick="member_sms('','<?= urlencode($data['receiverName']); ?>','<?= implode('-', $data['receiverCellPhone']); ?>')">SMS 보내기</a>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>주소</th>
                                <td>
                                    <div class="form-inline">
                                        <input type="text" name="info[receiverZonecode]" value="<?= gd_isset($data['receiverZonecode']); ?>" size="5" class="form-control"/>
                                        <input type="hidden" name="info[receiverZipcode]" value="<?= gd_isset($data['receiverZipcode']); ?>"/>
                                        <span id="inforeceiverZipcodeText" class="number <?php if (strlen($data['receiverZipcode']) != 7) {
                                            echo 'display-none';
                                        } ?>">(<?= $data['receiverZipcode']; ?>)</span>
                                        <input type="button" onclick="postcode_search('info[receiverZonecode]', 'info[receiverAddress]', 'info[receiverZipcode]');" value="우편번호찾기" class="btn btn-sm btn-gray"/>
                                    </div>
                                    <div class="mgt5">
                                        <input type="text" name="info[receiverAddress]" value="<?= gd_isset($data['receiverAddress']); ?>" class="form-control"/>
                                    </div>
                                    <div class="mgt5">
                                        <input type="text" name="info[receiverAddressSub]" value="<?= gd_isset($data['receiverAddressSub']); ?>" class="form-control"/>
                                    </div>
                                </td>
                            </tr>
                            <!--// 기준몰 수령지 정보 끝 -->
                        <?php } ?>
                        <tr>
                            <th>배송 메세지</th>
                            <td>
                                <?=gd_isset($navarPayMemo)?>
                                <textarea name="info[orderMemo]" rows="3" class="form-control"><?= gd_isset($data['orderMemo']); ?></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <?php
            if (empty($addFieldData) === false) {
                ?>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-title gd-help-manual">추가 정보</div>
                        <input type="hidden" name="info[sno]" value="<?= gd_isset($data['infoSno']); ?>" class="form-control width100"/>
                        <table class="table table-cols">
                            <?php
                            foreach ($addFieldData as $addFieldKey => $addFieldVal) {
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
                        </table>
                    </div>
                </div>
                <?php
            }
            ?>

            <?php if (!$isProvider) { ?>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-title gd-help-manual">요청사항 / 상담메모</div>
                        <div class="pull-left notice-info">
                            요청사항/상담메모의 내용이 수정 또는 삭제된 경우 "저장" 버튼을 클릭해야 적용됩니다.
                        </div>
                        <table class="table table-rows mgb5">
                            <colgroup>
                                <col class="width-sm" />
                                <col class="width-md" />
                                <col class="width50p" />
                                <col class="width50p" />
                                <col class="width-sm" />
                            </colgroup>
                            <thead>
                            <tr>
                                <th>작성일</th>
                                <th>작성자</th>
                                <th>요청사항</th>
                                <th>상담메모</th>
                                <th>관리</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($data['consult']) === false) { ?>
                                <?php foreach ($data['consult'] as $key => $val) { ?>
                                    <tr class="text-center">
                                        <td class="nowrap"><?=$val['regDt']?></td>
                                        <td class="nowrap"><?=$val['managerId']?> / <?=$val['managerNm']?></td>
                                        <td class="text-left js-request-memo"><?=$val['requestMemo']?></td>
                                        <td class="text-left js-consult-memo"><?=$val['consultMemo']?></td>
                                        <td class="nowrap">
                                            <button type="button" class="btn btn-sm btn-gray js-consult-modify" data-sno="<?=$val['sno']?>">수정</button>
                                            <button type="button" class="btn btn-sm btn-gray js-consult-delete" data-sno="<?=$val['sno']?>">삭제</button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="5" class="no-data">
                                        등록된 내용이 없습니다.
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xs-12">
                        <table class="table table-cols">
                            <colgroup>
                                <col class="width-md"/>
                                <col/>
                                <col class="width-md"/>
                                <col/>
                            </colgroup>
                            <tbody>
                            <tr>
                                <th>고객요청사항</th>
                                <td>
                                    <input type="hidden" name="consult[sno]" value="">
                                    <input type="hidden" name="consult[orderNo]" value="<?=$data['orderNo']?>">
                                    <textarea maxlength="1000" name="consult[requestMemo]" class="form-control js-maxlength"></textarea>
                                </td>
                                <th>고객상담메모</th>
                                <td>
                                    <textarea maxlength="1000" name="consult[consultMemo]" class="form-control js-maxlength"></textarea>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <div class="table-title gd-help-manual">PG 로그</div>
                        <table class="table table-cols">
                            <colgroup>
                                <col class="width-md"/>
                                <col/>
                            </colgroup>
                            <tr>
                                <th>PG 로그</th>
                                <td>
                                    <pre class="pre-scrollable mgb0"><?= gd_trim(gd_htmlspecialchars_decode(gd_isset($data['orderPGLog']))); ?></pre>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-xs-6">
                        <div class="table-title gd-help-manual">관리자메모</div>
                        <textarea name="order[adminMemo]" rows="5" class="form-control"><?= gd_isset($data['adminMemo']); ?></textarea>
                    </div>
                </div>
            <?php } ?>
        </div>
</form>

<script type="text/javascript">
    <!--
    var settleKind = '<?=$data['settleKind']?>';
    var orderGoodsCnt = '<?=$data['orderGoodsCnt']?>';
    var orderChannelFl = '<?=$data['orderChannelFl']?>';
    var apiOrderNo = '<?=$data['apiOrderNo']?>';
    var orderNo = '<?=$data['orderNo']?>';

    $(document).ready(function () {
        $('.js-btn-naverpay-reload').bind('click',function () {
            $.post('order_ps.php', {
                mode: 'collectNaverpayOrder',
                orderNo: orderNo,
                naverpayOrderNo: apiOrderNo,
                async:false,
                type : 'json',
            }, function (data) {
                if(data.result == true){
                    alert('<b>갱신이 완료되었습니다.</b>');
                    setTimeout(function(){
                        location.reload();
                    },1000)
                }
                else {
                    alert(data.msg);
                }
                console.log(data);
            });
        })

        $('#frmOrder').validate({
            submitHandler: function (form) {
                switch($('[name="bundle[methodType]"]:checked').val()) {
                    case 'cancel':
                        if (!$('input[name*=\'cancel[statusCheck]\']:checkbox:checked').length) {
                            alert('취소 처리 할 상품을 선택하세요.');
                            return false;
                        }
                        break;
                    case 'refund':
                        if (!$('input[name*=\'refund[statusCheck]\']:checkbox:checked').length) {
                            alert('환불 처리 할 상품을 선택하세요.');
                            return false;
                        }
                        break;
                    case 'back':
                        if (!$('input[name*=\'back[statusCheck]\']:checkbox:checked').length) {
                            alert('반품 처리 할 상품을 선택하세요.');
                            return false;
                        }
                        break;
                    case 'exchange':
                        if (!$('input[name*=\'exchange[statusCheck]\']:checkbox:checked').length) {
                            alert('교환 처리 할 상품을 선택하세요.');
                            return false;
                        }
                        break;
                }

                form.target = 'ifrmProcess';
                form.submit();
            },
            rules: {
                'cancel[handleReason]': {
                    required: function() {
                        return $('[name="bundle[methodType]"]:checked').val() == 'cancel';
                    }
                },
                'cancel[orderStatus]': {
                    required: function() {
                        return $('[name="bundle[methodType]"]:checked').val() == 'cancel';
                    }
                },
                'refund[handleReason]': {
                    required: function() {
                        return $('[name="bundle[methodType]"]:checked').val() == 'refund';
                    }
                },
                'refund[refundMethod]': {
                    required: function() {
                        if(orderChannelFl == 'naverpay') {
                            return false;
                        }
                        return $('[name="bundle[methodType]"]:checked').val() == 'refund';
                    }
                },
                'back[handleReason]': {
                    required: function() {
                        return $('[name="bundle[methodType]"]:checked').val() == 'back';
                    }
                },
                'back[refundMethod]': {
                    required: function() {
                        if(orderChannelFl == 'naverpay') {
                            return false;
                        }
                        return $('[name="bundle[methodType]"]:checked').val() == 'back';
                    }
                },
                'exchange[handleReason]': {
                    required: function() {
                        if(orderChannelFl == 'naverpay') {
                            return false;
                        }
                        return $('[name="bundle[methodType]"]:checked').val() == 'exchange';
                    }
                },
            },
            messages: {
                'cancel[handleReason]': {
                    required: '취소사유를 선택하세요.'
                },
                'cancel[orderStatus]': {
                    required: '취소처리 상태를 선택하세요.'
                },
                'refund[handleReason]': {
                    required: '환불사유를 선택하세요.'
                },
                'refund[refundMethod]': {
                    required: '환불수단을 선택하세요.'
                },
                'back[handleReason]': {
                    required: '반품사유를 선택하세요.'
                },
                'back[refundMethod]': {
                    required: '환불수단을 선택하세요.'
                },
                'exchange[handleReason]': {
                    required: '교환사유를 선택하세요.'
                },
            }
        });

        // 클래임 접수시 수량 초과 체크
        $('input[name*="[goodsCnt]"]').blur(function(e){
            var orginCnt = parseInt($(this).siblings('input[name*="[goodsOriginCnt]"]').val()),
                thisCnt = parseInt($(this).val()),
                alertTitle = '환불수량';
            if (thisCnt > orginCnt || thisCnt <= 0) {
                switch($('[name="bundle[methodType]"]:checked').val()) {
                    case 'cancel':
                        alertTitle = '취소수량';
                        break;
                    case 'refund':
                        alertTitle = '환불수량';
                        break;
                    case 'back':
                        alertTitle = '반품수량';
                        break;
                    case 'exchange':
                        alertTitle = '교환수량';
                        break;
                }
                alert(alertTitle + '은 주문수량 “' + orginCnt + '” 보다 큰값 또는 0값을 입력할 수 없습니다.');
                $(this).val(orginCnt);
            }
        });

        // 주문 로그 보기 Ajax layer
        $(document).on('click', '.js-order-log', function (e) {
            var goodsSno = $(this).data('sno');
            var goodsNm = $(this).data('name');
            $.post('layer_order_log.php', {
                orderNo: '<?= gd_isset($data['orderNo']);?>',
                goodsSno: goodsSno,
                goodsNm: goodsNm
            }, function (data) {
                layer_popup(data, '주문 로그 보기', 'wide');
            });
        });

        // 송장번호 입력하면 체크박스 자동 체크되도록
        $(document).on('keyup', 'input[name*="bundle[goods][invoiceNo]"]', function(e) {
            if ($(this).val().length > 0) {
                $(this).closest('tr').find('input[name*="bundle[statusCheck]"]:checkbox').prop('checked', true);
            } else {
                $(this).closest('tr').find('input[name*="bundle[statusCheck]"]:checkbox').prop('checked', false);
            }
        });

        // 송장 정보 일괄 적용 (체크하지 않으면 전체 적용하고 체크한 경우 체크한 주문만 일괄 적용)
        $(document).on('click', '.js-invoice-apply', function(e) {
            var deliverySno = $('#applyDeliverySno').val();
            var invoiceNo = $('#applyInvoiceNo').val();

            // 처리할 상품 체크 수
            var statusCheckCnt = $('input[name*=\'bundle[statusCheck]\']:checkbox:checked').length;
            if (statusCheckCnt == 0) {
                if (deliverySno != '') {
                    $('select[name*="bundle[goods][invoiceCompanySno]"]').val(deliverySno);
                }
                if (invoiceNo != '') {
                    $('input[name*="bundle[goods][invoiceNo]"]').val(invoiceNo);
                }
                if (deliverySno != '' && invoiceNo != '') {
                    $('input[name*=\'bundle[statusCheck]\']:checkbox').prop('checked', true);
                }
            } else {
                // 체크박스 체크
                $('tr[id*=\'statusCheck_\']').each(function (i) {
                    var checkboxChk = $('tr[id*=\'statusCheck_\']').eq(i).find('td:eq(0)').find('input:checkbox:checked').length;
                    if (typeof checkboxChk != 'undefined') {
                        if (checkboxChk == 1) {
                            var orderGoodsNo = $('tr[id*=\'statusCheck_\']').eq(i).find('td:eq(0)').find('input:checkbox:checked').val();
                            if (deliverySno != '') {
                                $('select[name="bundle[goods][invoiceCompanySno][' + orderGoodsNo + ']"]').val(deliverySno);
                            }
                            if (invoiceNo != '') {
                                $('input[name="bundle[goods][invoiceNo][' + orderGoodsNo + ']"]').val(invoiceNo);
                            }
                            if (deliverySno != '' && invoiceNo != '') {
                                $('input[name="bundle[statusCheck][' + orderGoodsNo + ']"]').prop('checked', true);
                            }
                        }
                    }
                });
            }
        });

        $('.js-convert-exchange').click(function(e) {
            $('.js-convert-exchange').removeClass('active');
            $('.js-convert-exchange').removeClass('btn-black').addClass('btn-white');
            $(this).addClass('active');
            $(this).removeClass('btn-white').addClass('btn-black');

            $('#tabOrderStatus > .nav-tabs li.active a').trigger({
                type: 'show.bs.tab',
                isUseMall: $(this).data('use-mall')
            });
        });

        // 상품정보 내역탭의 내용이 나타날때 발생하는 이벤트
        $('#tabOrderStatus > .nav-tabs a').bind('show.bs.tab', function(e){
            var orderStatusMode = $(this).attr('href').replace('#tab-status-', ''),
                tabId = $(this).attr('href');

            var isUseMall = false;
            if (!_.isUndefined(e.isUseMall)) {
                isUseMall = e.isUseMall;
            } else {
                isUseMall = $('.js-convert-exchange.active').data('use-mall');
            }

            $('#tabOrderStatus .tab-content').addClass('loading');
            $.ajax({
                method: 'post',
                url: './inc_order_view.php',
                data: {
                    orderNo: '<?=$data['orderNo']?>',
                    orderStatusMode: orderStatusMode,
                    isUseMall: isUseMall,
                },
                async: true,
                dataType: 'html',
                cache: false
            }).success(function(data){
                $('#tabOrderStatus .tab-content').removeClass('loading');
                $(tabId).html(data);
            }).error(function (e) {
                $('#tabOrderStatus .tab-content').removeClass('loading');
                alert(e.responseText);
            });
        });

        // 클래임정보 내역탭의 내용이 나타날때 발생하는 이벤트
        $('#tabClaimStatus > .nav-tabs a').bind('show.bs.tab', function(e){
            var orderStatusMode = $(this).attr('href').replace('#tab-claim-', ''),
                tabId = $(this).attr('href');
            $('#tabClaimStatus .tab-content').addClass('loading');
            $.ajax({
                method: 'post',
                url: './inc_claim_view.php',
                data: {
                    orderNo: '<?=$data['orderNo']?>',
                    orderStatusMode: orderStatusMode
                },
                async: true,
                dataType: 'html',
                cache: true
            }).success(function(data){
                $('#tabClaimStatus .tab-content').removeClass('loading');
                $(tabId).html(data);
            }).error(function (e) {
                $('#tabClaimStatus .tab-content').removeClass('loading');
                alert(e.responseText);
            });
        });

        // 시작시 주문내역 출력
        if ($('#tabOrderStatus .nav-tabs li').hasClass('active')) {
            $('#tabOrderStatus .nav-tabs li.active a').trigger('show.bs.tab');
        }
        if ($('#tabClaimStatus .nav-tabs li').hasClass('active')) {
            $('#tabClaimStatus .nav-tabs li.active a').trigger('show.bs.tab');
        }

        // 일괄 처리 모드의 체크
        $(document).on('change', '.js-status-change', function (e) {
            set_check_reset();
            if ($(this).val() == '') {
                return false;
            }

            // !중요! 결제확인을 입금대기로 변경하려는 경우 모두 입금대기로 변경되어져야 한다.
            if ($('input[name*=\'bundle[statusCheck]\']:checkbox:checked').length > 0 && $(this).val() == 'o1' && 'p' == '<?=$data['statusMode'];?>') {
                $('input[name*=\'bundle[statusCheck]\']:checkbox').prop('checked', true);
            }
            var tmpCode = $(this).val().substr(0, 1);

            set_check_reset(true);
            set_check_status(tmpCode)
        });

        // 상품주문상태 일괄 변경 (체크박스 관련 부분만 폼을 별도로 생성해서 작업 되어진다)
        $(document).on('click', '.js-ordergoods-status', function (e) {
            var dom = {
                table: $(this).closest('.table-action').siblings('table').eq(0),
                statusSelect: $(this).siblings('select').eq(0),
            };


            if (!$('input[name*=\'bundle[statusCheck]\']:checkbox:checked').length) {
                alert('일괄 처리할 상품을 선택하세요.');
                return false;
            }
            if (dom.statusSelect.text() == '') {
                alert('일괄 처리할 주문상태를 선택하세요.');
                return false;
            }

            // !중요! 결제확인->입금대기, 입금대기->결제확인으로 변경하려는 경우 모두 입금대기로 변경되어져야 한다.
            var modalMessage = '선택한 상품을 "' + dom.statusSelect.find('option:selected').html() + '" 상태로 변경하시겠습니까?';
            if (dom.statusSelect.val() == 'o1' && 'p' == '<?=$data['statusMode'];?>') {
                $('input[name*=\'bundle[statusCheck]\']:checkbox').prop('checked', true);
                modalMessage = '해당 주문의 전체상품이 "' + dom.statusSelect.find('option:selected').html() + '" 상태로 변경됩니다.<br>변경하시겠습니까?';
            } else if (dom.statusSelect.val() == 'p1' && 'o' == '<?=$data['statusMode'];?>') {
                $('input[name*=\'bundle[statusCheck]\']:checkbox').prop('checked', true);
                modalMessage = '해당 주문의 전체상품이 "' + dom.statusSelect.find('option:selected').html() + '" 상태로 변경됩니다.<br>변경하시겠습니까?';
            }

            if(dom.statusSelect.val().indexOf('naverpay')>-1){
                var snoList = [];
                $('[name^="bundle[statusCheck]"]:checked').not(':disabled').each(function () {
                    if($(this).is(':visible')){
                        snoList.push($(this).val());
                    }
                });
                var orderGoodsNos = snoList.join(',');
                var tmpNaverPayMode = dom.statusSelect.val().split('_');
                var naverPayMode = tmpNaverPayMode[2];
//                if(dom.statusSelect.val() == 'b_naverpay_ReleaseReturnHold'){//반품보류 해제
                if(naverPayMode == 'ReleaseReturnHold' || naverPayMode == 'ReleaseExchangeHold'){//반품 or 교환 보류 해제
                    $.post('../order/order_naverpay_ps.php', {'mode': naverPayMode,'orderNo': '<?= gd_isset($data['orderNo']);?>',  'orderGoodsNos' : orderGoodsNos}, function (data) {
                            if((data.result) === 'success'){
                                top.location.reload();
                            }
                            else {
                                alert('<b>'+data.msg+'</b>');
                            }
                        },'json'
                    )

                    return;
                }

                $.get('../order/layer_naverpay_order.php', {'mode': 'naverpayLayer','status': dom.statusSelect.val(),'orderNo': '<?= gd_isset($data['orderNo']);?>', 'handleSno': $(this).data('handle-sno') , 'orderGoodsNos' : orderGoodsNos}, function (data) {
                    if(data.substring(0,5) == 'error'){
                        var errorData = data.split("|");
                        alert(errorData[1]);
                        return;
                    }

                    BootstrapDialog.show({
                        title: dom.statusSelect.find('option:selected').text()+' 처리',
                        size: get_layer_size('wide'),
                        message: data,
                        closable: true,
                    });

//                    layer_popup(data, dom.statusSelect.find('option:selected').text(), 'wide');
                });

                return;
            }

            // 확인창 출력
            BootstrapDialog.confirm({
                type: BootstrapDialog.TYPE_WARNING,
                title: '주문상태 변경',
                message: modalMessage,
                callback: function (result) {
                    // 확인 버튼 클릭시
                    if (result) {
                        // 다른 폼에 데이터를 추가해서 일괄변경 처리를 한다.
                        var $form = $('#frmOrderStatus');
                        $form.empty().append('<input type="hidden" name="mode" value="status_change" />');

                        $('input[name*="bundle[statusCheck]"]:checkbox:checked').not(':disabled').each(function (idx) {
                            var statusMode = $(this).closest('td').find('input[name*="bundle[statusMode]"]').val().substr(0, 1);
                            $form.append('<input type="hidden" name="statusMode[' + statusMode + '][]" value="' + statusMode + '" />');
                            $form.append('<input type="hidden" name="statusCheck[' + statusMode + '][]" value="<?= gd_isset($data['orderNo'])  . INT_DIVISION;?>' + $(this).closest('td').find('input[name*="bundle[goods][sno]"]').val() + '" />');
                            $form.append('<input type="hidden" name="changeStatus" value="' + dom.statusSelect.find('option:selected').val() + '" />');
                            $form.append('<input type="hidden" name="orderChannelFl" value="' + orderChannelFl + '" />');
                        });
                        $('#frmOrderStatus').validate({
                            submitHandler: function (form) {
                                form.target = 'ifrmProcess';
                                form.submit();
                            }
                        });
                        $('#frmOrderStatus').submit();
                    }
                }
            });
        });

        // 결제히스토리
        $('.js-pay-history').click(function (e) {
            var goodsSno = $(this).data('sno');
            var goodsNm = $(this).data('name');
            $.post('layer_order_pay_history.php', {
                orderNo: '<?= gd_isset($data['orderNo']);?>'
            }, function (data) {
                layer_popup(data, '결제 히스토리', 'wide');
            });
        });

        // 쿠폰 사용 보기 Ajax layer
        $('.js-order-coupon').click(function (e) {
            $.post('layer_order_coupon.php', {'orderNo': '<?= gd_isset($data['orderNo']);?>'}, function (data) {
                layer_popup(data, '쿠폰 정보 보기', 'wide');
            });
        });

        // 클래임정보 수정 Ajax layer
        $('.js-claim-view').click(function (e) {
            $.post('layer_refund_view.php', {'orderNo': '<?= gd_isset($data['orderNo']);?>', 'handleSno': $(this).data('handle-sno')}, function (data) {
                layer_popup(data, '클래임정보 수정', 'wide');
            });
        });

        // 환불정보 수정 Ajax layer
        $('.js-refund-view').click(function (e) {
            $.post('layer_refund_view.php', {'orderNo': '<?= gd_isset($data['orderNo']);?>', 'handleSno': $(this).data('handle-sno')}, function (data) {
                layer_popup(data, '환불정보 수정', 'wide');
            });
        });

        // 입금 은행 변경 Ajax layer
        $('.js-bank-change').click(function (e) {
            $.post('layer_bank_selector.php', '', function (data) {
                layer_popup(data, '입금 은행 변경');
            });
        });

        // 클래임 처리 모드 변경
        $('input[name="bundle[methodType]"]').click(function(e) {
            $('[id*=orderStatusEach-]').hide();
            if ($('#orderStatusEach-' + $(this).val()).length > 0) {
                $('#orderStatusEach-' + $(this).val()).show();
            } else {
                alert('[' + $.trim($(this).parents('label').text()) + '] 가능한 상품이 없습니다.');
            }
        });

        $('.js-no-method-type').click(function(e){
            $('input[name="bundle[methodType]"]').prop('checked', false);
            $('[id*=orderStatusEach-]').hide();
        });

        // 최초결제정보 토글
        $('.js-pay-toggle').click(function(e){
            var target = $(this).closest('tr').siblings('#' + $(this).data('target')).eq(0);
            var tr = $(this).closest('tr'),
                td = tr.find('td.th .list-unstyled');
            if (target.find('td').is(':visible')) {
                $(this).removeClass('active');
                $(this).closest('th').css({borderBottom: '1px solid #E6E6E6'});
                target.find('th').css({display: 'none'});
                target.find('td').css({display: 'none'});
            } else {
                $(this).addClass('active');
                $(this).closest('th').css({borderBottom: 'none'});
                target.find('th').css({display: ''});
                target.find('td').css({display: ''});
            }
        });

        // 최초결제정보의 토글 버튼 노출 여부 설정
        $('.js-pay-toggle').each(function(idx){
            var count = $(this).data('number');
            if (count == 0) {
                $(this).remove();
            }
        });

        // 요청사항/상담메모
        $('.js-consult-modify').click(function(e){
            $('input[name="consult[sno]"]').val($(this).data('sno'));
            $('textarea[name="consult[requestMemo]"]').val($(this).closest('tr').find('.js-request-memo').text());
            $('textarea[name="consult[consultMemo]"]').val($(this).closest('tr').find('.js-consult-memo').text());
        });

        // 요청사항/상담메모 삭제 처리
        $('.js-consult-delete').click(function(e){
            var element = $(this).closest('tr'),
                self = $(this);
            BootstrapDialog.confirm({
                type: BootstrapDialog.TYPE_WARNING,
                title: '요청사항 및 상담메모 삭제',
                message: '선택한 상담메모를 삭제하시겠습니까? 삭제하시면 복구하실 수 없습니다.',
                callback: function (result) {
                    // 확인 버튼 클릭시
                    if (result) {
                        // 다른 폼에 데이터를 추가해서 일괄변경 처리를 한다.
                        $.post('../order/order_ps.php', {
                            mode: 'delete_consult',
                            sno: self.data('sno'),
                            orderNo: '<?= gd_isset($data['orderNo']);?>'
                        }, function (data) {
                            alert(data.message);
                            if (data.code == 0) {
                                element.remove();
                            }
                        });
                    }
                }
            });
        });

        $('.js-order-refund').click(function(e){
            e.preventDefault();
            refund_view_popup($(this).attr('href'));
        });

        $('.js-checkall').click(function(){
            if ($(this).data('target-name') == 'refund[statusCheck]') {
                refund_method_set(orderChannelFl, settleKind);
            }
        });

        $(':checkbox[name^="refund[statusCheck]"]').click(function(){
            refund_method_set(orderChannelFl, settleKind);
        });

        $('select[name="refund[refundMethod]"]').change(function(){
            payco_notice_msg(orderChannelFl, $(this).val());
        });
    })

    /**
     * 주문 처리 상품의 가능 여부를 리셋
     *
     * @param string chkMode 리셋 모드
     */
    function set_check_reset(isDisabled) {
        if (isDisabled) {
            // tr 의 배경색 및 checkbox를 disabled 합니다.
            $('tr[id*="statusCheck_"]').addClass('disabled');
            $('tr[id*="addStatusCheck_"]').addClass('disabled');
            $('tr[id*="statusCheck_"] input').prop('disabled', true);
            $('tr[id*="statusCheck_"] select').prop('disabled', true);
        } else {
            // tr 의 배경색 및 checkbox를 초기화 합니다.
            $('tr[id*="statusCheck_"]').removeClass('disabled');
            $('tr[id*="addStatusCheck_"]').removeClass('disabled');
            $('tr[id*="statusCheck_"] input').prop('disabled', false);
            $('tr[id*="statusCheck_"] select').prop('disabled', false);
        }
    }

    /**
     * 에스크로 거절 확인
     */
    function escrow_deny_register() {
        frame_popup('frame_escrow_deny.php?orderNo=<?= gd_isset($data['orderNo']);?>', '에스크로 거절 확인');
    }

    /**
     * 현금영수증 신청
     */
    function cash_receipt_register() {
        frame_popup('frame_cash_receipt_register.php?orderNo=<?= gd_isset($data['orderNo']);?>', '현금영수증 신청');
    }

    /**
     * 세금계산서 신청
     *
     * @param string modeStr 모드
     */
    function tax_invoice_register(modeStr) {
        if (typeof modeStr == 'undefined') {
            titleStr = '신청';
            modeStr = 'register';
        } else {
            titleStr = '수정';
        }
        frame_popup('frame_tax_invoice_register.php?orderNo=<?= gd_isset($data['orderNo']);?>&mode=' + modeStr, '세금계산서 ' + titleStr);
    }

    /**
     * 환불관리 전용 새창
     */
    function refund_view_popup(uri) {
        win = popup({
            url: uri,
            target: '',
            width: '1200',
            height: '800',
            scrollbars: 'yes',
            resizable: 'yes'
        });
        win.focus();
        return win;
    }

    function refund_method_set(orderChannelFl, settleKind) {
        if (orderChannelFl != 'payco') return;
        var checkLen = $(':checkbox[name^="refund[statusCheck]"]:checked').length;

        if (orderGoodsCnt == checkLen) { // 전체환불
            $('.payco-notice').addClass('display-none');
            $('select[name="refund[refundMethod]"] option').not('[value="PG환불"], [value="기타환불"]').wrap('<span>').parent().hide();
        } else if (checkLen > 0) { // 부분환불
            switch (settleKind.substr(1, 1)) {
                case 'b':
                    $('.payco-notice').removeClass('display-none');
                    if ($('select[name="refund[refundMethod]"] option').parent().is('span')) {
                        $('select[name="refund[refundMethod]"] option').not('[value="PG환불"], [value="기타환불"]').unwrap();
                    }
                    break;
                default:
                    $('.payco-notice').addClass('display-none');
                    $('select[name="refund[refundMethod]"] option').not('[value="PG환불"], [value="기타환불"]').wrap('<span>').parent().hide();
                    break;
            }
        } else {
            $('.payco-notice').addClass('display-none');
            if ($('select[name="refund[refundMethod]"] option').parent().is('span')) {
                $('select[name="refund[refundMethod]"] option').not('[value="PG환불"], [value="기타환불"]').unwrap();
            }
        }
    }

    var payco_notice_msg = function(orderChannelFl, refundMethod){
        if (orderChannelFl != 'payco') return;

        if (refundMethod == '기타환불') {
            $('.payco-notice-msg').removeClass('display-none');
        } else {
            $('.payco-notice-msg').addClass('display-none');
        }
    }
    //-->
</script>
