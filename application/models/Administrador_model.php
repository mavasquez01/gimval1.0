<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administrador_model extends CI_Model
{
    public function listarAlumnas($busqueda = '')
    {
        $this->db->select('a.*');

        // Si hay varios planes activos, toma el vencimiento más lejano.
        $this->db->select('(
        SELECT MAX(pa.fecha_termino)
        FROM plan_alumna pa
        WHERE pa.rut_alumna = a.rut
          AND pa.id_estado_plan = 1
    ) AS fecha_termino_plan', false);

        $this->db->from('alumna a');

        if ($busqueda !== '') {
            $this->db->group_start();
            $this->db->like('a.rut', $busqueda);
            $this->db->or_like('a.nombre', $busqueda);
            $this->db->or_like('a.apellido', $busqueda);
            $this->db->group_end();
        }

        $this->db->order_by('a.nombre', 'ASC');
        $this->db->order_by('a.rut', 'ASC');

        return $this->db->get()->result();
    }

    public function listarProfesoras($busqueda = '')
    {
        $this->db->from('profesor');

        if ($busqueda !== '') {
            $this->db->group_start();
            $this->db->like('rut', $busqueda);
            $this->db->or_like('nombre', $busqueda);
            $this->db->or_like('apellido', $busqueda);
            $this->db->group_end();
        }

        $this->db->order_by('nombre', 'ASC');
        $this->db->order_by('rut', 'ASC');

        return $this->db->get()->result();
    }

    public function obtenerDetalleAlumna($rut)
    {
        return $this->db
            ->select('a.rut, a.nombre, a.apellido, u.email')
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
            pa.fecha_inicio,
            pa.fecha_termino,
            pa.clases_restantes,
            pa.id_estado_plan,
            p.nombre_plan,
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
}
;
