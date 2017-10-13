<form id="frm" action="../member/member_ps.php" method="post">
    <div class="page-header js-affix">
        <h3>회원 수정 </h3>
        <input type="button" value="저장" class="btn btn-red btn-register">
    </div>
    <input type="hidden" name="mode" id="mode" value="<?= $mode ?>"/>
    <input type="hidden" name="memNo" id="memNo" value="<?= $data['memNo'] ?>"/>
    <?php include('_member_view.php'); ?>
    <?php include('_member_view_business.php'); ?>
    <?php include('_member_view_other.php'); ?>
</form>
<?php include('_member_view_history.php'); ?>
<script type="text/javascript" src="<?= PATH_ADMIN_GD_SHARE ?>script/member.js"></script>
<script type="text/javascript" src="<?= PATH_ADMIN_GD_SHARE ?>script/member2.js"></script>
<script type="text/javascript">
    member2.init($('#frm'));
    member2.set_my_page(true);
    $('.btn-register').click({form: $('#frm')}, member2.save);

    $('#emailDomain').removeClass('error');
    $('#nickNm').removeClass('error');
    $('#busiNo').removeClass('error');
</script>
