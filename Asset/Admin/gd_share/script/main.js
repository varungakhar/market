var gd_main_index = (function ($) {
    "use strict";
    return function () {
        return {
            show_sub_menu: function (object) {
                var sub_menu = $(object.getAttribute('href'));
                sub_menu.removeClass('display-none');
            }
        };
    };
})($);

$(document).ready(function () {
    var main_index = new gd_main_index($);
    $('#headerManagerSubMenu').dropdown();


    var memoLayer = {
        element: $('#adminMemo'),
        btn: $('.js-memo-fold'),
        show: function () {
            this.btn.text('메모접기');
            this.btn.removeClass('btn-icon-fold-on');
            this.btn.addClass('btn-icon-fold-off');
            this.element.show();
        },
        hide: function () {
            this.btn.text('메모열기');
            this.btn.removeClass('btn-icon-fold-off');
            this.btn.addClass('btn-icon-fold-on');
            this.element.hide();
        }
    };

    var isPageChange = false;
    $('select[name=viewAuth]').bind('change', function () {
        var value = $(this).val();
        /*$("textarea").change(function () { isPageChange = true; }); if (isPageChange) { if (confirm("메모내용을 저장하지 않고 열람권한 변경 시 입력 중인 내용이 삭제됩니다.\n 메모내용을 저장하시겠습니까?")) { var oldValue = (value == 'all') ? 'self' : 'all'; $('#frmMemo input[name=oldViewAuth]').val(oldValue); $('.js-memo-save').trigger('click'); } isPageChange = false;}*/
        $('.js-memo').hide();
        $('.js-memo[data-type="' + value + '"]').show();
        $.post('main_setting_ps.php', {'mode': 'memo', code: 'viewAuth', 'viewAuth': value});
    });

    //메모접기/열기
    $('.js-memo-fold').bind('click', function () {
        var isMemoShow = $('#adminMemo').is(':visible');
        var flag = 'n';
        memoLayer.hide();
        if (!isMemoShow) {
            flag = 'y';
            memoLayer.show();
        }
        $.post('../base/main_setting_ps.php', {'mode': 'memo', code: 'isVisible', 'isVisible': flag});
    });

    $('.js-memo-clear').bind('click', function () {
        viewAuth = $('select[name=viewAuth]').val();
        $('textarea[name="memo[' + viewAuth + ']"]').val('');
    });

    //메모저장
    $('.js-memo-save').bind('click', function () {
        var $frmMemo = $('#frmMemo');
        $frmMemo.attr('action', '../base/main_setting_ps.php');
        $frmMemo.attr('target', 'ifrmProcess');
        $frmMemo.submit();
    });

    //초기화
    if (isVisibleMemo == 'n') {
        memoLayer.hide();
    } else {
        memoLayer.show();
    }

    $('select[name=viewAuth]').trigger('change');
    $('.js-memo').hide();
    $('.js-memo[data-type="' + viewAuth + '"]').show();
});


$(document).ready(function () {
    if ($("#selftraffic").length > 0) {
        $(window).load(function () {
            $.get("./solution_information_traffic.php", {pageKey: "86254"},
                function (data) {
                    //alert(data);
                    if (data != '' && data != '[[]]') {
                        var r = eval(data);
                        for (i = 0; i < r.length; i++) {
                            $("#t_time").text(r[i]['0']['refresh']);
                            if (r[i]['0']['limits'] > 0) $("#t_limit").text(r[i]['0']['limits']);
                            else $("#t_limit").text('무제한');
                            $("#t_ntraffic").text(r[i]['0']['usages']);
                        }
                    }
                });
        });
    }
});
