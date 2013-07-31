<?php if (!defined('SERVER_ROOT')) exit('No direct script access allowed');

class login_Controller extends Controller {
     function __construct() {
        parent::__construct('cores', 'login');
        $this->model->goback_url = $this->view->get_controller_url();
    }

    public function main()
    {
        $this->dsp_login();
    }

    public function dsp_login()
    {
        $this->view->render('dsp_login');
    }

    public function do_login()
    {
        $this->model->goback_url = $this->view->get_controller_url();
        $this->model->do_login();
    }
    public function do_logout(){
        @session::init();
        session::destroy();
        #header('location:' . SITE_ROOT);
		$link = mysql_connect("localhost", "root", "root");
		mysql_select_db($database, $link);
		
		$user = $_POST['email'];
		$sql = "update information set kind=0 where username = '$user'";
		$result = mysql_query($sql, $link);
		if ($result)
		{
			$affectedRow = mysql_affected_rows();
			echo $user." đã đăng xuất";
		}
        $this->dsp_login();
    }
}