<script type="text/javascript">
    <!--
    $(document).ready(function () {

        $(".js-layer-naver-stats").click(function(e){
            layer_add_info('naver_stats');
        });

        $("input[name='naverVersion']").click(function () {
            if($(this).val() =='3') {
                $(".js-naver-summary-url").hide();
                $("input[name='nv_pcard']").val('');
            } else {
                $(".js-naver-summary-url").show();
            }
        });

        <?php if($data['naverVersion'] =='3') { ?>
        $(".js-naver-summary-url").hide();
        <?php } ?>

    });
    //-->
</script>

<form id="frmConfig" action="dburl_ps.php" method="post" target="ifrmProcess">
    <input type="hidden" name="type" value="config"/> <input type="hidden" name="company" value="naver"/>
    <input type="hidden" name="mode" value="config"/>
    <div class="page-header js-affix">
        <h3><?php echo end($naviMenu->location); ?>
            <small></small>
        </h3>
        <input type="submit" value="저장" class="btn btn-red">
    </div>

    <table class="table table-cols">
        <colgroup>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tr>
            <th>사용 여부</th>
            <td>
                <label class="radio-inline" title="네이버 공통유입 스크립트를 사용하시려면 &quot;사용함&quot;을 체크를 하시면 됩니다.!">
                    <input type="radio" name="naverFl" value="y" <?php echo gd_isset($checked['naverFl']['y']); ?> />사용함
                </label>
                <label class="radio-inline" title="네이버 공통유입 스크립트를 사용하지 않으려면 &quot;사용안함&quot;을 체크를 하시면 됩니다.!">
                    <input type="radio" name="naverFl" value="n" <?php echo gd_isset($checked['naverFl']['n']); ?> />사용안함
                </label>
            </td>
        </tr>
        <tr>
            <th>CPA 주문수집<br/> 동의여부</th>
            <td>
                <label class="checkbox-inline">
                    <input type="checkbox" name="cpaAgreement" value="y" <?php echo gd_isset($checked['cpaAgreement']['y']); ?> />사용함
                    <?php if ($data['cpaAgreement'] == 'y') { ?>
                        <span class="notice-ref notice-sm">(동의 일시 : <?= $data['cpaAgreementDt'] ?>)</span> <?php } ?>
                </label>
                <div class="notice-info mgb10">
                    네이버에서 CPA 주문수집에 동의하신 경우에만 주문완료시 주문정보를 네이버측으로 전송합니다.<br/> CPA 주문수집이 정상적으로 이루어 져야만 차후 CPA로의 과금전환이 이루어질수 있습니다.<br/> 주문수집에 동의하신뒤에는 반드시 체크하여주시기 바라며, CPA 주문수집에대한 문의는 네이버 쇼핑광고센터로 문의주시기 바랍니다.<br/> 네이버 쇼핑광고센터 : 02) 3469-3360<br/>

                </div>
            </td>
        </tr>
        <tr>
            <th>네이버 쇼핑<br/>버전 설정</th>
            <td>
                <label class="radio-inline">
                    <input type="radio" name="naverVersion" value="3" <?php echo gd_isset($checked['naverVersion']['3']); ?> />v3.0 (신규)
                </label>
                <label class="radio-inline" >
                    <input type="radio" name="naverVersion" value="2" <?php echo gd_isset($checked['naverVersion']['2']); ?> />v2.0 (기존)
                </label>
                <div class="notice-info mgb10">
                    네이버 쇼핑 버전 설정 정보와 네이버 쇼핑 파트너존>상품관리>상품정보수신현황>쇼핑몰 상품DB URL에 설정된 EP버전이 동일해야 합니다. <br/>
                    동일하게 설정되지 않은 경우 상품 정보를 수신할 수 없습니다.  <a href="http://adcenter.shopping.naver.com" target="_blank" class="btn-link">네이버 쇼핑파트너존</a>
                </div>
            </td>
        </tr>
        <tr>
            <th>네이버 쇼핑<br/>상품 노출 설정</th>
            <td>
                <a href="/marketing/naver_goods_config.php" class="btn btn-gray btn-sm" target="_blank">네이버 쇼핑 상품 설정</a>
                <input type="button" value="노출상품 현황 확인" class="btn btn-gray btn-sm js-layer-naver-stats"/>
            </td>
        </tr>
        <tr>
            <th>상품가격 설정</th>
            <td>
                <div class="notice-info">
                    네이버 쇼핑에 노출되는 가격정보를 설정합니다. 설정 사항을 체크할 경우 쿠폰 및 할인율이 적용되어 노출됩니다.
                </div>
                <div class="notice-danger">
                    해당 기능 이용 시 상품가격이 실제 판매가랑 달라져 네이버 검수 시 반려사유가 될 수 있으므로 유의 바랍니다.
                </div>
                <br/>
                <div>
                    <label class="checkbox-inline"><input type="checkbox" name="dcGoods" value="y" <?php echo gd_isset($checked['dcGoods']['y']); ?>>상품할인</label>
                </div>
                <br/>
                <div>
                    <span class="noline"><label class="checkbox-inline"><input type="checkbox" name="dcCoupon" value="y" <?php echo gd_isset($checked['dcCoupon']['y']); ?>>쿠폰적용</label></span>
                    <div class="notice-info">
                        쿠폰은 <a href="../promotion/coupon_list.php"  class="snote btn-link">프로모션 &gt; 쿠폰리스트 </a>에서 관리 가능합니다.
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <th>상품명 머릿말 설정</th>
            <td>
                <input type="text" name="goodsHead" class="form-control" style="width:250px;" value="<?php echo gd_isset($data['goodsHead']) ?>"/>
                <div class="notice-info">
                    상품명 머리말 설정을 위한 치환코드<br/> 머리말 상품에 입력된 "상품번호"를 넣고 싶을 때 : {_goodsNo}<br/> 머리말 상품에 입력된 "제조사"를 넣고 싶을 때 : {_maker}<br/> 머리말 상품에 입력된 "브랜드"를 넣고 싶을 때 : {_brand}
                </div>
            </td>
        </tr>
        <tr>
            <th>네이버 쇼핑<br/> 이벤트 문구 설정</th>
            <td>
                <div class="form-inline">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="naverEventCommon" value="y" <?php echo gd_isset($checked['naverEventCommon']['y']); ?>>공통 문구
                        <input type="text" name="naverEventDescription" class="form-control width-2xl js-maxlength" maxlength="100" value="<?php echo gd_isset($data['naverEventDescription']) ?>"/>
                    </label>
                </div>
                <label class="checkbox-inline">
                    <input type="checkbox" name="naverEventGoods" value="y" <?php echo gd_isset($checked['naverEventGoods']['y']); ?>>상품별 문구
                </label>
                <div class="notice-info">
                    상품별 문구는
                    <a href="/goods/goods_register.php" target="_blank" class="btn-link">상품>상품관리>상품등록</a>의 상품상세설명에서 이벤트 문구를 입력하거나,
                    <a href="/goods/excel_goods_up.php" target="_blank" class="btn-link">상품>상품엑셀관리>상품업로드</a>에서 일괄 등록할 수 있습니다.
                </div>
                <div class="notice-info">이벤트 문구는 공통문구 + 상품별문구로 구성됩니다. 이벤트 문구에서 사용할 항목을 체크하세요</div>
                <div class="notice-info">이벤트 문구 설정 후 반드시
                    <a href="http://adcenter.shopping.naver.com" target="_blank" class="btn-link">네이버 쇼핑파트너존</a>에서 등록 요청을 하셔야 하며, 담당자가 확인 후 노출 처리 됩니다.
                </div>
                </div>
            </td>
        </tr>
        <tr>
            <th>네이버 쇼핑<br/> 무이자 할부정보</th>
            <td>
                <input type="text" name="nv_pcard" class="form-control" style="width:250px;" value="<?php echo gd_isset($data['nv_pcard']) ?>"/>
                <div class="notice-info" >
                    예) 기존 버전(v2.0) : 삼성3/현대3/국민6<br/>
                    신규 버전(v3.0) : 삼성카드^2~3|현대카드^2~3|KB국민카드^2~6
                </div>
            </td>
        </tr>
    </table>
</form>

<?php if ($data['naverFl'] == 'y') { ?>
    <form id="frmGen" action="dburl_ps.php" method="post">
        <input type="hidden" name="type" value="gen"/> <input type="hidden" name="company" value="naver"/>
        <table class="table table-rows">
            <thead>
            <tr>
                <th>업체</th>
                <th>상품 DB URL [페이지 미리보기]</th>
            </tr>
            </thead>
            <tbody>
            <tr class="center">
                <td class="width-md">네이버 쇼핑<br/>상품 DB URL</td>
                <td class="left">
                    <?php
                    $dbUrlFile = UserFilePath::data('dburl', 'naver', 'naver_all');

                    echo '<div>[전체상품] <a href="' . $mallDomain . 'partner/naver_all.php" target="_blank">' . $mallDomain . 'partner/naver_all.php</a> <a href="' . $mallDomain . 'partner/naver_all.php" target="_blank" class="btn btn-gray btn-sm">미리보기</a></div>';

                    ?>
                    <?php

                    echo '<div class="mgt5 js-naver-summary-url">[요약상품] <a href="' . $mallDomain . 'partner/naver_summary.php" target="_blank">' . $mallDomain . 'partner/naver_summary.php</a>  <a href="' . $mallDomain . 'partner/naver_summary.php" target="_blank" class="btn btn-gray btn-sm">미리보기</a></div>';

                    ?>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
<?php } ?>
<div class="center" style="margin:20px;">
    <a href="https://adcenter.shopping.naver.com/" target="_blank"/><img src="<?= PATH_ADMIN_GD_SHARE ?>img/marketing/btn_naver_go.gif"/></a>
</div>
