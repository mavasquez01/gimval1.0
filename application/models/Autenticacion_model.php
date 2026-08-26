<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Autenticacion_model extends CI_Model
{
    public function buscarPorCorreo($correo)
    {
        $this->db->where('correo', $correo);
        $query = $this->db->get('usuario');

        return $query->row();
    }
}