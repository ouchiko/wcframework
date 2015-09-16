<?php

	use AppSpace\Controllers\Controller;
	use MVC\models\IpRecorderModel;


	class IpRecorderController extends Controller {



		public function init() {

		}

		public function push(){
			$model = new IpRecorderModel();
			print_r($_SERVER);
			$model -> pushIpAddress($_SERVER['REMOTE_ADDR']);
		}

		public function get() {
			$model = new IpRecorderModel();
			$data['json'] = $model -> getIpAddress();

			$this->view("json_default.html", $data);
		}
    
	}

?>