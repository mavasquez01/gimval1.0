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
        $protegerRutas = false;

        if ($protegerRutas) {
            if (!$this->session->userdata('logueado')) {
                redirect('autenticacion');
            }
        }
    }


    public function index()
    {
        // 1. Obtener la sesión y cargar el perfil de la alumna primero
        $idUsuario = $this->session->userdata('id_usuario');
        $perfil = $this->Alumna_model->obtenerAlumnaPorId($idUsuario);

        // Extraer RUT solo si el perfil existe
        $rut = $perfil->rut ?? null;

        // 2. Consultas que dependen del perfil o RUT del usuario
        $plan = $rut ? $this->Alumna_model->obtenerPlanActivo($rut) : null;
        $resultado_al01 = $rut ? $this->Alumna_model->AL_01($rut) : ['success' => false];
        $resultado_al02 = $this->Alumna_model->AL_02();
        $al_03 = $rut ? $this->Alumna_model->AL_03($rut) : null;

        // 3. Procesamiento y formateo de datos
        $al_01 = ($resultado_al01['success'] && !empty($resultado_al01['data']))
            ? (object) $resultado_al01['data'][0]
            : null;

        $al_02 = [];
        if (!empty($resultado_al02['success']) && !empty($resultado_al02['data']) && is_array($resultado_al02['data'])) {
            foreach ($resultado_al02['data'] as $fila) {
                $al_02[] = (object) $fila;
            }
        }
        // 4. Estructura de datos para la vista
        $data = [
            'perfil' => $perfil,
            'al_01' => $al_01,
            'al_02' => $al_02,
            'al_03' => $al_03,
            'plan' => $plan,
        ];

        // 5. Carga de vistas
        $this->load->view('template/alumna/panelAlumna/header');
        $this->load->view('alumna/panelAlumna', $data);
        $this->load->view('template/alumna/panelAlumna/footer');
    }

    public function agendaJson()
    {
        $rut_alumna = $this->_obtenerRutAlumna(); // TODO: sesión

        // Se especifica la zona horaria local
        $tz = new DateTimeZone('America/Santiago');

        $lunes = new DateTime('now', $tz);
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

        $ahora = new DateTime('now', $tz);

        foreach ($bloques as $bloque) {
            foreach ($dias as &$dia) {
                if ($dia['fecha_iso'] === $bloque->fecha) {
                    $fecha_bloque = new DateTime($bloque->fecha, $tz);

                    // Comparación usando zona horaria explícita
                    $fecha_hora_bloque = new DateTime($bloque->fecha . ' ' . $bloque->hora_inicio, $tz);

                    $es_pasado = ($fecha_hora_bloque <= $ahora);

                    // Regla de cancelación: hasta 1 hora antes
                    $limite_cancelacion = (clone $fecha_hora_bloque)->modify('-1 hour');
                    $puede_cancelar = ($ahora < $limite_cancelacion);

                    $dia['bloques'][] = [
                        'id_bloque' => (int) $bloque->id_bloque,
                        'id_reserva' => $bloque->id_reserva_propia ? (int) $bloque->id_reserva_propia : null,
                        'hora_inicio' => substr($bloque->hora_inicio, 0, 5),
                        'especialidad' => $bloque->especialidad,
                        'profesor_nombre' => $bloque->profesor_nombre,
                        'fecha_texto' => $fecha_bloque->format('d') . ' ' . $meses[(int) $fecha_bloque->format('n')] . ' ' . $fecha_bloque->format('Y'),
                        'fecha_iso' => $bloque->fecha,
                        'cupos_ocupados' => (int) $bloque->cupos_ocupados,
                        'cupos_maximos' => (int) $bloque->cupos_maximos,
                        'reservado_por_mi' => (bool) $bloque->reservado_por_mi,
                        'pasado' => $es_pasado,
                        'puede_cancelar' => $puede_cancelar,
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

    /**
     * Método auxiliar privado para obtener el RUT de la alumna logueada.
     */
    private function _obtenerRutAlumna()
    {
        $idUsuario = $this->session->userdata('id_usuario');
        if (!$idUsuario) {
            return null;
        }

        $perfil = $this->Alumna_model->obtenerAlumnaPorId($idUsuario);
        return $perfil ? $perfil->rut : null;
    }

    public function cancelarReserva()
    {
        $rut_alumna = $this->_obtenerRutAlumna();

        if (!$rut_alumna) {
            $this->output->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'mensaje' => 'Sesión no válida o perfil no encontrado.']));
            return;
        }

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

    public function crearReserva()
    {
        $rut_alumna = $this->_obtenerRutAlumna();

        if (!$rut_alumna) {
            $this->output->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'mensaje' => 'Sesión no válida o perfil no encontrado.']));
            return;
        }

        $id_bloque = (int) $this->input->post('id_bloque');

        if (!$id_bloque) {
            $this->output->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'mensaje' => 'Falta id_bloque.']));
            return;
        }

        $resultado = $this->Alumna_model->AL_06($rut_alumna, $id_bloque);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($resultado));
    }

    public function obtener_mis_clases()
    {
        $rut_alumna = $this->_obtenerRutAlumna();

        if (!$rut_alumna) {
            $this->output->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'mensaje' => 'Sesión no válida.']));
            return;
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(
                $this->Alumna_model->AL_07($rut_alumna)
            );
    }

    public function rutina()
    {
        $this->load->view('template/alumna/rutina/header');
        $this->load->view('alumna/rutina');
        $this->load->view('template/alumna/rutina/footer');
    }

    public function obtener_rutina()
    {
        $id_rutina = $this->input->get('id_rutina');

        if (!$id_rutina || !is_numeric($id_rutina)) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'id_rutina inválido o no enviado.'
                ]));
        }

        $rut_alumna = $this->_obtenerRutAlumna();

        if (!$rut_alumna) {
            return $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Sesión no válida.'
                ]));
        }

        $this->load->model('Rutina_model');

        $rutina = $this->Rutina_model->obtener_rutina($id_rutina);

        if (!$rutina || !$rutina->vigente) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Rutina no encontrada o no vigente.'
                ]));
        }

        $ejercicios = $this->Rutina_model->obtener_ejercicios($id_rutina, $rut_alumna);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'rutina' => $rutina,
                'ejercicios' => $ejercicios
            ]));
    }

    public function guardar_progreso()
    {
        $rut_alumna = $this->_obtenerRutAlumna();

        if (!$rut_alumna) {
            return $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Sesión no válida.']));
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $ejercicios = $input['ejercicios'] ?? [];

        if (empty($ejercicios)) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'No se recibieron ejercicios.']));
        }

        $this->load->model('Rutina_model');
        $hoy = date('Y-m-d');

        foreach ($ejercicios as $ej) {
            if (!isset($ej['id_ejercicio'], $ej['peso']))
                continue;
            $this->Rutina_model->guardar_peso($rut_alumna, $ej['id_ejercicio'], $ej['peso'], $hoy);
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['success' => true]));
    }

    public function modificarDatos()
    {
        $idUsuario = $this->session->userdata('id_usuario');

        $data['perfil'] = $this->Alumna_model->obtenerAlumnaPorId($idUsuario);

        $this->load->view('template/alumna/modificarDatos/header');
        $this->load->view('alumna/modificarDatos', $data);
        $this->load->view('template/alumna/modificarDatos/footer');
    }

    public function actualizarDatos()
    {
        $idUsuario = $this->session->userdata('id_usuario');

        if (!$idUsuario) {
            redirect('autenticacion');
            return;
        }

        // Validar nombre
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

        // Validar apellido
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

        // Validar correo
        $this->form_validation->set_rules(
            'correo',
            'Correo electrónico',
            'required|trim|valid_email',
            [
                'required' => 'Debes ingresar tu correo electrónico.',
                'valid_email' => 'Debes ingresar un correo electrónico válido.'
            ]
        );

        // Validar fecha
        $this->form_validation->set_rules(
            'fecha_nacimiento',
            'Fecha de nacimiento',
            'required|trim|callback_validarFechaNacimiento'
        );

        // Validar teléfono
        $this->form_validation->set_rules(
            'telefono',
            'Teléfono',
            'required|trim|regex_match[/^\+?[0-9]{8,15}$/]',
            [
                'required' => 'Debes ingresar tu teléfono.',
                'regex_match' => 'El teléfono debe contener entre 8 y 15 números.'
            ]
        );

        // Ejecutar todas las validaciones
        if ($this->form_validation->run() == FALSE) {

            $this->session->set_flashdata('alerta_datos', [
                'icon' => 'warning',
                'title' => 'Revisa tus datos',
                'text' => strip_tags(validation_errors("\n", "\n"))
            ]);

            redirect('alumna/modificarDatos');
            return;
        }

        // Obtener correo
        $correo = $this->input->post('correo', TRUE);

        // Revisar que el correo no pertenezca a otro usuario
        $correoExistente = $this->Autenticacion_model
            ->buscarPorCorreoExceptoUsuario($correo, $idUsuario);

        if ($correoExistente) {

            $this->session->set_flashdata('alerta_datos', [
                'icon' => 'warning',
                'title' => 'Correo ya registrado',
                'text' => 'El correo ingresado ya pertenece a otro usuario.'
            ]);

            redirect('alumna/modificarDatos');
            return;
        }

        // Datos de la tabla alumna
        $datos = [
            'nombre' => $this->input->post('nombre', TRUE),
            'apellido' => $this->input->post('apellido', TRUE),
            'telefono' => $this->input->post('telefono', TRUE),
            'fecha_nacimiento' => $this->input->post('fecha_nacimiento', TRUE)
        ];

        // Actualizar datos personales
        $resultadoDatos = $this->Alumna_model->actualizarDatos(
            $idUsuario,
            $datos
        );

        // Actualizar correo en usuario
        $resultadoCorreo = $this->Autenticacion_model->actualizarCorreo(
            $idUsuario,
            $correo
        );

        if (!$resultadoDatos || !$resultadoCorreo) {

            $this->session->set_flashdata('alerta_datos', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudieron actualizar tus datos.'
            ]);

            redirect('alumna/modificarDatos');
            return;
        }

        // Actualizamos también el correo guardado en sesión
        $this->session->set_userdata('correo', $correo);

        $this->session->set_flashdata('alerta_datos', [
            'icon' => 'success',
            'title' => 'Datos actualizados',
            'text' => 'Tus datos fueron actualizados correctamente.'
        ]);

        redirect('alumna/modificarDatos');
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

    public function convenios()
    {
        $data['convenios'] = $this->Alumna_model->AL_08();

        $this->load->view('template/alumna/convenios/header');
        $this->load->view('alumna/convenios', $data);
        $this->load->view('template/alumna/convenios/footer');
    }

    public function cambiarContrasena()
    {
        $idUsuario = $this->session->userdata('id_usuario');

        if (!$idUsuario) {
            redirect('autenticacion');
            return;
        }

        $this->load->view('template/alumna/cambiarContrasena/header');
        $this->load->view('alumna/cambiarContrasena');
        $this->load->view('template/alumna/cambiarContrasena/footer');
    }

    public function guardarContrasena()
    {
        $idUsuario = $this->session->userdata('id_usuario');

        if (!$idUsuario) {
            redirect('autenticacion');
            return;
        }

        // Validaciones
        $this->form_validation->set_rules(
            'contrasena_actual',
            'Contraseña actual',
            'required',
            [
                'required' => 'Debes ingresar tu contraseña actual.'
            ]
        );

        $this->form_validation->set_rules(
            'nueva_contrasena',
            'Nueva contraseña',
            'required|min_length[8]',
            [
                'required' => 'Debes ingresar una nueva contraseña.',
                'min_length' => 'La nueva contraseña debe tener al menos 8 caracteres.'
            ]
        );

        $this->form_validation->set_rules(
            'confirmar_contrasena',
            'Confirmar contraseña',
            'required|matches[nueva_contrasena]',
            [
                'required' => 'Debes confirmar la nueva contraseña.',
                'matches' => 'Las contraseñas no coinciden.'
            ]
        );

        // Si las validaciones fallan
        if ($this->form_validation->run() == FALSE) {

            $this->session->set_flashdata('alerta_contrasena', [
                'icon' => 'warning',
                'title' => 'Revisa tus datos',
                'text' => strip_tags(validation_errors("\n", "\n"))
            ]);

            redirect('alumna/cambiarContrasena');
            return;
        }

        // Obtenemos los datos enviados
        $contrasenaActual = $this->input->post('contrasena_actual');
        $nuevaContrasena = $this->input->post('nueva_contrasena');

        // Buscamos el usuario
        $usuario = $this->Autenticacion_model->buscarPorId($idUsuario);

        if (!$usuario) {
            redirect('autenticacion');
            return;
        }

        // Comprobamos que la contraseña actual sea correcta
        if (!password_verify($contrasenaActual, $usuario->contrasena_hash)) {

            $this->session->set_flashdata('alerta_contrasena', [
                'icon' => 'error',
                'title' => 'Contraseña incorrecta',
                'text' => 'La contraseña actual no es correcta.'
            ]);

            redirect('alumna/cambiarContrasena');
            return;
        }

        // Evitamos usar la misma contraseña actual
        if (password_verify($nuevaContrasena, $usuario->contrasena_hash)) {

            $this->session->set_flashdata('alerta_contrasena', [
                'icon' => 'warning',
                'title' => 'Contraseña no válida',
                'text' => 'La nueva contraseña no puede ser igual a la actual.'
            ]);

            redirect('alumna/cambiarContrasena');
            return;
        }

        // Generamos el nuevo hash
        $hash = password_hash(
            $nuevaContrasena,
            PASSWORD_DEFAULT
        );

        // Actualizamos la contraseña
        $resultado = $this->Alumna_model->cambiarContrasena(
            $idUsuario,
            $hash
        );

        if (!$resultado) {

            $this->session->set_flashdata('alerta_contrasena', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo actualizar la contraseña.'
            ]);

            redirect('alumna/cambiarContrasena');
            return;
        }

        // Todo salió correctamente
        $this->session->set_flashdata('alerta_contrasena', [
            'icon' => 'success',
            'title' => 'Contraseña actualizada',
            'text' => 'Tu contraseña fue actualizada correctamente.'
        ]);

        redirect('alumna/cambiarContrasena');
    }

    public function cerrarSesion()
    {
        $this->session->sess_destroy();

        redirect('autenticacion');
    }

}