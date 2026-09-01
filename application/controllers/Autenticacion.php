<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Autenticacion extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Autenticacion_model');
        $this->load->model('Alumna_model');
    }
    public function index()
    {

        $this->load->view('template/autenticacion/iniciarSesion/header');
        $this->load->view('autenticacion/iniciarSesion');
        $this->load->view('template/autenticacion/iniciarSesion/footer');
    }

    public function completarPerfil()
    {
        if (!$this->session->userdata('logueado')) {
            redirect('autenticacion');
            return;
        }

        $protegerPerfilCompletado = true;

        if ($protegerPerfilCompletado) {

            $idUsuario = $this->session->userdata('id_usuario');

            $perfil = $this->Alumna_model->obtenerAlumnaPorId($idUsuario);

            if ($perfil) {
                redirect('alumna');
                return;
            }
        }

        $this->load->view('template/autenticacion/completarPerfil/header');
        $this->load->view('autenticacion/completarPerfil');
        $this->load->view('template/autenticacion/completarPerfil/footer');
    }


    public function guardarPerfil()
    {
        $idUsuario = $this->session->userdata('id_usuario');

        if (!$idUsuario) {
            redirect('autenticacion');
            return;
        }

        // Evitar crear dos perfiles para el mismo usuario
        $perfilExistente = $this->Alumna_model->obtenerAlumnaPorId($idUsuario);

        if ($perfilExistente) {
            redirect('alumna');
            return;
        }

        // Validaciones del formulario
        $this->form_validation->set_rules(
            'rut',
            'RUT',
            'required|trim|callback_validarRut'
        );

        $this->form_validation->set_rules(
            'nombre',
            'Nombre',
            'required|trim|min_length[2]|max_length[60]|regex_match[/^[A-Za-zÁÉÍÓÚáéíóúÑñÜü ]+$/]',
            [
                'required' => 'Debes ingresar tu nombre.',
                'min_length' => 'El nombre debe tener al menos 2 caracteres.',
                'max_length' => 'El nombre no puede superar los 60 caracteres.',
                'regex_match' => 'El nombre solo puede contener letras y espacios.'
            ]
        );

        $this->form_validation->set_rules(
            'apellido',
            'Apellido',
            'required|trim|min_length[2]|max_length[60]|regex_match[/^[A-Za-zÁÉÍÓÚáéíóúÑñÜü ]+$/]',
            [
                'required' => 'Debes ingresar tu apellido.',
                'min_length' => 'El apellido debe tener al menos 2 caracteres.',
                'max_length' => 'El apellido no puede superar los 60 caracteres.',
                'regex_match' => 'El apellido solo puede contener letras y espacios.'
            ]
        );

        $this->form_validation->set_rules(
            'fecha_nacimiento',
            'Fecha de nacimiento',
            'required|trim|callback_validarFechaNacimiento'
        );

        $this->form_validation->set_rules(
            'telefono',
            'Teléfono',
            'required|trim|regex_match[/^\+?[0-9]{8,15}$/]',
            [
                'required' => 'Debes ingresar tu teléfono.',
                'regex_match' => 'El teléfono debe contener entre 8 y 15 números.'
            ]
        );

        // Si alguna validación falla
        if ($this->form_validation->run() == FALSE) {

            $this->session->set_flashdata('alerta_perfil', [
                'icon' => 'warning',
                'title' => 'Revisa tus datos',
                'text' => strip_tags(validation_errors("\n", "\n"))
            ]);

            redirect('autenticacion/completarPerfil');
            return;
        }


        // Comprobar que el RUT no esté registrado
        $rut = $this->input->post('rut', TRUE);

        $rutExistente = $this->Alumna_model->buscarPorRut($rut);

        if ($rutExistente) {

            $this->session->set_flashdata('alerta_perfil', [
                'icon' => 'warning',
                'title' => 'RUT ya registrado',
                'text' => 'El RUT ingresado ya se encuentra registrado.'
            ]);

            redirect('autenticacion/completarPerfil');
            return;
        }


        // Datos ya validados
        $datos = [
            'rut' => $rut,
            'id_usuario' => $idUsuario,
            'nombre' => $this->input->post('nombre', TRUE),
            'apellido' => $this->input->post('apellido', TRUE),
            'fecha_nacimiento' => $this->input->post('fecha_nacimiento', TRUE),
            'telefono' => $this->input->post('telefono', TRUE),
        ];

        $resultado = $this->Alumna_model->guardarPerfil($datos);

        if (!$resultado) {

            $this->session->set_flashdata('alerta_perfil', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo guardar el perfil.'
            ]);

            redirect('autenticacion/completarPerfil');
            return;
        }

        redirect('alumna');
    }

    public function validarRut($rut)
    {
        $rut = preg_replace('/[^0-9kK]/', '', $rut);

        if (strlen($rut) < 2) {
            $this->form_validation->set_message(
                'validarRut',
                'El RUT ingresado no es válido.'
            );
            return false;
        }

        $dv = strtoupper(substr($rut, -1));
        $numero = substr($rut, 0, -1);

        if (!ctype_digit($numero)) {
            $this->form_validation->set_message(
                'validarRut',
                'El RUT ingresado no es válido.'
            );
            return false;
        }

        $suma = 0;
        $multiplicador = 2;

        for ($i = strlen($numero) - 1; $i >= 0; $i--) {
            $suma += ((int) $numero[$i]) * $multiplicador;

            $multiplicador++;

            if ($multiplicador > 7) {
                $multiplicador = 2;
            }
        }

        $resto = 11 - ($suma % 11);

        if ($resto == 11) {
            $dvCalculado = '0';
        } elseif ($resto == 10) {
            $dvCalculado = 'K';
        } else {
            $dvCalculado = (string) $resto;
        }

        if ($dv !== $dvCalculado) {
            $this->form_validation->set_message(
                'validarRut',
                'El RUT ingresado no es válido.'
            );
            return false;
        }

        return true;
    }

    public function validarFechaNacimiento($fecha)
    {
        $fechaObjeto = DateTime::createFromFormat('Y-m-d', $fecha);

        if (!$fechaObjeto || $fechaObjeto->format('Y-m-d') !== $fecha) {

            $this->form_validation->set_message(
                'validarFechaNacimiento',
                'La fecha de nacimiento no es válida.'
            );

            return false;
        }

        $hoy = new DateTime();

        if ($fechaObjeto > $hoy) {

            $this->form_validation->set_message(
                'validarFechaNacimiento',
                'La fecha de nacimiento no puede ser futura.'
            );

            return false;
        }

        $edad = $hoy->diff($fechaObjeto)->y;

        if ($edad < 14 || $edad > 100) {

            $this->form_validation->set_message(
                'validarFechaNacimiento',
                'La fecha de nacimiento ingresada no es válida.'
            );

            return false;
        }

        return true;
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
                        $perfil = $this->Alumna_model->obtenerAlumnaPorId($usuario->id_usuario);

                        if ($perfil) {
                            redirect('alumna');
                        } else {
                            redirect('autenticacion/completarPerfil');
                        }

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
