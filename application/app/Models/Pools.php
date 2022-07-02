<?php

namespace App\Models;

class Pools
{
    private $data = NULL;

    public function __construct()
    {
        $this->data = json_decode($this->read_pools_json());
    }

    /**
     * Reads pool data from JSON file
     * @return string|false 
     */
    private function read_pools_json()
    {
        $ret = "";
        $fname = APPPATH . "/Models/pools.json";
        $handle = fopen($fname, "r");
        if ($handle) {
            $ret = fread($handle, filesize($fname));
        }
        return $ret;
    }

    /**
     * return this object's $data
     * @return mixed 
     */
    public function get_all_pools()
    {
        return $this->data->pools;
    }

    /**
     * Get arrya of pools filtered by give ids 
     * @param mixed $arr_pool_id 
     * @return array 
     */
    public function get_pools($arr_pool_id)
    {
        $ret = array();
        if (!empty($arr_pool_id)) {
            foreach ($this->data->pools as $item) {
                if (in_array($item->id, $arr_pool_id))
                    $ret[] = $item;
            }
        }
        return $ret;
    }

    /**
     * Get CIDR of specified pool
     * @param mixed $pool_id 
     * @return mixed 
     */
    public function get_pool_cidr($pool_id)
    {
        foreach ($this->data->pools as $item) {
            if ($item->id == $pool_id)
                return $item->cidr;
        }
        return false;
    }
}
