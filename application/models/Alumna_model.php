<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alumna_model extends CI_Model
{

    // Obtener datos de la alumna
    public function obtenerAlumnaPorId($idUsuario)
    {
        $this->db->where('id_usuario', $idUsuario);

        $query = $this->db->get('alumna');

        return $query->row();
    }

    //Obtener Proxima Clase
    public function AL_01($alumna)
    {
        return $this->ejecutar_sp("CALL AL_01(?)", [$alumna]);
    }

    public function AL_02()
    {
        return $this->ejecutar_sp("CALL AL_02()");
    }

    public function AL_03($alumna)
    {
        $sql = "SELECT pa.clases_restantes AS clases_restantes, p.cantidad_clases AS total_clases "
            . "FROM plan_alumna AS pa "
            . "JOIN plan AS p ON p.id_plan = pa.id_plan "
            . "WHERE rut_alumna = ?;";
        return $this->db->query($sql, [$alumna])->row();
    }

    public function AL_04($alumna, $fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT b.id_bloque, b.fecha, b.hora_inicio, b.hora_termino, b.cupos_maximos, "
            . "p.nombre AS profesor_nombre, p.especialidad, "
            . "COUNT(r.id_reserva) AS cupos_ocupados, "
            . "MAX(CASE WHEN r.rut_alumna = ? AND r.vigente = 1 THEN 1 ELSE 0 END) AS reservado_por_mi, "
            . "MAX(CASE WHEN r.rut_alumna = ? AND r.vigente = 1 THEN r.id_reserva ELSE NULL END) AS id_reserva_propia "
            . "FROM bloque_horario AS b "
            . "JOIN profesor AS p ON p.rut = b.rut_profesor "
            . "LEFT JOIN reserva AS r ON r.id_bloque = b.id_bloque AND r.vigente = 1 "
            . "WHERE b.vigente = 1 AND b.fecha BETWEEN ? AND ? "
            . "GROUP BY b.id_bloque, b.fecha, b.hora_inicio, b.hora_termino, b.cupos_maximos, p.nombre, p.especialidad "
            . "ORDER BY b.fecha ASC, b.hora_inicio ASC";

        return $this->db->query($sql, [$alumna, $alumna, $fecha_inicio, $fecha_fin])->result();
    }

    public function AL_05($alumna, $id_reserva)
    {
        $reserva = $this->db->query(
            "SELECT r.id_reserva, pa.id_plan_alumna "
            . "FROM reserva AS r "
            . "LEFT JOIN plan_alumna AS pa ON pa.rut_alumna = r.rut_alumna AND pa.id_estado_plan = 1 "
            . "WHERE r.id_reserva = ? AND r.rut_alumna = ? AND r.vigente = 1 "
            . "LIMIT 1",
            [$id_reserva, $alumna]
        )->row();

        if (!$reserva) {
            return ['success' => false, 'mensaje' => 'La reserva no existe, ya fue cancelada, o no te pertenece.'];
        }

        $this->db->trans_start();

        $this->db->query("UPDATE reserva SET vigente = 0 WHERE id_reserva = ?", [$id_reserva]);

        if ($reserva->id_plan_alumna) {
            $this->db->query(
                "UPDATE plan_alumna SET clases_restantes = clases_restantes + 1 WHERE id_plan_alumna = ?",
                [$reserva->id_plan_alumna]
            );
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return ['success' => false, 'mensaje' => 'No se pudo cancelar la reserva. Intenta de nuevo.'];
        }

        return ['success' => true, 'mensaje' => 'Reserva cancelada correctamente.'];
    }



    private function ejecutar_sp($sql, $params = [])
    {
        $db_debug = $this->db->db_debug;
        $this->db->db_debug = false;

        $query = $this->db->query($sql, $params);
        $error = $this->db->error();

        $data = [];

        if ($query instanceof CI_DB_result && isset($query->result_id) && is_object($query->result_id)) {
            $data = $query->result_array();
            $query->free_result();
        }

        $this->limpiar_resultados_sp();

        $this->db->db_debug = $db_debug;

        if ($query === false || !empty($error['code'])) {
            return [
                'success' => false,
                'mensaje' => !empty($error['message']) ? $error['message'] : 'No se pudo realizar la operación.',
                'codigo' => !empty($error['code']) ? $error['code'] : 0,
                'data' => []
            ];
        }

        return [
            'success' => true,
            'mensaje' => 'Operación realizada correctamente.',
            'data' => $data
        ];
    }

    private function limpiar_resultados_sp()
    {
        while ($this->db->conn_id->more_results() && $this->db->conn_id->next_result()) {
            if ($res = $this->db->conn_id->store_result()) {
                $res->free();
            }
        }
    }
}


