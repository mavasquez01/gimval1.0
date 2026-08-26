<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Administrador extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        //true: login obligatorio 
        //false: para acceder sin login
        $protegerRutas = false;

        if ($protegerRutas) {
            if (!$this->session->userdata('logueado')) {
                redirect('autenticacion');
            }
        }
    }
    public function index()
    {

        $this->load->view('template/administrador/panelAdmin/header');
        $this->load->view('administrador/panelAdmin');
        $this->load->view('template/administrador/panelAdmin/footer');
    }

    public function horarios()
    {

        $this->load->view('template/administrador/horarios/header');
        $this->load->view('administrador/horarios');
        $this->load->view('template/administrador/horarios/footer');
    }

    public function gestionUsers()
    {

        $this->load->view('template/administrador/gestionUsers/header');
        $this->load->view('administrador/gestionUsers');
        $this->load->view('template/administrador/gestionUsers/footer');
    }

    public function editarBloque()
    {

        $this->load->view('template/administrador/editarBloque/header');
        $this->load->view('administrador/editarBloque');
        $this->load->view('template/administrador/editarBloque/footer');
    }

    public function detalleUser()
    {

        $this->load->view('template/administrador/detalleUser/header');
        $this->load->view('administrador/detalleUser');
        $this->load->view('template/administrador/detalleUser/footer');
    }

    public function crearUser()
    {

        $this->load->view('template/administrador/crearUser/header');
        $this->load->view('administrador/crearUser');
        $this->load->view('template/administrador/crearUser/footer');
    }

    public function crearBloque()
    {

        $this->load->view('template/administrador/crearBloque/header');
        $this->load->view('administrador/crearBloque');
        $this->load->view('template/administrador/crearBloque/footer');
    }
}

