<?php

namespace App\Controllers;

use App\Models\Hosts as HostsModel;
use App\Models\Pools as PoolsModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Validation\Exceptions\ValidationException;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use InvalidArgumentException;

class Hosts extends Controller
{
    protected $db;
    protected $hosts_model;
    protected $pools_model;
    protected $session;
    protected $validation;

    public function __construct()
    {
        helper(["form", "app_helper"]);

        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->db = db_connect();

        $this->hosts_model = new HostsModel($this->db);
        $this->pools_model = new PoolsModel();

        if (!user_logged_in($this->session))
            return redirect()->to("/login");
    }

    /**
     * Returns array of available ip pools 
     * @return array 
     */
    protected function get_pools()
    {
        $arr_pools_id = $this->session->manage_pools;
        if (empty($arr_pools_id))
            return array();
        return $this->pools_model->get_pools($arr_pools_id);
    }

    /**
     * Returns array of hosts of given pool 
     * @param mixed $arr_pools 
     * @return array|null 
     */
    protected function get_hosts($arr_pools)
    {
        return $this->hosts_model->get_hosts_by_pool($arr_pools);
    }

    /**
     * Displays view for route errors
     * @param mixed $data 
     * @return void 
     * @throws InvalidArgumentException 
     */
    protected function show_error_view($data)
    {
        $content = view("templates/header", $data) .
            view("templates/nav", $data) .
            view("hosts/error", $data) .
            view("templates/footer");
        echo ($content);
    }

    /**
     * Displays view for route /hosts/add
     * @param mixed $data 
     * @return void 
     * @throws InvalidArgumentException 
     */
    protected function show_add_view($data)
    {
        $content =
            view("templates/header", $data) .
            view("templates/nav", $data) .
            view("hosts/add", $data) .
            view("templates/footer");
        echo ($content);
    }

    /**
     * Displays view for route /hosts/edit
     * @param mixed $data 
     * @return void 
     * @throws InvalidArgumentException 
     */
    protected function show_edit_view($data)
    {
        $content =
            view("templates/header", $data) .
            view("templates/nav", $data) .
            view("hosts/edit", $data) .
            view("templates/footer");
        echo ($content);
    }

    /**
     * Displays view for route /hosts/delete
     * @param mixed $data 
     * @return void 
     * @throws InvalidArgumentException 
     */
    protected function show_delete_view($data)
    {
        $content =
            view("templates/header", $data) .
            view("templates/nav", $data) .
            view("hosts/delete", $data) .
            view("templates/footer");
        echo ($content);
    }

    /**
     * Default action for route /hosts
     * @return void 
     * @throws InvalidArgumentException 
     */
    public function index()
    {
        $data = array();
        $data["pools"] = $this->get_pools();
        $data["json"] = json_encode($this->get_hosts($data["pools"]));

        $content = view("templates/header", $data) .
            view("templates/nav", $data) .
            view("hosts/index", $data) .
            view("templates/footer");
        echo ($content);
    }

    /**
     * Action for route /hosts/add
     * @return void|RedirectResponse 
     * @throws InvalidArgumentException 
     * @throws ValidationException 
     * @throws HTTPException 
     */
    public function add()
    {
        $data = [
            "pools" => $this->get_pools(),
            "validation" => $this->validation
        ];

        if (empty($data["pools"])) {
            $data["title"] = lang("Kea.hosts_msg_addhost");
            $data["errormsg"] = lang("Kea.error_nopermission");
            $this->show_error_view($data);
            return;
        }

        if ($this->session->has("host_pool_selected_id")) {
            $data["host_pool_selected_id"] = $this->session->get("host_pool_selected_id");
        } else {
            $data["host_pool_selected_id"] = $data["pools"][0]->id;
            $this->session->set("host_pool_selected_id", $data["host_pool_selected_id"]);
        }

        if ($this->request->getMethod() != "post") {
            $this->show_add_view($data);
            return;
        }

        $rules = [
            "host_mac" => "trim|required|mac_address_check[]|hostmac_unique_check_add[]",
            "hostname" => "trim|required|hostname_unique_check_add[]"
        ];

        if ($this->validate($rules) === false) {
            $this->show_add_view($data);
        } else {
            $host_ip = false;
            $pool_id = $this->request->getPost("host_pool");
            if (!empty($pool_id)) {
                $arr_pools_id = array($pool_id);
                $arr_pools = $this->pools_model->get_pools($arr_pools_id);
                $hosts = $this->get_hosts($arr_pools);
                $arr_ip_used = array();
                foreach ($hosts as $host_item)
                    $arr_ip_used[] = $host_item["host_ip"];

                $pool_cidr = $this->pools_model->get_pool_cidr($pool_id);
                $host_ip = ip_find_first_not_used($pool_cidr, $arr_ip_used);
            }

            if ($host_ip == false) {
                $data["title"] = lang("Kea.hosts_msg_addhost");
                $data["errormsg"] = lang("Kea.error_noipavailable");
                $this->show_error_view($data);
                return;
            }

            if ($this->hosts_model->insert(
                $host_ip,
                $this->request->getPost("host_mac"),
                $this->request->getPost("hostname")
            ))
                return redirect()->to("/hosts");
            else
                show_error(lang("Kea.error_database"));
        }
    }

    /**
     * Action for route /hosts/edit
     * @param mixed $host_id 
     * @return void|RedirectResponse 
     * @throws InvalidArgumentException 
     * @throws ValidationException 
     * @throws HTTPException 
     */
    public function edit($host_id)
    {
        $data = [];

        if (empty($host_id)) {
            $data["title"] = lang("Kea.hosts_msg_edithost");
            $data["errormsg"] = lang("Kea.hosts_msg_error_record_not_found");
            $this->show_error_view($data);
            return;
        }

        $host_obj = $this->hosts_model->get_single_host($host_id);
        if (!$host_obj) {
            $data["title"] = lang("Kea.hosts_msg_edithost");
            $data["errormsg"] = lang("Kea.hosts_msg_error_record_not_found");
            $this->show_error_view($data);
            return;
        }

        $data = [
            "validation" => $this->validation
        ];

        $host_obj->host_mac = mac_add_separator($host_obj->host_mac);
        $data["host_item"] = $host_obj;

        if ($this->request->getMethod() != "post") {
            $this->show_edit_view($data);
            return;
        }

        $rules = [
            "host_id" => "trim|required",
            "host_mac" => "trim|required|mac_address_check[]|hostmac_unique_check_edit[{$host_obj->host_mac}]",
            "hostname" => "trim|required|hostname_unique_check_edit[{$host_obj->hostname}]"
        ];

        if ($this->validate($rules) === false) {
            $this->show_edit_view($data);
        } else {
            if ($this->hosts_model->update(
                $this->request->getPost("host_id"),
                $this->request->getPost("host_mac"),
                $this->request->getPost("hostname")
            ))
                return redirect()->to("/hosts");
            else { {
                    $data["title"] = lang("Kea.hosts_msg_edithost");
                    $data["errormsg"] = lang("Kea.error_database");
                    $this->show_error_view($data);
                    return;
                }
            }
        }
    }

    /**
     * Action for route /hosts/delete
     * @param mixed $host_id 
     * @return void 
     * @throws InvalidArgumentException 
     * @throws ValidationException 
     * @throws HTTPException 
     */
    public function delete($host_id)
    {
        $data = [];

        if (empty($host_id)) {
            $data["title"] = lang("Kea.hosts_msg_deletehost");
            $data["errormsg"] = lang("Kea.hosts_msg_error_record_not_found");
            $this->show_error_view($data);
            return;
        }

        $data["host_item"] = $this->hosts_model->get_single_host($host_id);
        if (empty($data["host_item"])) {
            $data["title"] = lang("Kea.hosts_msg_edithost");
            $data["errormsg"] = lang("Kea.hosts_msg_error_record_not_found");
            $this->show_error_view($data);
            return;
        }

        $data = [
            "validation" => $this->validation
        ];

        if ($this->request->getMethod() != "post") {
            $this->show_delete_view($data);
            return;
        }

        $rules = [
            "host_id" => "trim|required"
        ];

        if ($this->validate($rules) === false) {
            $this->show_delete_view($data);
        } else {
            $host_id = $this->request->getPost("host_id");
            if ($this->hosts_model->delete($host_id))
                redirect()->to("/hosts");
            else {
                $data["title"] = lang("Kea.hosts_msg_delhost");
                $data["errormsg"] = lang("Kea.error_database");
                $this->show_error_view($data);
            }
        }
    }
}
