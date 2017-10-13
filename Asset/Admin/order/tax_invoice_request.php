<div class="page-header js-affix">
    <h3><?php echo end($naviMenu->location); ?></h3>
</div>

<!-- 검색을 위한 form -->
<form id="frmSearchOrder" method="get" class="js-form-enter-submit">
    <input type="hidden" name="detailSearch" value="<?= $search['detailSearch']; ?>"/>

    <div class="table-title gd-help-manual">발행 요청 검색</div>

    <div class="search-detail-box">
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
                <td>
                    <div class="form-inline">
                        <?= gd_select_box('key', 'key', $search['combineSearch'], null, $search['key'], null, null, 'form-control input-sm'); ?>
                        <input type="text" name="keyword" value="<?= $search['keyword']; ?>" class="form-control"/>
                    </div>
                </td>
                <th>사업자번호</th>
                <td>
                    <div class="form-inline">
                        <input type="text" name="taxBusiNo" value="<?= $search['taxBusiNo']; ?>" class="form-control js-number"/>
                    </div>
                </td>
            </tr>
            <tr>
                <th>발행요청일</th>
                <td colspan="3">
                    <div class="form-inline">
                        <input type="hidden" name="searchDateFl" value="regDt">
                        <div class="input-group js-datepicker">
                            <input type="text" name="searchDate[]" value="<?= $search['searchDate'][0]; ?>" class="form-control width-xs">
                                <span class="input-group-addon">
                                    <span class="btn-icon-calendar">
                                    </span>
                                </span>
                        </div>
                        ~
                        <div class="input-group js-datepicker">
                            <input type="text" name="searchDate[]" value="<?= $search['searchDate'][1]; ?>" class="form-control width-xs">
                                <span class="input-group-addon">
                                    <span class="btn-icon-calendar">
                                    </span>
                                </span>
                        </div>

                        <div class="btn-group js-dateperiod" data-toggle="buttons" data-target-name="searchDate[]">
                            <label class="btn btn-white btn-sm hand"><input type="radio" name="searchPeriod" value="0">오늘
                            </label>
                            <label class="btn btn-white btn-sm hand"><input type="radio" name="searchPeriod" value="7">7일
                            </label>
                            <label class="btn btn-white btn-sm hand"><input type="radio" name="searchPeriod" value="15">15일
                            </label>
                            <label class="btn btn-white btn-sm hand"><input type="radio" name="searchPeriod" value="30">1개월
                            </label>
                            <label class="btn btn-white btn-sm hand"><input type="radio" name="searchPeriod" value="90">3개월
                            </label>
                            <label class="btn btn-white btn-sm hand"><input type="radio" name="searchPeriod" value="-1">전체
                            </label>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
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
                <?= gd_select_box('pageNum', 'pageNum', gd_array_change_key_value([10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 200, 300, 500,]), '개 보기', $page->page['list']); ?>
            </div>
        </div>
    </div>
</form>
<!-- // 검색을 위한 form -->

<!-- 프린트 출력을 위한 form -->
<form id="frmOrderPrint" name="frmOrderPrint" action="" method="post" class="display-none">
    <input type="hidden" name="orderPrintCode" value=""/>
    <input type="hidden" name="orderPrintMode" value=""/>
</form>
<!-- // 프린트 출력을 위한 form -->

<div>
    <form id="frmList" action="" method="get" target="ifrmProcess">
        <input type="hidden" name="mode" value="">
        <input type="hidden" name="godobillSend" value="">

        <div class="table-action mgt0 mgb0">
            <div class="pull-left form-inline">
                <button type="button" class="btn btn-white checkDelete">선택 삭제</button>
            </div>
            <div class="pull-right">
                <div class="form-inline">
                    <?php if ($taxInfo['paper'] == 'y') { ?>
                        <button type="button" class="btn btn-white btn-icon-tax-common sendGeneral">일반세금계산서발행</button><?php } ?>
                    <?php if ($taxInfo['godobill'] == 'y') { ?>
                        <button type="button" class="btn btn-white btn-icon-tax-godobill sendElectronic">전자세금계산서발행</button><?php } ?>
                </div>
            </div>
        </div>

        <table class="table table-rows tax-invoice-request">
            <thead>
            <tr>
                <th class="width2p"><input type="checkbox" value="y" class="js-checkall" data-target-name="orderNo[]"></th>
                <th class="width3p">번호</th>
                <th class="width5p">발행요청일</th>
                <th class="width5p">주문번호</th>
                <th class="width5p">주문상태</th>
                <th class="width5p">요청인</th>
                <th>사업자정보</th>
                <th class="width5p">결제금액</th>
                <th class="width5p">세금등급</th>
                <th class="width5p">발행액</th>
                <th class="width5p">공급가액</th>
                <th class="width5p">세액</th>
                <th class="width5p">발행일</th>
                <th class="width8p">메모</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (gd_isset($data)) {
                $taxInvoiceStr = array('t' => '과세', 'f' => '면세');
                foreach ($data as $key => $val) {
                    foreach ($val['taxInvoiceInfo'] as $k => $v) {
                        ?>
                        <tr <?php if (substr($val['orderStatus'], 0, 1) == 'r') {
                            echo "style=background:#efefef";
                        } ?>>
                            <?php if ($k == '0') { ?>
                                <td class="center" rowspan="<?= count($val['taxInvoiceInfo']) ?>">
                                    <input type="checkbox" name="orderNo[]" value="<?php echo $val['orderNo']; ?>"/>
                                    <input type="hidden" name="taxInvoiceData[<?= $val['orderNo'] ?>][sno]" value="<?php echo $val['sno']; ?>"/>
                                </td>
                                <td class="center" rowspan="<?= count($val['taxInvoiceInfo']) ?>"><?php echo number_format($page->idx--); ?></td>
                                <td class="center" rowspan="<?= count($val['taxInvoiceInfo']) ?>"><?php echo gd_date_format('Y-m-d', $val['regDt']); ?></td>
                                <td class="center" rowspan="<?= count($val['taxInvoiceInfo']) ?>"><a href="./order_view.php?orderNo=<?= $val['orderNo']; ?>" title="주문번호" target="_blank"
                                                                                                     class="btn btn-link font-num"><?php echo $val['orderNo']; ?></a></td>
                                <td class="center" rowspan="<?= count($val['taxInvoiceInfo']) ?>"><?php echo $val['orderStatusStr']; ?></td>
                                <td class="center" rowspan="<?= count($val['taxInvoiceInfo']) ?>"><?php echo $val['requestNm']; ?>(<?= $val['memId'] ? $val['memId'] : "비회원"; ?>)</td>
                                <td rowspan="<?= count($val['taxInvoiceInfo']) ?>">
                                    <input type="hidden" class="form-control width-xs" name="taxInvoiceData[<?= $val['orderNo'] ?>][taxPolicy]" value="<?= $val['taxPolicy'] ?>"/>
                                    <input type="hidden" class="form-control width-xs" name="taxInvoiceData[<?= $val['orderNo'] ?>][requestNm]" value="<?= $val['requestNm'] ?>"/>
                                    <input type="hidden" class="form-control width-xs" name="taxInvoiceData[<?= $val['orderNo'] ?>][requestGoodsNm]" value="<?= $val['requestGoodsNm'] ?>"/>

                                    <div class="form-inline business-information">
                                        <div>
                                            <span>사업자 번호 : </span>
                                            <input type="text" class="form-control width-xs js-number" name="taxInvoiceData[<?= $val['orderNo'] ?>][taxBusiNo]" value="<?= $val['taxBusiNo'] ?>"/>
                                        </div>
                                        <div>
                                            <span>회사명 : </span>
                                            <input type="text" class="form-control width-xs" name="taxInvoiceData[<?= $val['orderNo'] ?>][taxCompany]" value="<?= $val['taxCompany'] ?>"/>
                                        </div>
                                        <div>
                                            <span>대표자명 : </span>
                                            <input type="text" class="form-control width-xs" name="taxInvoiceData[<?= $val['orderNo'] ?>][taxCeoNm]" value="<?= $val['taxCeoNm'] ?>"/>
                                        </div>
                                        <div>
                                            <span>업태 : </span>
                                            <input type="text" class="form-control width-xs" name="taxInvoiceData[<?= $val['orderNo'] ?>][taxService]" value="<?= $val['taxService'] ?>"/>
                                        </div>
                                        <div>
                                            <span>종목 : </span>
                                            <input type="text" class="form-control width-xs" name="taxInvoiceData[<?= $val['orderNo'] ?>][taxItem]" value="<?= $val['taxItem'] ?>"/>
                                        </div>
                                        <div>
                                            <span>사업장 주소 : </span>
                                            <input type="text" class="form-control width-2xl" name="taxInvoiceData[<?= $val['orderNo'] ?>][taxAddress]" value="<?= $val['taxAddress'] ?>"/><br/>
                                        </div>
                                        <div>
                                            <span>발행 이메일 : </span>
                                            <input type="text" class="form-control width-2xl" name="taxInvoiceData[<?= $val['orderNo'] ?>][taxEmail]" value="<?= $val['taxEmail'] ?>"/><br/>
                                        </div>
                                    </div>
                                </td>
                            <?php } ?>
                            <td class="center"><span class="font-num"><?php echo gd_currency_display($v['totalPrice']); ?></span></td>
                            <td class="center"><?php echo $taxInvoiceStr[$v['tax']]; ?></td>
                            <td class="center"><span class="font-num"><?php echo gd_currency_display($v['totalPrice']); ?></td>
                            <td class="center"><span class="font-num"><?php echo gd_currency_display($v['price']); ?></td>
                            <td class="center"><span class="font-num"><?php echo gd_currency_display($v['vat']); ?></td>
                                <?php if ($k == '0') { ?>
                                    <td class="center" rowspan="<?= count($val['taxInvoiceInfo']) ?>">
                                        <input type="text" class="form-control width-xs" name="taxInvoiceData[<?= $val['orderNo'] ?>][issueDt]" value="<?= $val['issueDt'] ?>"/>
                                    </td>
                                    <td class="center" rowspan="<?= count($val['taxInvoiceInfo']) ?>"><input type="text" class="form-control" name="taxInvoiceData[<?= $val['orderNo'] ?>][adminMemo]"
                                                                                                             value="<?= $val['adminMemo'] ?>"/></td>
                                <?php } ?>
                        </tr>
                        <?php
                    }
                }
            } else {
                ?>
                <tr>
                    <td class="no-data" colspan="15">검색된 정보가 없습니다.</td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>

        <div class="table-action clearfix">
            <div class="pull-left form-inline">
                <button type="button" class="btn btn-white checkDelete">선택 삭제</button>
            </div>
            <div class="pull-right">
                <div class="form-inline">
                    <?php if ($taxInfo['paper'] == 'y') { ?>
                        <button type="button" class="btn btn-white btn-icon-tax-common sendGeneral">일반세금계산서발행</button><?php } ?>
                    <?php if ($taxInfo['godobill'] == 'y') { ?>
                        <button type="button" class="btn btn-white btn-icon-tax-godobill sendElectronic">전자세금계산서발행</button><?php } ?>
                    <button type="button" class="btn btn-white btn-icon-excel js-excel-download" data-target-form="frmSearchOrder" data-search-count="<?= $page->recode['total'] ?>" data-total-count="<?= $page->recode['amount'] ?>" data-target-list-form="frmList" data-target-list-sno="orderNo">엑셀다운로드</button>
                </div>
            </div>
        </div>
    </form>
    <div class="notice-info">전자세금계산서 이용시, 국세청 시스템에서 지원되지 않는 일부 특정문자는 제외되어 전송됩니다.  ex)랲, 됬, 풻 등</div>
    <div class="notice-info">전자세금계산서발행 신청 후, <a href="https://godobill.godo.co.kr/front/intro.php" target="_blank" class="btn-link">고도빌>전자세금계산서 발급/관리>전자세금계산서 관리</a>에서 추가적으로 '전송'을 진행하셔야만 발급이 완료됩니다.</div>
    <div class="notice-danger">전자세금계산서를 고도빌에서 수기로 발행하는 경우, 세금계산서가 이중으로 발행될 수 있으니 주의 바랍니다.</div>
    <div class="center"><?php echo $page->getPage(); ?></div>
</div>

<script type="text/javascript">
    <!--
    $(document).ready(function () {
        // 삭제
        $('button.checkDelete').click(function () {
            var chkCnt = $('input:checkbox[name="orderNo[]"]:checked').length;
            if (chkCnt == 0) {
                alert('선택된 내역이 없습니다.');
                return;
            }
            if (confirm('선택한 ' + chkCnt + '개의 세금계산서 발행요청건을 정말로 삭제하시겠습니까?\n삭제시 정보는 복구 되지 않습니다.')) {
                $('#frmList input[name=\'mode\']').val('tax_invoice_delete');
                $('#frmList').attr('method', 'post');
                $('#frmList').attr('action', 'tax_invoice_ps.php');
                $('#frmList').submit();
            }
        });

        var emailRegular = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

        // 일반세금계산서
        $('button.sendGeneral').click(function () {
            var chkCnt = $('input:checkbox[name="orderNo[]"]:checked').length;
            if (chkCnt == 0) {
                alert('선택된 내역이 없습니다.');
                return;
            }
            var checkIssueDt = 0;
            $('input:checkbox[name="orderNo[]"]:checked').each(function (index) {
                if (!emailRegular.test($.trim($('input:text[name="taxInvoiceData[' + $(this).val() + '][taxEmail]"]').val()))) {
                    alert('발행 이메일을 정확하게 입력하여 주세요.');
                    return false;
                }

                if ($.trim($('input:text[name="taxInvoiceData[' + $(this).val() + '][issueDt]"]').val()) == '') {
                    alert('발행일이 없습니다. 발행일을 입력해 주세요.');
                    return false;
                }
                checkIssueDt++;
            });
            if (checkIssueDt == chkCnt) {
                if (confirm('선택한 ' + chkCnt + '개를 일반 세금계산서 발행하시겠습니까?')) {
                    $('#frmList input[name=\'mode\']').val('send_invoice');
                    $('#frmList input[name=\'godobillSend\']').val('n');

                    $('#frmList').attr('method', 'post');
                    $('#frmList').attr('action', 'tax_invoice_ps.php');
                    $('#frmList').submit();
                }
            }
        });

        // 전자세금계산서
        $('button.sendElectronic').click(function () {
            var chkCnt = $('input:checkbox[name="orderNo[]"]:checked').length;
            if (chkCnt == 0) {
                alert('선택된 내역이 없습니다.');
                return;
            }

            var checkIssueDt = 0;
            $('input:checkbox[name="orderNo[]"]:checked').each(function (index) {
                if (!emailRegular.test($.trim($('input:text[name="taxInvoiceData[' + $(this).val() + '][taxEmail]"]').val()))) {
                    alert('발행 이메일을 정확하게 입력하여 주세요.');
                    return false;
                }

                if ($.trim($('input:text[name="taxInvoiceData[' + $(this).val() + '][issueDt]"]').val()) == '') {
                    alert('발행일이 없습니다. 발행일을 입력해 주세요.');
                    return false;
                }
                checkIssueDt++;
            });

            if (checkIssueDt == chkCnt) {
                if (confirm('선택한 ' + chkCnt + '개를 전자 세금계산서 발행하시겠습니까?')) {
                    $('#frmList input[name=\'mode\']').val('send_invoice');
                    $('#frmList input[name=\'godobillSend\']').val('y');

                    $('#frmList').attr('method', 'post');
                    $('#frmList').attr('action', 'tax_invoice_ps.php');
                    $('#frmList').submit();
                }
            }
        });

        $('select[name=\'pageNum\']').change(function () {
            $('#frmSearchOrder').submit();
        });

        $('select[name=\'sort\']').change(function () {
            $('#frmSearchOrder').submit();
        });
    });
    //-->
</script>
