<?php
require_once __DIR__ . '/../record/record_Model.php';

class report_Model extends record_Model {

    function __construct() {
        parent::__construct();
    }
    public function qry_all_record_type_with_spec_code()
    {
        $sql = 'Select PK_RECORD_TYPE, C_SPEC_CODE, C_CODE, C_NAME From t_r3_record_type Where C_STATUS>0 Order By C_ORDER';
        if (CONST_USE_ADODB_CACHE_FOR_REPORT)
        {
            return $this->db->CacheGetAll($sql);
        }

        return $this->db->GetAll($sql);
    }

    public function qry_all_report_data_6($period, $arr_all_spec)
    {
        $arr_all_report_data    = Array();
        $v_report_subtitle      = '[Kỳ báo cáo]';
        switch ($period)
        {
            case 'year':
                //Bao cap tiep nhan theo nam
                $year = get_request_var('year');
                $the_date = $year . '-01-'. '01';

                $sql = '';
                $i=0;
                foreach ($arr_all_spec as $code => $name)
                {
                    $sql .= ($i >0) ? ' Union All ' : '';
                    if ($this->is_mssql())
                    {
                        $sql .= "SELECT
                                    '$code' AS C_SPEC_CODE
                                    ,'$name' as C_SPEC_NAME
                                    ,(
                                      SELECT COUNT(*)
                                      FROM view_record R LEFT JOIN t_r3_record_type RT ON R.FK_RECORD_TYPE=RT.PK_RECORD_TYPE
                                      WHERE DATEDIFF(year, '$the_date', R.C_RECEIVE_DATE)=0
                                        AND RT.C_SPEC_CODE='$code'
                                     ) AS C_COUNT";
                    }
                    elseif ($this->is_mysql())
                    {
                        $sql .= "SELECT
                                    '$code' AS C_SPEC_CODE
                                    ,'$name' as C_SPEC_NAME
                                    ,(
                                      SELECT COUNT(*)
                                      FROM view_record R LEFT JOIN t_r3_record_type RT ON R.FK_RECORD_TYPE=RT.PK_RECORD_TYPE
                                      WHERE YEAR(R.C_RECEIVE_DATE)=$year
                                        AND RT.C_SPEC_CODE='$code'
                                     ) AS C_COUNT";
                    }

                    $i++;
                }
                $v_report_subtitle   = 'Năm ' . get_request_var('year');
                //Nếu là năm quá khứ thì cache
                if (CONST_USE_ADODB_CACHE_FOR_REPORT && $year < Date('Y'))
                {
                    $arr_all_report_data =  $this->db->cacheGetAll($sql);
                }
                else
                {
                    $arr_all_report_data =  $this->db->getAll($sql);
                }

                break;

            case 'month':
                $month  = intval(get_request_var('month'));
                $year   = intval(get_request_var('year'));
                $the_date = $year . '-' . $month . '-' . '01';

                $sql = '';
                $i=0;
                foreach ($arr_all_spec as $code => $name)
                {
                    $sql .= ($i >0) ? ' Union All ' : '';
                    if ($this->is_mssql())
                    {
                        $sql .= "SELECT
                                    '$code' AS SPEC_CODE
                                    ,'$name' as C_SPEC_NAME
                                    ,(SELECT COUNT(*)
                                      FROM view_record R LEFT JOIN t_r3_record_type RT ON R.FK_RECORD_TYPE=RT.PK_RECORD_TYPE
                                      WHERE DATEDIFF(month, '$the_date', R.C_RECEIVE_DATE)=0
                                        AND RT.C_SPEC_CODE='$code'
                                     ) AS C_COUNT";
                    }
                    elseif ($this->is_mysql())
                    {
                        $sql .= "SELECT
                                    '$code' AS SPEC_CODE
                                    ,'$name' as C_SPEC_NAME
                                    ,(SELECT COUNT(*)
                                      FROM view_record R LEFT JOIN t_r3_record_type RT ON R.FK_RECORD_TYPE=RT.PK_RECORD_TYPE
                                      WHERE YEAR(R.C_RECEIVE_DATE)=$year
                                        AND MONTH(R.C_RECEIVE_DATE)=$month
                                        AND RT.C_SPEC_CODE='$code'
                                     ) AS C_COUNT";
                    }
                    $i++;
                }
                $v_report_subtitle   = 'Tháng ' . $month . ' năm ' . $year;

                //Nếu là tháng quá khứ thì dùng cache
                if (CONST_USE_ADODB_CACHE_FOR_REPORT && $month < Date('m') && $year <= Date('Y'))
                {
                    $arr_all_report_data =  $this->db->CachegetAll($sql);
                }
                else
                {
                    $arr_all_report_data =  $this->db->getAll($sql);
                }

                break;

            case 'week':
                $v_begin_date   = jwDate::ddmmyyyy_to_yyyymmdd(get_request_var('begin_date'));
                $v_end_date     = jwDate::ddmmyyyy_to_yyyymmdd(get_request_var('end_date'));

                $sql = '';
                $i=0;
                foreach ($arr_all_spec as $code => $name)
                {
                    $sql .= ($i >0) ? ' Union All ' : '';
                    if ($this->is_mssql())
                    {
                        $sql .= "SELECT
                                    '$code' AS SPEC_CODE
                                    ,'$name' as C_SPEC_NAME
                                    ,(SELECT COUNT(*)
                                      FROM view_record R LEFT JOIN t_r3_record_type RT ON R.FK_RECORD_TYPE=RT.PK_RECORD_TYPE
                                      WHERE DATEDIFF(day, '$v_begin_date', R.C_RECEIVE_DATE)>=0
                                        AND DATEDIFF(day, '$v_end_date', R.C_RECEIVE_DATE)<=0
                                        AND RT.C_SPEC_CODE='$code'
                                     ) AS C_COUNT";
                    }
                    elseif ($this->is_mysql())
                    {
                        $sql .= "SELECT
                                    '$code' AS SPEC_CODE
                                    ,'$name' as C_SPEC_NAME
                                    ,(SELECT COUNT(*)
                                      FROM view_record R LEFT JOIN t_r3_record_type RT ON R.FK_RECORD_TYPE=RT.PK_RECORD_TYPE
                                      WHERE DATEDIFF('$v_begin_date',R.C_RECEIVE_DATE)<=0
                                        AND DATEDIFF('$v_end_date',R.C_RECEIVE_DATE)>=0
                                        AND RT.C_SPEC_CODE='$code'
                                     ) AS C_COUNT";
                    }
                    $i++;
                }

                $v_report_subtitle   = 'Tuần từ ' . get_request_var('begin_date') . ' đến ' . get_request_var('end_date');

                //Dung Cache nếu là tuần quá khứ
                if ($v_end_date < 1)
                {

                }
                $arr_all_report_data =  $this->db->getAll($sql);
                break;

            case 'date':
            default:
                $date = jwDate::ddmmyyyy_to_yyyymmdd(get_request_var('date'));

                $sql = '';
                $i=0;
                foreach ($arr_all_spec as $code => $name)
                {
                    $sql .= ($i >0) ? ' Union All ' : '';
                    if ($this->is_mssql())
                    {
                        $sql .= "SELECT
                                    '$code' AS SPEC_CODE
                                    ,'$name' as C_SPEC_NAME
                                    ,(SELECT COUNT(*)
                                      FROM view_record R LEFT JOIN t_r3_record_type RT ON R.FK_RECORD_TYPE=RT.PK_RECORD_TYPE
                                      WHERE DATEDIFF(day, '$date', R.C_RECEIVE_DATE)=0
                                        AND RT.C_SPEC_CODE='$code'
                                     ) AS C_COUNT";
                    }
                    elseif ($this->is_mysql())
                    {
                        $sql .= "SELECT
                                    '$code' AS SPEC_CODE
                                    ,'$name' as C_SPEC_NAME
                                    ,(SELECT COUNT(*)
                                      FROM view_record R LEFT JOIN t_r3_record_type RT ON R.FK_RECORD_TYPE=RT.PK_RECORD_TYPE
                                      WHERE DATEDIFF('$date',R.C_RECEIVE_DATE)=0
                                        AND RT.C_SPEC_CODE='$code'
                                     ) AS C_COUNT";
                    }

                    $i++;
                }
                $v_report_subtitle   = 'Ngày ' . get_request_var('date');

                //Cache nếu là ngày quá khứ
                if ($date < 1)
                {

                }
                $arr_all_report_data =  $this->db->getAll($sql);

                break;
        }

        $ret['report_subtitle'] = $v_report_subtitle;
        $ret['arr_all_report_data'] = $arr_all_report_data;

        return $ret;
    }

    /**
     * Lấy danh sách phòng ban có tham gia vào việc thụ lý hồ sơ
     * @param Boolean $use_cache Co sung dung cache khong?
     */
    public function qry_all_exec_group($use_cache=FALSE)
    {
        $stmt = 'Select Distinct
                    G.C_CODE
                    ,G.C_NAME
                From t_cores_group G Left join t_r3_user_task UT On UT.C_GROUP_CODE = G.C_CODE
                Where UT.C_TASK_CODE LIKE ?
                Order by G.C_NAME';
        $params = array('%'._CONST_XML_RTT_DELIM . _CONST_PHAN_CONG_ROLE);
        return ($use_cache) ? $this->db->cacheGetAssoc($stmt, $params) : $this->db->getAssoc($stmt, $params);
    }

    /**
     * Lay danh sach tat ca phong ban
     * @param type $use_cache
     */
    public function qry_all_group($use_cache=FALSE)
    {
        $stmt = 'Select Distinct
                    G.C_CODE
                    ,G.C_NAME
                From t_cores_group G';
                //Order by G.C_NAME';
        return ($use_cache) ? $this->db->cacheGetAssoc($stmt) : $this->db->getAssoc($stmt);
    }

    public function qry_all_report_data_3($date)
    {
        //Danh sach phong ban chuyen mon
        $arr_all_exec_group            = $this->qry_all_exec_group(CONST_USE_ADODB_CACHE_FOR_REPORT);

        //1.Tong so HS tiep nhan trong ngay theo tung phong ban
        $sql = '';
        foreach ($arr_all_exec_group as $code => $name)
        {
            $sql .= ($sql != '') ? ' Union All ' : '';
            $sql .= "Select
                        COUNT(*) C_COUNT
                      , '$code' AS C_GROUP_CODE
                      From view_record R
                      Where ExtractValue(C_XML_PROCESSING, '//step[to_group_code=''$code'' and contains(datetime,''$date'')][last()]/@code[last()]') != ''";
        }
        $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
        if (is_past_date($date) &&  CONST_USE_ADODB_CACHE_FOR_REPORT)
        {
            $arr_count_received_record_by_group = $this->db->CacheGetAll($sql);
        }
        else
        {
            $arr_count_received_record_by_group = $this->db->getAll($sql);
        }

        //2. Tong so HS đang xử lý trong ngay theo tung phong ban
        $sql = "Select
                    COUNT(*) C_COUNT
                  , ExtractValue(C_XML_PROCESSING, '//next_task/@group_code') C_GROUP_CODE
                From view_processing_record
                Where
                  (   C_NEXT_TASK_CODE Like '%" . _CONST_XML_RTT_DELIM . _CONST_PHAN_CONG_ROLE . "'
                      OR C_NEXT_TASK_CODE Like '%" . _CONST_XML_RTT_DELIM . _CONST_THU_LY_ROLE . "'
                      OR C_NEXT_TASK_CODE Like '%" . _CONST_XML_RTT_DELIM . _CONST_XET_DUYET_ROLE . "'
                      OR C_NEXT_TASK_CODE Like '%" . _CONST_XML_RTT_DELIM . _CONST_YEU_CAU_THU_LY_LAI_ROLE . "'
                      OR C_NEXT_TASK_CODE Like '%" . _CONST_XML_RTT_DELIM . _CONST_XET_DUYET_BO_SUNG_ROLE . "'
                  )
                Group by C_GROUP_CODE";
        $arr_count_processing_record_by_group = $this->db->getAll($sql);

        //3. Tong so HS đang cham tien do trong ngay theo tung phong ban
        $sql = "Select
                    COUNT(*) C_COUNT
                  , ExtractValue(C_XML_PROCESSING, '//next_task/@group_code') C_GROUP_CODE
                From view_processing_record

                Where C_BIZ_DAYS_EXCEED IS NULL
                And (DATEDIFF(C_DOING_STEP_DEADLINE_DATE, NOW()) < 0)
                And (C_NEXT_TASK_CODE IS NOT NULL)
                Group by C_GROUP_CODE";
        $arr_count_delayed_record_by_group = $this->db->getAll($sql);

        //4. Tong hồ sơ đang quá hạn trong ngày theo tung phong ban
        $sql = "Select
                    COUNT(*) C_COUNT
                  , ExtractValue(C_XML_PROCESSING, '//next_task/@group_code') C_GROUP_CODE
                From view_processing_record

                Where C_BIZ_DAYS_EXCEED Is Null
                    And (datediff(C_RETURN_DATE, Now()) < 0)
                Group by C_GROUP_CODE";
        $arr_count_overtime_record_by_group = $this->db->getAll($sql);

        //return
        $ret['arr_count_received_record_by_group']      = $arr_count_received_record_by_group;
        $ret['arr_count_processing_record_by_group']    = $arr_count_processing_record_by_group;
        $ret['arr_count_delayed_record_by_group']       = $arr_count_delayed_record_by_group;
        $ret['arr_count_overtime_record_by_group']      = $arr_count_overtime_record_by_group;

        return $ret;

    }

    /**
     * Cham tien do
     * @return type
     */
    public function qry_all_report_data_12()
    {
        $sql = "SELECT
                    ExtractValue(C_XML_PROCESSING,'//next_task[last()]/@group_code') AS C_DOING_GROUP_CODE
                    ,Case When (R.C_REJECTED = 1) Then 3 When (R.C_REJECTED <> 1 And (R.C_CLEAR_DATE Is Not Null)) Then 2 Else 1 End as C_ACTIVITY
                    , CASE WHEN (DATEDIFF(NOW(), R.C_DOING_STEP_DEADLINE_DATE)>0) THEN (SELECT -1 * (COUNT(*)) FROM view_working_date WD WHERE DATEDIFF(WD.C_DATE, NOW())<=0 AND DATEDIFF(WD.C_DATE, R.C_DOING_STEP_DEADLINE_DATE)>0 ) ELSE (SELECT (COUNT(*)) FROM view_working_date WD WHERE DATEDIFF(WD.C_DATE, NOW())>=0 AND DATEDIFF(WD.C_DATE, R.C_DOING_STEP_DEADLINE_DATE)<0 ) END AS C_DOING_STEP_DAYS_REMAIN
                    , CASE WHEN (DATEDIFF(NOW(),R.C_RETURN_DATE)>0) THEN (SELECT -1 * (COUNT(*)) FROM view_working_date WD WHERE DATEDIFF(WD.C_DATE, NOW())<=0 AND DATEDIFF(WD.C_DATE, R.C_RETURN_DATE)>0 ) ELSE (SELECT (COUNT(*)) FROM view_working_date WD WHERE DATEDIFF(WD.C_DATE, NOW())>=0 AND DATEDIFF(WD.C_DATE, R.C_RETURN_DATE)<0 ) END AS C_RETURN_DAYS_REMAIN
                    , R.PK_RECORD
                    , R.FK_RECORD_TYPE
                    , R.C_RECORD_NO
                    , CAST(R.C_RECEIVE_DATE AS CHAR(19)) AS C_RECEIVE_DATE
                    , CAST(R.C_RETURN_DATE AS CHAR(19)) AS C_RETURN_DATE
                    , R.C_RETURN_PHONE_NUMBER
                    , R.C_XML_DATA
                    , R.C_XML_PROCESSING
                    , R.C_DELETED
                    , R.C_CLEAR_DATE
                    , R.C_XML_WORKFLOW
                    , R.C_RETURN_EMAIL
                    , R.C_REJECTED
                    , R.C_REJECT_REASON
                    , R.C_CITIZEN_NAME
                    , R.C_ADVANCE_COST
                    , R.C_CREATE_BY
                    , R.C_NEXT_TASK_CODE
                    , R.C_NEXT_USER_CODE
                    , R.C_NEXT_CO_USER_CODE
                    , R.C_LAST_TASK_CODE
                    , R.C_LAST_USER_CODE
                    , CAST(R.C_DOING_STEP_BEGIN_DATE AS CHAR(19)) AS C_DOING_STEP_BEGIN_DATE
                    , R.C_DOING_STEP_DEADLINE_DATE
                    , R.C_BIZ_DAYS_EXCEED
                    , a.C_TASK_CODE
                    , a.C_STEP_TIME
                    , 8 as TT
                FROM
                (
                    SELECT
                        RID.`PK_RECORD`
                      , UT.C_TASK_CODE
                      , UT.C_STEP_TIME
                    FROM
                    (
                        SELECT
                            PK_RECORD
                          , C_NEXT_TASK_CODE
                          , C_NEXT_USER_CODE
                        FROM view_processing_record
                        WHERE C_BIZ_DAYS_EXCEED IS NULL
                            AND (DATEDIFF(C_DOING_STEP_DEADLINE_DATE, NOW()) < 0)
                            AND (C_NEXT_TASK_CODE IS NOT NULL)
                    )  RID LEFT JOIN t_r3_user_task UT ON (RID.C_NEXT_TASK_CODE = UT.C_TASK_CODE AND RID.C_NEXT_USER_CODE = UT.C_USER_LOGIN_NAME)
                ) a LEFT JOIN view_record R ON a.PK_RECORD=R.PK_RECORD
                ORDER BY C_DOING_GROUP_CODE, R.C_RECEIVE_DATE DESC
                ";
       return $this->db->getAll($sql);
    }

    /**
     * Bao cao thu tuc hanh chinh qua han
     */
    public function qry_all_report_data_13()
    {
        $sql = "SELECT
                    ExtractValue(C_XML_PROCESSING,'//next_task[last()]/@group_code') AS C_DOING_GROUP_CODE
                    ,Case When (R.C_REJECTED = 1) Then 3 When (R.C_REJECTED <> 1 And (R.C_CLEAR_DATE Is Not Null)) Then 2 Else 1 End as C_ACTIVITY
                    , CASE WHEN (DATEDIFF(NOW(), R.C_DOING_STEP_DEADLINE_DATE)>0) THEN (SELECT -1 * (COUNT(*)) FROM view_working_date WD WHERE DATEDIFF(WD.C_DATE, NOW())<=0 AND DATEDIFF(WD.C_DATE, R.C_DOING_STEP_DEADLINE_DATE)>0 ) ELSE (SELECT (COUNT(*)) FROM view_working_date WD WHERE DATEDIFF(WD.C_DATE, NOW())>=0 AND DATEDIFF(WD.C_DATE, R.C_DOING_STEP_DEADLINE_DATE)<0 ) END AS C_DOING_STEP_DAYS_REMAIN
                    , CASE WHEN (DATEDIFF(NOW(),R.C_RETURN_DATE)>0) THEN (SELECT -1 * (COUNT(*)) FROM view_working_date WD WHERE DATEDIFF(WD.C_DATE, NOW())<=0 AND DATEDIFF(WD.C_DATE, R.C_RETURN_DATE)>0 ) ELSE (SELECT (COUNT(*)) FROM view_working_date WD WHERE DATEDIFF(WD.C_DATE, NOW())>=0 AND DATEDIFF(WD.C_DATE, R.C_RETURN_DATE)<0 ) END AS C_RETURN_DAYS_REMAIN
                    , R.PK_RECORD
                    , R.FK_RECORD_TYPE
                    , R.C_RECORD_NO
                    , CAST(R.C_RECEIVE_DATE AS CHAR(19)) AS C_RECEIVE_DATE
                    , CAST(R.C_RETURN_DATE AS CHAR(19)) AS C_RETURN_DATE
                    , R.C_RETURN_PHONE_NUMBER
                    , R.C_XML_DATA
                    , R.C_XML_PROCESSING
                    , R.C_DELETED
                    , R.C_CLEAR_DATE
                    , R.C_XML_WORKFLOW
                    , R.C_RETURN_EMAIL
                    , R.C_REJECTED
                    , R.C_REJECT_REASON
                    , R.C_CITIZEN_NAME
                    , R.C_ADVANCE_COST
                    , R.C_CREATE_BY
                    , R.C_NEXT_TASK_CODE
                    , R.C_NEXT_USER_CODE
                    , R.C_NEXT_CO_USER_CODE
                    , R.C_LAST_TASK_CODE
                    , R.C_LAST_USER_CODE
                    , CAST(R.C_DOING_STEP_BEGIN_DATE AS CHAR(19)) AS C_DOING_STEP_BEGIN_DATE
                    , R.C_DOING_STEP_DEADLINE_DATE
                    , R.C_BIZ_DAYS_EXCEED
                    , a.C_TASK_CODE
                    , a.C_STEP_TIME
                    , 8 as TT
                FROM
                (
                    SELECT
                        RID.`PK_RECORD`
                      , UT.C_TASK_CODE
                      , UT.C_STEP_TIME
                    FROM
                    (
                        SELECT
                            PK_RECORD
                          , C_NEXT_TASK_CODE
                          , C_NEXT_USER_CODE
                        From view_processing_record
                        Where C_BIZ_DAYS_EXCEED Is Null
                            And (datediff(C_RETURN_DATE, Now()) < 0)
                    )  RID LEFT JOIN t_r3_user_task UT ON (RID.C_NEXT_TASK_CODE = UT.C_TASK_CODE AND RID.C_NEXT_USER_CODE = UT.C_USER_LOGIN_NAME)
                ) a LEFT JOIN view_record R ON a.PK_RECORD=R.PK_RECORD
                ORDER BY C_DOING_GROUP_CODE, R.C_RECEIVE_DATE DESC
                ";
       return $this->db->getAll($sql);
    }
    /**
     * Bao cao thu tuc hanh chinh dang bo sung
     */
    public function qry_all_report_data_14()
    {
        $sql = "SELECT
                    ExtractValue(C_XML_PROCESSING,'//next_task[last()]/@group_code') AS C_DOING_GROUP_CODE
                    ,Case When (R.C_REJECTED = 1) Then 3 When (R.C_REJECTED <> 1 And (R.C_CLEAR_DATE Is Not Null)) Then 2 Else 1 End as C_ACTIVITY
                    , CASE WHEN (DATEDIFF(NOW(), R.C_DOING_STEP_DEADLINE_DATE)>0) THEN (SELECT -1 * (COUNT(*)) FROM view_working_date WD WHERE DATEDIFF(WD.C_DATE, NOW())<=0 AND DATEDIFF(WD.C_DATE, R.C_DOING_STEP_DEADLINE_DATE)>0 ) ELSE (SELECT (COUNT(*)) FROM view_working_date WD WHERE DATEDIFF(WD.C_DATE, NOW())>=0 AND DATEDIFF(WD.C_DATE, R.C_DOING_STEP_DEADLINE_DATE)<0 ) END AS C_DOING_STEP_DAYS_REMAIN
                    , CASE WHEN (DATEDIFF(NOW(),R.C_RETURN_DATE)>0) THEN (SELECT -1 * (COUNT(*)) FROM view_working_date WD WHERE DATEDIFF(WD.C_DATE, NOW())<=0 AND DATEDIFF(WD.C_DATE, R.C_RETURN_DATE)>0 ) ELSE (SELECT (COUNT(*)) FROM view_working_date WD WHERE DATEDIFF(WD.C_DATE, NOW())>=0 AND DATEDIFF(WD.C_DATE, R.C_RETURN_DATE)<0 ) END AS C_RETURN_DAYS_REMAIN
                    , R.PK_RECORD
                    , R.FK_RECORD_TYPE
                    , R.C_RECORD_NO
                    , CAST(R.C_RECEIVE_DATE AS CHAR(19)) AS C_RECEIVE_DATE
                    , CAST(R.C_RETURN_DATE AS CHAR(19)) AS C_RETURN_DATE
                    , R.C_RETURN_PHONE_NUMBER
                    , R.C_XML_DATA
                    , R.C_XML_PROCESSING
                    , R.C_DELETED
                    , R.C_CLEAR_DATE
                    , R.C_XML_WORKFLOW
                    , R.C_RETURN_EMAIL
                    , R.C_REJECTED
                    , R.C_REJECT_REASON
                    , R.C_CITIZEN_NAME
                    , R.C_ADVANCE_COST
                    , R.C_CREATE_BY
                    , R.C_NEXT_TASK_CODE
                    , R.C_NEXT_USER_CODE
                    , R.C_NEXT_CO_USER_CODE
                    , R.C_LAST_TASK_CODE
                    , R.C_LAST_USER_CODE
                    , CAST(R.C_DOING_STEP_BEGIN_DATE AS CHAR(19)) AS C_DOING_STEP_BEGIN_DATE
                    , R.C_DOING_STEP_DEADLINE_DATE
                    , R.C_BIZ_DAYS_EXCEED
                    , a.C_TASK_CODE
                    , a.C_STEP_TIME
                    , 8 as TT
                    , CAST(DATE_FORMAT(ExtractValue(C_XML_PROCESSING, '//step[contains(@code,''" . _CONST_XML_RTT_DELIM . _CONST_THONG_BAO_BO_SUNG_ROLE . "'')][last()]/datetime'),'%d-%m-%Y %H:%i:%s') AS CHAR ) as C_ANNOUNCE_DATE
                FROM
                (
                    SELECT
                        RID.`PK_RECORD`
                      , UT.C_TASK_CODE
                      , UT.C_STEP_TIME
                    FROM
                    (
                        SELECT
                            PK_RECORD
                          , C_NEXT_TASK_CODE
                          , C_NEXT_USER_CODE
                         From view_processing_record
                        Where C_NEXT_TASK_CODE like '%" . _CONST_XML_RTT_DELIM . _CONST_BO_SUNG_ROLE . "'
                    )  RID LEFT JOIN t_r3_user_task UT ON (RID.C_NEXT_TASK_CODE = UT.C_TASK_CODE AND RID.C_NEXT_USER_CODE = UT.C_USER_LOGIN_NAME)
                ) a LEFT JOIN view_record R ON a.PK_RECORD=R.PK_RECORD
                ORDER BY R.C_RECEIVE_DATE DESC
                ";
       return $this->db->getAll($sql);
    }

    /**
     * Bao cao thu tuc hanh chinh bi tu choi
     */
    public function qry_all_report_data_15($begin_date_yyyymmdd, $end_date_yyyymmdd)
    {
        $sql = "SELECT
                    ExtractValue(C_XML_PROCESSING,'//next_task[last()]/@group_code') AS C_DOING_GROUP_CODE
                    ,Case When (R.C_REJECTED = 1) Then 3 When (R.C_REJECTED <> 1 And (R.C_CLEAR_DATE Is Not Null)) Then 2 Else 1 End as C_ACTIVITY
                    , CASE WHEN (DATEDIFF(NOW(), R.C_DOING_STEP_DEADLINE_DATE)>0) THEN (SELECT -1 * (COUNT(*)) FROM view_working_date WD WHERE DATEDIFF(WD.C_DATE, NOW())<=0 AND DATEDIFF(WD.C_DATE, R.C_DOING_STEP_DEADLINE_DATE)>0 ) ELSE (SELECT (COUNT(*)) FROM view_working_date WD WHERE DATEDIFF(WD.C_DATE, NOW())>=0 AND DATEDIFF(WD.C_DATE, R.C_DOING_STEP_DEADLINE_DATE)<0 ) END AS C_DOING_STEP_DAYS_REMAIN
                    , CASE WHEN (DATEDIFF(NOW(),R.C_RETURN_DATE)>0) THEN (SELECT -1 * (COUNT(*)) FROM view_working_date WD WHERE DATEDIFF(WD.C_DATE, NOW())<=0 AND DATEDIFF(WD.C_DATE, R.C_RETURN_DATE)>0 ) ELSE (SELECT (COUNT(*)) FROM view_working_date WD WHERE DATEDIFF(WD.C_DATE, NOW())>=0 AND DATEDIFF(WD.C_DATE, R.C_RETURN_DATE)<0 ) END AS C_RETURN_DAYS_REMAIN
                    , R.PK_RECORD
                    , R.FK_RECORD_TYPE
                    , R.C_RECORD_NO
                    , CAST(R.C_RECEIVE_DATE AS CHAR(19)) AS C_RECEIVE_DATE
                    , CAST(DATE_FORMAT(R.C_RECEIVE_DATE,'%d-%m-%Y %H:%i:%s') AS CHAR) AS C_RECEIVE_DATE_DDMMYYYY
                    , CAST(R.C_RETURN_DATE AS CHAR(19)) AS C_RETURN_DATE
                    , CAST(DATE_FORMAT(R.C_RETURN_DATE,'%d-%m-%Y %H:%i:%s') AS CHAR) AS C_RETURN_DATE_DDMMYYYY
                    , R.C_RETURN_PHONE_NUMBER
                    , R.C_XML_DATA
                    , R.C_XML_PROCESSING
                    , R.C_DELETED
                    , R.C_CLEAR_DATE
                    , R.C_XML_WORKFLOW
                    , R.C_RETURN_EMAIL
                    , R.C_REJECTED
                    , R.C_REJECT_REASON
                    , R.C_CITIZEN_NAME
                    , R.C_ADVANCE_COST
                    , R.C_CREATE_BY
                    , R.C_NEXT_TASK_CODE
                    , R.C_NEXT_USER_CODE
                    , R.C_NEXT_CO_USER_CODE
                    , R.C_LAST_TASK_CODE
                    , R.C_LAST_USER_CODE
                    , CAST(R.C_DOING_STEP_BEGIN_DATE AS CHAR(19)) AS C_DOING_STEP_BEGIN_DATE
                    , R.C_DOING_STEP_DEADLINE_DATE
                    , R.C_BIZ_DAYS_EXCEED
                    , a.C_TASK_CODE
                    , a.C_STEP_TIME
                    , 8 as TT
                    , CAST(DATE_FORMAT(C_REJECTED_DATE,'%d-%m-%Y %H:%i:%s') AS CHAR) AS C_REJECTED_DATE_DDMMYYYY
                FROM
                (
                    SELECT
                        RID.`PK_RECORD`
                      , UT.C_TASK_CODE
                      , UT.C_STEP_TIME
                      , C_REJECTED_DATE
                    FROM
                    (
                        SELECT
                            PK_RECORD
                          , C_NEXT_TASK_CODE
                          , C_NEXT_USER_CODE
                          , CAST(DATE_FORMAT(ExtractValue(C_XML_PROCESSING, '//step[@code=''REJECT''][last()]/datetime'),'%Y-%m-%d %H:%i:%s') AS CHAR) AS C_REJECTED_DATE
                        From view_record
                        Where C_REJECTED=1
                    )  RID LEFT JOIN t_r3_user_task UT ON (RID.C_NEXT_TASK_CODE = UT.C_TASK_CODE AND RID.C_NEXT_USER_CODE = UT.C_USER_LOGIN_NAME)
                ) a LEFT JOIN view_record R ON a.PK_RECORD=R.PK_RECORD
                Where (DATEDIFF('$begin_date_yyyymmdd',C_REJECTED_DATE) <=0) And (DATEDIFF('$end_date_yyyymmdd', C_REJECTED_DATE) >=0)
                ORDER BY R.C_RECEIVE_DATE DESC
                ";
       return $this->db->getAll($sql);
    }

    public function qry_all_report_data_16($v_spec_code, $v_record_type_id, $v_begin_date_yyyymmdd, $v_end_date_yyyymmdd)
    {
        $arr_report_filter = Array();
        $sql = "Select
                    C_CITIZEN_NAME
                    ,FK_RECORD_TYPE
                    ,C_RECORD_NO
                    ,C_RECEIVE_DATE
                    ,C_RETURN_DATE
                    ,CAST(DATE_FORMAT(ExtractValue(C_XML_PROCESSING, '//step[@code=''REJECT''][last()]/datetime'),'%d-%m-%Y %H:%i:%s') AS CHAR(19)) AS C_REJECTED_DATE_DDMMYYYY
                    ,C_CLEAR_DATE
              From view_record
              Where 1>0 ";
        if ($v_spec_code != '')
        {
            //Danh sach linh vuc
            $arr_all_spec           = $this->assoc_list_get_all_by_listtype_code(_CONST_DANH_MUC_LINH_VUC, CONST_USE_ADODB_CACHE_FOR_REPORT);
            array_push($arr_report_filter, array('Lĩnh vực: ', $arr_all_spec[$v_spec_code]));

            if ($v_record_type_id > 0)
            {
                $sql .= " And FK_RECORD_TYPE=$v_record_type_id";

                //Danh sach loai thu tuc
                $arr_all_record_type = $this->db->cacheGetAssoc("Select PK_RECORD_TYPE, C_CODE, C_NAME From t_r3_record_type");
                array_push($arr_report_filter, array('Loại hồ sơ: ', $arr_all_record_type[$v_record_type_id]['C_CODE'] . ' - ' . $arr_all_record_type[$v_record_type_id]['C_NAME']));
            }
            else
            {
                if (CONST_USE_ADODB_CACHE_FOR_REPORT)
                {
                    $v_record_type_id_list = implode(',', $this->db->CacheGetCol("Select PK_RECORD_TYPE From t_r3_record_type RT Where C_SPEC_CODE='$v_spec_code'"));
                }
                else
                {
                    $v_record_type_id_list = implode(',', $this->db->getCol("Select PK_RECORD_TYPE From t_r3_record_type RT Where C_SPEC_CODE='$v_spec_code'"));
                }
                $sql .= " And FK_RECORD_TYPE In ($v_record_type_id_list)";
            }
        }

        if ($v_begin_date_yyyymmdd != '')
        {
            $sql .= " And (DATEDIFF('$v_begin_date_yyyymmdd',C_RECEIVE_DATE) <=0)";

            array_push($arr_report_filter, array('Tiếp nhận từ ngày: ', $v_begin_date_yyyymmdd));
        }
        if ($v_end_date_yyyymmdd != '')
        {
            $sql .= " And (DATEDIFF('$v_end_date_yyyymmdd', C_RECEIVE_DATE) >=0)";
            array_push($arr_report_filter, array('đến ngày: ', $v_end_date_yyyymmdd));
        }

        $ret['report_data']     = $this->db->getAll($sql);
        $ret['report_filter']   = $arr_report_filter;
        return $ret;
    }

    public function qry_all_report_data_7($v_begin_date_yyyymmdd, $v_end_date_yyyymmdd)
    {
        //Danh muc linh vuc


        $stmt = "Select
                        a.C_SPEC_CODE
                      , COUNT(*) C_TOTAL_RECORD
                      , SUM(a.C_FINAL_FEE) AS C_TOTAL_FEE
                      ,a.C_CHARGE_DATE
                  From
                  (
                      Select
                            RF.FK_RECORD
                          , RF.C_FINAL_FEE
                          , R.FK_RECORD_TYPE
                          , RT.C_SPEC_CODE
                          , ExtractValue(R.C_XML_PROCESSING, '//step[contains(@code,''" . _CONST_XML_RTT_DELIM . _CONST_THU_LY_ROLE . "'')][last()]/datetime[last()]') AS C_CHARGE_DATE
                      From t_r3_record_fee RF Left join view_record R On RF.FK_RECORD = R.PK_RECORD
                        Left join t_r3_record_type RT On R.FK_RECORD_TYPE = RT.PK_RECORD_TYPE
                  ) a
                  Where a.C_SPEC_CODE IS NOT NULL
                        AND  DATEDIFF('$v_begin_date_yyyymmdd',a.C_CHARGE_DATE)<=0
                        AND DATEDIFF('$v_end_date_yyyymmdd',a.C_CHARGE_DATE)>=0
                  Group by C_SPEC_CODE";
        $arr_report_data = $this->db->getAssoc($stmt);

        return $arr_report_data;
    }

    public function qry_all_report_data_7b($v_begin_date_yyyymmdd, $v_end_date_yyyymmdd)
    {
        //Lay danh sach Linh vuc
        $arr_all_spec           = $this->assoc_list_get_all_by_listtype_code(_CONST_DANH_MUC_LINH_VUC, CONST_USE_ADODB_CACHE_FOR_REPORT);
        $sql = '';
        foreach ($arr_all_spec as $code => $name)
        {
            $sql .= ($sql != '') ? ' Union All ' : '';
            $sql .= "Select
                            C_SPEC_CODE
                          , C_SPEC_NAME
                          , C_RECORD_NO
                          , C_CITIZEN_NAME
                          , C_FINAL_FEE
                          , C_FEE_DESCRIPTION
                          , DATE_FORMAT(C_CHARGE_DATE, '%d-%m-%Y %H:%i:%s') C_CHARGE_DATE
                    From (
                            Select
                                  RT.C_SPEC_CODE
                                , '$name' AS C_SPEC_NAME
                                , R.C_RECORD_NO
                                , R.C_CITIZEN_NAME
                                , RF.C_FINAL_FEE
                                , RF.C_FEE_DESCRIPTION
                                , ExtractValue(R.C_XML_PROCESSING, '//step[contains(@code,''" . _CONST_XML_RTT_DELIM . _CONST_THU_LY_ROLE . "'')][last()]/datetime[last()]') AS C_CHARGE_DATE
                            From T_R3_record_fee RF Left join view_record R On RF.FK_RECORD = R.PK_RECORD
                                  Left join t_r3_record_type RT On R.FK_RECORD_TYPE = RT.PK_RECORD_TYPE
                            Where RT.C_SPEC_CODE = '$code'
                          ) $code
                    Where DATEDIFF('$v_begin_date_yyyymmdd',C_CHARGE_DATE)<=0 And DATEDIFF('$v_end_date_yyyymmdd',C_CHARGE_DATE)>=0 ";
        }

        return $this->db->GetAll($sql);
    }
}