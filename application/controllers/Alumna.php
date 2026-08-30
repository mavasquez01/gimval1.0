<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Alumna extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        //true: login obligatorio 
        //false: para acceder sin login
        $protegerRutas = true;

        if ($protegerRutas) {
            if (!$this->session->userdata('logueado')) {
                redirect('autenticacion');
            }
        }
    }
    public function index()
    {

        $this->load->view('template/alumna/panelAlumna/header');
        $this->load->view('alumna/panelAlumna');
        $this->load->view('template/alumna/panelAlumna/footer');
    }

    public function rutina()
    {

        $this->load->view('template/alumna/rutina/header');
        $this->load->view('alumna/rutina');
        $this->load->view('template/alumna/rutina/footer');
    }

    public function modificarDatos()
    {

        $this->load->view('template/alumna/modificarDatos/header');
        $this->load->view('alumna/modificarDatos');
        $this->load->view('template/alumna/modificarDatos/footer');
    }

    public function convenios()
    {

        $this->load->view('template/alumna/convenios/header');
        $this->load->view('alumna/convenios');
        $this->load->view('template/alumna/convenios/footer');
    }

    public function cambiarContrasenia()
    {

        $this->load->view('template/alumna/cambiarContraseña/header');
        $this->load->view('alumna/cambiarContraseña');
        $this->load->view('template/alumna/cambiarContraseña/footer');
    }

    public function cerrarSesion()
    {
        $this->session->sess_destroy();

        redirect('autenticacion');
    }  
}