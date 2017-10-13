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
 * @link http://www.godo.co.kr
 */
if(gd_php_self() == '/share/member_crm_detail.php'){
    $widthClass = 'width-2xl';
    $checkBoxRow = 6;
}
?>
<form id="frm" action="../member/member_ps.php" method="post">
    <input type="hidden" name="mode" id="mode" value="<?= $mode ?>"/>
    <input type="hidden" name="memNo" id="memNo" value="<?= $data['memNo'] ?>"/>
    <?php include gd_admin_skin_path("member/_member_view.php"); ?>
    <?php include gd_admin_skin_path("member/_member_view_business.php"); ?>
    <?php include gd_admin_skin_path("member/_member_view_other.php"); ?>
</form>
<?php include "/../member/_member_view_history.php"; ?>
<script type="text/javascript" src="<?= PATH_ADMIN_GD_SHARE ?>script/member.js"></script>
<script type="text/javascript" src="<?= PATH_ADMIN_GD_SHARE ?>script/member2.js"></script>
<script type="text/javascript">
    $('#emailDomain').removeClass('error');
    $('#nickNm').removeClass('error');
    $('#busiNo').removeClass('error')

    member2.init($('#frm'));
    member2.set_my_page(true);
    $('.btn-register').click({form: $('#frm')}, member2.save);
</script>
