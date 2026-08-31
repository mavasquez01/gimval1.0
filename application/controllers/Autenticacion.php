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

    public function generarHash()
    {
        echo password_hash('12345678', PASSWORD_DEFAULT);
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

        // Si los datos enviados no cumplen las validaciones
        if ($this->form_validation->run() == FALSE) {

            $this->session->set_flashdata(
                'alerta_login',
                [
                    'icon' => 'warning',
                    'title' => 'Datos inválidos',
                    'text' => 'Debes ingresar correctamente tu correo y contraseña.'
                ]
            );

            redirect('autenticacion');
            return;
        }

        // Recibimos los datos enviados por POST
        $correo = $this->input->post('correo');
        $contrasena = $this->input->post('contrasena');

        // Buscamos al usuario por su email
        $usuario = $this->Autenticacion_model->buscarPorCorreo($correo);

        // Verificamos que el usuario exista
        if ($usuario) {

            // Comparamos la contraseña con contrasena_hash
            if (password_verify($contrasena, $usuario->contrasena_hash)) {

                // Guardamos los datos generales del usuario en sesión
                $this->session->set_userdata([
                    'id_usuario' => $usuario->id_usuario,
                    'correo' => $usuario->email,
                    'id_rol' => $usuario->id_rol,
                    'logueado' => TRUE
                ]);

                // Redirección dependiendo del rol
                switch ($usuario->id_rol) {

                    case 1:
                        redirect('alumna');
                        break;

                    case 2:
                        redirect('profesor');
                        break;

                    case 3:
                        redirect('administrador');
                        break;

                    default:
                        $this->session->sess_destroy();
                        redirect('autenticacion');
                        break;
                }

            } else {

                $this->session->set_flashdata(
                    'alerta_login',
                    [
                        'icon' => 'error',
                        'title' => 'Inicio de sesión incorrecto',
                        'text' => 'Correo o contraseña incorrectos.'
                    ]
                );

                redirect('autenticacion');
            }

        } else {

            $this->session->set_flashdata(
                'alerta_login',
                [
                    'icon' => 'error',
                    'title' => 'Inicio de sesión incorrecto',
                    'text' => 'Correo o contraseña incorrectos.'
                ]
            );

            redirect('autenticacion');
        }
    }
}
