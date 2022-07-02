<?php

namespace App\Rules;

use App\Models\Hosts as HostsModel;

class KeaRules
{
    protected $hosts_model;
    protected $db;

    public function __construct()
    {
        helper(["app_helper"]);
        $this->db = db_connect();
        $this->hosts_model = new HostsModel($this->db);
    }

    public function mac_address_check($str, ?string $param, array $data, ?string &$error): bool
    {
        $ret = mac_valid($str);
        if ($ret === false) {
            $error = "Value of {$str} is not a valid MAC address";
        }
        return $ret;
    }

    public function hostip_unique_check($str, ?string $param, array $data, ?string &$error): bool
    {
        if ($this->hosts_model->host_ip_exists($str)) {
            $error = "This IP address ({$str}) already exists in database";
            return false;
        }
        return true;
    }

    public function hostmac_unique_check_add($str, ?string $param, array $data, ?string &$error): bool
    {
        if ($this->hosts_model->host_mac_exists($str)) {
            $error = "This MAC address already exists in database";
            return false;
        }
        return true;
    }

    public function hostmac_unique_check_edit($str, ?string $host_mac_old, array $data, ?string &$error): bool
    {
        if (empty($host_mac_old))
            return false;

        $s_old = trim(strtoupper($host_mac_old));
        $s_new = trim(strtoupper($str));
        if ($s_new == $s_old)
            return true;

        if ($this->hosts_model->host_mac_exists($str)) {
            $error = "This MAC address already exists in database";
            return false;
        }

        return true;
    }

    public function hostname_unique_check_add($str, ?string $param, array $data, ?string &$error): bool
    {
        if ($this->hosts_model->host_name_exists($str)) {
            $error = "This host name already exists in database";
            return false;
        }

        return true;
    }

    public function hostname_unique_check_edit($str, ?string $hostname_old, array $data, ?string &$error): bool
    {
        if (empty($hostname_old))
            return false;

        $s_old = trim(strtoupper($hostname_old));
        $s_new = trim(strtoupper($str));
        if ($s_new == $s_old)
            return true;

        if ($this->hosts_model->host_name_exists($str)) {
            $error = "This host name already exists in database";
        }

        return true;
    }
}
