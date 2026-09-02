<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rutina_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Datos generales de la rutina.
     */
    public function obtener_rutina($id_rutina) {
        return $this->db
            ->select('id_rutina, id_bloque, fecha, vigente')
            ->from('rutina')
            ->where('id_rutina', $id_rutina)
            ->get()
            ->row();
    }

    /**
     * Ejercicios de la rutina + último peso registrado por la alumna para cada uno.
     */
    public function obtener_ejercicios($id_rutina, $rut_alumna) {
        $sql = "
            SELECT
                dr.id_detalle_rutina,
                dr.orden,
                dr.series,
                dr.repeticiones,
                e.id_ejercicio,
                e.nombre_ejercicio,
                e.descripcion,
                pa.peso_kg
            FROM detalle_rutina dr
            JOIN ejercicio e ON e.id_ejercicio = dr.id_ejercicio
            LEFT JOIN progreso_alumna pa
                ON pa.id_ejercicio = dr.id_ejercicio
                AND pa.rut_alumna = ?
                AND pa.fecha = (
                    SELECT MAX(fecha)
                    FROM progreso_alumna
                    WHERE rut_alumna = ? AND id_ejercicio = dr.id_ejercicio
                )
            WHERE dr.id_rutina = ?
            ORDER BY dr.orden ASC
        ";

        return $this->db
            ->query($sql, [$rut_alumna, $rut_alumna, $id_rutina])
            ->result();
    }

    /**
     * Inserta o actualiza el peso de un ejercicio para la alumna en la fecha de hoy.
     */
    public function guardar_peso($rut_alumna, $id_ejercicio, $peso_kg, $fecha) {
        $existe = $this->db
            ->select('id_progreso')
            ->from('progreso_alumna')
            ->where(['rut_alumna' => $rut_alumna, 'id_ejercicio' => $id_ejercicio, 'fecha' => $fecha])
            ->get()
            ->row();

        if ($existe) {
            $this->db->where('id_progreso', $existe->id_progreso);
            return $this->db->update('progreso_alumna', ['peso_kg' => $peso_kg]);
        }

        return $this->db->insert('progreso_alumna', [
            'rut_alumna'   => $rut_alumna,
            'id_ejercicio' => $id_ejercicio,
            'fecha'        => $fecha,
            'peso_kg'      => $peso_kg
        ]);
    }
}