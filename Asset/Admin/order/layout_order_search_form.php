<?php
/**
 * 주문리스트내 검색 폼 레이아웃
 *
 * @author Jong-tae Ahn <qnibus@godo.co.kr>
 */
?>

<!-- 검색을 위한 form -->
<form id="frmSearchOrder" method="get" class="js-form-enter-submit">
    <input type="hidden" name="detailSearch" value="<?= $search['detailSearch']; ?>"/>

    <div class="table-title <?=isset($currentUserHandleMode) ? '' : 'gd-help-manual'?>">
        주문 검색
    <span class="search"><button type="button" class="btn btn-sm btn-black" onclick="set_search_config(this.form)">검색설정저장</button></span>
    </div>

    <div class="search-detail-box">
        <table class="table table-cols">
            <colgroup>
                <col class="width-sm">
                <col>
                <col class="width-sm">
                <col>
            </colgroup>
            <tbody>
            <?php if ($gGlobal['isUse'] === true) { ?>
            <tr>
                <th>상점</th>
                <td colspan="3">
                    <label class="radio-inline">
                        <input type="radio" name="mallFl" value="all" <?= gd_isset($checked['mallFl']['all']); ?>/>전체
                    </label>
                    <?php
                    foreach ($gGlobal['useMallList'] as $val) {
                        ?>
                        <label class="radio-inline">
                            <input type="radio" name="mallFl" value="<?= $val['sno'] ?>" <?= gd_isset($checked['mallFl'][$val['sno']]); ?>/><span class="flag flag-16 flag-<?= $val['domainFl'] ?>"></span> <?= $val['mallName'] ?>
                        </label>
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <?php } ?>
            <?php if(gd_use_provider() === true) { ?>
            <?php if (!isset($isProvider) && $isProvider != true) { ?>
            <tr>
                <th>공급사 구분</th>
                <td colspan="3">
                    <label class="radio-inline">
                        <input type="radio" name="scmFl" value="all" <?= gd_isset($checked['scmFl']['all']); ?>/>전체
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="scmFl" value="0" <?= gd_isset($checked['scmFl']['0']); ?>/>본사
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="scmFl" value="1" class="js-layer-register" <?= gd_isset($checked['scmFl']['1']); ?> data-type="scm" data-mode="checkbox"/> 공급사
                    </label>
                    <input type="button" value="공급사 선택" class="btn btn-sm btn-gray js-layer-register" data-type="scm" data-mode="search"/>

                    <div id="scmLayer" class="selected-btn-group <?=$search['scmFl'] == '1' && !empty($search['scmNo']) ? 'active' : ''?>">
                        <h5>선택된 공급사 : </h5>
                        <?php if ($search['scmFl'] == '1' && empty($search['scmNo']) === false) { ?>
                            <?php foreach ($search['scmNo'] as $k => $v) { ?>
                                <div id="info_scm_<?= $v ?>" class="btn-group btn-group-xs">
                                    <input type="hidden" name="scmNo[]" value="<?= $v ?>"/>
                                    <input type="hidden" name="scmNoNm[]" value="<?= $search['scmNoNm'][$k] ?>"/>
                                    <span class="btn"><?= $search['scmNoNm'][$k] ?></span>
                                    <button type="button" class="btn btn-icon-delete" data-toggle="delete" data-target="#info_scm_<?= $v ?>">삭제</button>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </td>
            </tr>
            <?php } ?>
            <?php } ?>
            <tr>
                <th>검색어</th>
                <td colspan="3">
                    <div class="form-inline">
                        <?= gd_select_box('key', 'key', $search['combineSearch'], null, $search['key'], null, null, 'form-control '); ?>
                        <input type="text" name="keyword" value="<?= $search['keyword']; ?>" class="form-control"/>
                    </div>
                </td>
            </tr>
            <tr>
                <th>기간검색</th>
                <td colspan="3">
                    <div class="form-inline">
                        <?= gd_select_box('treatDateFl', 'treatDateFl', $search['combineTreatDate'], null, $search['treatDateFl'], null, null, 'form-control '); ?>
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
                            <label class="btn btn-white btn-sm hand"><input type="radio" name="periodFl" value="6">7일
                            </label>
                            <label class="btn btn-white btn-sm hand"><input type="radio" name="periodFl" value="14">15일
                            </label>
                            <label class="btn btn-white btn-sm hand"><input type="radio" name="periodFl" value="29">1개월
                            </label>
                            <label class="btn btn-white btn-sm hand"><input type="radio" name="periodFl" value="89">3개월
                            </label>
                            <label class="btn btn-white btn-sm hand"><input type="radio" name="periodFl" value="364">1년
                            </label>
                        </div>
                    </div>
                </td>
            </tr>
            <?php if (isset($handleFl) && $isUserHandle) { ?>
                <tr>
                    <th>처리상태</th>
                    <td colspan="3">
                        <dl class="dl-horizontal dl-checkbox">
                            <dt>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="userHandleFl[]" value="" class="js-not-checkall" data-target-name="userHandleFl[]" <?= gd_isset($checked['userHandleFl']['']) ?>/> 전체
                                </label>
                            </dt>
                            <dd>
                                <?php $chk = 0;
                                foreach ($handleFl as $key => $val) { ?>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="userHandleFl[]" value="<?= $key ?>" <?= gd_isset($checked['userHandleFl'][$key]) ?> /> <?= $val ?>
                                    </label>
                                    <?php $chk++;
                                    if ($chk % 8 == 0) {
                                        echo '<br/>';
                                    }
                                } ?>
                            </dd>
                        </dl>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
            <tbody class="js-search-detail">
            <tr>
                <th>주문유형</th>
                <td>
                    <div class="dl-horizontal dl-checkbox">
                        <label class="checkbox-inline" style="margin: 0 10px 0 0;">
                            <input type="checkbox" name="orderTypeFl[]" value="" class="js-not-checkall" data-target-name="orderTypeFl[]" <?= gd_isset($checked['orderTypeFl']['']) ?>/> 전체
                        </label>
                        <?php
                        foreach ($type as $key => $val) { ?>
                            <label class="checkbox-inline" style="margin: 0 10px 0 0;">
                                <input type="checkbox" name="orderTypeFl[]" value="<?= $key ?>" <?= gd_isset($checked['orderTypeFl'][$key]) ?> /> <?= $val ?>
                            </label>
                        <?php } ?>
                    </div>
                </td>
                <th>주문채널구분</th>
                <td>
                    <div class="dl-horizontal dl-checkbox">
                        <label class="checkbox-inline" style="margin: 0 10px 0 0;">
                            <input type="checkbox" name="orderChannelFl[]" value="" class="js-not-checkall" data-target-name="orderChannelFl[]" <?= gd_isset($checked['orderChannelFl']['']) ?>/> 전체
                        </label>
                        <?php
                        foreach ($channel as $key => $val) { ?>
                            <label class="checkbox-inline" style="margin: 0 10px 0 0;">
                                <input type="checkbox" name="orderChannelFl[]" value="<?= $key ?>" <?= gd_isset($checked['orderChannelFl'][$key]) ?> /><?= is_file(UserFilePath::adminSkin('gd_share', 'img', 'channel_icon', $key . '.gif')) ?  gd_html_image(UserFilePath::adminSkin('gd_share', 'img', 'channel_icon', $key . '.gif')->www(), null) : ''; ?> <?= $val ?>
                            </label>
                        <?php } ?>
                    </div>
                </td>
            </tr>
            <?php if (empty($statusSearchableRange) === false) { ?>
                <?php if ($search['view'] === 'orderGoods' || $currentStatusCode !== null) { ?>
            <tr>
                <th>주문상태</th>
                <td colspan="3">
                    <div class="dl-horizontal dl-checkbox">
                        <span <?php if (count($statusSearchableRange) > 10) echo 'class="width-xs"'; ?> style="display: inline-block; margin: 0 10px 0 0;">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="orderStatus[]" value="" class="js-not-checkall" data-target-name="orderStatus[]" <?= gd_isset($checked['orderStatus']['']) ?>/> 전체
                            </label>
                        </span>
                        <?php
                        foreach ($statusSearchableRange as $key => $val) {
                            // 공급사 관리자인 경우 입금대기 제거
                            if (gd_use_provider() === true) {
                                if (isset($isProvider) && $isProvider === true) {
                                    if (substr($key, 0, 1) == 'o') {
                                        continue;
                                    }
                                }
                            }

                            // 반품리스트에서 반품회수완료 제거
                            if ($currentStatusCode == 'b' && $key == 'b4') {
                                continue;
                            }
                        ?>
                        <span <?php if (count($statusSearchableRange) > 10) echo 'class="width-xs"'; ?>  style="display: inline-block; margin: 0 10px 0 0;">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="orderStatus[]" value="<?= $key ?>" <?= gd_isset($checked['orderStatus'][$key]) ?> /> <?= $val ?>
                            </label>
                        </span>
                        <?php } ?>
                    </div>
                </td>
            </tr>
                <?php } else { ?>
                    <?php
                    foreach ($statusSearchableRange as $key => $val) {
                        // 공급사 관리자인 경우 입금대기 제거
                        if (gd_use_provider() === true) {
                            if (isset($isProvider) && $isProvider === true) {
                                if (substr($key, 0, 1) == 'o') {
                                    continue;
                                }
                            }
                        }

                        // 반품리스트에서 반품회수완료 제거
                        if ($currentStatusCode == 'b' && $key == 'b4') {
                            continue;
                        }
                        if (empty($checked['orderStatus'][$key]) === false) {
                        ?>
                            <input type="hidden" name="orderStatus[]" value="<?= empty($checked['orderStatus'][$key]) === false ? $key : '' ?>" />
                    <?php } } ?>
                <?php } ?>
            <?php } ?>
            <?php if (!$isProvider) { ?>
            <tr>
                <th>결제수단</th>
                <td colspan="3">
                    <div class="dl-horizontal dl-checkbox">
                        <span style="margin: 0 10px 0 0; width: 160px; display: inline-block;">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="settleKind[]" value="" class="js-not-checkall" data-target-name="settleKind[]" <?= gd_isset($checked['settleKind']['']) ?>/> 전체
                            </label>
                        </span>
                        <?php
                        $payMethod = '';
                        foreach ($settle as $key => $val) {
                            switch (substr($key, 0, 1)) {
                                case 'e':
                                    $payMethod = ' (에스크로)';
                                    break;
                                case 'f':
                                    $payMethod = ' (간편결제)';
                                    break;
                            }
                            ?>
                            <span style="margin: 0 10px 0 0; width: 160px; display: inline-block;">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="settleKind[]" value="<?= $key ?>" <?= gd_isset($checked['settleKind'][$key]) ?> /><img src="<?=PATH_ADMIN_GD_SHARE?>img/settlekind_icon/icon_settlekind_<?= $key ?>.gif" alt="<?= $val['name'] . $payMethod ?>" data-pin-nopin="true">
                                    <?= $val['name'] . $payMethod ?>
                                </label>
                            </span>
                        <?php } ?>
                    </div>
                </td>
            </tr>
            <?php } ?>
            <tr>
                <th>송장번호</th>
                <td colspan="3" class="form-inline">
                    <?php
                    // 배송 업체
                    $delivery = App::load(\Component\Delivery\Delivery::class);
                    $tmpDelivery = $delivery->getDeliveryCompany(null, true, $data['orderChannelFl']);
                    $deliveryCom[0] = '=배송 업체=';
                    $deliverySno = 0;

                    if (empty($tmpDelivery) === false) {
                        foreach ($tmpDelivery as $key => $val) {
                            // 기본 배송업체 sno
                            if ($key == 0) {
                                $deliverySno = $val['sno'];
                            }
                            $deliveryCom[$val['sno']] = $val['companyName'];
                        }
                        unset($tmpDelivery);
                    }
                    echo gd_select_box(null, 'invoiceCompanySno', $deliveryCom, null, $search['invoiceCompanySno'], null);
                    ?>
                    <label class="radio-inline mgl10">
                        <input type="radio" name="invoiceNoFl" value="" <?= gd_isset($checked['invoiceNoFl']['']); ?> />전체
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="invoiceNoFl" value="y" <?= gd_isset($checked['invoiceNoFl']['y']); ?> /> 송장번호 등록
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="invoiceNoFl" value="n" <?= gd_isset($checked['invoiceNoFl']['n']); ?> /> 송장번호 미등록
                    </label>
                </td>
            </tr>
            <tr>
                <th>회원정보</th>
                <td>
                    <label class="checkbox-inline">
                        <input type="checkbox" name="firstSaleFl" value="y" <?= gd_isset($checked['firstSaleFl']['y']); ?>/> 첫주문
                    </label>
                </td>
                <th>배송정보</th>
                <td>
                    <label class="checkbox-inline">
                        <input type="checkbox" name="withGiftFl" value="y" <?= gd_isset($checked['withGiftFl']['y']); ?>/> 사은품 포함
                    </label>
                    <label class="checkbox-inline">
                        <input type="checkbox" name="withMemoFl" value="y" <?= gd_isset($checked['withMemoFl']['y']); ?>/> 배송메세지 입력
                    </label>
                    <label class="checkbox-inline">
                        <input type="checkbox" name="withAdminMemoFl" value="y" <?= gd_isset($checked['withAdminMemoFl']['y']); ?>/> 관리자메모 입력
                    </label>
                </td>
            </tr>
            <?php if (!$isProvider) { ?>
            <tr>
                <th>회원검색</th>
                <td>
                    <label class="radio-inline">
                        <input type="radio" name="memFl" value="" <?= gd_isset($checked['memFl']['']); ?> />전체
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="memFl" value="n" <?= gd_isset($checked['memFl']['n']); ?> />비회원
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="memFl" value="y" <?= gd_isset($checked['memFl']['y']); ?> />회원
                    </label>
                    <button type="button" class="btn btn-gray btn-sm js-layer-register" data-type="member_group">회원등급 선택</button>
                    <div id="member_groupLayer" class="selected-btn-group <?=$search['memFl'] == 'y' && !empty($search['memberGroupNo']) ? 'active' : ''?>">
                        <h5>선택된 회원등급</h5>
                        <?php if ($search['memFl'] == 'y' && empty($search['memberGroupNo']) === false) { ?>
                            <?php foreach ($search['memberGroupNo'] as $k => $v) { ?>
                                <div id="info_member_group_<?= $v ?>" class="btn-group btn-group-xs">
                                    <input type="hidden" name="memberGroupNo[]" value="<?= $v ?>"/>
                                    <input type="hidden" name="memberGroupNoNm[]" value="<?= $search['memberGroupNoNm'][$k] ?>"/>
                                    <span class="btn"><?= $search['memberGroupNoNm'][$k] ?></span>
                                    <button type="button" class="btn btn-icon-delete" data-toggle="delete" data-target="#info_member_group_<?= $v ?>">삭제</button>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </td>
                <th>결제금액</th>
                <td>
                    <div class="form-inline">
                        <input type="text" name="settlePrice[]" value="<?= $search['settlePrice'][0]; ?>" class="form-control width-sm"/>원 ~
                        <input type="text" name="settlePrice[]" value="<?= $search['settlePrice'][1]; ?>" class="form-control width-sm"/>원
                    </div>
                </td>
            </tr>
            <?php if ($currentStatusCode == 'o') { ?>
                <tr>
                    <th>영수증 신청</th>
                    <td>
                        <label class="radio-inline">
                            <input type="radio" name="receiptFl" value="" <?= gd_isset($checked['receiptFl']['']); ?> />전체
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="receiptFl" value="r" <?= gd_isset($checked['receiptFl']['r']); ?> /><?= gd_html_image(UserFilePath::adminSkin('gd_share', 'img', 'receipt_icon', 'receipt_r.png')->www(), null); ?> 현금영수증
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="receiptFl" value="t" <?= gd_isset($checked['receiptFl']['t']); ?> /><?= gd_html_image(UserFilePath::adminSkin('gd_share', 'img', 'receipt_icon', 'receipt_t.png')->www(), null); ?> 세금계산서
                        </label>
                    </td>
                    <th>입금경과일</th>
                    <td>
                        <div class="form-inline">
                            <input type="text" name="overDepositDay" value="<?= $search['overDepositDay']; ?>" class="form-control width-2xs"/>
                            일 이상 경과
                        </div>
                    </td>
                </tr>
            <?php } elseif ($currentStatusCode == 'f') { ?>
                    <tr>
                        <th>영수증 신청</th>
                        <td colspan="3">
                            <label class="radio-inline">
                                <input type="radio" name="receiptFl" value="" <?= gd_isset($checked['receiptFl']['']); ?> />전체
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="receiptFl" value="r" <?= gd_isset($checked['receiptFl']['r']); ?> /><?= gd_html_image(UserFilePath::adminSkin('gd_share', 'img', 'receipt_icon', 'receipt_r.png')->www(), null); ?> 현금영수증
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="receiptFl" value="t" <?= gd_isset($checked['receiptFl']['t']); ?> /><?= gd_html_image(UserFilePath::adminSkin('gd_share', 'img', 'receipt_icon', 'receipt_t.png')->www(), null); ?> 세금계산서
                            </label>
                        </td>
                    </tr>
            <?php } elseif (in_array($currentStatusCode, ['p', 'g'])) { ?>
                    <tr>
                        <th>영수증 신청</th>
                        <td>
                            <label class="radio-inline">
                                <input type="radio" name="receiptFl" value="" <?= gd_isset($checked['receiptFl']['']); ?> />전체
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="receiptFl" value="r" <?= gd_isset($checked['receiptFl']['r']); ?> /><?= gd_html_image(UserFilePath::adminSkin('gd_share', 'img', 'receipt_icon', 'receipt_r.png')->www(), null); ?> 현금영수증
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="receiptFl" value="t" <?= gd_isset($checked['receiptFl']['t']); ?> /><?= gd_html_image(UserFilePath::adminSkin('gd_share', 'img', 'receipt_icon', 'receipt_t.png')->www(), null); ?> 세금계산서
                            </label>
                        </td>
                        <th>배송지연일</th>
                        <td>
                            <div class="form-inline">
                                <input type="text" name="underDeliveryDay" value="<?= $search['underDeliveryDay']; ?>" class="form-control width-2xs"/> 일 이상 지연
                                <!--                            <label class="checkbox-inline mgl10">-->
                                <!--                                <input type="checkbox" name="underDeliveryOrder" value="y" --><?//= gd_isset($checked['underDeliveryOrder']['y']); ?><!--/> 포함된 주문 모두-->
                                <!--                            </label>-->
                                <div class="notice-info">입력 시 배송 전 주문상태만 검색 가능합니다.</div>
                            </div>
                        </td>
                    </tr>
            <?php } else { ?>
                <tr>
                    <th>영수증 신청</th>
                    <td colspan="3">
                        <label class="radio-inline">
                            <input type="radio" name="receiptFl" value="" <?= gd_isset($checked['receiptFl']['']); ?> />전체
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="receiptFl" value="r" <?= gd_isset($checked['receiptFl']['r']); ?> /><?= gd_html_image(UserFilePath::adminSkin('gd_share', 'img', 'receipt_icon', 'receipt_r.png')->www(), null); ?> 현금영수증
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="receiptFl" value="t" <?= gd_isset($checked['receiptFl']['t']); ?> /><?= gd_html_image(UserFilePath::adminSkin('gd_share', 'img', 'receipt_icon', 'receipt_t.png')->www(), null); ?> 세금계산서
                        </label>
                    </td>
                </tr>
                <tr>
                    <th>입금경과일</th>
                    <td>
                        <div class="form-inline">
                            <input type="text" name="overDepositDay" value="<?= $search['overDepositDay']; ?>" class="form-control width-2xs"/>
                            일 이상 경과
                        </div>
                    </td>
                    <th>배송지연일</th>
                    <td>
                        <div class="form-inline">
                            <input type="text" name="underDeliveryDay" value="<?= $search['underDeliveryDay']; ?>" class="form-control width-2xs"/> 일 이상 지연
<!--                            <label class="checkbox-inline mgl10">-->
<!--                                <input type="checkbox" name="underDeliveryOrder" value="y" --><?//= gd_isset($checked['underDeliveryOrder']['y']); ?><!--/> 포함된 주문 모두-->
<!--                            </label>-->
                            <div class="notice-info">입력 시 배송 전 주문상태만 검색 가능합니다.</div>
                        </div>
                    </td>
                </tr>
            <?php } ?>
                <tr>
                    <th>프로모션 정보</th>
                    <td <?=($currentStatusCode == 'o' ? 'colspan="3"':'')?>>
                        <div>
                            <input type="button" value="쿠폰선택" class="btn btn-sm btn-gray js-layer-register" data-type="coupon"/>
                            <!--                            <input type="button" value="기획전선택" class="btn btn-sm btn-gray js-layer-register" data-type="event"/>-->
                            <label class="checkbox-inline mgl10"><input type="checkbox" name="couponAllFl" value="y" <?=gd_isset($checked['couponAllFl']['y']); ?>> 쿠폰사용 주문 전체 검색
                            </label>
                            <label class="checkbox-inline mgl10"></label>
                            <div id="couponLayer" class="selected-btn-group <?=!empty($search['couponNo']) ? 'active' : ''?>">
                                <h5>선택된 쿠폰 : </h5>
                                <?php if (empty($search['couponNo']) === false) { ?>
                                    <div id="info_coupon_<?= $search['couponNo'] ?>" class="btn-group btn-group-xs">
                                        <input type="hidden" name="couponNo" value="<?= $search['couponNo'] ?>"/>
                                        <input type="hidden" name="couponNoNm" value="<?= $search['couponNoNm'] ?>"/>
                                        <span class="btn"><?= $search['couponNoNm'] ?></span>
                                        <button type="button" class="btn btn-icon-delete" data-toggle="delete" data-target="#info_coupon_<?= $search['couponNo'] ?>">삭제</button>
                                    </div>
                                <?php } ?>
                            </div>
                            <!--                            <div id="eventLayer" class="selected-btn-group --><?//=!empty($search['eventNo']) ? 'active' : ''?><!--">-->
                            <!--                                <h5>선택된 기획전 : </h5>-->
                            <!--                                --><?php //if (empty($search['eventNo']) === false) { ?>
                            <!--                                    <div id="info_event_--><?//= $search['eventNo'] ?><!--" class="btn-group btn-group-xs">-->
                            <!--                                        <input type="hidden" name="eventNo" value="--><?//= $search['eventNo'] ?><!--"/>-->
                            <!--                                        <input type="hidden" name="eventNoNm" value="--><?//= $search['eventNoNm'] ?><!--"/>-->
                            <!--                                        <span class="btn">--><?//= $search['eventNoNm'] ?><!--</span>-->
                            <!--                                        <button type="button" class="btn btn-icon-delete" data-toggle="delete" data-target="#info_event_--><?//= $search['eventNo'] ?><!--">삭제</button>-->
                            <!--                                    </div>-->
                            <!--                                --><?php //} ?>
                            <!--                            </div>-->
                        </div>
                    </td>
                    <?php if ($currentStatusCode != 'o') { ?>
                    <th>수동입금확인</th>
                    <td>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="manualPayment" value="y" <?= gd_isset($checked['manualPayment']['y']); ?>/> 수동입금확인 주문만 보기
                        </label>
                    </td>
                    </td>
                    <?php } ?>
                </tr>
            <?php } ?>
            <tr>
                <th>브랜드</th>
                <td <?php if(gd_is_plus_shop(PLUSSHOP_CODE_PURCHASE) !== true || gd_is_provider() !== false) { ?> colspan="3" <?php } ?>>
                    <div class="form-inline">
                        <label><input type="button" value="브랜드선택" class="btn btn-sm btn-gray js-layer-register" data-type="brand"  data-mode="radio" /></label>
                        <label class="checkbox-inline mgl10"><input type="checkbox" name="brandNoneFl" value="y" <?=gd_isset($checked['brandNoneFl']['y']); ?>> 브랜드 미지정 상품</label>
                        <div id="brandLayer" class="selected-btn-group <?=!empty($search['brandCd']) ? 'active' : ''?>">
                            <h5>선택된 브랜드 : </h5>
                            <?php if (empty($search['brandCd']) === false) { ?>
                                <div id="info_brand_<?= $search['brandCd'] ?>" class="btn-group btn-group-xs">
                                    <input type="hidden" name="brandCd" value="<?= $search['brandCd'] ?>"/>
                                    <input type="hidden" name="brandCdNm" value="<?= $search['brandCdNm'] ?>"/>
                                    <span class="btn"><?= $search['brandCdNm'] ?></span>
                                    <button type="button" class="btn btn-icon-delete" data-toggle="delete" data-target="#info_brand_<?= $search['brandCd'] ?>">삭제</button>
                                </div>
                            <?php } ?>
                        </div>

                    </div>
                </td>
                <?php if(gd_is_plus_shop(PLUSSHOP_CODE_PURCHASE) === true && gd_is_provider() === false) { ?>
                    <th>매입처</th>
                    <td>
                        <div class="form-inline">
                            <label>
                                <button type="button" class="btn btn-gray btn-sm js-layer-register" data-type="purchase" data-mode="checkbox">매입처 선택</button>
                            </label>
                            <label class="checkbox-inline mgl10"><input type="checkbox" name="purchaseNoneFl" value="y" <?=gd_isset($checked['purchaseNoneFl']['y']); ?>> 매입처 미지정 상품</label>

                            <div id="purchaseLayer" class="selected-btn-group <?=!empty($search['purchaseNo']) ? 'active' : ''?>">
                                <h5>선택된 매입처 : </h5>
                                <?php if (empty($search['purchaseNo']) === false) {
                                    foreach ($search['purchaseNo'] as $k => $v) { ?>
                                        <div id="info_scm_<?= $v ?>" class="btn-group btn-group-xs">
                                            <input type="hidden" name="purchaseNo[]" value="<?= $v ?>"/>
                                            <input type="hidden" name="purchaseNoNm[]" value="<?= $search['purchaseNoNm'][$k] ?>"/>
                                            <span class="btn"><?= $search['purchaseNoNm'][$k] ?></span>
                                            <button type="button" class="btn btn-icon-delete" data-toggle="delete" data-target="#info_scm_<?= $v ?>">삭제</button>
                                        </div>
                                    <?php }
                                } ?>
                                <label><input type="button" value="전체 삭제" class="btn btn-sm btn-gray" data-toggle="delete" data-target="#purchaseLayer div"/></label>
                            </div>

                        </div>
                    </td>
                <?php } ?>
            </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-sm btn-link js-search-toggle bold">상세검색 <span>닫힘</span></button>
    </div>
    <div class="table-btn">
        <input type="submit" value="검색" class="btn btn-lg btn-black">
    </div>

    <?php if ($isUserHandle) { ?>
    <ul class="nav nav-tabs mgb30" role="tablist">
        <li role="presentation" <?=$search['view'] == 'exchange' ? 'class="active"' : ''?>>
            <a href="../order/order_list_user_exchange.php?view=exchange&<?=$queryString ? 'searchFl=y&' . $queryString : ''?>">교환신청 관리 (<strong>전체 <?=$userHandleCount['exchangeAll']?></strong> | <strong class="text-danger">신청 <?=$userHandleCount['exchangeRequest']?></strong> | <strong class="text-info">처리완료 <?=$userHandleCount['exchangeAccept']?>)</strong></a>
        </li>
        <li role="presentation" <?=$search['view'] == 'back' ? 'class="active"' : ''?>>
            <a href="../order/order_list_user_exchange.php?view=back&<?=$queryString ? 'searchFl=y&' . $queryString : ''?>">반품신청 관리 (<strong>전체 <?=$userHandleCount['backAll']?></strong> | <strong class="text-danger">신청 <?=$userHandleCount['backRequest']?></strong> | <strong class="text-info">처리완료 <?=$userHandleCount['backAccept']?>)</strong></a>
        </li>
        <li role="presentation" <?=$search['view'] == 'refund' ? 'class="active"' : ''?>>
            <a href="../order/order_list_user_exchange.php?view=refund&<?=$queryString ? 'searchFl=y&' . $queryString : ''?>">환불신청 관리 (<strong>전체 <?=$userHandleCount['refundAll']?></strong> | <strong class="text-danger">신청 <?=$userHandleCount['refundRequest']?></strong> | <strong class="text-info">처리완료 <?=$userHandleCount['refundAccept']?>)</strong></a>
        </li>
    </ul>

    <div class="table-sub-title">
        <?php
        switch ($search['view']) {
            case 'exchange':
                echo '교환신청 관리';
                break;
            case 'back':
                echo '반품신청 관리';
                break;
            case 'refund':
                echo '환불신청 관리';
                break;
        }
        ?>
    </div>
    <?php } ?>

    <?php
    if (!$isUserHandle && in_array(substr($currentStatusCode, 0, 1), ['','o','p','g','d','s'])) {
        if (isset($search['view'])) {
            $tableHeaderClass = 'table-header-tab';
            if (in_array($currentStatusCode, ['','o'])) {
                $actionClass = 'order';
            } elseif (in_array(substr($currentStatusCode,0, 1), ['p','g','d','s'])) {
                $actionClass = 'orderGoodsSimple';
            }
    ?>
        <ul class="nav nav-tabs mgb0" role="tablist">
            <li role="presentation" <?=$search['view'] == $actionClass ? 'class="active"' : ''?>>
                <a href="../order/<?=$page->page['url']?>?view=<?=$actionClass?>&<?=$queryString ? 'searchFl=y&' . $queryString : ''?>">주문번호별</a>
            </li>
            <li role="presentation" <?=$search['view'] == 'orderGoods' ? 'class="active"' : ''?>>
                <a href="../order/<?=$page->page['url']?>?view=orderGoods&<?=$queryString ? 'searchFl=y&' . $queryString : ''?>">상품주문번호별</a>
            </li>
        </ul>
    <?php
        }
    }
    ?>

    <div class="table-header <?=$tableHeaderClass?>">
        <div class="pull-left">
            검색 <strong class="text-danger"><?= number_format(gd_isset($page->recode['total'], 0)); ?></strong>개 /
            전체 <strong class="text-danger"><?= number_format(gd_isset($page->recode['amount'], 0)); ?></strong>개
            ( 검색된 주문 총 <?php if (!$isProvider) { ?>결제<?php } ?>금액 : <?= gd_currency_symbol() ?><span class="text-danger"><?=gd_money_format($page->recode['totalPrice'])?></span><?=gd_currency_string()?>
            <?php if (false && !$isProvider) { // 아직 환불에 대한 처리방법 결정되지 않음 ?>
            | 총 실결제금액 : <?= gd_currency_symbol() ?><span class="text-danger"><?=gd_money_format($page->recode['totalGoodsPrice'] + $page->recode['totalDeliveryPrice'])?></span><?=gd_currency_string()?>
                - <small>테스트확인용 상품금액: <?=number_format($page->recode['totalGoodsPrice'])?>/
                배송비: <?=number_format($page->recode['totalDeliveryPrice'])?></small>
            <?php } ?>
            )
        </div>
        <div class="pull-right">
            <div class="form-inline">
                <?= gd_select_box('sort', 'sort', $search['sortList'], null, $search['sort']); ?>
                <?= gd_select_box('pageNum', 'pageNum', gd_array_change_key_value([10,20,30,40,50,60,70,80,90,100,200,300,500,]), '개 보기', $page->page['list']); ?>
            </div>
        </div>
    </div>
    <input type="hidden" name="view" value="<?=$search['view']?>"/>
    <input type="hidden" name="searchFl" value="y">
    <input type="hidden" name="applyPath" value="<?=gd_php_self()?>?view=<?=$search['view']?>">
</form>
<!-- // 검색을 위한 form -->

<!-- 프린트 출력을 위한 form -->
<form id="frmOrderPrint" name="frmOrderPrint" action="" method="post" class="display-none">
    <input type="hidden" name="orderPrintCode" value=""/>
    <input type="hidden" name="orderPrintMode" value=""/>
</form>
<!-- // 프린트 출력을 위한 form -->
