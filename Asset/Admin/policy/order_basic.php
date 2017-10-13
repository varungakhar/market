<form id="frmOrderBasic" name="frmOrderBasic" action="order_ps.php" method="post" target="ifrmProcess">
    <input type="hidden" name="mode" value="updateOrderBasic"/>

    <div class="page-header js-affix">
        <h3><?php echo end($naviMenu->location); ?></h3>
        <input type="submit" value="저장" class="btn btn-red">
    </div>

    <div class="table-title gd-help-manual">
        주문 설정
    </div>
    <table class="table table-cols">
        <colgroup><col class="width-md" /><col /></colgroup>
        <tbody>
        <tr>
            <th>
                결제페이지<br />청약의사 재확인 설정
            </th>
            <td>
                <div class="form-inline radio">
                    <label class="radio-inline">
                        <input type="radio" name="reagreeConfirmFl" value="y" <?=$checked['reagreeConfirmFl']['y']?>> 사용함
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="reagreeConfirmFl" value="n" <?=$checked['reagreeConfirmFl']['n']?>> 사용안함
                    </label>
                </div>
            </td>
        </tr>
        <tr>
            <th>자동배송완료</th>
            <td>
                <div class="form-inline radio">
                    <label>
                        <input type="radio" name="autoDeliveryCompleteFl" value="y" <?=$checked['autoDeliveryCompleteFl']['y']?>> '배송중'으로 주문상태 변경한 뒤
                    </label>
                    <input type="text" name="autoDeliveryCompleteDay" class="form-control width-2xs js-number" value="<?=$data['autoDeliveryCompleteDay']?>" title="" required="required" > 일 후 '배송완료'로 자동 주문상태 변경
                </div>
                <div class="form-inline radio">
                    <label>
                        <input type="radio" name="autoDeliveryCompleteFl" value="n" <?=$checked['autoDeliveryCompleteFl']['n']?>> 사용안함
                    </label>
                </div>
            </td>
        </tr>
        <tr>
            <th>자동구매확정</th>
            <td>
                <div class="form-inline radio">
                    <label>
                        <input type="radio" name="autoOrderConfirmFl" value="y" <?=$checked['autoOrderConfirmFl']['y']?>> '배송완료'로 주문상태 변경한 뒤
                    </label>
                    <input type="text" name="autoOrderConfirmDay" class="form-control width-2xs js-number" value="<?=$data['autoOrderConfirmDay']?>" title="" required="required" > 일 후 '구매확인'으로 자동 주문상태 변경
                </div>
                <div class="form-inline radio">
                    <label>
                        <input type="radio" name="autoOrderConfirmFl" value="n" <?=$checked['autoOrderConfirmFl']['n']?>> 사용안함
                    </label>
                </div>
            </td>
        </tr>
        <?php if (gd_is_plus_shop(PLUSSHOP_CODE_USEREXCHANGE)) { ?>
        <tr>
            <th>고객 교환/반품/환불<br>신청기능 사용설정</th>
            <td>
                <div class="form-inline radio">
                    <label>
                        <input type="radio" name="userHandleFl" value="y" <?=$checked['userHandleFl']['y']?>> 사용함
                    </label>
                </div>
                <div class="form-inline radio">
                    <label>
                        <input type="radio" name="userHandleFl" value="n" <?=$checked['userHandleFl']['n']?>> 사용안함
                    </label>
                    <span class="notice-info">사용안함 선택 시 쇼핑몰에서 구매자가 직접 교환/반품/환불 신청할 수 없습니다.</span>
                </div>
            </td>
        </tr>
        <?php } ?>
        <!--<tr>
            <th>수기주문 결제유형</th>
            <td>
                <div class="form-inline radio">
                    <label>
                        <input type="radio" name="handOrderType" value="online" <?=$checked['handOrderType']['online']?>> 온라인 결제만 제공 (무통장입금)
                    </label>
                </div>
                <div class="form-inline radio">
                    <label>
                        <input type="radio" name="handOrderType" value="all" <?=$checked['handOrderType']['all']?>> 온라인/오프라인결제 제공 (신용카드/무통장입금) <span class="text-muted">오프라인 결제는 매출통계에만 적용됩니다.</span>
                    </label>
                </div>
            </td>
        </tr>-->
        </tbody>
    </table>
</form>


