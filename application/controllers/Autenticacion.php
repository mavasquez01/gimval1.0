<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Autenticacion extends CI_Controller {
    public function index()
    {

        $this->load->view('template/autenticacion/iniciarSesion/header');
        $this->load->view('autenticacion/iniciarSesion');
        $this->load->view('template/autenticacion/iniciarSesion/footer');
    }

    public function completarPerfil()
    {

        $this->load->view('template/autenticacion/completarPerfil/header');
        $this->load->view('autenticacion/completarPerfil');
        $this->load->view('template/autenticacion/completarPerfil/footer');
    }

    public function nuevaContrasenia()
    {

        $this->load->view('template/autenticacion/nuevaContrasenia/header');
        $this->load->view('autenticacion/nuevaContrasenia');
        $this->load->view('template/autenticacion/nuevaContrasenia/footer');
    }

    public function recuperarContrasenia()
    {

        $this->load->view('template/autenticacion/recuperarContrasenia/header');
        $this->load->view('autenticacion/recuperarContrasenia');
        $this->load->view('template/autenticacion/recuperarContrasenia/footer');
    }
}    