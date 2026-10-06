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
    const ROLES_USUARIO = [
        'alumna' => ['nombre_rol' => 'alumna', 'tabla' => 'alumna'],
        'profesor' => ['nombre_rol' => 'profesor', 'tabla' => 'profesor'],
        'admin' => ['nombre_rol' => 'administrador', 'tabla' => 'administrador'],
    ];
    public function index()
    {

        $this->load->view('template/administrador/panelAdmin/header');
        $this->load->view('administrador/panelAdmin');
        $this->load->view('template/administrador/panelAdmin/footer');
    }

    public function horarios()
    {
        $this->load->helper(['url', 'form']);
        $this->load->library('session');

        $datos = ['tokenBloque' => $this->tokenBloque()];

        $this->load->view('template/administrador/horarios/header', $datos);
        $this->load->view('administrador/horarios', $datos);
        $this->load->view('template/administrador/horarios/footer');
    }

    const POR_PAGINA = 10;
    const DIAS_SEMANA = 6; // lunes a sábado; usa 7 para incluir domingo (y agrega 'D' en LETRAS, ya está)

    public function gestionUsers()
    {
        $this->load->helper('url');

        $tab = $this->input->get('tab') === 'profesores' ? 'profesores' : 'alumnas';
        $datos = ['tab' => $tab];

        $this->load->view('template/administrador/gestionUsers/header', $datos);
        $this->load->view('administrador/gestionUsers', $datos);
        $this->load->view('template/administrador/gestionUsers/footer');
    }

    public function apiUsuarios($tipo = '')
    {
        $this->load->database();
        $this->load->model('Administrador_model');

        // Lista blanca: agregar un tipo nuevo = agregar una línea aquí
        $tipos = [
            'alumnas' => ['listar' => 'listarAlumnas', 'contar' => 'contarAlumnas'],
            'profesoras' => ['listar' => 'listarProfesoras', 'contar' => 'contarProfesoras'],
        ];

        if (!isset($tipos[$tipo])) {
            return $this->responderJson(['error' => 'Tipo no válido'], 404);
        }

        $busqueda = $this->input->get('buscar');
        $busqueda = is_string($busqueda) ? trim($busqueda) : '';
        $pagina = (int) $this->input->get('pagina');

        $total = $this->Administrador_model->{$tipos[$tipo]['contar']}($busqueda);
        $paginas = max(1, (int) ceil($total / self::POR_PAGINA));
        $pagina = min(max(1, $pagina), $paginas);

        $items = $this->Administrador_model->{$tipos[$tipo]['listar']}(
            $busqueda,
            self::POR_PAGINA,
            ($pagina - 1) * self::POR_PAGINA
        );

        if ($tipo === 'alumnas') {
            $items = $this->agregarDiasPlan($items);
        }

        return $this->responderJson([
            'items' => $items,
            'pagina' => $pagina,
            'paginas' => $paginas,
            'total' => $total,
        ]);
    }

    // La lógica de fechas se queda en el servidor (zona Santiago)
    private function agregarDiasPlan(array $items)
    {
        $zona = new DateTimeZone('America/Santiago');
        $hoy = new DateTimeImmutable('today', $zona);

        foreach ($items as $it) {
            $it->dias_plan = null;

            if (!empty($it->fecha_termino_plan)) {
                $txt = substr($it->fecha_termino_plan, 0, 10);
                $v = DateTimeImmutable::createFromFormat('!Y-m-d', $txt, $zona);

                if ($v && $v->format('Y-m-d') === $txt) {
                    $it->dias_plan = (int) $hoy->diff($v)->format('%r%a');
                }
            }
            unset($it->fecha_termino_plan);
        }
        return $items;
    }

    private function responderJson($data, $status = 200)
    {
        $this->output
            ->set_status_header($status)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    public function editarBloque()
    {
        $this->load->database();
        $this->load->helper(['url', 'form']);      // antes solo 'url'
        $this->load->library('session');           // nuevo
        $this->load->model('Administrador_model');

        $id = filter_var($this->input->get('id'), FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1]
        ]);

        if ($id === false) {
            show_404();
            return;
        }

        $bloque = $this->Administrador_model->obtenerBloque($id);

        if (!$bloque) {
            show_404();
            return;
        }

        $datos = [
            'bloque' => $bloque,
            'profesores' => $this->Administrador_model
                ->listarProfesoresParaBloque($bloque->rut_profesor),
            'tokenBloque' => $this->tokenBloque(),
        ];

        $this->load->view('template/administrador/editarBloque/header', $datos);
        $this->load->view('administrador/editarBloque', $datos);
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
            'puedeExtender' => true,
            'planes' => $this->Administrador_model->listarPlanes(),
            'estadosPlan' => $this->Administrador_model->listarEstadosPlan(),
            'duracionRenovacion' => Administrador_model::DURACION_DIAS_PLAN,
            'tokenEstado' => $this->tokenEstado()

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

    public function detalleProfesor()
    {
        $this->load->database();
        $this->load->helper(['url', 'form']);      // antes solo 'url'
        $this->load->library('session');           // nuevo
        $this->load->model('Administrador_model');

        $rut = $this->input->get('rut');

        if (!is_string($rut) || trim($rut) === '') {
            show_404();
            return;
        }

        $profesor = $this->Administrador_model
            ->obtenerDetalleProfesor(trim($rut));

        if (!$profesor) {
            show_404();
            return;
        }

        $datos = [
            'profesor' => $profesor,
            'tokenEstado' => $this->tokenEstado(),
        ];

        $this->load->view('template/administrador/detalleUser/header', $datos);
        $this->load->view('administrador/detalleUser', $datos);
        $this->load->view('template/administrador/detalleUser/footer');
    }

    public function crearUser()
    {
        $this->load->helper(['url', 'form']);
        $this->load->library('session');

        $rol = $this->input->get('rol');
        $datos = [
            'tokenUsuario' => $this->tokenUsuario(),
            'rolInicial' => in_array($rol, ['alumna', 'profesor', 'admin'], true) ? $rol : '',
            'old' => $this->session->flashdata('crear_old'),
        ];

        $this->load->view('template/administrador/crearUser/header', $datos);
        $this->load->view('administrador/crearUser', $datos);
        $this->load->view('template/administrador/crearUser/footer');
    }

    public function crearBloque()
    {
        $this->load->database();
        $this->load->helper(['url', 'form']);
        $this->load->library('session');
        $this->load->model('Administrador_model');

        // ?fecha=YYYY-MM-DD permite abrir el formulario con un día ya elegido
        $fecha = $this->fechaIso($this->input->get('fecha'));
        $hoy = date('Y-m-d');

        $datos = [
            'profesores' => $this->Administrador_model->listarProfesoresParaBloque(''),
            'tokenBloque' => $this->tokenBloque(),
            'fechaInicial' => ($fecha !== false && $fecha >= $hoy) ? $fecha : $hoy,
            'old' => $this->session->flashdata('crear_bloque_old'),
        ];

        $this->load->view('template/administrador/crearBloque/header', $datos);
        $this->load->view('administrador/crearBloque', $datos);
        $this->load->view('template/administrador/crearBloque/footer');
    }

    public function guardarNuevoBloque()
    {
        $this->load->helper('url');
        $this->load->library('session');

        if ($this->input->method(TRUE) !== 'POST') {
            show_error('Debes enviar el formulario.', 405);
            return;
        }

        if (!$this->tokenBloqueValido()) {
            show_error('El formulario expiró. Vuelve a abrir la pantalla.', 403);
            return;
        }

        $volver = site_url('administrador/crearBloque');

        $inicio = $this->input->post('hora_inicio');
        $termino = $this->input->post('hora_termino');
        $fecha = $this->fechaIso($this->input->post('fecha'));
        $rutProfesor = $this->input->post('rut_profesor');
        $cuposTexto = $this->input->post('cupos_maximos');

        $old = [
            'hora_inicio' => is_string($inicio) ? $inicio : '',
            'hora_termino' => is_string($termino) ? $termino : '',
            'fecha' => is_string($this->input->post('fecha')) ? $this->input->post('fecha') : '',
            'rut_profesor' => is_string($rutProfesor) ? $rutProfesor : '',
            'cupos_maximos' => is_string($cuposTexto) ? $cuposTexto : '',
        ];

        $error = function ($msg) use ($volver, $old) {
            $this->session->set_flashdata('bloque_error', $msg);
            $this->session->set_flashdata('crear_bloque_old', $old);
            redirect($volver);
        };

        $cupos = is_string($cuposTexto)
            ? filter_var($cuposTexto, FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1, 'max_range' => 999]
            ])
            : false;

        $horaValida = function ($h) {
            return is_string($h) && preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $h) === 1;
        };

        if (
            !$horaValida($inicio) || !$horaValida($termino)
            || $fecha === false || $cupos === false
            || !is_string($rutProfesor) || trim($rutProfesor) === ''
        ) {
            return $error('Revisa los datos del bloque: hay campos inválidos.');
        }

        if ($termino <= $inicio) {
            return $error('La hora de término debe ser posterior a la de inicio.');
        }

        if ($fecha < date('Y-m-d')) {
            return $error('La fecha no puede ser anterior a hoy.');
        }

        $rutProfesor = trim($rutProfesor);

        $this->load->database();
        $this->load->model('Administrador_model');

        // '' como "profesor actual": solo se aceptan profesores activos
        if (!$this->Administrador_model->profesorPermitidoEnBloque($rutProfesor, '')) {
            return $error('El profesor seleccionado no existe o está inactivo.');
        }

        if ($this->Administrador_model->hayChoqueProfesor(0, $rutProfesor, $fecha, $inicio . ':00', $termino . ':00')) {
            return $error('Ese profesor ya tiene otra clase en ese horario.');
        }

        $idNuevo = $this->Administrador_model->crearBloque([
            'rut_profesor' => $rutProfesor,
            'fecha' => $fecha,
            'hora_inicio' => $inicio . ':00',
            'hora_termino' => $termino . ':00',
            'cupos_maximos' => $cupos,
        ]);

        if ($idNuevo === false) {
            return $error('No se pudo crear el bloque. Intenta nuevamente.');
        }

        $this->rotarTokenBloque();

        // Vuelve a Horarios en la semana del bloque creado
        redirect(site_url('administrador/horarios') . '?semana=' . rawurlencode($fecha));
    }

    private function tokenPlanValido()
    {
        $token = $this->input->post('token_extension');
        $guardado = $this->session->userdata('token_extension_plan');

        return is_string($token)
            && is_string($guardado)
            && $guardado !== ''
            && hash_equals($guardado, $token);
    }

    private function rotarTokenPlan()
    {
        $this->session->set_userdata('token_extension_plan', bin2hex(random_bytes(32)));
    }

    private function fechaIso($valor)
    {
        if (!is_string($valor))
            return false;

        $f = DateTimeImmutable::createFromFormat('!Y-m-d', $valor);
        return ($f && $f->format('Y-m-d') === $valor) ? $valor : false;
    }

    public function renovarPlan()
    {
        $this->load->helper('url');
        $this->load->library('session');

        if ($this->input->method(TRUE) !== 'POST') {
            show_error('Debes enviar el formulario.', 405);
            return;
        }

        if (!$this->tokenPlanValido()) {
            show_error('El formulario expiró. Vuelve a abrir el perfil.', 403);
            return;
        }

        $rut = $this->input->post('rut');
        if (!is_string($rut) || trim($rut) === '') {
            show_error('Falta identificar a la alumna.', 400);
            return;
        }
        $rut = trim($rut);

        $destino = site_url('administrador/detalleUser') . '?rut=' . rawurlencode($rut);

        $idTexto = $this->input->post('id_plan_alumna');
        $fechaAnterior = $this->input->post('fecha_anterior');
        $idPlan = is_string($idTexto)
            ? filter_var($idTexto, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])
            : false;

        if ($idPlan === false || !is_string($fechaAnterior)) {
            $this->session->set_flashdata('extension_error', 'Datos del plan inválidos.');
            redirect($destino);
            return;
        }

        $this->load->database();
        $this->load->model('Administrador_model');

        $plan = $this->Administrador_model->obtenerUltimoPlanAlumna($rut);

        if (!$plan || (int) $plan->id_plan_alumna !== $idPlan || $plan->fecha_termino !== $fechaAnterior) {
            $this->session->set_flashdata(
                'extension_error',
                'El plan cambió o ya fue renovado. Revisa los datos actualizados.'
            );
            redirect($destino);
            return;
        }

        if ($this->Administrador_model->renovarPlan($rut, $idPlan, $fechaAnterior)) {
            $this->rotarTokenPlan();
            $this->session->set_flashdata('extension_ok', 'Plan renovado correctamente.');
        } else {
            $this->session->set_flashdata(
                'extension_error',
                'No se pudo renovar el plan. Recarga el perfil e intenta nuevamente.'
            );
        }

        redirect($destino);
    }

    public function modificarPlan()
    {
        $this->load->helper('url');
        $this->load->library('session');

        if ($this->input->method(TRUE) !== 'POST') {
            show_error('Debes enviar el formulario.', 405);
            return;
        }

        if (!$this->tokenPlanValido()) {
            show_error('El formulario expiró. Vuelve a abrir el perfil.', 403);
            return;
        }

        $rut = $this->input->post('rut');
        if (!is_string($rut) || trim($rut) === '') {
            show_error('Falta identificar a la alumna.', 400);
            return;
        }
        $rut = trim($rut);

        $destino = site_url('administrador/detalleUser') . '?rut=' . rawurlencode($rut);
        $error = function ($msg) use ($destino) {
            $this->session->set_flashdata('extension_error', $msg);
            redirect($destino);
        };

        $int = function ($campo, $min, $max = PHP_INT_MAX) {
            $v = $this->input->post($campo);
            return is_string($v)
                ? filter_var($v, FILTER_VALIDATE_INT, ['options' => ['min_range' => $min, 'max_range' => $max]])
                : false;
        };

        $idPlanAlumna = $int('id_plan_alumna', 1);
        $idPlanNuevo = $int('id_plan', 1);
        $idEstado = $int('id_estado_plan', 1);
        $clases = $int('clases_restantes', 0, 999);
        $inicio = $this->fechaIso($this->input->post('fecha_inicio'));
        $termino = $this->fechaIso($this->input->post('fecha_termino'));
        $fechaAnterior = $this->input->post('fecha_anterior');

        if (
            $idPlanAlumna === false || $idPlanNuevo === false || $idEstado === false
            || $clases === false || $inicio === false || $termino === false
            || !is_string($fechaAnterior)
        ) {
            return $error('Revisa los datos del plan: hay campos inválidos.');
        }

        if ($termino < $inicio) {
            return $error('La fecha de término no puede ser anterior al inicio.');
        }

        $this->load->database();
        $this->load->model('Administrador_model');

        $plan = $this->Administrador_model->obtenerUltimoPlanAlumna($rut);

        if (!$plan || (int) $plan->id_plan_alumna !== $idPlanAlumna || $plan->fecha_termino !== $fechaAnterior) {
            return $error('El plan cambió. Revisa los datos actualizados.');
        }

        if (
            !$this->Administrador_model->obtenerPlan($idPlanNuevo)
            || !$this->Administrador_model->existeEstadoPlan($idEstado)
        ) {
            return $error('El plan o el estado seleccionado no existe.');
        }

        $sinCambios = (int) $plan->id_plan === $idPlanNuevo
            && $plan->fecha_inicio === $inicio
            && $plan->fecha_termino === $termino
            && (int) $plan->clases_restantes === $clases
            && (int) $plan->id_estado_plan === $idEstado;

        if ($sinCambios) {
            return $error('No hay cambios que guardar.');
        }

        $guardado = $this->Administrador_model->modificarPlan($idPlanAlumna, $rut, $fechaAnterior, [
            'id_plan' => $idPlanNuevo,
            'fecha_inicio' => $inicio,
            'fecha_termino' => $termino,
            'clases_restantes' => $clases,
            'id_estado_plan' => $idEstado,
        ]);

        if ($guardado) {
            $this->rotarTokenPlan();
            $this->session->set_flashdata('extension_ok', 'Plan actualizado correctamente.');
            redirect($destino);
            return;
        }

        return $error('No se pudo actualizar el plan. Recarga el perfil e intenta nuevamente.');
    }
    private function tokenEstado()
    {
        $token = $this->session->userdata('token_estado_usuario');

        if (!is_string($token) || $token === '') {
            $token = bin2hex(random_bytes(32));
            $this->session->set_userdata('token_estado_usuario', $token);
        }

        return $token;
    }

    public function cambiarEstadoUsuario()
    {
        $this->load->helper('url');
        $this->load->library('session');

        if ($this->input->method(TRUE) !== 'POST') {
            show_error('Debes enviar el formulario.', 405);
            return;
        }

        $token = $this->input->post('token_estado');
        $guardado = $this->session->userdata('token_estado_usuario');

        if (
            !is_string($token)
            || !is_string($guardado)
            || $guardado === ''
            || !hash_equals($guardado, $token)
        ) {
            show_error('El formulario expiró. Vuelve a abrir el perfil.', 403);
            return;
        }

        $tipo = $this->input->post('tipo');
        $rut = $this->input->post('rut');
        $activoTexto = $this->input->post('activo');

        if (
            !in_array($tipo, ['alumna', 'profesor'], true)
            || !is_string($rut) || trim($rut) === ''
            || !in_array($activoTexto, ['0', '1'], true)
        ) {
            show_error('Solicitud inválida.', 400);
            return;
        }

        $rut = trim($rut);
        $activo = (int) $activoTexto;

        $destino = site_url(
            $tipo === 'alumna'
            ? 'administrador/detalleUser'
            : 'administrador/detalleProfesor'
        ) . '?rut=' . rawurlencode($rut);

        $this->load->database();
        $this->load->model('Administrador_model');

        $actual = $this->Administrador_model->obtenerActivo($tipo, $rut);

        if ($actual === null) {
            show_404();
            return;
        }

        if ($actual === $activo) {
            $this->session->set_flashdata(
                'estado_error',
                'El estado ya estaba actualizado. Revisa los datos.'
            );
            redirect($destino);
            return;
        }

        if ($this->Administrador_model->cambiarEstadoUsuario($tipo, $rut, $activo)) {
            $this->session->set_userdata('token_estado_usuario', bin2hex(random_bytes(32)));
            $this->session->set_flashdata(
                'estado_ok',
                $activo === 1 ? 'Reactivado correctamente.' : 'Desactivado correctamente.'
            );
        } else {
            $this->session->set_flashdata(
                'estado_error',
                'No se pudo cambiar el estado. Intenta nuevamente.'
            );
        }

        redirect($destino);
    }
    public function resumenPanel()
    {
        $this->load->database();
        $this->load->model('Administrador_model');

        $zona = new DateTimeZone('America/Santiago');
        $ahora = new DateTimeImmutable('now', $zona);
        $hoy = $ahora->format('Y-m-d');

        return $this->responderJson([
            'success' => true,
            'hoy' => $hoy,
            'alumnas_activas' => $this->Administrador_model->contarAlumnasActivas(),
            'profesores_activos' => $this->Administrador_model->contarProfesoresActivos(),
            'clases_hoy' => $this->Administrador_model->contarClasesHoy($hoy),
            'alertas_planes' => $this->Administrador_model->contarAlertasPlanes(
                $ahora->modify('+7 days')->format('Y-m-d')
            ),
            'proximas_clases' => $this->Administrador_model->listarProximasClases(
                $hoy,
                $ahora->format('H:i:s'),
                5
            ),
        ]);
    }

    public function apiHorarios()
    {
        $this->load->database();
        $this->load->model('Administrador_model');

        $zona = new DateTimeZone('America/Santiago');
        $hoy = new DateTimeImmutable('today', $zona);

        // ?semana=YYYY-MM-DD: cualquier día de la semana que se quiere ver
        $base = $hoy;
        $texto = $this->input->get('semana');

        if (is_string($texto)) {
            $f = DateTimeImmutable::createFromFormat('!Y-m-d', $texto, $zona);
            if ($f && $f->format('Y-m-d') === $texto) {
                $base = $f;
            }
        }

        $lunes = $base->modify('monday this week');

        $dias = [];
        for ($i = 0; $i < self::DIAS_SEMANA; $i++) {
            $fecha = $lunes->modify('+' . $i . ' days')->format('Y-m-d');
            $dias[$fecha] = ['fecha' => $fecha, 'bloques' => []];
        }

        $fechas = array_keys($dias);
        $desde = $fechas[0];
        $hasta = end($fechas);

        foreach ($this->Administrador_model->listarBloquesRango($desde, $hasta) as $b) {
            if (isset($dias[$b->fecha])) {
                $dias[$b->fecha]['bloques'][] = $b;
            }
        }

        return $this->responderJson([
            'success' => true,
            'hoy' => $hoy->format('Y-m-d'),
            'semana' => [
                'inicio' => $desde,
                'fin' => $hasta,
                'anterior' => $lunes->modify('-7 days')->format('Y-m-d'),
                'siguiente' => $lunes->modify('+7 days')->format('Y-m-d'),
            ],
            'dias' => array_values($dias),
        ]);
    }

    private function tokenBloque()
    {
        $token = $this->session->userdata('token_bloque');

        if (!is_string($token) || $token === '') {
            $token = bin2hex(random_bytes(32));
            $this->session->set_userdata('token_bloque', $token);
        }

        return $token;
    }

    private function tokenBloqueValido()
    {
        $token = $this->input->post('token_bloque');
        $guardado = $this->session->userdata('token_bloque');

        return is_string($token)
            && is_string($guardado)
            && $guardado !== ''
            && hash_equals($guardado, $token);
    }

    private function rotarTokenBloque()
    {
        $this->session->set_userdata('token_bloque', bin2hex(random_bytes(32)));
    }

    public function guardarBloque()
    {
        $this->load->helper('url');
        $this->load->library('session');

        if ($this->input->method(TRUE) !== 'POST') {
            show_error('Debes enviar el formulario.', 405);
            return;
        }

        if (!$this->tokenBloqueValido()) {
            show_error('El formulario expiró. Vuelve a abrir el bloque.', 403);
            return;
        }

        $id = filter_var($this->input->post('id_bloque'), FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1]
        ]);

        if ($id === false) {
            show_error('Falta identificar el bloque.', 400);
            return;
        }

        $volver = site_url('administrador/editarBloque') . '?id=' . $id;
        $error = function ($msg) use ($volver) {
            $this->session->set_flashdata('bloque_error', $msg);
            redirect($volver);
        };

        $inicio = $this->input->post('hora_inicio');
        $termino = $this->input->post('hora_termino');
        $fecha = $this->fechaIso($this->input->post('fecha'));
        $rutProfesor = $this->input->post('rut_profesor');
        $cupos = filter_var($this->input->post('cupos_maximos'), FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 999]
        ]);

        $horaValida = function ($h) {
            return is_string($h) && preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $h) === 1;
        };

        if (
            !$horaValida($inicio) || !$horaValida($termino)
            || $fecha === false || $cupos === false
            || !is_string($rutProfesor) || trim($rutProfesor) === ''
        ) {
            return $error('Revisa los datos del bloque: hay campos inválidos.');
        }

        if ($termino <= $inicio) {
            return $error('La hora de término debe ser posterior a la de inicio.');
        }

        $this->load->database();
        $this->load->model('Administrador_model');

        $bloque = $this->Administrador_model->obtenerBloque($id);

        if (!$bloque) {
            show_404();
            return;
        }

        if ($cupos < (int) $bloque->reservas) {
            return $error('Los cupos no pueden ser menos que las reservas actuales (' . (int) $bloque->reservas . ').');
        }

        $rutProfesor = trim($rutProfesor);

        if (!$this->Administrador_model->profesorPermitidoEnBloque($rutProfesor, $bloque->rut_profesor)) {
            return $error('El profesor seleccionado no existe o está inactivo.');
        }

        if ($this->Administrador_model->hayChoqueProfesor($id, $rutProfesor, $fecha, $inicio . ':00', $termino . ':00')) {
            return $error('Ese profesor ya tiene otra clase en ese horario.');
        }

        $guardado = $this->Administrador_model->actualizarBloque($id, [
            'rut_profesor' => $rutProfesor,
            'fecha' => $fecha,
            'hora_inicio' => $inicio . ':00',
            'hora_termino' => $termino . ':00',
            'cupos_maximos' => $cupos,
        ]);

        if (!$guardado) {
            return $error('No se pudo guardar. Intenta nuevamente.');
        }

        $this->rotarTokenBloque();

        // Vuelve a la semana del bloque guardado
        redirect(site_url('administrador/horarios') . '?semana=' . rawurlencode($fecha));
    }

    public function eliminarBloque()
    {
        $this->load->helper('url');
        $this->load->library('session');

        if ($this->input->method(TRUE) !== 'POST') {
            show_error('Debes enviar el formulario.', 405);
            return;
        }

        if (!$this->tokenBloqueValido()) {
            show_error('El formulario expiró. Vuelve a abrir el bloque.', 403);
            return;
        }

        $id = filter_var($this->input->post('id_bloque'), FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1]
        ]);

        if ($id === false) {
            show_error('Falta identificar el bloque.', 400);
            return;
        }

        $this->load->database();
        $this->load->model('Administrador_model');

        $bloque = $this->Administrador_model->obtenerBloque($id);

        if (!$bloque) {
            show_404();
            return;
        }

        $volver = site_url('administrador/editarBloque') . '?id=' . $id;

        if (!$this->Administrador_model->eliminarBloque($id)) {
            $this->session->set_flashdata(
                'bloque_error',
                'No se pudo eliminar: el bloque tiene reservas vigentes.'
            );
            redirect($volver);
            return;
        }

        $this->rotarTokenBloque();
        redirect(site_url('administrador/horarios') . '?semana=' . rawurlencode(substr((string) $bloque->fecha, 0, 10)));
    }

    private function tokenUsuario()
    {
        $token = $this->session->userdata('token_usuario');

        if (!is_string($token) || $token === '') {
            $token = bin2hex(random_bytes(32));
            $this->session->set_userdata('token_usuario', $token);
        }

        return $token;
    }

    private function claveTemporal($largo = 10)
    {
        $alfabeto = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $clave = '';

        for ($i = 0; $i < $largo; $i++) {
            $clave .= $alfabeto[random_int(0, strlen($alfabeto) - 1)];
        }

        return $clave;
    }

    public function guardarUser()
    {
        $this->load->helper('url');
        $this->load->library('session');

        if ($this->input->method(TRUE) !== 'POST') {
            show_error('Debes enviar el formulario.', 405);
            return;
        }

        $token = $this->input->post('token_usuario');
        $guardado = $this->session->userdata('token_usuario');

        if (
            !is_string($token) || !is_string($guardado)
            || $guardado === '' || !hash_equals($guardado, $token)
        ) {
            show_error('El formulario expiró. Vuelve a abrir la pantalla.', 403);
            return;
        }

        $volver = site_url('administrador/crearUser');

        $correo = $this->input->post('correo');
        $correo = is_string($correo) ? trim($correo) : '';
        $rol = $this->input->post('rol');

        $old = ['correo' => $correo, 'rol' => is_string($rol) ? $rol : ''];

        $error = function ($msg) use ($volver, $old) {
            $this->session->set_flashdata('usuario_error', $msg);
            $this->session->set_flashdata('crear_old', $old);
            redirect($volver);
        };

        if (!in_array($rol, ['alumna', 'profesor', 'admin'], true)) {
            return $error('Selecciona un rol válido.');
        }

        if (
            $correo === '' || mb_strlen($correo) > 120
            || !filter_var($correo, FILTER_VALIDATE_EMAIL)
        ) {
            return $error('Ingresa un correo electrónico válido.');
        }

        $this->load->database();
        $this->load->model('Administrador_model');

        if ($this->Administrador_model->existeCorreo($correo)) {
            return $error('Ya existe un usuario con ese correo.');
        }

        $clave = $this->claveTemporal();

        $resultado = $this->Administrador_model->crearUsuario(
            $rol,
            $correo,
            password_hash($clave, PASSWORD_DEFAULT)
        );

        if ($resultado !== true) {
            return $error(
                $resultado === 'rol'
                ? 'No se encontró el rol en la base de datos. Revisa la tabla rol.'
                : 'No se pudo crear el usuario. Intenta nuevamente.'
            );
        }

        $enviado = $this->enviarClaveTemporal($correo, $clave);

        $this->session->set_userdata('token_usuario', bin2hex(random_bytes(32)));

        if ($enviado) {
            $this->session->set_flashdata(
                'usuario_ok',
                "Usuario creado. Se envió una contraseña temporal a $correo."
            );
        } else {
            // El usuario ya existe: se muestra la clave para entregarla a mano
            $this->session->set_flashdata('usuario_ok', "Usuario creado: $correo.");
            $this->session->set_flashdata('usuario_clave', $clave);
        }

        redirect($volver);
    }

    private function enviarClaveTemporal($correo, $clave)
    {
        $this->load->library('email');
        $this->config->load('email', TRUE);

        $desde = $this->config->item('smtp_user', 'email');
        $login = site_url('autenticacion');

        $this->email->clear();
        $this->email->from($desde, 'Valkiria Center');
        $this->email->to($correo);
        $this->email->subject('Tu acceso a Valkiria Center');
        $this->email->message(
            '<p>Hola,</p>'
            . '<p>Se creó una cuenta para ti en Valkiria Center.</p>'
            . '<p><strong>Correo:</strong> ' . html_escape($correo) . '<br>'
            . '<strong>Contraseña temporal:</strong> ' . html_escape($clave) . '</p>'
            . '<p>Inicia sesión aquí y completa tus datos: '
            . '<a href="' . html_escape($login) . '">' . html_escape($login) . '</a></p>'
        );

        return $this->email->send(false);
    }

    public function generarHorarios()
    {
        $this->load->helper('url');
        $this->load->library('session');

        if ($this->input->method(TRUE) !== 'POST') {
            show_error('Debes enviar el formulario.', 405);
            return;
        }

        if (!$this->tokenBloqueValido()) {
            show_error('El formulario expiró. Vuelve a abrir Horarios.', 403);
            return;
        }

        $this->load->database();
        $this->load->model('Administrador_model');

        // La semana que genera el procedimiento: lunes a domingo de la actual
        $lunes = (new DateTimeImmutable('today', new DateTimeZone('America/Santiago')))
            ->modify('monday this week');
        $domingo = $lunes->modify('+6 days');
        $rango = $lunes->format('d/m') . ' al ' . $domingo->format('d/m');

        $resultado = $this->Administrador_model->generarHorarios();

        if ($resultado === true) {
            $this->rotarTokenBloque();
            $this->session->set_flashdata(
                'horarios_ok',
                "Horarios generados para la semana del $rango."
            );
        } elseif ($resultado === 'existe') {
            $this->session->set_flashdata(
                'horarios_error',
                "La semana del $rango ya tiene horarios. No se generó nada para evitar duplicados."
            );
        } else {
            $this->session->set_flashdata(
                'horarios_error',
                'No se pudieron generar los horarios. Revisa el registro de errores.'
            );
        }

        redirect(site_url('administrador/horarios') . '?semana=' . $lunes->format('Y-m-d'));
    }

}