<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FR extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Common_Model', 'cm', TRUE);
		$this->load->model('User_Model', '', TRUE);
		$this->load->model('Session_Model', 'ses', TRUE);
		$this->load->driver('crs');
	}
	
	/*public function test()
	{
		print_r($this->crs->ontology->Type('Lenovo_IdeaTab_S6000'));	
	}
	
	public function index()
	{
		$data['view'] 	= 'fr/_index';
		$data['title']	= 'Rekomendasi Berdasarkan Kebutuhan Fungsional';
		
		$this->load->view('tpl', $data);
	}
	
	public function start()
	{
		if(!$this->input->is_ajax_request()) return false;
		if(!$this->input->post('inp')) return false;
		
		$data = $this->input->post('inp');
		
		$id = $this->User_Model->add($data);
		
		$this->session->set_userdata(array('iduser' => $id));
		
		echo 'ok;';
	}*/
	
	public function ui_question_u1()
	{
		$data['brand']	= $this->cm->brand();
		// $data['os']		= $this->cm->os();
		$data['type']	= $this->cm->type();
		$data['fr'] 	= $this->crs->usermodeling->GenerateGeneralQ();
		
		echo $this->load->view('fr/_fr', $data, true);
	}
	
	public function ui_question_u4()
	{
		$usermodel	= $this->session->userdata('usermodel');
		$data['fr'] = $this->crs->usermodeling->GenerateSpecificQ($usermodel);
		$data['function'] = 'recommend';
		
		$this->session->set_userdata(array('uu' => 'u4'));
		
		echo $this->load->view('fr/_fr_no', $data, true);
	}
	
	public function ui_question_u2()
	{
		if(!$this->input->is_ajax_request()) return false;
		if(!$this->input->post('product')) return false;
		
		$product 	= $this->input->post('product');
		$products 	= explode('||', $product);
		$data['result'] = $this->crs->usermodeling->GenerateDistinguishQ($products);
		
		echo $this->load->view('fr/_u2', $data, true);
	}
	
	public function ui_question_u3()
	{
		$usermodel	= $this->session->userdata('usermodel');
		$data['fr'] = $this->crs->usermodeling->GenerateUnExploredQ($usermodel);
		$data['function'] = 'recommend';
		
		$this->session->set_userdata(array('uu' => 'u3'));
		
		echo $this->load->view('fr/_fr_no', $data, true);
	}
	
	public function ui_question_u5()
	{
		
		$usermodel	= $this->session->userdata('usermodel');
		$pref		= $this->session->userdata('pref');
		$data['result'] = $this->crs->usermodeling->GenerateContradictoryQ($usermodel, $pref);
		$data['brand']	= $this->cm->brand();
		// $data['os']		= $this->cm->os();
		$data['type']	= $this->cm->type();
		
		echo $this->load->view('fr/_u5', $data, true);
	}
	
	public function ui_recommend_u1()
	{
		if(!$this->input->is_ajax_request()) return false;
		if(!$this->input->post('inp')) return false;
		
		$inp		= $this->input->post('inp');
		$usermodel 	= array();
		$pref		= array();
		$iduser		= $this->session->userdata('iduser');
		
		foreach($inp['usermodel'] as $name => $value)
		{
			$usermodel[] = array('name' => $name, 'level' => 1, 'status' => $value, 'leaf' => true);
		}
		
		if(!empty($inp['brand'])) 	$pref['brand'] = $inp['brand'];
		if(!empty($inp['os'])) 		$pref['os'] = $inp['os'];
		if(!empty($inp['type'])) 	$pref['type'] = $inp['type'];
		
		if(!empty($inp['price']['start']) && empty($inp['price']['end']))
		{
			$pref['price']['start'] = (int) str_replace(array('.', '_'), '', $inp['price']['start']);
			$pref['price']['end'] 	= 30000000;
		}
		else if(empty($inp['price']['start']) && !empty($inp['price']['end']))
		{
			$pref['price']['start'] = 0;
			$pref['price']['end'] 	= (int) str_replace(array('.', '_'), '', $inp['price']['end']);
		}
		else if(!empty($inp['price']['start']) && !empty($inp['price']['end']))
		{
			$pref['price']['start'] = (int) str_replace(array('.', '_'), '', $inp['price']['start']);
			$pref['price']['end'] 	= (int) str_replace(array('.', '_'), '', $inp['price']['end']);
		}
		
		$this->session->set_userdata(array('usermodel' => $usermodel, 'level' => 1, 'pref' => $pref));
		
		/*echo 'recommend 1<pre>';
		echo 'usermodel :';
		var_dump($usermodel);
		echo 'preferensi :';
		var_dump($pref);
		//var_dump($level);
		echo '</pre>';*/
		
		$result = $this->crs->pre->Recommend($usermodel, $pref);
		
		if(empty($result))
		{
			echo "error;;u5";
		}
		else if(!empty($result) && count($result) <= $this->config->item('limit_recommend'))
		{
			$data['result'] = $this->crs->pre->explain($result, $usermodel, $pref);
			echo $this->load->view('fr/_recommend', $data, true);
		}
		else if(!empty($result) && count($result) > $this->config->item('limit_recommend'))
		{
			echo "error;;produk yang sesuai sebanyak = ".count($result)." produk\nJumlah produk yang sesuai masih terlalu banyak\nTekan ok untuk pertanyaan berikutnya yang lebih spesifik atau cancel untuk berhenti";
		}
	}
	
	public function ui_recommend()
	{
		if(!$this->input->is_ajax_request()) return false;
		if(!$this->input->post('inp')) return false;
		
		$inp		= $this->input->post('inp');
		$usermodel 	= $this->session->userdata('usermodel');
		$level 		= $this->session->userdata('level');
		$pref		= $this->session->userdata('pref');
		$iduser		= $this->session->userdata('iduser');
		$uu			= $this->session->userdata('uu');
		
		foreach($inp['usermodel'] as $name => $value)
		{
			$usermodel[] = array('name' => $name, 'level' => $level, 'status' => $value, 'leaf' => true);
		}
		
		$this->session->set_userdata(array('usermodel' => $usermodel));
		/*echo 'recommend<pre>';
		echo 'usermodel :';
		var_dump($usermodel);
		echo 'preferensi :';
		var_dump($pref);
		var_dump($level);
		echo '</pre>';*/
		$result = $this->crs->pre->Recommend($usermodel, $pref);
		
		if(empty($result))
		{
			echo "error;;u5";
		}
		else if(!empty($result) && count($result) <= $this->config->item('limit_recommend'))
		{
			$data['result'] = $this->crs->pre->explain($result, $usermodel, $pref);
			echo $this->load->view('fr/_recommend', $data, true);
		}
		else if(!empty($result) && count($result) > $this->config->item('limit_recommend'))
		{
			echo "error;;produk yang sesuai sebanyak = ".count($result)." produk\nJumlah produk yang sesuai masih terlalu banyak\nTekan ok untuk pertanyaan berikutnya yang lebih spesifik atau cancel untuk berhenti";
		}
	}
	
	public function ui_recommend_u5()
	{
		if(!$this->input->is_ajax_request()) return false;
		if(!$this->input->post('inp')) return false;
		
		$inp		= $this->input->post('inp');
		$usermodel 	= $this->session->userdata('usermodel');
		$level 		= $this->session->userdata('level');
		$pref		= $this->session->userdata('pref');
		$iduser		= $this->session->userdata('iduser');
		//var_dump($usermodel);
		
		if(!empty($inp['usermodel']))
		{
			foreach($inp['usermodel'] as $name => $value)
			{
				foreach($usermodel as $id => $um)
				{
					if($name == $um['name'])
						$usermodel[$id]['status'] = $value;
				}
			}		
			$this->session->set_userdata(array('usermodel' => $usermodel));
		}
		else
		{
			//save to db
			//$this->ses->adding($iduser, '', '', 'u5');	
		}
		
		if(!empty($inp['chk_pref']))
		{
			foreach($inp['chk_pref'] as $p)
				$pref[$p] = isset($inp[$p]) ? $inp[$p] : array();
				
			$this->session->set_userdata(array('pref' => $pref));
		}
		
		if(!empty($inp['price']))
		{
			if(!empty($inp['price']['start']) && empty($inp['price']['end']))
			{
				$pref['price']['start'] = (int) str_replace(array('.', '_'), '', $inp['price']['start']);
				$pref['price']['end'] 	= 30000000;
			}
			else if(empty($inp['price']['start']) && !empty($inp['price']['end']))
			{
				$pref['price']['start'] = 0;
				$pref['price']['end'] 	= (int) str_replace(array('.', '_'), '', $inp['price']['end']);
			}
			else if(!empty($inp['price']['start']) && !empty($inp['price']['end']))
			{
				$pref['price']['start'] = (int) str_replace(array('.', '_'), '', $inp['price']['start']);
				$pref['price']['end'] 	= (int) str_replace(array('.', '_'), '', $inp['price']['end']);
			}
			
			$this->session->set_userdata(array('pref' => $pref));
		}	
		
		/*echo 'recommend5<pre>';
		echo 'usermodel :';
		var_dump($usermodel);
		echo 'preferensi :';
		var_dump($pref);
		echo '</pre>';*/
		$result = $this->crs->pre->Recommend($usermodel, $pref);
		
		if(empty($result))
		{
			echo "error;;u5";
		}
		else if(!empty($result) && count($result) <= $this->config->item('limit_recommend'))
		{
			$data['result'] = $this->crs->pre->explain($result, $usermodel, $pref);
			echo $this->load->view('fr/_recommend', $data, true);
		}
		else if(!empty($result) && count($result) > $this->config->item('limit_recommend'))
		{
			echo "error;;produk yang sesuai sebanyak = ".count($result)." produk\nJumlah produk yang sesuai masih terlalu banyak\nTekan ok untuk pertanyaan berikutnya yang lebih spesifik atau cancel untuk berhenti";
		}
	}
	
	public function ui_choose()
	{
		if(!$this->input->is_ajax_request()) return false;
		if(!$this->input->post('prod')) return false;
		
		$inp 	 = $this->input->post('prod');
		$product = $this->crs->ontology->get_product();
		$result  = array();
		
		$usermodel 	= $this->session->userdata('usermodel');
		$level 		= $this->session->userdata('level');
		$pref		= $this->session->userdata('pref');
		$iduser		= $this->session->userdata('iduser');
		
		foreach($product as $prod)
		{
			if($prod->produk == $inp)
			{
				$result[] = array('produk' => $prod->produk, 'suppf' => $prod->suppf);
				
				//save to db
				//$this->ses->adding($iduser, $prod->produk, '', 'choose');
			}
		}
		
		$resultx = $this->crs->pre->recommend_single($result, $usermodel, $pref);
		
		if(!empty($result))
			$data['result'] = $this->crs->pre->explain($resultx, $usermodel, $pref);
		else
			$data['result'] = array();
		
		echo $this->load->view('fr/_choose', $data, true);
	}
	
	/*public function ui_recommend_u3()
	{
		if(!$this->input->is_ajax_request()) return false;
		if(!$this->input->post('inp')) return false;
		
		$inp		= $this->input->post('inp');
		$usermodel 	= $this->session->userdata('usermodel');
		$level 		= $this->session->userdata('level');
		$pref		= array();
		
		foreach($inp['usermodel'] as $name => $value)
			$usermodel[] = array('name' => $name, 'level' => $level, 'status' => $value, 'leaf' => true);
		
		$this->session->set_userdata(array('usermodel' => $usermodel));
		echo '<pre>';
		var_dump($usermodel);
		echo '</pre>';
		$result = $this->crs->pre->Recommend($usermodel, $pref);
		
		if(count($result) <= $this->config->item('limit_recommend'))
		{
			$data['result'] = $this->crs->pre->explain($result, $usermodel, $pref);
			echo $this->load->view('fr/_recommend', $data, true);
		}
		else
		{
			echo "error;;produk yang sesuai sebanyak = ".count($result)." produk\nJumlah produk masih terlalu banyak\nTekan ok untuk memasukkan kebutuhan yang lebih spesifik atau cancel untuk berhenti";
		}
	}*/
}

?>