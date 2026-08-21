<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Horarios extends CI_Controller {
    public function index()
    {

        $this->load->view('template/administrador/horarios/header');
        $this->load->view('administrador/horarios');
        $this->load->view('template/administrador/horarios/footer');
    }
}