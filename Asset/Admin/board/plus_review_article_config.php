<style>
    .plus_review_config .js-number {
        width: 70px !important;
    }

    .plus_review_config ul li {
        margin-bottom: 5px;
    }

    .plus_review_config #addFormTable tr.placeholder {
        position: relative;
        background-color: red;
        /** More li styles **/
    }

</style>

<script type="text/javascript">
    <!--
    function layer_register(parentLayerFormID, dataInputNm, dataFormID) {
        var addParam = {
            "parentFormID": parentLayerFormID,
            "dataInputNm": dataInputNm,
            "dataFormID": dataFormID,
        };
        layer_add_info('member_group', addParam);
    }

    $(document).ready(function () {
        $('[name=minLimitLengthFl]').bind('click', function () {
            $('[name=minContentsLength]').prop('disabled', $(this).val() == 'n');
        })

        $('[name=minLimitLengthFl]:checked').trigger('click');

        $('[name="photoWidget[thumSizeType]"]').bind('click', function () {
            $('[name="photoWidget[thumWidth]"]').prop('disabled', $(this).val() == 'auto');
        })

        $('[name="photoWidget[thumSizeType]"]:checked').trigger('click');

        $('input[name="photoWidget[cols]"]').keyup(function (e) {
            var code = e.keyCode || e.which;
            if (code == '9') {
                return true;
            }
            $(this).val($(this).val().replace(/[^0-9]/g, ""));
            if ($(this).val() == '') {
                return false;
            }
            if ($(this).val() > 20 || $(this).val() < 1) {
                alert('1~20까지만 설정하실 수 있습니다.');
                $(this).val('');
                return false;
            }
        })

        $('input[name="photoWidget[thumWidth]"]').keyup(function (e) {
            var code = e.keyCode || e.which;
            if (code == '9') {
                return true;
            }
            $(this).val($(this).val().replace(/[^0-9]/g, ""));
            if ($(this).val() == '') {
                return false;
            }
            if ($(this).val() < 1) {
                alert('1이상 설정하실 수 있습니다.');
                $(this).val('');
                return false;
            }
        })

        $('input[name="photoWidget[rows]"]').keyup(function (e) {
            var code = e.keyCode || e.which;
            if (code == '9') {
                return true;
            }
            $(this).val($(this).val().replace(/[^0-9]/g, ""));
            if ($(this).val() == '') {
                return false;
            }
            if ($(this).val() > 20 || $(this).val() < 1) {
                alert('1~20까지만 설정하실 수 있습니다.');
                $('[name="photoWidget[rows]"]').val('');
                return false;
            }
        })


        $('.js-btn-widget').bind('click', function () {
            var cols = $('[name="photoWidget[cols]"]').val();
            var rows = $('[name="photoWidget[rows]"]').val();

            if ((cols > 20 || cols == 0 || _.isEmpty(cols)) || (rows > 20 || rows == 0 || _.isEmpty(cols))) {
                alert('1~20까지만 설정하실 수 있습니다.');
                $('[name="photoWidget[cols]"]').val('');
                $('[name="photoWidget[rows]"]').val('');
                return;
            }

            var thumSizeType = $('[name="photoWidget[thumSizeType]"]:checked').val();
            var thumWidth = $('[name="photoWidget[thumWidth]"]').val();

            if ($('[name="photoWidget[thumSizeType]"]:checked').length < 1) {
                alert('썸네일 사이즈 타입을 선택해주세요.');
                return;
            }

            if ($('[name="photoWidget[thumSizeType]"][value=menual]').is(':checked')) {
                if (_.isEmpty(thumWidth) || thumWidth == 0) {
                    alert('수동설정 사이즈를 1이상 입력해주세요.');
                    return;
                }
            }

            $.ajax({
                method: 'get',
                url: 'plus_review_widget_preview.php',
                data: {'cols': cols, 'rows': rows, 'thumSizeType': thumSizeType, 'thumWidth': thumWidth},
                dataType: 'text'
            }).success(function (data) {
                BootstrapDialog.show({
                    title: '포토리뷰 게시판 위젯생성',
                    size: get_layer_size('wide-xlg'),
                    message: data,
                    closable: true
                });
            }).error(function (e) {
                console.log(e);
                alert(e);
            });
        })

        $('.ui-sort-table').sortable();

        $(':radio[name=authWrite]').bind('click', function () {
//            $('.js-target-not-buyer-area').hide();
            $('.js-target-buyer-area').hide();
            if ($(this).val() == 'buyer') {
                $('.js-target-buyer-area').show();
            }
            else {
//                $('.js-target-not-buyer-area').show();
            }
        })

//        $(':radio[name=authWrite]:checked').trigger('click');
        if ($(':radio[name=authWrite]:checked').val() == 'buyer') {
//            $('.js-target-not-buyer-area').hide();
            $('.js-target-buyer-area').show();
        }
        else {
//            $('.js-target-not-buyer-area').show();
            $('.js-target-buyer-area').hide();
        }

        $(':radio[name=mileageFl]').bind('click', function () {
            if ($(this).val() == 'y') {
                $('.js-miliage-use-tr').show();
            }
            else {
                $('.js-miliage-use-tr').hide();
            }
        });
        $(':radio[name=mileageFl]:checked').trigger('click');

        $('body').on('change', 'select[name^="addForm[inputType]"]', function () { //추가정보양식 입력형태 셀렉트
            if (($(this).val() == 'select')) {
                var addBtn = '<button type="button" class="btn btn-sm btn-white btn-icon-plus js-btn-labal-value-add mgl10">추가</button>';
                $(this).closest('tr').find('[name^="addForm[labelValue]"]').data('type', 'select').attr('placeholder', 'Enter키를 이용 입력값을 연속적으로 입력하세요. ex) 160~165').after(addBtn);
            }
            else {
                $(this).closest('tr').find('[name^="addForm[labelValue]"]').data('type', 'input').attr('placeholder', 'ex) 160~165');
                $(this).closest('tr').find('.js-label-value-list>li:not(:first)').remove();
                $(this).closest('tr').find('.js-btn-labal-value-add').remove();
            }
        });

        $('body').off('keypress').on('keypress', '[name^="addForm[labelValue]"]', function (e) {
            var type = $(this).data('type');
            if (e.which == 13) {
                $(this).closest('tr').find('.js-btn-labal-value-add').trigger('click');
                $(this).closest('tr').find('[name^="addForm[labelValue]"]:last').focus();
                e.preventDefault();
                return false
            }
        })

        $('body').on('click', '.js-btn-labal-value-add', function () {  //추가정보 양식 셀렉트박스일때 입력값 추가 버튼 클릭
            var row = $(this).closest('tr').data('row');
            var removeBtn = '<button type="button" class="btn btn-sm btn-white btn-icon-minus js-btn-labal-remove-li mgl10">삭제</button>';
            var addFormRow = '<li><input type="text" class="form-control width80p" name="addForm[labelValue][' + row + '][]">' + removeBtn + '</li>';
            $(this).closest('tr').find('ul:last').append(addFormRow);
        })

        $('body').on('click', '.js-btn-form-add', function () { //추가정보양식 추가 클릭
            var templateAddForm = _.template($('#templateAddForm').html());
            if ($('#addFormTable tbody tr').length > 10) {
                alert('최대 10개까지 등록 가능합니다.');
                return;
            }

            var maxIndex = 0;
            $('#addFormTable tbody tr').each(function () {
                var index = $(this).data('row');
                if (maxIndex < index) {
                    maxIndex = index;
                }
            });
            $('#addFormTable tbody:last').append(templateAddForm({index: maxIndex + 1}));
            $('.ui-sort-table').sortable();
        })

        $(':radio[name=addFormFl]').bind('click', function () {
            if ($(this).val() == 'y') {
                $('.js-add-form-tr').show();
            }
            else {
                $('.js-add-form-tr').hide();
            }
        })

        $(':radio[name=addFormFl]:checked').trigger('click');

        $('body').on('click', '.js-btn-labal-remove-li', function () {
            $(this).closest('li').remove();
        })

        $('body').on('click', '.js-btn-remove-tr', function () {
            $(this).closest('tr').remove();
        })


        $('.js-group-select').bind('click', function () {
            $(this).closest('td').find('input[type="radio"][value="group"]').trigger('click');
        });

        $('input[name=useFl]').bind('click', function () {
            if ($(this).val() == 'y') {
                alert('사용함 설정 시 기존 "상품후기" 게시판과 함께 노출됩니다. <br> 상품후기 게시판을 사용안함으로 설정해주세요.');
            }
        })

        // 폼검증
        $("#frm").validate({
            ignore: ':hidden',
            dialog: false,
            submitHandler: function (form) {
                form.target = 'ifrmProcess';
                if ($(':radio[name=addFormFl]:checked').val() == 'y') {
                    if ($('input[name^="addForm[labelName]"]').length < 1) {
                        alert('추가정보 양식을 입력해주세요.');
                        return;
                    }
                }

                var minLength = 1;
                var maxLength = 2000;
                var minLimitLength = $('[name=minContentsLength]').val();
                if ($('[name=minLimitLengthFl]:checked').val() == 'y') {
                    if (minLimitLength == 0) {
                        alert('최소 1자 이상은 입력하셔야 합니다.');
                        return;
                    }

                    if (minLimitLength < minLength) {
                        alert('최소 ' + minLength + '자 이상은 입력하셔야 합니다.');
                        return;
                    }
                    if (minLimitLength > maxLength) {
                        alert('최대 ' + maxLength + '자 까지 입력가능합니다.');
                        return;
                    }
                }

                form.submit();
            },
            rules: {
                minContentsLength: {
                    required: function () {
                        return $('[name=minLimitLengthFl][value=y]').is(':checked');
                    },
                }
            },
            messages: {
                minContentsLength: {
                    required: '최소 1자 이상은 입력하셔야 합니다.',
                },

            }
        });
    });

    //-->
</script>
<div class="plus_review_config">
    <form id="frm" action="plus_review_ps.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" value="save">
        <div class="page-header js-affix">
            <h3><?php echo end($naviMenu->location); ?>
                <small>플러스리뷰 게시판에 대한 설정을 합니다.</small>
            </h3>
            <input type="submit" value="저장" class="btn btn-red"/>
        </div>

        <div class="table-title">기본설정</div>
        <table class="table table-cols">
            <colgroup>
                <col class="width-lg"/>
                <col/>
            </colgroup>
            <tr>
                <th>사용 여부</th>
                <td class="form-inline">
                    <label class="radio-inline"><input type="radio" name="useFl"
                                                       value="y" <?= gd_isset($checked['useFl']['y']) ?> /> 사용</label>
                    <label class="radio-inline"><input type="radio" name="useFl"
                                                       value="n" <?= gd_isset($checked['useFl']['n']) ?> /> 사용안함</label>
                </td>
            </tr>

            <tr>
                <th>
                    포토리뷰 게시판 위젯
                </th>
                <td>
                    <table class="table table-cols">
                        <tr class="form-inline">
                            <th class="width-sm">레이아웃</th>
                            <td><input type="text" name="photoWidget[cols]" class=" form-control width-2xs" value=""> *
                                <input name="photoWidget[rows]" class="form-control width-2xs" value="">
                                <span class="notice-info">포토리뷰 위젯생성 전에 레이아웃 및 썸네일 사이즈를 입력해주세요.</span>
                            </td>
                        </tr>
                        <tr>
                            <th>썸네일 사이즈</th>
                            <td class="form-inline">
                                <label class="radio-inline"><input type="radio" name="photoWidget[thumSizeType]" value="auto">페이지에
                                    자동맞춤</label>
                                <label class="radio-inline"><input type="radio" name="photoWidget[thumSizeType]" value="menual">수동설정</label>
                                <input type="text" class="form-control width-2xs" name="photoWidget[thumWidth]" value=""> px
                                <div class="notice-info">페이지에 자동맞춤으로 설정 시 위젯이 삽입된 페이지에 맞게 썸네일 이미지 사이즈가 자동 조절 됩니다.</div>
                            </td>
                        </tr>
                    </table>
                    <div>
                        <button type="button" class="btn btn-gray js-btn-widget">위젯생성</button>
                    </div>
                    <div class="notice-info">포토리뷰 게시판 위젯을 생성하여 원하는 페이지에 삽입할 수 있습니다.
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    전체리뷰 게시판 주소
                </th>
                <td>
                    <div style="padding-bottom:10px;">PC : <?= $data['allReviewUri']['front'] ?>
                        <button type="button" data-clipboard-text="<?= $data['allReviewUri']['front'] ?>" class="js-clipboard btn btn-gray btn-sm"
                                title="<?= $data['allReviewUri']['front']; ?>">
                            복사하기
                        </button>
                    </div>
                    <div>모바일 : <?= $data['allReviewUri']['mobile'] ?>
                        <button type="button" data-clipboard-text="<?= $data['allReviewUri']['mobile'] ?>" class="js-clipboard btn btn-gray btn-sm"
                                title="<?= $data['allReviewUri']['mobile']; ?>">
                            복사하기
                        </button>
                    </div>

                    <div class="notice-info">등록된 모든 리뷰를 리스트 형태로 모아볼 수 있는 클래식한 게시판입니다.</div>
                </td>
            </tr>
            <tr>
                <th>
                    상품기준 리뷰 게시판 주소
                </th>
                <td>
                    <div style="padding-bottom:10px;">PC : <?= $data['goodsReviewUri']['front'] ?>
                        <button type="button" data-clipboard-text="<?= $data['goodsReviewUri']['front'] ?>" class="js-clipboard btn btn-gray btn-sm"
                                title="<?= $data['goodsReviewUri']['front']; ?>">
                            복사하기
                        </button>
                    </div>
                    <div>모바일 : <?= $data['goodsReviewUri']['mobile'] ?>
                        <button type="button" data-clipboard-text="<?= $data['goodsReviewUri']['mobile'] ?>" class="js-clipboard btn btn-gray btn-sm"
                                title="<?= $data['goodsReviewUri']['mobile']; ?>">
                            복사하기
                        </button>
                    </div>
                    <div class="notice-info">리뷰가 등록된 상품 기준으로 모아볼 수 있는 게시판입니다.</div>
                </td>
            </tr>
            <tr>
                <th>쓰기권한 설정</th>
                <td class="form-inline">
                    <label class="radio-inline"><input type="radio" name="authWrite" value="all" <?= $checked['authWrite']['all'] ?>>전체(회원+비회원)</label>
                    <label class="radio-inline"><input type="radio" name="authWrite" value="member" <?= $checked['authWrite']['member'] ?>>회원전용(비회원제외)</label>
                    <label class="radio-inline"><input type="radio" name="authWrite" value="group" <?= $checked['authWrite']['group'] ?>
                                                       onclick="layer_register('authWriteGroup','authWriteGroup','info_authWriteGroup')">특정회원등급</label>
                    <button type="button" class="btn btn-sm btn-gray js-group-select">회원등급 선택</button>
                    </label>

                    <label class="radio-inline"><input type="radio" name="authWrite" value="buyer" <?= $checked['authWrite']['buyer'] ?>>구매자만</label>
                    <select name="authWriteStatus">
                        <option value="d2" <?= $selected['authWriteStatus']['d2'] ?>>배송완료</option>
                        <option value="s1" <?= $selected['authWriteStatus']['s1'] ?>>구매확정</option>
                    </select>
                    <div id="authWriteGroup" class="selected-btn-group <?= ($data['authWriteGroup']) ? 'active' : '' ?>">
                        <?php if (is_array($data['authWriteGroup'])) { ?>
                            <h5>선택된 회원등급</h5>
                            <?php foreach ($data['authWriteGroup'] as $k => $v) { ?>
                                <span id="info_authWriteGroup_<?= $k ?>" class="btn-group btn-group-xs">
                                <input type="hidden" name="authWriteGroup[]" value="<?= $k ?>"/>
                                <span class="btn"><?= $v ?></span>
                                <button type="button" class="btn btn-white btn-icon-delete" data-toggle="delete" data-target="#info_authWriteGroup_<?= $k ?>">삭제</button>
                            </span>
                            <?php }
                        } ?>
                        <label>
                    </div>
                    <?php // } ?>

                </td>
            </tr>
            <tr>
                <th>글쓰기 시 상품연동</th>
                <td class="form-inline">
                    <label class="radio-inline"><input type="radio" checked>주문상품</label>
                    <label class="checkbox-inline">
                    <span class="js-target-buyer-area" style="display: none">
                   <input type="checkbox" name="orderDuplicateIgnoreFl" value="y" <?= gd_isset($checked['orderDuplicateIgnoreFl']['y']) ?>>주문내역 중복 허용</label>
                    </span>
                    <div style="margin-top:5px" class="notice-danger">
                        쓰기권한 설정이 '구매자만'이 아닐 경우 리뷰 중복 등록이 가능합니다. 동일 주문상품에 대해 리뷰 중복 등록 제한을 원할 경우 '구매자만'으로 설정해주세요.
                    </div>
                    <div class="notice-info">주문상품 연동 사용 시 주문상품 중 1개를 선택하여 게시글을 작성 할 수 있습니다.</div>
                    <div class="notice-info">주문내역 중복 허용 미체크 시 같은 주문상품으로 2회 이상 상품리뷰 등록이 불가능합니다.</div>
                </td>
            </tr>
            <tr>
                <th>댓글 기능</th>
                <td class="form-inline">
                    <label class="radio-inline"><input type="radio" name="memoFl" value="y" <?= gd_isset($checked['memoFl']['y']) ?> >사용함</label>
                    <label class="radio-inline"><input type="radio" name="memoFl" value="n" <?= gd_isset($checked['memoFl']['n']) ?> >사용안함</label>
                </td>
            </tr>

            <tr>
                <th>댓글권한 설정</th>
                <td class="form-inline">
                    <label class="radio-inline"><input type="radio" name="authMemoWrite" value="all" <?= $checked['authMemoWrite']['all'] ?>>전체(회원+비회원)</label>
                    <label class="radio-inline"><input type="radio" name="authMemoWrite" value="admin" <?= $checked['authMemoWrite']['admin'] ?>>관리자 전용</label>
                    <label class="radio-inline"><input type="radio" name="authMemoWrite" value="member" <?= $checked['authMemoWrite']['member'] ?>>회원전용(비회원제외)</label>
                    <label class="radio-inline"><input type="radio" name="authMemoWrite" value="group" <?= $checked['authMemoWrite']['group'] ?>
                                                       onclick="layer_register('authMemoWriteGroup','authMemoWriteGroup','info_authMemoWriteGroup')">특정회원등급</label>
                    <label>
                        <button type="button" class="btn btn-sm btn-gray js-group-select">회원등급 선택</button>
                    </label>
                    <label class="radio-inline"><input type="radio" name="authMemoWrite" value="buyer" <?= $checked['authMemoWrite']['buyer'] ?>>구매자만</label>
                    <select name="authMemoWriteStatus">
                        <option value="d2" <?= $selected['authMemoWriteStatus']['d2'] ?>>배송완료</option>
                        <option value="s1" <?= $selected['authMemoWriteStatus']['s1'] ?>>구매확정</option>
                    </select>
                    <div id="authMemoWriteGroup" class="selected-btn-group <?= ($data['authMemoWriteGroup']) ? 'active' : '' ?>">
                        <?php if (is_array($data['authMemoWriteGroup'])) { ?>
                            <h5>선택된 회원등급</h5>
                            <?php foreach ($data['authMemoWriteGroup'] as $k => $v) { ?>
                                <span id="info_authMemoWriteGroup_<?= $k ?>" class="btn-group btn-group-xs">
                                <input type="hidden" name="authMemoWriteGroup[]" value="<?= $k ?>"/>
                                <span class="btn"><?= $v ?></span>
                                <button type="button" class="btn btn-white btn-icon-delete" data-toggle="delete" data-target="#info_authMemoWriteGroup_<?= $k ?>">삭제</button>
                            </span>
                            <?php }
                        } ?>
                    </div>


                </td>
            </tr>

            <tr>
                <th>
                    작성자 표시방법
                </th>
                <td>
                    <label class="radio-inline"><input type="radio" name="writerDisplay"
                                                       value="name" <?= gd_isset($checked['writerDisplay']['name']) ?> /> 이름표시</label>
                    <label class="radio-inline"><input type="radio" name="writerDisplay"
                                                       value="id" <?= gd_isset($checked['writerDisplay']['id']) ?> /> 아이디표시</label>
                    <label class="radio-inline"><input type="radio" name="writerDisplay"
                                                       value="nick" <?= gd_isset($checked['writerDisplay']['nick']) ?> /> 닉네임표시</label>
                </td>
            </tr>
            <tr>
                <th>
                    관리자 표시방법
                </th>
                <td>
                    <div class="notice-info">관리자는 작성자에 '관리자'로 표시됩니다.</div>
                </td>
            </tr>
            <tr>
                <th>
                    작성자 노출제한
                </th>
                <td>
                    <select name="writerDisplayLimit" class="form-control">
                        <option value="0" <?= gd_isset($selected['writerDisplayLimit'][0]) ?>>전체노출</option>
                        <option value="1" <?= gd_isset($selected['writerDisplayLimit'][1]) ?>>1글자 노출</option>
                        <option value="2" <?= gd_isset($selected['writerDisplayLimit'][2]) ?>>2글자 노출</option>
                    </select>
                </td>
            </tr>
        </table>


        <div class="table-title">리뷰작성 혜택 설정</div>
        <table class="table table-cols">
            <colgroup>
                <col class="width-lg"/>
                <col/>
            </colgroup>
            <tr>
                <th>리뷰혜택 안내문구</th>
                <td class="form-inline">
                <textarea rows="5" class="width100p form-control  js-maxlength" maxlength="250" name="reviewBenefitInfo"
                          placeholder="리뷰 작성 시 혜택 등을 구매자에게 안내해주세요."><?= $data['reviewBenefitInfo'] ?></textarea>
                    <div class="notice-info">리뷰 작성 시 혜택에 대한 안내문구를 입력해주세요. 리뷰 작성 영역에 노출됩니다.</div>
                </td>
            </tr>
            <tr>
                <th>마일리지 사용유무</th>
                <td>
                    <label class="radio-inline"><input name="mileageFl" type="radio" value='y' <?= gd_isset($checked['mileageFl']['y']) ?> />
                        사용함</label>
                    <label class="radio-inline">
                        <input name="mileageFl" type="radio"
                               value='n' <?= gd_isset($checked['mileageFl']['n']) ?> />
                        사용안함
                    </label>
                </td>
            </tr>

            <tr class="js-miliage-use-tr">
                <th>마일리지 지급</th>
                <td class="form-inline">
                    <div style="padding-bottom:10px">
                        플러스리뷰 작성 시 <input type="text" name="mileageAmount[review]" class="js-number form-control" value="<?= $data['mileageAmount']['review'] ?>" required>원 지급
                    </div>
                    <div style="padding-bottom:10px"> 포토리뷰 작성 시 <input type="text" name="mileageAmount[photo]" class="js-number form-control"
                                                                       value="<?= $data['mileageAmount']['photo'] ?>" required>원 추가지급
                    </div>
                    <div>상품별 첫 리뷰 작성 시 <input type="text" name="mileageAmount[first]" class="js-number form-control" value="<?= $data['mileageAmount']['first'] ?>" required>원 추가지급
                    </div>
                </td>
            </tr>
            <tr class="js-miliage-use-tr">
                <th>게시글 삭제 시 <br/> 마일리지 차감</th>
                <td>
                    <label class="radio-inline">
                        <input name="mileageDeleteFl" type="radio"
                               value='y' <?= gd_isset($checked['mileageDeleteFl']['y']) ?> />
                        사용함
                    </label>
                    <label class="radio-inline">
                        <input name="mileageDeleteFl" type="radio"
                               value='n' <?= gd_isset($checked['mileageDeleteFl']['n']) ?> />
                        사용안함
                    </label>
                </td>
            </tr>
            <tr class="js-miliage-use-tr">
                <th>차감 마일리지 <br/>부족 시 처리방법</th>
                <td>
                    <label class="radio-inline">
                        <input name="mileageLackAction" type="radio"
                               value='delete' <?= gd_isset($checked['mileageLackAction']['delete']) ?> />
                        마이너스 차감 후 게시글 삭제
                    </label>
                    <label class="radio-inline">
                        <input name="mileageLackAction" type="radio"
                               value='noDelete' <?= gd_isset($checked['mileageLackAction']['noDelete']) ?> />
                        게시글 삭제 불가
                    </label>
                </td>
            </tr>
        </table>

        <div class="table-title">기능설정</div>
        <table class="table table-cols">
            <colgroup>
                <col class="width-lg"/>
                <col/>
            </colgroup>
            <tr>
                <th>추가정보 입력</th>
                <td>
                    <label class="radio-inline"><input name="addFormFl" type="radio" value='y' <?= gd_isset($checked['addFormFl']['y']) ?> />
                        사용함</label>
                    <label class="radio-inline">
                        <input name="addFormFl" type="radio"
                               value='n' <?= gd_isset($checked['addFormFl']['n']) ?> />
                        사용안함
                    </label>
                    <div class="notice-info">리뷰등록 시 추가로 입력할 옵션을 설정합니다.</div>
                </td>
            </tr>
            <tr class="js-add-form-tr">
                <th>추가정보 양식 설정</th>
                <td>
                    <div class="notice-info">추가정보 양식 설정 후 추가 시 구매자가 리뷰 작성을 할 때 아래와 같이 노출됩니다.</div>
                    <div class="notice-info">최대 10까지 등록 가능합니다.</div>
                    <div class="notice-info">추가정보 항목은 마우스 드래그 앤 드롭으로 순서 조정이 가능합니다.</div>
                    <button type="button" class="btn btn-sm btn-white btn-icon-plus js-btn-form-add mgb10">추가</button>

                    <table class="table table-cols width80p" id="addFormTable">
                        <tr>
                            <th class="width-md">항목명</th>
                            <th class="width-md">입력형태</th>
                            <th>입력값</th>
                            <th class="width-2xs center">사용</th>
                            <th class="width-2xs center">필수</th>
                            <th class="width-xs center">삭제</th>
                        </tr>
                        <tbody class="ui-sort-table">
                        <?php
                        $addForm = $data['addForm'];
                        foreach ($addForm['labelName'] as $k => $v) { ?>
                            <tr data-row="<?= $k ?>">
                                <td><input type="text" name="addForm[labelName][<?= $k ?>]" value="<?= $addForm['labelName'][$k] ?>" class="form-control width-lg"></td>
                                <td>
                                    <select name="addForm[inputType][<?= $k ?>]">
                                        <option value="text" <?php if ($addForm['inputType'][$k] == 'text') echo 'selected' ?>>텍스트 입력</option>
                                        <option value="select" <?php if ($addForm['inputType'][$k] == 'select') echo 'selected' ?>>셀렉트 박스</option>
                                    </select>
                                </td>
                                <td class="form-inline">
                                    <ul class="js-label-value-list">
                                        <?php if ($addForm['inputType'][$k] == 'select') {
                                            foreach ($addForm['labelValue'][$k] as $_k => $_v) {
                                                ?>
                                                <li><input type="text" class="form-control width80p" name="addForm[labelValue][<?= $k ?>][]" value="<?= $_v ?>">
                                                <?php if ($_k == 0) { ?>
                                                    <button type="button" class="btn btn-sm btn-white btn-icon-plus js-btn-labal-value-add mgl10">추가</button></li>
                                                <?php } else { ?>
                                                    <button type="button" class="btn btn-sm btn-white btn-icon-minus js-btn-labal-remove-li mgl10">삭제</button></li>
                                                    <?php
                                                } ?>


                                                <?php
                                            }
                                        } else { ?>
                                            <li><input type="text" class="form-control width-lg" name="addForm[labelValue][<?= $k ?>][]"
                                                       value="<?= $addForm['labelValue'][$k][0] ?>">
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </td>
                                <td class="center">
                                    <label class="checkbox-inline"><input type="checkbox" name="addForm[useFl][<?= $k ?>]"
                                                                          value="y" <?php if ($addForm['useFl'][$k] == 'y') echo 'checked' ?>></label>
                                </td>
                                <td class="center"><label class="checkbox-inline"><input type="checkbox" name="addForm[requireFl][<?= $k ?>]"
                                                                                         value="y" <?php if ($addForm['requireFl'][$k] == 'y') echo 'checked' ?>></label></td>
                                <td class="center">
                                    <button type="button" class="btn btn-sm btn-white btn-icon-minus js-btn-remove-tr">삭제</button>
                                </td>
                            </tr>
                            <?php
                        } ?>
                        </tbody>
                    </table>
                </td>
            </tr>

            <tr>
                <th>리뷰작성 안내 문구 설정</th>
                <td class="form-inline">
                    <textarea rows="5" class="width100p form-contro" name="reviewPlaceHolder"><?= $data['reviewPlaceHolder'] ?></textarea>
                    <div class="notice-info">리뷰를 작성하는 구매자들에게 표시될 안내문구를 설정합니다.</div>
                </td>
            </tr>
            <tr>
                <th>구매한 상품 옵션 노출</th>
                <td>
                    <label class="radio-inline">
                        <input name="displayOptionFl" type="radio"
                               value='y' <?= gd_isset($checked['displayOptionFl']['y']) ?> />
                        사용함
                    </label>
                    <label class="radio-inline">
                        <input name="displayOptionFl" type="radio"
                               value='n' <?= gd_isset($checked['displayOptionFl']['n']) ?> />
                        사용안함
                    </label>
                </td>
            </tr>
            <tr>
                <th>게시글 작성 시 평가</th>
                <td>
                    <span class="notice-info">플러스리뷰는 사용자 평가 기반의 기능이므로 평가 항목은 필수입니다.</span>
                </td>
            </tr>
            <tr>
                <th>게시글 추천</th>
                <td>
                    <label class="radio-inline">
                        <input name="recommendFl" type="radio"
                               value='y' <?= gd_isset($checked['recommendFl']['y']) ?> />
                        사용함
                    </label>
                    <label class="radio-inline">
                        <input name="recommendFl" type="radio"
                               value='n' <?= gd_isset($checked['recommendFl']['n']) ?> />
                        사용안함
                    </label>
                </td>
            </tr>
            <tr>
                <th>
                    NEW아이콘 효력
                </th>
                <td class="form-inline">
                    <input type="text" name="bdNewFl" id="newIconHour" size="5" class="form-control js-number wd-sm2" required
                           value="<?= gd_isset($data['newIconHour']) ?>"/> 시간
                </td>
            </tr>
        </table>

        <div class="table-title">리뷰작성 설정</div>
        <table class="table table-cols">
            <colgroup>
                <col class="width-lg"/>
                <col/>
            </colgroup>
            <tr>
                <th>최소 글자수 제한</th>
                <td class="form-inline">
                    <div>
                        <label class="radio-inline"><input type="radio" name="minLimitLengthFl" value="n" <?= $checked['minLimitLengthFl']['n'] ?>>제한없음</label>
                    </div>
                    <div>
                        <label class="radio-inline"><input type="radio" name="minLimitLengthFl" value="y" <?= $checked['minLimitLengthFl']['y'] ?>>
                            리뷰 작성 글자 수 최소 <input type="text" name="minContentsLength" value="<?= $data['minContentsLength'] ?>" class="form-control js-number">자 이상 입력해야 등록 가능하도록 설정
                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <th>첨부이미지 최대크기</th>
                <td class="form-inline">
                    <input type="text" required name="uploadMaxSize" value="<?= $data['uploadMaxSize'] ?>" class="form-control js-number">Mbyte(s)
                </td>
            </tr>
            <tr>
                <th>첨부이미지 등록 설정</th>
                <td class="form-inline">
                    <label class="radio-inline"><input type="radio" name="uploadRequiredFl" value="y" <?= $checked['uploadRequiredFl']['y'] ?>>필수</label>
                    <label class="radio-inline"><input type="radio" name="uploadRequiredFl" value="n" <?= $checked['uploadRequiredFl']['n'] ?>>선택</label>
                </td>
            </tr>
            <tr>
                <th>첨부이미지 파일 개수
                </th>
                <td>
                    <select name="uploadMaxCount">
                        <?php for ($i = 4; $i >= 1; $i--) { ?>
                            <option value="<?= $i ?>" <?= $selected['uploadMaxCount'][$i] ?>><?= $i ?>개</option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
        </table>
    </form>
    <script id="templateAddForm" type="text/html">
        <tr data-row="<%=index%>">
            <td><input type="text" name="addForm[labelName][<%=index%>]" class="form-control width-lg" placeholder="ex)키"></td>
            <td>
                <select name="addForm[inputType][<%=index%>]">
                    <option value="text">텍스트 입력</option>
                    <option value="select">셀렉트 박스</option>
                </select>
            </td>
            <td class="form-inline">
                <ul class="js-label-value-list">
                    <li><input type="text" class="form-control width80p" placeholder="ex) 160~165" data-type="text" name="addForm[labelValue][<%=index%>][]"></li>
                </ul>
            </td>
            <td class="center"><label class="checkbox-inline"><input type="checkbox" name="addForm[useFl][<%=index%>]" checked value="y"></label></td>
            <td class="center"><label class="checkbox-inline"><input type="checkbox" name="addFormp[requireFl][<%=index%>]" value="y"></label></td>
            <td class="center">
                <button type="button" class="btn btn-sm btn-white btn-icon-minus js-btn-remove-tr">삭제</button>
            </td>
        </tr>
    </script>
</div>
