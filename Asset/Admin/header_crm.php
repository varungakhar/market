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
 * @link      http://www.godo.co.kr
 * CRM 팝업 상단
 */
$memberFl = $memberData['memberFl'] == 'personal' ? '개인' : '사업자';
$sexFl = $memberData['sexFl'] == 'm' ? '남자' : $memberData['sexFl'] == 's' ? '여자' : '';
$groupInfo = gd_member_groups();
$groupName = $groupInfo[$memberData['groupSno']];
$navTabs = Request::get()->get('navTabs', substr(Request::getInfoUri()['filename'], 11));
$active['navTabs'][$navTabs] = 'active';
$cellPhone = is_array($memberData['cellPhone']) ? implode('', $memberData['cellPhone']) : $memberData['cellPhone'];
$memberMall = $gGlobal['mallList'][$memberData['mallSno']];
?>
<div class="page-header form-inline crm">
    <h3>회원 관리</h3>
    <form id="formMemberSearch">
        <input type="hidden" name="memNo" value="<?= $memberData['memNo'] ?>">
        <input type="hidden" name="memNm" value="<?= $memberData['memNm'] ?>">
        <input type="hidden" name="cellPhone" value="<?= $cellPhone ?>">
        <div class="pull-right pdr0">
            <label class="control-label">회원검색</label>
            <input type="text" class="form-control" placeholder="회원검색" name="keyword">
            <input type="submit" class="btn btn-hfix btn-black" value="검색">
            <input type="button" class="btn close" value="x">
        </div>
    </form>
</div>

<div class="crm-summary row">
    <div class="col-xs-6 crm-summary-left">
        <h3><a href="member_crm.php?memNo=<?= $memberData['memNo']; ?>"><?= $memberData['memNm'] . '(' . $memberData['nickNm'] . ')' ?></a></h3>
        <button type="button" class="btn btn-icon-mail" id="btnSendMail" data-email="<?= $memberData['email'] ?>" data-mailling-fl="<?= $memberData['maillingFl'] ?>">메일</button>
        <?php if ($memberData['mallSno'] == $gGlobal['defaultMallSno']) { ?>
            <button type="button" class="btn btn-icon-sms js-sms-send" data-cellphone="<?= $cellPhone ?>" data-smsfl="<?= $memberData['smsFl'] ?>" data-memno="<?= $memberData['memNo']; ?>" data-type="select" data-opener="member" data-target-selector="this">SMS
            </button>
        <?php } ?>
        <button type="button" class="btn btn-icon-cs" id="btnCounsel">상담</button>
        <button type="button" class="btn btn-gray">
            <span class="flag flag-16 flag-<?= $memberMall['domainFl']; ?>"></span><?= $memberMall['mallName']; ?>
        </button>
        <ul class="list-inline">
            <li><?= $groupName ?></li>
            <li>최종로그인일: <?= gd_date_format('Y-m-d', $memberData['lastLoginDt']) ?></li>
        </ul>
    </div>
    <div class="col-xs-6 crm-summary-right text-right">
        <ul class="list-inline">
            <li>
                <strong class="icon-mileage">마일리지</strong>
                <div><a href="./member_crm_mileage.php?memNo=<?= $memberData['memNo'] ?>&navTabs=mileage"><?= gd_money_format($memberData['mileage']) . gd_display_mileage_unit() ?></a></div>
            </li>
            <li>
                <strong class="icon-deposit">예치금</strong>
                <div><a href="./member_crm_deposit.php?memNo=<?= $memberData['memNo'] ?>&navTabs=deposit"><?= gd_money_format($memberData['deposit']) . gd_display_deposit('unit') ?></a></div>
            </li>
            <li>
                <strong class="icon-coupon">쿠폰</strong>
                <div><a href="./member_crm_coupon.php?memNo=<?= $memberData['memNo']; ?>&navTabs=coupon"><?= number_format($memberCouponCount); ?></a></div>
            </li>
        </ul>
    </div>
</div>

<ul class="crm-summary-count list-inline">
    <li>총 구매금액
        <strong class="text-red"><?= gd_currency_display($memberData['saleAmt']) ?></strong>
        &nbsp;|
    </li>
    <li>총 구매건수 <?= $memberData['saleCnt'] ?>&nbsp;|</li>
    <li>총 상담건수 <?= $memberData['counselCount'] ?>&nbsp;|</li>
    <li>1:1 문의건수
        <strong class="text-red"><?= $memberData['noAnswerCount'] ?></strong>
        /<?= $memberData['questionCount'] ?>
    </li>
</ul>

<ul class="nav nav-tabs mgb20" id="navTabsHeader">
    <li role="presentation" class="<?= $active['navTabs']['0']; ?>">
        <a href="member_crm.php?memNo=<?= $memberData['memNo']; ?>">요약보기</a>
    </li>
    <li role="presentation" class="<?= $active['navTabs']['detail']; ?>">
        <a href="member_crm_detail.php?memNo=<?= $memberData['memNo']; ?>&navTabs=detail">회원정보</a>
    </li>
    <li role="presentation" class="<?= $active['navTabs']['order']; ?>">
        <a href="member_crm_order.php?memNo=<?= $memberData['memNo']; ?>&navTabs=order">주문내역</a>
    </li>
    <li role="presentation" class="<?= $active['navTabs']['mileage']; ?>">
        <a href="member_crm_mileage.php?memNo=<?= $memberData['memNo']; ?>&navTabs=mileage">마일리지내역</a>
    </li>
    <li role="presentation" class="<?= $active['navTabs']['deposit']; ?>">
        <a href="member_crm_deposit.php?memNo=<?= $memberData['memNo']; ?>&navTabs=deposit">예치금내역</a>
    </li>
    <li role="presentation" class="<?= $active['navTabs']['coupon']; ?>">
        <a href="member_crm_coupon.php?memNo=<?= $memberData['memNo']; ?>&navTabs=coupon">쿠폰내역</a>
    </li>
    <li role="presentation" class="<?= $active['navTabs']['sms']; ?>">
        <a href="member_crm_sms.php?memNo=<?= $memberData['memNo']; ?>&navTabs=sms">SMS발송내역</a></li>
    <li role="presentation" class="<?= $active['navTabs']['mail']; ?>">
        <a href="member_crm_mail.php?memNo=<?= $memberData['memNo']; ?>&navTabs=mail">메일발송내역</a>
    </li>
    <li role="presentation" class="<?= $active['navTabs']['counsel']; ?>">
        <a href="member_crm_counsel.php?memNo=<?= $memberData['memNo']; ?>&navTabs=counsel">상담내역</a>
    </li>
    <li role="presentation" class="<?= $active['navTabs']['qa']; ?>">
        <a href="member_crm_qa.php?memNo=<?= $memberData['memNo']; ?>&navTabs=qa">문의내역</a>
    </li>
</ul>


<div class="page-header js-affix">
    <?php
    if (empty($navTabs) === false) {
        switch ($navTabs) {
            case 'detail' :
                echo '<h3>회원정보</h3><input type="submit" value="저장" class="btn btn-red btn-register">';
                break;
            case 'order' :
                echo '<h3>주문내역</h3>';
                break;
            case 'mileage' :
                if ($memberData['mallSno'] == $gGlobal['defaultMallSno']) {
                    echo '<h3>마일리지내역</h3><input type="submit" value="마일리지 지급/차감" class="btn btn-red btn-register">';
                }
                break;
            case 'deposit' :
                if ($memberData['mallSno'] == $gGlobal['defaultMallSno']) {
                    echo '<h3>예치금내역</h3><input type="submit" value="예치금 지급/차감" class="btn btn-red btn-register">';
                }
                break;
            case 'coupon' :
                if ($memberData['mallSno'] == $gGlobal['defaultMallSno']) {
                    echo '<h3>쿠폰내역</h3>';
                }
                break;
            case 'sms' :
                if ($memberData['mallSno'] == $gGlobal['defaultMallSno']) {
                    echo '<h3>SMS발송내역</h3><input type="submit" value="SMS발송" class="btn btn-red btn-register">';
                }
                break;
            case 'mail' :
                echo '<h3>메일발송내역</h3><input type="submit" value="메일발송" class="btn btn-red btn-register">';
                break;
            case 'counsel' :
                echo '<h3>상담내역</h3><input type="submit" value="상담등록" class="btn btn-red btn-register">';
                break;
            case 'qa' :
                echo '<h3>문의내역</h3>';
                break;
            default:
                break;
        }
    } else {
        echo '<h3>요약보기</h3>';
    }
    ?>
</div>
<style>
    body { overflow-y:scroll; }
</style>
