<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Users;

class Admin extends Controller
{
    protected $users_model;
    protected $session;

    public function __construct()
    {
        helper(["app_helper"]);

        $this->session = \Config\Services::session();

        if (!user_logged_in($this->session))
            return redirect()->to("/login");

        $this->users_model = new Users();
    }

    private function user_is_admin($session)
    {
        $userobj = $this->users_model->get_user_by_name($session->username);
        if ($userobj && $userobj->manage_users == True)
            return True;
        else
            return False;
    }

    public function index()
    {
        $data = [];
        $content = "";

        if (!$this->user_is_admin($this->session)) {
            $data["title"] = lang("Administration");
            $data["errormsg"] = lang("Kea.error_nopermission");
            $content = view("templates/header", $data) .
                view("templates/nav", $data) .
                view("admin/error", $data) .
                view("templates/footer");
        } else {
            $content = view("templates/header", $data) .
                view("templates/nav", $data) .
                view("admin/index", $data) .
                view("templates/footer");
        }
        echo $content;
    }
}
