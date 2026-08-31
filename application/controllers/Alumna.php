<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Alumna extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // Cargamos el modelo de autenticación
        $this->load->model('Autenticacion_model');
        $this->load->model('Alumna_model');

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
        //$correo = $this->session->userdata('correo');

        //$data['perfil'] = $this->Autenticacion_model->buscarPorCorreo($correo);

        // 1. Estas dos líneas DEBEN estar antes del array $data


        $resultado_al01 = $this->Alumna_model->AL_01("44444444-4");
        $resultado_al02 = $this->Alumna_model->AL_02();

        // AL_01: una sola fila (o null si no hay próxima clase)
        $al_01 = ($resultado_al01['success'] && !empty($resultado_al01['data']))
            ? (object) $resultado_al01['data'][0]
            : null;

        // AL_02: varias filas -> castear cada una individualmente
        $al_02 = [];
        if ($resultado_al02['success']) {
            foreach ($resultado_al02['data'] as $fila) {
                $al_02[] = (object) $fila;
            }
        }

        $idUsuario = $this->session->userdata('id_usuario');

        $perfil = $this->Alumna_model->obtenerAlumnaPorId($idUsuario);
        $data = [
            'perfil' => $perfil,
            'al_01' => $al_01,
            'al_02' => $al_02,
            'al_03' => $this->Alumna_model->AL_03("44444444-4"),
        ];

        $this->load->view('template/alumna/panelAlumna/header');
        $this->load->view('alumna/panelAlumna', $data);
        $this->load->view('template/alumna/panelAlumna/footer');
    }

    public function agendaJson()
    {
        $rut_alumna = "44444444-4"; // TODO: sesión

        $lunes = new DateTime();
        $lunes->modify('monday this week');
        $sabado = (clone $lunes)->modify('+5 days');

        $bloques = $this->Alumna_model->AL_04(
            $rut_alumna,
            $lunes->format('Y-m-d'),
            $sabado->format('Y-m-d')
        );

        $nombres_dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
        $etiquetas = ['L', 'M', 'W', 'J', 'V', 'S'];
        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $dias = [];
        for ($i = 0; $i < 6; $i++) {
            $fecha_dia = (clone $lunes)->modify("+{$i} days");
            $dias[] = [
                'key' => $nombres_dias[$i],
                'etiqueta' => $etiquetas[$i],
                'numero' => (int) $fecha_dia->format('j'),
                'fecha_iso' => $fecha_dia->format('Y-m-d'),
                'bloques' => [],
            ];
        }

        foreach ($bloques as $bloque) {
            foreach ($dias as &$dia) {
                if ($dia['fecha_iso'] === $bloque->fecha) {
                    $fecha_bloque = new DateTime($bloque->fecha);
                    $dia['bloques'][] = [
                        'id_bloque' => (int) $bloque->id_bloque,
                        'hora_inicio' => substr($bloque->hora_inicio, 0, 5),
                        'especialidad' => $bloque->especialidad,
                        'profesor_nombre' => $bloque->profesor_nombre,
                        'fecha_texto' => $fecha_bloque->format('d') . ' ' . $meses[(int) $fecha_bloque->format('n')] . ' ' . $fecha_bloque->format('Y'),
                        'cupos_ocupados' => (int) $bloque->cupos_ocupados,
                        'cupos_maximos' => (int) $bloque->cupos_maximos,
                        'reservado_por_mi' => (bool) $bloque->reservado_por_mi,
                    ];
                    break;
                }
            }
        }
        unset($dia);

        $payload = [
            'texto_semana' => 'Semana del ' . $lunes->format('j') . ' al ' . $sabado->format('j') . ' ' . $meses[(int) $sabado->format('n')],
            'dias' => $dias,
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($payload));
    }
    public function cancelarReserva()
    {
        $rut_alumna = "44444444-4"; // TODO: reemplazar por sesión real
        $id_reserva = (int) $this->input->post('id_reserva');

        if (!$id_reserva) {
            $this->output->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'mensaje' => 'Falta id_reserva.']));
            return;
        }

        $resultado = $this->Alumna_model->AL_05($rut_alumna, $id_reserva);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($resultado));
    }

    public function rutina()
    {

        $this->load->view('template/alumna/rutina/header');
        $this->load->view('alumna/rutina');
        $this->load->view('template/alumna/rutina/footer');
    }

    public function modificarDatos()
    {

        // Obtenemos el id del usuario que inició sesión
        $idUsuario = $this->session->userdata('id_usuario');

        // Buscamos sus datos personales en la tabla alumna
        $data['perfil'] = $this->Alumna_model->obtenerAlumnaPorId($idUsuario);

        $this->load->view('template/alumna/modificarDatos/header');
        $this->load->view('alumna/modificarDatos', $data);
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