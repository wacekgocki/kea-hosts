<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class Hosts
{
    protected $db;

    public function __construct(ConnectionInterface $db)
    {
        helper(["app_helper"]);
        $this->db = $db;
    }

    /**
     * returns all hosts from database
     * @return array|NULL array of objects with query results
     */
    public function get_all_hosts()
    {
        $sql = "SELECT host_id, INET_NTOA(ipv4_address) AS `host_ip`, HEX(dhcp_identifier) AS `host_mac`, hostname " .
            "FROM hosts ORDER BY hostname ASC";
        $query = $this->db->query($sql);
        return $query->getResultArray();
    }

    /**
     * returns hosts that belong to one of given networks
     * @param $arr_cidr  array of strings with network addresses e.g ["192.168.1.0/24", "172.16.1.1/16"]
     * @return array|NULL array of objects with query results
     */
    public function get_hosts_by_pool($arr_pools)
    {
        $sql = "SELECT host_id, INET_NTOA(ipv4_address) AS `host_ip`, HEX(dhcp_identifier) AS `host_mac`, hostname " .
            "FROM hosts ORDER BY hostname ASC";
        $query = $this->db->query($sql);
        $arr_result = $query->getResultArray();
        $ret = array();
        foreach ($arr_result as $res) {
            foreach ($arr_pools as $pool) {
                if (ip_in_range($res["host_ip"], $pool->cidr)) {
                    $res["pool_id"] = $pool->id;
                    $ret[] = $res;
                    break;
                }
            }
        }
        return $ret;
    }

    /**
     * returns host that has given id
     * @param integer|string  host id from database e.g. 1, "123"
     * @return object|NULL object with query result or
     */
    public function get_single_host($host_id)
    {
        $sql = "SELECT host_id, INET_NTOA(ipv4_address) AS `host_ip`, HEX(dhcp_identifier) AS `host_mac`, hostname " .
            "FROM hosts WHERE host_id = ? ";
        $query = $this->db->query($sql, array($host_id));
        return $query->getRow();
    }

    /**
     * Checks if given ip address is already in hosts table
     * @param mixed $host_ip 
     * @return bool 
     */
    public function host_ip_exists($host_ip)
    {
        $sql = "SELECT host_id FROM hosts WHERE INET_NTOA(ipv4_address) = ? ";
        $query = $this->db->query($sql, array($host_ip));
        return $query->getRow() != NULL ? true : false;
    }

    /**
     * Checks if given MAC address is already in hosts table
     * @param mixed $host_mac 
     * @return bool 
     */
    public function host_mac_exists($host_mac)
    {
        if (!mac_valid($host_mac))
            return false;
        $mac = str_replace(":", "", mac_normalize($host_mac));
        $sql = "SELECT host_id FROM hosts WHERE UPPER(HEX(dhcp_identifier)) = UPPER(?)";
        $query = $this->db->query($sql, array($mac));
        return $query->getRow() != NULL ? true : false;
    }

    /**
     * Checks if given hostname is already in hosts database
     * @param mixed $host_name 
     * @return bool 
     */
    public function host_name_exists($host_name)
    {
        $name = trim($host_name);
        $sql = "SELECT host_id FROM hosts WHERE UPPER(hostname) = UPPER(?)";
        $query = $this->db->query($sql, array($name));
        return $query->getRow() != NULL ? true : false;
    }

    /**
     * deletes record with given id
     * @param  integer $host_id record"s primary key value
     * @return boolean          true on success, Fsalse on failure
     */
    public function delete($host_id)
    {
        if (empty($host_id))
            return false;
        $s_id = $this->db->escape($host_id);
        $sql = "DELETE FROM hosts WHERE host_id=$s_id";
        return $this->db->simpleQuery($sql);
    }

    /**
     * inserts new record
     * @param string $host_ip    new ip address like "172.12.12.1"
     * @param string $host_mac   new mac adress like aa:bb:cc:dd:ee:ff:gg
     * @param string $host_descr host name or description
     * @return boolean           true on success, False on failure
     */
    public function insert($host_ip, $host_mac, $host_descr)
    {
        if (empty($host_ip) || empty($host_mac) || empty($host_descr))
            return FALSE;
        $mac =  $this->db->escape(mac_normalize($host_mac));
        $ip =  $this->db->escape($host_ip);
        $desc =  $this->db->escape(trim($host_descr));
        $sql = "INSERT INTO hosts(dhcp_identifier, dhcp_identifier_type, dhcp4_subnet_id," .
            " dhcp6_subnet_id, ipv4_address, hostname, dhcp4_client_classes, dhcp6_client_classes)" .
            " VALUES(UNHEX(REPLACE($mac, \":\", \"\")), 0, 1, NULL, INET_ATON($ip), $desc, NULL, NULL)";
        return $this->db->simpleQuery($sql);
    }

    /**
     * updates record
     * @param integer|string $host_id record id, e.g. 123123
     * @param string $host_mac        new mac adress like aa:bb:cc:dd:ee:ff:gg
     * @param string $host_descr      new host name or description
     * @return boolean                true on success, False on failure
     */
    public function update($host_id, $host_mac, $host_descr)
    {
        if (empty($host_id) || empty($host_mac) || empty($host_descr))
            return FALSE;
        $mac =  $this->db->escape(mac_normalize($host_mac));
        $id =  $this->db->escape($host_id);
        $desc =  $this->db->escape(trim($host_descr));
        $sql = "UPDATE hosts SET dhcp_identifier=UNHEX(REPLACE($mac, \":\", \"\")), hostname=$desc WHERE host_id=$id";
        return $this->db->simpleQuery($sql);
    }
}
