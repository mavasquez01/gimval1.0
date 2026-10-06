<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administrador_model extends CI_Model
{
    const DURACION_DIAS_PLAN = 30;
    const ESTADO_ACTIVO = 1;
    const ESTADO_VENCIDO = 2;

    const ROLES_USUARIO = [
        'alumna' => ['nombre_rol' => 'alumna', 'tabla' => 'alumna'],
        'profesor' => ['nombre_rol' => 'profesor', 'tabla' => 'profesor'],
        'admin' => ['nombre_rol' => 'administrador', 'tabla' => 'administrador'],
    ];

    private function filtrarBusqueda($busqueda, $campos)
    {
        if ($busqueda === '')
            return;

        $this->db->group_start();
        foreach ($campos as $i => $campo) {
            $i === 0 ? $this->db->like($campo, $busqueda)
                : $this->db->or_like($campo, $busqueda);
        }
        $this->db->group_end();
    }

    public function listarAlumnas($busqueda = '', $limite = 10, $offset = 0)
    {
        $this->db->select('a.rut, a.nombre, a.apellido, a.activo');
        $this->db->select('(
        SELECT MAX(pa.fecha_termino)
        FROM plan_alumna pa
        WHERE pa.rut_alumna = a.rut AND pa.id_estado_plan = 1
    ) AS fecha_termino_plan', false);
        $this->db->from('alumna a');
        $this->filtrarBusqueda($busqueda, ['a.rut', 'a.nombre', 'a.apellido']);
        $this->db->order_by('a.nombre', 'ASC')->order_by('a.rut', 'ASC');
        $this->db->limit($limite, $offset);

        return $this->db->get()->result();
    }

    public function contarAlumnas($busqueda = '')
    {
        $this->db->from('alumna a');
        $this->filtrarBusqueda($busqueda, ['a.rut', 'a.nombre', 'a.apellido']);
        return $this->db->count_all_results();
    }

    public function listarProfesoras($busqueda = '', $limite = 10, $offset = 0)
    {
        $this->db->select('p.rut, p.nombre, p.apellido, p.activo')->from('profesor p');
        $this->filtrarBusqueda($busqueda, ['p.rut', 'p.nombre', 'p.apellido']);
        $this->db->order_by('p.nombre', 'ASC')->order_by('p.rut', 'ASC');
        $this->db->limit($limite, $offset);

        return $this->db->get()->result();
    }

    public function contarProfesoras($busqueda = '')
    {
        $this->db->from('profesor p');
        $this->filtrarBusqueda($busqueda, ['p.rut', 'p.nombre', 'p.apellido']);
        return $this->db->count_all_results();
    }
    public function obtenerDetalleAlumna($rut)
    {
        return $this->db
            ->select('a.rut, a.nombre, a.apellido, u.email, a.activo')
            ->from('alumna a')
            ->join('usuario u', 'u.id_usuario = a.id_usuario', 'left')
            ->where('a.rut', $rut)
            ->get()
            ->row();
    }

    public function obtenerUltimoPlanAlumna($rut)
    {
        return $this->db
            ->select('
                pa.id_plan_alumna,
                pa.id_plan,
                pa.fecha_inicio,
                pa.fecha_termino,
                pa.clases_restantes,
                pa.id_estado_plan,
                p.nombre_plan,
                p.cantidad_clases,
                ep.nombre_estado
            ')
            ->from('plan_alumna pa')
            ->join('plan p', 'p.id_plan = pa.id_plan', 'left')
            ->join(
                'estado_plan ep',
                'ep.id_estado_plan = pa.id_estado_plan',
                'left'
            )
            ->where('pa.rut_alumna', $rut)
            ->order_by('pa.fecha_inicio', 'DESC')
            ->order_by('pa.id_plan_alumna', 'DESC')
            ->limit(1)
            ->get()
            ->row();
    }

    public function extenderPlan($idPlan, $rut, $dias, $fechaAnterior)
    {
        if (!is_int($dias) || $dias < 1 || $dias > 365) {
            return false;
        }

        $fecha = DateTimeImmutable::createFromFormat(
            '!Y-m-d',
            $fechaAnterior
        );

        if (!$fecha || $fecha->format('Y-m-d') !== $fechaAnterior) {
            return false;
        }

        $nuevaFecha = $fecha->modify('+' . $dias . ' days');

        if ((int) $nuevaFecha->format('Y') > 9999) {
            return false;
        }

        // La fecha anterior evita aplicar dos veces un formulario repetido.
        $resultado = $this->db
            ->where('id_plan_alumna', $idPlan)
            ->where('rut_alumna', $rut)
            ->where('fecha_termino', $fechaAnterior)
            ->update('plan_alumna', [
                'fecha_termino' => $nuevaFecha->format('Y-m-d')
            ]);

        return $resultado && $this->db->affected_rows() === 1;
    }

    public function obtenerDetalleProfesor($rut)
    {
        return $this->db
            ->select('p.rut, p.nombre, p.apellido, p.activo, u.email')
            ->from('profesor p')
            ->join('usuario u', 'u.id_usuario = p.id_usuario', 'left')
            ->where('p.rut', $rut)
            ->get()
            ->row();
    }

    public function listarPlanes()
    {
        return $this->db
            ->select('id_plan, nombre_plan, cantidad_clases')
            ->from('plan')
            ->order_by('nombre_plan', 'ASC')
            ->get()->result();
    }

    public function listarEstadosPlan()
    {
        return $this->db
            ->select('id_estado_plan, nombre_estado')
            ->from('estado_plan')
            ->order_by('id_estado_plan', 'ASC')
            ->get()->result();
    }

    public function obtenerPlan($idPlan)
    {
        return $this->db->where('id_plan', $idPlan)->get('plan')->row();
    }

    public function existeEstadoPlan($idEstado)
    {
        return $this->db
            ->where('id_estado_plan', $idEstado)
            ->count_all_results('estado_plan') === 1;
    }

    public function renovarPlan($rut, $idPlanAlumna, $fechaAnterior)
    {
        $zona = new DateTimeZone('America/Santiago');
        $hoy = new DateTimeImmutable('today', $zona);

        $this->db->trans_begin();

        // Bloquea la fila para que dos clics simultáneos no renueven dos veces
        $anterior = $this->db->query(
            'SELECT pa.id_plan, pa.fecha_inicio, pa.fecha_termino,
                pa.clases_restantes, pa.id_estado_plan, p.cantidad_clases
         FROM plan_alumna pa
         JOIN plan p ON p.id_plan = pa.id_plan
         WHERE pa.id_plan_alumna = ? AND pa.rut_alumna = ?
         FOR UPDATE',
            [$idPlanAlumna, $rut]
        )->row();

        if (!$anterior || $anterior->fecha_termino !== $fechaAnterior) {
            $this->db->trans_rollback();
            return false;
        }

        // Debe seguir siendo el último plan de la alumna
        $hayMasNuevo = $this->db
            ->group_start()
            ->where('fecha_inicio >', $anterior->fecha_inicio)
            ->or_group_start()
            ->where('fecha_inicio', $anterior->fecha_inicio)
            ->where('id_plan_alumna >', $idPlanAlumna)
            ->group_end()
            ->group_end()
            ->where('rut_alumna', $rut)
            ->count_all_results('plan_alumna') > 0;

        if ($hayMasNuevo) {
            $this->db->trans_rollback();
            return false;
        }

        $finAnterior = DateTimeImmutable::createFromFormat(
            '!Y-m-d',
            $anterior->fecha_termino,
            $zona
        );

        if (!$finAnterior) {
            $this->db->trans_rollback();
            return false;
        }

        // Empieza hoy; si el plan seguía vigente, conserva los días restantes
        $base = $finAnterior >= $hoy ? $finAnterior : $hoy;
        $termino = $base->modify('+' . self::DURACION_DIAS_PLAN . ' days');

        // Clases que le quedan + clases del plan nuevo
        $clasesTotales = max(0, (int) $anterior->clases_restantes)
            + (int) $anterior->cantidad_clases;

        // El plan anterior se cierra y traspasa sus clases al nuevo
        $this->db
            ->where('id_plan_alumna', $idPlanAlumna)
            ->update('plan_alumna', [
                'id_estado_plan' => self::ESTADO_VENCIDO,
                'clases_restantes' => 0,
            ]);

        $this->db->insert('plan_alumna', [
            'rut_alumna' => $rut,
            'id_plan' => (int) $anterior->id_plan,
            'fecha_inicio' => $hoy->format('Y-m-d'),
            'fecha_termino' => $termino->format('Y-m-d'),
            'clases_restantes' => $clasesTotales,
            'id_estado_plan' => self::ESTADO_ACTIVO,
        ]);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }
    public function modificarPlan($idPlanAlumna, $rut, $fechaAnterior, array $d)
    {
        $ok = $this->db
            ->where('id_plan_alumna', $idPlanAlumna)
            ->where('rut_alumna', $rut)
            ->where('fecha_termino', $fechaAnterior)
            ->update('plan_alumna', [
                'id_plan' => $d['id_plan'],
                'fecha_inicio' => $d['fecha_inicio'],
                'fecha_termino' => $d['fecha_termino'],
                'clases_restantes' => $d['clases_restantes'],
                'id_estado_plan' => $d['id_estado_plan'],
            ]);

        return $ok && $this->db->affected_rows() === 1;
    }

    private function tablaUsuario($tipo)
    {
        $tablas = ['alumna' => 'alumna', 'profesor' => 'profesor'];
        return $tablas[$tipo] ?? null;
    }

    public function obtenerActivo($tipo, $rut)
    {
        $tabla = $this->tablaUsuario($tipo);
        if ($tabla === null)
            return null;

        $fila = $this->db
            ->select('activo')
            ->where('rut', $rut)
            ->get($tabla)
            ->row();

        return $fila ? (int) $fila->activo : null;
    }

    public function cambiarEstadoUsuario($tipo, $rut, $activo)
    {
        $tabla = $this->tablaUsuario($tipo);
        if ($tabla === null)
            return false;

        $activo = $activo ? 1 : 0;

        $this->db->trans_begin();

        $fila = $this->db
            ->select('id_usuario')
            ->where('rut', $rut)
            ->get($tabla)
            ->row();

        if (!$fila) {
            $this->db->trans_rollback();
            return false;
        }

        // Ficha de la alumna / profesor
        $this->db->where('rut', $rut)->update($tabla, ['activo' => $activo]);

        // Cuenta de acceso
        if ($fila->id_usuario !== null) {
            $this->db
                ->where('id_usuario', $fila->id_usuario)
                ->update('usuario', ['activo' => $activo]);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }

    public function contarAlumnasActivas()
    {
        return (int) $this->db->where('activo', 1)->count_all_results('alumna');
    }

    public function contarProfesoresActivos()
    {
        return (int) $this->db->where('activo', 1)->count_all_results('profesor');
    }

    public function contarClasesHoy($hoy)
    {
        return (int) $this->db
            ->where('fecha', $hoy)
            ->where('vigente', 1)
            ->count_all_results('bloque_horario');
    }

    // Alumnas activas cuyo plan activo vence a más tardar en $fechaLimite
// (incluye planes ya vencidos que sigan marcados como activos)
    public function contarAlertasPlanes($fechaLimite)
    {
        $fila = $this->db->query(
            'SELECT COUNT(*) AS total FROM (
            SELECT pa.rut_alumna, MAX(pa.fecha_termino) AS fin
            FROM plan_alumna pa
            JOIN alumna a ON a.rut = pa.rut_alumna AND a.activo = 1
            WHERE pa.id_estado_plan = 1
            GROUP BY pa.rut_alumna
            HAVING fin <= ?
        ) t',
            [$fechaLimite]
        )->row();

        return $fila ? (int) $fila->total : 0;
    }

    public function listarProximasClases($fecha, $hora, $limite = 5)
    {
        return $this->db
            ->select("b.id_bloque, b.fecha, b.hora_inicio, b.hora_termino,
                  CONCAT(p.nombre, ' ', p.apellido) AS nombre_profesor", false)
            ->from('bloque_horario b')
            ->join('profesor p', 'p.rut = b.rut_profesor', 'left')
            ->where('b.vigente', 1)
            ->group_start()
            ->where('b.fecha >', $fecha)
            ->or_group_start()
            ->where('b.fecha', $fecha)
            ->where('b.hora_inicio >=', $hora)
            ->group_end()
            ->group_end()
            ->order_by('b.fecha', 'ASC')
            ->order_by('b.hora_inicio', 'ASC')
            ->limit($limite)
            ->get()
            ->result();
    }

    private function seleccionarBloque()
    {
        $this->db->select(
            "b.id_bloque, b.rut_profesor, b.fecha, b.hora_inicio, b.hora_termino,
         b.cupos_maximos, b.vigente,
         CONCAT(p.nombre, ' ', p.apellido) AS nombre_profesor,
         (SELECT COUNT(*) FROM reserva r
           WHERE r.id_bloque = b.id_bloque AND r.vigente = 1) AS reservas",
            false
        );
        $this->db->from('bloque_horario b');
        $this->db->join('profesor p', 'p.rut = b.rut_profesor', 'left');
    }

    public function listarBloquesRango($desde, $hasta)
    {
        $this->seleccionarBloque();

        return $this->db
            ->where('b.fecha >=', $desde)
            ->where('b.fecha <=', $hasta)
            ->where('b.vigente', 1)
            ->order_by('b.fecha', 'ASC')
            ->order_by('b.hora_inicio', 'ASC')
            ->get()
            ->result();
    }

    public function obtenerBloque($id)
    {
        $this->seleccionarBloque();

        return $this->db
            ->where('b.id_bloque', $id)
            ->get()
            ->row();
    }

    public function listarProfesoresParaBloque($rutActual)
    {
        return $this->db
            ->select('rut, nombre, apellido')
            ->from('profesor')
            ->group_start()
            ->where('activo', 1)
            ->or_where('rut', $rutActual)
            ->group_end()
            ->order_by('nombre', 'ASC')
            ->get()
            ->result();
    }

    public function profesorPermitidoEnBloque($rut, $rutActual)
    {
        return $this->db
            ->where('rut', $rut)
            ->group_start()
            ->where('activo', 1)
            ->or_where('rut', $rutActual)
            ->group_end()
            ->count_all_results('profesor') === 1;
    }

    // ¿El profesor ya tiene otro bloque vigente que se solape en esa fecha?
    public function hayChoqueProfesor($idBloque, $rut, $fecha, $inicio, $termino)
    {
        return $this->db
            ->where('rut_profesor', $rut)
            ->where('fecha', $fecha)
            ->where('vigente', 1)
            ->where('id_bloque !=', $idBloque)
            ->where('hora_inicio <', $termino)
            ->where('hora_termino >', $inicio)
            ->count_all_results('bloque_horario') > 0;
    }

    public function actualizarBloque($id, array $d)
    {
        $this->db->where('id_bloque', $id)->update('bloque_horario', $d);

        // affected_rows() = 0 si no hubo cambios; eso no es un error
        return $this->db->error()['code'] === 0;
    }

    // Baja lógica; no se permite si hay reservas vigentes
    public function eliminarBloque($id)
    {
        $this->db->trans_begin();

        $reservas = (int) $this->db
            ->where('id_bloque', $id)
            ->where('vigente', 1)
            ->count_all_results('reserva');

        if ($reservas > 0) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->where('id_bloque', $id)->update('bloque_horario', ['vigente' => 0]);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }

    public function existeCorreo($correo)
    {
        return $this->db->where('email', $correo)->count_all_results('usuario') > 0;
    }
    // Devuelve true, 'rol' (rol no encontrado) o false (error)
    public function crearUsuario($rol, $correo, $hash)
    {
        $cfg = self::ROLES_USUARIO[$rol] ?? null;
        if ($cfg === null)
            return false;

        $fila = $this->db
            ->select('id_rol')
            ->where('LOWER(nombre_rol)', strtolower($cfg['nombre_rol']))
            ->get('rol')
            ->row();

        if (!$fila)
            return 'rol';

        return $this->db->insert('usuario', [
            'email' => $correo,
            'contrasena_hash' => $hash,
            'id_rol' => (int) $fila->id_rol,
            'activo' => 1,
            'fecha_creacion' => date('Y-m-d H:i:s'),
        ]) ? true : false;
    }

    // Devuelve el id del bloque nuevo o false si falla
    public function crearBloque(array $d)
    {
        $ok = $this->db->insert('bloque_horario', [
            'rut_profesor' => $d['rut_profesor'],
            'fecha' => $d['fecha'],
            'hora_inicio' => $d['hora_inicio'],
            'hora_termino' => $d['hora_termino'],
            'cupos_maximos' => $d['cupos_maximos'],
            'vigente' => 1,
        ]);

        return $ok ? (int) $this->db->insert_id() : false;
    }

    // Ejecuta el procedimiento almacenado generar_horarios.
    // Devuelve true, 'existe' (la semana ya tiene horarios) o false.
    public function generarHorarios()
    {
        $debug = $this->db->db_debug;
        $this->db->db_debug = false;

        $resultado = false;
        $error = ['code' => 0, 'message' => ''];

        try {
            $resultado = $this->db->query('CALL generar_horarios()');
            $error = $this->db->error();

            if (is_object($resultado) && method_exists($resultado, 'free_result')) {
                $resultado->free_result();
            }
        } catch (Throwable $e) {
            // PHP 8.1+: los errores SQL (incluido SIGNAL) llegan como excepción
            $resultado = false;
            $error = ['code' => (int) $e->getCode(), 'message' => $e->getMessage()];
        }

        $this->liberarResultadosPendientes();
        $this->db->db_debug = $debug;

        if ($resultado === false) {
            // 1644 = SIGNAL '45000' del procedimiento ("Ya existen horarios...")
            if ((int) $error['code'] === 1644) {
                return 'existe';
            }

            log_message('error', 'generar_horarios falló: ' . $error['message']);
            return false;
        }

        return true;
    }

    // Un CALL deja resultados pendientes; si no se liberan, la siguiente consulta falla
    private function liberarResultadosPendientes()
    {
        $conn = $this->db->conn_id;
        if (!($conn instanceof mysqli))
            return;

        try {
            for ($i = 0; $i < 10 && $conn->more_results(); $i++) {
                $conn->next_result();
                $extra = $conn->use_result();

                if ($extra instanceof mysqli_result) {
                    $extra->free();
                }
            }
        } catch (Throwable $e) {
            // no hay nada más que liberar
        }
    }

}