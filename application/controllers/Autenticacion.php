<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Autenticacion extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Autenticacion_model');
    }
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

    public function iniciarSesion()
    {
        // Reglas de validación
        $this->form_validation->set_rules(
            'correo',
            'Correo',
            'required|trim|valid_email'
        );

        $this->form_validation->set_rules(
            'contrasena',
            'Contraseña',
            'required'
        );

        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
            return;
        }

        $correo = $this->input->post('correo');
        $contrasena = $this->input->post('contrasena');

        $usuario = $this->Autenticacion_model->buscarPorCorreo($correo);

        if ($usuario) {
            if (password_verify($contrasena, $usuario->contraseña)) {

                redirect('administrador');

            } else {

                echo "Contraseña incorrecta";
            }
        } else {
            echo "Usuario no encontrado";
        }
    }
}
