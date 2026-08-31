<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Autenticacion_model extends CI_Model
{
    public function buscarPorCorreo($correo)
    {
        $this->db->where('email', $correo);
        $this->db->where('activo', 1);

        $query = $this->db->get('usuario');

        return $query->row();
    }

    public function buscarPorId($idUsuario)
    {
        $this->db->where('id_usuario', $idUsuario);
        $this->db->where('activo', 1);

        $query = $this->db->get('usuario');

        return $query->row();
    }


}