<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Pools as PoolsModel;

class Pools extends Controller
{
    protected $pools_model;
    protected $session;

    public function __construct()
    {
        helper(["app_helper"]);

        $this->session = \Config\Services::session();
        $this->pools_model = new PoolsModel();

        if (!user_logged_in($this->session))
            return redirect()->to("/login");
    }

    /**
     * Action for route /pools
     * @return void 
     * @throws InvalidArgumentException 
     */
    public function index()
    {
        $data = null;
        $data["pools"] = $this->pools_model->get_all_pools();
        $content = view("templates/header", $data) .
            view("templates/nav", $data) .
            view("pools/index", $data) .
            view("templates/footer");
        echo ($content);
    }
}
