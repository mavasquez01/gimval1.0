<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alumna_model extends CI_Model {

    //Obtener Proxima Clase
    public function AL_01($alumna) {
        return $this->ejecutar_sp("CALL AL_01(?)", [$alumna]);
    }
    //Obtener proximas clases vista rapida
    public function AL_02() {
        return $this->ejecutar_sp("CALL AL_02()");
    }
    //Obteneer clases restantes vista rapida
    public function AL_03($alumna){
        $sql = "SELECT pa.clases_restantes AS clases_restantes, p.cantidad_clases AS total_clases "
                . "FROM plan_alumna AS pa "
                . "JOIN plan AS p ON p.id_plan = pa.id_plan "
                . "WHERE rut_alumna = ?;";
        return $this->db->query($sql, [$alumna])->row();
    }
    //Obtener horario
    public function AL_04($alumna, $fecha_inicio, $fecha_fin) {
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
    
    //Cancelar reserva
    public function AL_05($alumna, $id_reserva) {
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

    //Hacer Reserva 

    public function AL_06($alumna, $bloque)
        {
           
            $this->db->trans_begin();
            //Comprobamos el bloque
            $infoBloque = $this->db->query(
                "SELECT
                    b.id_bloque as id_bloque,
                    p.nombre as nombre,
                    b.fecha as fecha,
                    b.hora_inicio as hora_inicio,
                    b.hora_termino as hora_termino,
                    b.cupos_maximos as cupos_maximos,
                    b.vigente as vigente
                FROM bloque_horario as b
                JOIN profesor as p ON b.rut_profesor = p.rut
                WHERE id_bloque = ?
                FOR UPDATE",
                [$bloque]
            )->row_array();

            if (!$infoBloque) {

                $this->db->trans_rollback();

                return [
                    'success' => false,
                    'mensaje' => 'El bloque seleccionado no existe.'
                ];
            }

            if ((int)$infoBloque['vigente'] !== 1) {

                $this->db->trans_rollback();

                return [
                    'success' => false,
                    'mensaje' => 'Este bloque ya no se encuentra disponible.'
                ];
            }
            //Comprobamos la reserva
            $reservaExistente = $this->db->query(
                "SELECT id_reserva
                FROM reserva
                WHERE rut_alumna = ?
                AND id_bloque = ?
                AND vigente = 1
                LIMIT 1",
                [$alumna, $bloque]
            )->row();

            if ($reservaExistente) {

                $this->db->trans_rollback();

                return [
                    'success' => false,
                    'mensaje' => 'Esta clase ya se encuentra registrada.'
                ];
            }
            //Comprobamos los cupos
            $reservas = $this->db->query(
                "SELECT COUNT(*) AS cantidad
                FROM reserva
                WHERE id_bloque = ?
                AND vigente = 1",
                [$bloque]
            )->row();

            $reservasActuales = (int)$reservas->cantidad;
            $cuposMaximos = (int)$infoBloque['cupos_maximos'];


            if ($reservasActuales >= $cuposMaximos) {

                $this->db->trans_rollback();

                return [
                    'success' => false,
                    'mensaje' => 'Esta clase ya no tiene cupos disponibles.'
                ];
            }
            //Comprobamos las clases restantes del plan
            $plan = $this->db->query(
                "SELECT clases_restantes
                FROM plan_alumna
                WHERE rut_alumna = ?
                LIMIT 1",
                [$alumna]
            )->row();

            if (!$plan) {

                $this->db->trans_rollback();

                return [
                    'success' => false,
                    'mensaje' => 'No se encontró un plan asociado a la alumna.'
                ];
            }

            if ((int)$plan->clases_restantes <= 0) {

                $this->db->trans_rollback();

                return [
                    'success' => false,
                    'mensaje' => 'No tienes clases disponibles para realizar esta reserva.'
                ];
            }
            //Insertamos la reserva
            $insertada = $this->db->query(
                "INSERT INTO reserva
                    (id_bloque, rut_alumna, fecha_reserva, asistencia, vigente)
                VALUES (?, ?, NOW(), 0, 1)",
                [$bloque, $alumna]
            );

            if (!$insertada) {

                $this->db->trans_rollback();

                return [
                    'success' => false,
                    'mensaje' => 'No se pudo crear la reserva.'
                ];
            }
            //Descontamos la clase
            $actualizado = $this->db->query(
                "UPDATE plan_alumna
                SET clases_restantes = clases_restantes - 1
                WHERE rut_alumna = ?
                AND clases_restantes > 0",
                [$alumna]
            );

            if (!$actualizado || $this->db->affected_rows() !== 1) {

                $this->db->trans_rollback();

                return [
                    'success' => false,
                    'mensaje' => 'No fue posible descontar la clase del plan.'
                ];
            }
            //Comprobamos y confirmamos
            if ($this->db->trans_status() === FALSE) {

                $this->db->trans_rollback();

                return [
                    'success' => false,
                    'mensaje' => 'No se pudo confirmar la reserva. Intenta nuevamente.'
                ];
            }
            $this->db->trans_commit();
            //Respuesta pal yeison
            return [
                'success' => true,
                'mensaje' => 'Reserva realizada correctamente.',
                'id_bloque' => $infoBloque['id_bloque'],
                'nombre' => $infoBloque['nombre'],
                'fecha' => $infoBloque['fecha'],
                'hora_inicio' => $infoBloque['hora_inicio'],
                'hora_termino' => $infoBloque['hora_termino']
            ];
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


