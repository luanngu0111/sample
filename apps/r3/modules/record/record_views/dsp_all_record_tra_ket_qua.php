<?php
/**
  	* o	Chương trình: Xây dựng phần mềm một cửa điện tử nguồn mở cho các quận huyện.
	* o	Thực hiện: Ban Quản lý các dự án công nghiệp công nghệ thông tin-Bộ Thông tin và Truyền thông.
	* o	Thuộc dự án: Hỗ trợ địa phương xây dựng, hoàn thiện một số sản phẩm phần mềm nguồn mở theo Quyết định 112/QĐ-TTg ngày 20/01/2012 của Thủ tướng Chính phủ.
	* o	Tác giả: Công ty Cổ phần Đầu tư và Phát triển Công nghệ Tâm Việt
	* o	Email: LTBinh@gmail.com
	* o	Điện thoại: 0936.114411
	* 
*/

if (!defined('SERVER_ROOT')) exit('No direct script access allowed');

//View data
$arr_all_record_type    = $VIEW_DATA['arr_all_record_type'];
$v_record_type_code     = $VIEW_DATA['record_type_code'];
$arr_all_record         = $VIEW_DATA['arr_all_record'];

//header
$this->template->title = 'Trả kết quả';
$this->template->display('dsp_header.php');

?>
<form name="frmMain" id="frmMain" action="" method="POST">
    <?php
    echo $this->hidden('controller',$this->get_controller_url());
    echo $this->hidden('hdn_item_id','0');
    echo $this->hidden('hdn_item_id_list','');

    echo $this->hidden('hdn_dsp_single_method','dsp_single_record');
    echo $this->hidden('hdn_dsp_all_method', 'dsp_all_record');
    echo $this->hidden('hdn_update_method','update_record');
    echo $this->hidden('hdn_delete_method','delete_record');
    echo $this->hidden('hdn_handover_method','do_handover_record');

    echo $this->hidden('record_type_code', $v_record_type_code);

    echo $this->hidden('hdn_role', _CONST_TRA_KET_QUA_ROLE);

    ?>
    <?php echo $this->dsp_div_notice($VIEW_DATA['active_role_text'] );?>
 	<!-- filter -->
    <?php $this->dsp_div_filter($v_record_type_code, $arr_all_record_type);?>

    <div id="solid-button">
        <input type="button" class="solid certificate" value="Trả kết quả"
               onclick="btn_return_onclick();" />
    </div>
    <div class="clear"></div>

    <div id="procedure">
        <?php
        if ($this->load_abs_xml($this->get_xml_config($v_record_type_code, 'list')))
        {
            echo $this->render_form_display_all_record($arr_all_record, FALSE);
        }
        ?>
    </div>
    <?php echo $this->paging2($arr_all_record);?>
    <!--
    <div class="button-area">
        <input type="button" name="btn_return" class="button certificate" value="Trả kết quả" onclick="btn_return_onclick();"/>
    </div> -->

    <!-- Context menu -->
    <ul id="myMenu" class="contextMenu">
        <li class="return">
            <a href="#return">Trả kết quả</a>
        </li>
        <li class="statistics">
            <a href="#statistics">Xem tiến độ</a>
        </li>
    </ul>
</form>
<script>

    $(function() {
        //Show context on each row
        $(".adminlist tr[role='presentation']").contextMenu({
            menu: 'myMenu'
        }, function(action, el, pos) {
            v_record_id = $(el).attr('data-item_id');
            switch (action){
                case 'return':
                    btn_return_onclick(v_record_id);
                    break;

                case 'statistics':
                    dsp_single_record_statistics(v_record_id);
                    break;
            }
        });

        //Quick action
        <?php if (strtoupper($this->active_role) == _CONST_TRA_KET_QUA_ROLE): ?>
            $('.adminlist tr[role="presentation"] td[role="action"] .quick_action').each(function(index) {
                v_item_id =   $(this).attr('data-item_id');

                html = '';

                //Phe duyet
                html += '<a href="javascript:void(0)" onclick="btn_return_onclick(\'' + v_item_id + '\')" class="quick_action">';
                html += '<img src="' + SITE_ROOT + 'public/images/btn_certificate_16x16.png" title="Trả kết quả"  /></a>';

                //Thong tin tien do
                html += '<a href="javascript:void(0)" onclick="dsp_single_record_statistics(\'' + v_item_id + '\')" class="quick_action">';
                html += '<img src="' + SITE_ROOT + 'public/images/statistics-16x16.png" title="Xem tiến độ"  /></a>';

                $(this).html(html);
            });

        <?php endif;?>
    });

    function btn_return_onclick(record_id)
    {
        var f = document.frmMain;
        if (typeof(record_id) == 'undefined')
        {
            record_id = get_all_checked_checkbox(f.chk, ',');
        }

        if (record_id != '')
        {
            $("#hdn_item_id_list").val(record_id);
        }
        else
        {
            alert('Chưa có hồ sơ nào được chọn');
            return;
        }

        var url = '<?php echo $this->get_controller_url();?>dsp_record_result/' + record_id
                + '&pop_win=1'
                + '&record_type=' + $("#sel_record_type").val();

        showPopWin(url, 1000, 600, null, true);

/*
        if (confirm('Bạn chắc chắn trả kết quả các hồ sơ đã chọn?'))
        {
            m = $("#controller").val() + 'do_return_record';
            $("#frmMain").attr('action', m);
            f.submit();
        }
        */
    }
</script>
<?php $this->template->display('dsp_footer.php');