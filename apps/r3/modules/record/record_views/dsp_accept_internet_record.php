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

//display header
$this->template->title = 'Chấp nhận hồ sơ';

$v_pop_win = isset($_REQUEST['pop_win']) ? '_pop_win' : '';
$this->template->display('dsp_header' . $v_pop_win . '.php');

$v_record_id            = $arr_single_record['PK_RECORD'];
$v_record_no            = $arr_single_record['C_RECORD_NO'];
$v_receive_date         = $arr_single_record['C_RECEIVE_DATE'];
$v_return_phone_number  = $arr_single_record['C_RETURN_PHONE_NUMBER'];
$v_return_email         = $arr_single_record['C_RETURN_EMAIL'];
$v_citizen_name         = $arr_single_record['C_CITIZEN_NAME'];

$v_xml_data             = $arr_single_record['C_XML_DATA'];

//Convert date
$v_receive_date         = jwDate::yyyymmdd_to_ddmmyyyy($v_receive_date, TRUE);

//Tinh toan ngay tra ket qua
$v_total_time = $arr_single_record['C_TOTAL_TIME'];
$v_return_date = $arr_single_record['C_RETURN_DATE'];
$v_return_date_by_text  = $this->return_date_by_text($v_return_date);
?>
<form name="frmMain" method="post" id="frmMain" action="<?php echo $this->get_controller_url();?>do_sign_record">
    <?php
    echo $this->hidden('controller', $this->get_controller_url());

    echo $this->hidden('hdn_dsp_single_method', 'dsp_single_record');
    echo $this->hidden('hdn_dsp_all_method', 'dsp_all_record');
    echo $this->hidden('hdn_update_method', 'update_record');
    echo $this->hidden('hdn_delete_method', 'delete_record');
    echo $this->hidden('hdn_item_id', $v_record_id);

    echo $this->hidden('pop_win', $v_pop_win);
?>
<div class="page-title">Phản hồi công dân</div>
    <input type="radio" name="rad_accept" value="email" id="rad_accept_email" checked="checked"/><label for="rad_accept_email">Gửi Email</label>
    <input type="radio" name="rad_accept" value="phone" id="rad_accept_phone" /><label for="rad_accept_phone">Gọi điện thoại</label>
    <br/>
    <textarea style="width:100%;height:350px" name="txt_email_content">
        <?php printf(_CONST_INTERNET_RECORD_ACCEPT_EMAIL,$v_citizen_name,$v_receive_date,$arr_single_record['C_RECORD_TYPE_NAME'] ,$arr_single_record['C_RECORD_NO'] ,$v_total_time,$v_return_date_by_text);?>
    </textarea>

    <!-- Buttons -->
    <div class="button-area">
        <input type="button" name="btn_do_approval" class="button save" value="<?php echo __('update');?>" onclick="btn_do_approval_internet_record_onclick();" />
        <?php $v_back_action = ($v_pop_win === '') ? 'btn_back_onclick();' : 'try{window.parent.hidePopWin();}catch(e){window.close();};';?>
        <input type="button" name="cancel" class="button close" value="<?php echo __('close window'); ?>" onclick="<?php echo $v_back_action;?>"/>
    </div>
</form>
<script>
    /**
     * Comment
     */
    function btn_do_approval_internet_record_onclick()
    {
        $("#frmMain").attr('action', $("#controller").val() + 'do_accept_internet_record');
        $("#frmMain").submit();
    }
</script>
<?php $this->template->display('dsp_footer' .$v_pop_win . '.php');