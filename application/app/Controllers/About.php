<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class About extends Controller
{
    protected $session;

    public function __construct()
    {
        helper(["app_helper"]);

        $this->session = \Config\Services::session();

        if (!user_logged_in($this->session))
            redirect()->to("/login");
    }

    public function index()
    {
        $data = null;
        $data["version"] = "0.0.1";
        $data["author"] = "Author name<br>" .
            "<a href=\"#\">Author's web<br>" .
            "GitHub: <a href=\"https://www.github.com\">Author's github profile</a>";

        $content = view("templates/header", $data) .
            view("templates/nav", $data) .
            view("about/index", $data) .
            view("templates/footer");
        echo ($content);
    }
}
