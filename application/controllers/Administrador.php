
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Administrador extends CI_Controller {
    public function index()
    {

        $this->load->view('template/administrador/panelAdmin/header');
        $this->load->view('administrador/panelAdmin');
        $this->load->view('template/administrador/panelAdmin/footer');
    }
}

