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

define('_CONST_DEFAULT_ROWS_PER_PAGE',10);
define('_CONST_LIST_DELIM','<~>');
define('_CONST_DEFAULT_DW_OFF','7,1');//Ngay nghi cuoi tuan mac dinh: 7 => thu bay; 1 => Chu nhat
define('_CONST_DEFAULT_DATE_OFF','1/1, 30/04, 01/05, 02/09');//Ngay nghi le mac dinh: format: dd/mm

define('_CONST_STAFF_GROUP_CODE','CAN_BO');
define('_CONST_TEAM_LEADER_GROUP_CODE','LANH_DAO_PHONG');
define('_CONST_BOD_GROUP_CODE','LANH_DAO_DON_VI');

//File đính kèm hồ sơ
//define('_CONST_RECORD_FILE_ACCEPT', 'doc|docx|pdf');
define('_CONST_RECORD_FILE_ACCEPT', 'pdf');
//Thu tu Mot-Cua
define('_CONST_XML_RTT_DELIM', '::');
define('_CONST_HTML_RTT_DELIM', '--');

define('_CONST_GET_NEW_RECORD_NOTICE_INTERVAL', 3000); //mili sec

//Role
define('_CONST_XAC_NHAN_HO_SO_NOP_QUA_INTERNET_ROLE', 'XAC_NHAN_HO_SO_NOP_QUA_INTERNET');
define('_CONST_TIEP_NHAN_ROLE', 'TIEP_NHAN');
define('_CONST_BAN_GIAO_ROLE', 'BAN_GIAO');
define('_CONST_BO_SUNG_ROLE', 'BO_SUNG');
define('_CONST_TRA_KET_QUA_ROLE', 'TRA_KET_QUA');
define('_CONST_IN_PHIEU_TIEP_NHAN_ROLE', 'IN_PHIEU_TIEP_NHAN');
define('_CONST_CHUYEN_LEN_HUYEN_ROLE', 'CHUYEN_LEN_HUYEN');
define('_CONST_TRA_HO_SO_VE_XA_ROLE', 'TRA_HO_SO_VE_XA');
define('_CONST_THONG_BAO_BO_SUNG_ROLE', 'THONG_BAO_BO_SUNG');
define('_CONST_TAI_CHINH_ROLE', 'TAI_CHINH');
define('_CONST_PHAN_CONG_ROLE', 'PHAN_CONG');
define('_CONST_PHAN_CONG_LAI_ROLE', 'PHAN_CONG_LAI');
define('_CONST_THU_LY_ROLE', 'THU_LY');
define('_CONST_CHUYEN_YEU_CAU_XAC_NHAN_XUONG_XA_ROLE', 'CHUYEN_YEU_CAU_XAC_NHAN_XUONG_XA');
define('_CONST_THU_LY_HO_SO_LIEN_THONG_ROLE', 'THU_LY_HO_SO_LIEN_THONG');
define('_CONST_YEU_CAU_THU_LY_LAI_ROLE', 'YEU_CAU_THU_LY_LAI');
define('_CONST_XAC_NHAN_HO_SO_LIEN_THONG_ROLE', 'XAC_NHAN_LIEN_THONG');
define('_CONST_XET_DUYET_ROLE', 'XET_DUYET');
define('_CONST_XET_DUYET_BO_SUNG_ROLE', 'XET_DUYET_BO_SUNG');
define('_CONST_KY_ROLE', 'KY_DUYET');
define('_CONST_THU_PHI_ROLE', 'THU_PHI');
define('_CONST_Y_KIEN_LANH_DAO_ROLE', 'Y_KIEN_LANH_DAO');
define('_CONST_TRA_CUU_ROLE', 'TRA_CUU');
define('_CONST_BAO_CAO_ROLE', 'BAO_CAO');
define('_CONST_NOP_HO_SO_SANG_CHI_CUC_THUE_ROLE', 'NOP_HO_SO_SANG_CHI_CUC_THUE');
define('_CONST_NHAN_THONG_BAO_CUA_CHI_CUC_THUE_ROLE', 'NHAN_THONG_BAO_CUA_CHI_CUC_THUE');
define('_CONST_CHUYEN_THONG_BAO_THUE_VE_BP_MOT_CUA_ROLE', 'CHUYEN_THONG_BAO_THUE_VE_BP_MOT_CUA');
define('_CONST_TRA_THONG_BAO_NOP_THUE_ROLE', 'TRA_THONG_BAO_NOP_THUE');
define('_CONST_NHAN_BIEN_LAI_NOP_THUE_ROLE', 'NHAN_BIEN_LAI_NOP_THUE');
define('_CONST_CHUYEN_LAI_BUOC_TRUOC_ROLE', 'CHUYEN_LAI_BUOC_TRUOC');
define('_CONST_CHUYEN_HO_SO_LEN_SO_ROLE', 'CHUYEN_HO_SO_LEN_SO');
define('_CONST_NHAN_HO_SO_TU_SO_ROLE', 'NHAN_HO_SO_TU_SO');

//Sau thue
define('_CONST_AFTER_TAX_SUFFIX', '_SAU_THUE');
define('_CONST_PHAN_CONG_SAU_THUE_ROLE', 'PHAN_CONG_SAU_THUE');
define('_CONST_THU_LY_SAU_THUE_ROLE', 'THU_LY_SAU_THUE');
define('_CONST_DUYET_SAU_THUE_ROLE', 'PHE_DUYET_VA_TRINH_KY_SAU_THUE');

//Ket qua thu ly ho so
define('_CONST_RECORD_APPROVAL_ACCEPT', 'ACCEPT');
define('_CONST_RECORD_APPROVAL_SUPPLEMENT', 'SUPPLEMENT');
define('_CONST_RECORD_APPROVAL_REEXEC', 'REEXEC');
define('_CONST_RECORD_APPROVAL_REJECT', 'REJECT');

//Gio lam viec hanh chinh
define('_CONST_MORNING_BEGIN_WORKING_TIME', '07:30');
define('_CONST_MORNING_END_WORKING_TIME', '11:30');
define('_CONST_AFTERNOON_BEGIN_WORKING_TIME', '13:30');
define('_CONST_AFTERNOON_END_WORKING_TIME', '16:00');

//Quan ly van ban
define('_CONST_EDOC_VBDEN','VBDEN');
define('_CONST_EDOC_VBDI','VBDI');
define('_CONST_EDOC_VBNOI_BO','VBNOI_BO');
define('_CONST_GET_NEW_DOC_NOTICE_INTERVAL',3000);

//Role VBDEN
define('_CONST_VAO_SO_VAN_BAN_DEN_ROLE','VAO_SO_VAN_BAN_DEN');
define('_CONST_TRINH_VAN_BAN_DEN_ROLE','TRINH_VAN_BAN_DEN');
define('_CONST_DUYET_VAN_BAN_DEN_ROLE','DUYET_VAN_BAN_DEN');
define('_CONST_THU_LY_VAN_BAN_DEN_ROLE','THU_LY_VAN_BAN_DEN');
define('_CONST_PHOI_HOP_THU_LY_VAN_BAN_DEN_ROLE','PHOI_HOP_THU_LY_VAN_BAN_DEN');
define('_CONST_GIAM_SAT_THU_LY_VAN_BAN_DEN_ROLE','GIAM_SAT_THU_LY_VAN_BAN_DEN');
//Role VBDI
define('_CONST_SOAN_THAO_VAN_BAN_DI_ROLE','SOAN_THAO_VAN_BAN_DI');
define('_CONST_TRINH_DUYET_VAN_BAN_DI_ROLE','TRINH_DUYET_VAN_BAN_DI');
define('_CONST_DUYET_VAN_BAN_DI_ROLE','DUYET_VAN_BAN_DI');
define('_CONST_VAO_SO_VAN_BAN_DI_ROLE','VAO_SO_VAN_BAN_DI');
//Role VBNOIBO
define('_CONST_SOAN_THAO_VAN_BAN_NOI_BO_ROLE','SOAN_THAO_VAN_BAN_NOI_BO');
define('_CONST_TRINH_DUYET_VAN_BAN_NOI_BO_ROLE','TRINH_DUYET_VAN_BAN_NOI_BO');
define('_CONST_DUYET_VAN_BAN_NOI_BO_ROLE','DUYET_VAN_BAN_NOI_BO');
define('_CONST_VAO_SO_VAN_BAN_NOI_BO_ROLE','VAO_SO_VAN_BAN_NOI_BO');

define('_CONST_VAN_BAN_DUOC_CHIA_SE_ROLE','VAN_BAN_DUOC_CHIA_SE');

define('_CONST_SMTP_SERVER', 'smtp.gmail.com');
define('_CONST_SMTP_PORT', '465');
define('_CONST_SMTP_ACCOUNT', 'motcua.tamviettech@gmail.com');
define('_CONST_SMTP_ACCOUNT_NAME', 'Bộ phận một cửa');
define('_CONST_SMTP_PASSWORD', 'Muachimenbay^');
define('_CONST_SMTP_SSL', TRUE);
define('_CONST_INTERNET_RECORD_ACCEPT_EMAIL',
        "Kính gửi ông/bà: %s
        \n\nNgày %s, Bộ phận một cửa đã nhận được hồ sơ \"%s\" của ông bà nộp qua mạng Internet.
        \nBộ phận một cửa xin thông báo hồ sơ của ông/bà đã được chuyển tới bộ phận chuyên môn để giải quyết theo luật định.
        \nHồ sơ của ông/bà được cấp mã số là: %s. Để tra cứu, xin vui lòng truy cập vào địa chỉ: http://127.0.0.1/go-office-ce/r3/mavach
        \nTheo quy định hiện hành, hồ sơ sẽ được giải quyết trong %s ngày làm việc, và trả kết quả vào ngày %s
        \nXin mời ông/bà đến ngày giờ trên mang hồ sơ tới đến bộ phận một cửa để đối chiếu và nhận kết quả.
        \nMọi thắc mắc xin liên hệ \"Bộ phận Một cửa\" - 02403 123 456.
        \n\nTrân trọng.");

//ReCapcha
define('_CONST_RECAPCHA_PUBLIC_KEY', '6LdpjNoSAAAAAMvTFbLh2LPN4z32Dyb6YD2v8vUI');
define('_CONST_RECAPCHA_PRIVATE_KEY', '6LdpjNoSAAAAAB6kCDmrY8RmuysVHTWsr8qxSuQb');

//Danh muc
define('_CONST_DANH_MUC_LINH_VUC','DANH_MUC_LINH_VUC');
define('_CONST_DANH_MUC_BAO_CAO','DANH_MUC_BAO_CAO');