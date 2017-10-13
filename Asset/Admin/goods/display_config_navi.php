<script type="text/javascript">
    <!--
    $(document).ready(function () {
        $("#frmMenuLayer").validate({
            submitHandler: function (form) {
                form.target = 'ifrmProcess';
                form.submit();
                return false;
            },
            rules: {
            },
            messages: {
            }
        });

        <?php if($data['category']['naviUse'] =='n') { ?>display_switch('category','hide');<?php } ?>
        <?php if($data['brand']['naviUse'] =='n') { ?>display_switch('brand','hide');<?php } ?>
    });

    function display_switch(prefix,state)
    {
        if(state =='show')  $('#tr_count_'+prefix).show();
        else $('#tr_count_'+prefix).hide();
    }
    //-->
</script>
<form id="frmMenuLayer" name="frmMenuLayer" action="./display_config_ps.php" method="post"
      enctype="multipart/form-data">
    <input type="hidden" name="mode" value="navi_register"/>

    <div class="page-header js-affix">
        <h3><?=end($naviMenu->location);?> </h3>
        <div class="btn-group">
            <input type="submit"   value="저장" class="btn btn-red" />

        </div>
    </div>


    <div class="table-title gd-help-manual">
        카테고리 네비게이션 영역 설정
    </div>

    <table class="table table-cols">
        <colgroup>
            <col class="width-sm"/>
            <col/>
        </colgroup>
        <tr>
            <th class="require" >사용여부</th>
            <td  >
                <label class="radio-inline"><input type="radio" name="category[naviUse]" value="y"<?=gd_isset($checked['category']['naviUse']['y']);?> onclick="display_switch('category','show')">사용함</label>
                <label class="radio-inline"> <input type="radio"  name="category[naviUse]" value="n" <?=gd_isset($checked['category']['naviUse']['n']);?>  onclick="display_switch('category','hide')">사용안함</label>
            </td>
        </tr>
        <tr id="tr_count_category">
            <th class="require" >상품수 노출여부</th>
            <td  >
                <label class="radio-inline"><input type="radio" name="category[naviCount]" value="y"<?=gd_isset($checked['category']['naviCount']['y']);?> >사용함</label>
                <label class="radio-inline"> <input type="radio"  name="category[naviCount]" value="n" <?=gd_isset($checked['category']['naviCount']['n']);?> >사용안함 </label>
            </td>
        </tr>
    </table>



    <div class="table-title gd-help-manual">
        브랜드 네비게이션 영역 설정
    </div>

    <table class="table table-cols">
        <colgroup>
            <col class="width-sm"/>
            <col/>
        </colgroup>
        <tr>
            <th class="require" >사용여부</th>
            <td  >
                <label class="radio-inline"><input type="radio" name="brand[naviUse]" value="y"<?=gd_isset($checked['brand']['naviUse']['y']);?>  onclick="display_switch('brand','show')">사용함</label>
                <label class="radio-inline"> <input type="radio"  name="brand[naviUse]" value="n" <?=gd_isset($checked['brand']['naviUse']['n']);?>   onclick="display_switch('brand','hide')">사용안함 </label>
            </td>
        </tr>
        <tr  id="tr_count_brand">
            <th class="require" >상품수 노출여부</th>
            <td  >
                <label class="radio-inline"><input type="radio" name="brand[naviCount]" value="y"<?=gd_isset($checked['brand']['naviCount']['y']);?> >사용함</label>
                <label class="radio-inline"> <input type="radio"  name="brand[naviCount]" value="n" <?=gd_isset($checked['brand']['naviCount']['n']);?> > 사용안함</label>
            </td>
        </tr>
    </table>



</form>
