<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alumna_model extends CI_Model {

    //Obtener Proxima Clase
    public function AL_01($alumna) {
        return $this->ejecutar_sp("CALL AL_01(?)", [$alumna]);
    }

    public function AL_02() {
        return $this->ejecutar_sp("CALL AL_02()");
    }

    public function AL_03($alumna){
        $sql = "SELECT pa.clases_restantes AS clases_restantes, p.cantidad_clases AS total_clases "
                . "FROM plan_alumna AS pa "
                . "JOIN plan AS p ON p.id_plan = pa.id_plan "
                . "WHERE rut_alumna = ?;";
        return $this->db->query($sql, [$alumna])->row();
    }

    public function AL_04($alumna, $fecha_inicio, $fecha_fin) {
    $sql = "SELECT b.id_bloque, b.fecha, b.hora_inicio, b.hora_termino, b.cupos_maximos, "
         . "p.nombre AS profesor_nombre, p.especialidad, "
         . "COUNT(r.id_reserva) AS cupos_ocupados, "
         . "MAX(CASE WHEN r.rut_alumna = ? AND r.vigente = 1 THEN 1 ELSE 0 END) AS reservado_por_mi "
         . "FROM bloque_horario AS b "
         . "JOIN profesor AS p ON p.rut = b.rut_profesor "
         . "LEFT JOIN reserva AS r ON r.id_bloque = b.id_bloque AND r.vigente = 1 "
         . "WHERE b.vigente = 1 AND b.fecha BETWEEN ? AND ? "
         . "GROUP BY b.id_bloque, b.fecha, b.hora_inicio, b.hora_termino, b.cupos_maximos, p.nombre, p.especialidad "
         . "ORDER BY b.fecha ASC, b.hora_inicio ASC";

    return $this->db->query($sql, [$alumna, $fecha_inicio, $fecha_fin])->result();
}
    


    private function ejecutar_sp($sql, $params = []) {
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

    private function limpiar_resultados_sp() {
        while ($this->db->conn_id->more_results() && $this->db->conn_id->next_result()) {
            if ($res = $this->db->conn_id->store_result()) {
                $res->free();
            }
        }
    }
}


