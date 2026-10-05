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
        $this->load->database();
        $this->load->helper('url');
        $this->load->model('Administrador_model');

        $busqueda = $this->input->get('buscar');
        $busqueda = is_string($busqueda) ? trim($busqueda) : '';

        $busquedaProfesoras = $this->input->get('buscar_profesora');
        $busquedaProfesoras = is_string($busquedaProfesoras)
            ? trim($busquedaProfesoras)
            : '';

        $tab = $this->input->get('tab') === 'profesores'
            ? 'profesores'
            : 'alumnas';

        $datos = [
            'busqueda' => $busqueda,
            'busquedaProfesoras' => $busquedaProfesoras,
            'tab' => $tab,
            'alumnas' => $this->Administrador_model
                ->listarAlumnas($busqueda),
            'profesoras' => $this->Administrador_model
                ->listarProfesoras($busquedaProfesoras)
        ];

        $this->load->view(
            'template/administrador/gestionUsers/header',
            $datos
        );
        $this->load->view('administrador/gestionUsers', $datos);
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
        $this->load->database();
        $this->load->helper(['url', 'form']);
        $this->load->library('session');
        $this->load->model('Administrador_model');

        $rut = $this->input->get('rut');

        if (!is_string($rut) || trim($rut) === '') {
            show_404();
            return;
        }

        $rut = trim($rut);
        $alumna = $this->Administrador_model->obtenerDetalleAlumna($rut);

        if (!$alumna) {
            show_404();
            return;
        }

        $token = $this->session->userdata('token_extension_plan');

        if (!is_string($token) || $token === '') {
            $token = bin2hex(random_bytes(32));
            $this->session->set_userdata('token_extension_plan', $token);
        }

        $datos = [
            'alumna' => $alumna,
            'plan' => $this->Administrador_model->obtenerUltimoPlanAlumna($rut),
            'tokenExtension' => $token,
            'puedeExtender' => true
        ];

        $this->load->view('template/administrador/detalleUser/header', $datos);
        $this->load->view('administrador/detalleUser', $datos);
        $this->load->view('template/administrador/detalleUser/footer');
    }

    public function extenderPlan()
    {
        $this->load->helper('url');
        $this->load->library('session');

        if ($this->input->method(TRUE) !== 'POST') {
            show_error('Debes enviar el formulario.', 405);
            return;
        }

        $token = $this->input->post('token_extension');
        $tokenGuardado = $this->session->userdata('token_extension_plan');

        if (
            !is_string($token)
            || !is_string($tokenGuardado)
            || $tokenGuardado === ''
            || !hash_equals($tokenGuardado, $token)
        ) {
            show_error('El formulario expiró. Vuelve a abrir el perfil.', 403);
            return;
        }

        $rut = $this->input->post('rut');

        if (!is_string($rut) || trim($rut) === '') {
            show_error('Falta identificar a la alumna.', 400);
            return;
        }

        $rut = trim($rut);

        $destino = site_url('administrador/detalleUser')
            . '?rut=' . rawurlencode($rut);

        $diasTexto = $this->input->post('dias');
        $idTexto = $this->input->post('id_plan_alumna');
        $fechaAnterior = $this->input->post('fecha_anterior');

        $dias = is_string($diasTexto)
            ? filter_var($diasTexto, FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1, 'max_range' => 365]
            ])
            : false;

        $idPlan = is_string($idTexto)
            ? filter_var($idTexto, FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1]
            ])
            : false;

        if (
            $dias === false
            || $idPlan === false
            || !is_string($fechaAnterior)
        ) {
            $this->session->set_flashdata(
                'extension_error',
                'Introduce un número entero de días entre 1 y 365.'
            );

            redirect($destino);
            return;
        }

        $this->load->database();
        $this->load->model('Administrador_model');

        $plan = $this->Administrador_model->obtenerUltimoPlanAlumna($rut);

        if (
            !$plan
            || (int) $plan->id_plan_alumna !== $idPlan
            || $plan->fecha_termino !== $fechaAnterior
        ) {
            $this->session->set_flashdata(
                'extension_error',
                'El plan cambió o ya fue extendido. Revisa los datos actualizados.'
            );

            redirect($destino);
            return;
        }

        $guardado = $this->Administrador_model->extenderPlan(
            $idPlan,
            $rut,
            $dias,
            $fechaAnterior
        );

        if ($guardado) {
            $this->session->set_userdata(
                'token_extension_plan',
                bin2hex(random_bytes(32))
            );

            $this->session->set_flashdata(
                'extension_ok',
                'Plan extendido en ' . $dias
                . ($dias === 1 ? ' día.' : ' días.')
            );
        } else {
            $this->session->set_flashdata(
                'extension_error',
                'No se pudo extender el plan. Recarga el perfil e intenta nuevamente.'
            );
        }

        redirect($destino);
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

