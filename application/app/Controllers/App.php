<?php

namespace App\Controllers;

use App\Models\Users;
use CodeIgniter\Controller;
use CodeIgniter\Files\Exceptions\FileNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use CodeIgniter\Validation\Exceptions\ValidationException;
use InvalidArgumentException;

class App extends Controller
{
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    /**
     * Displays view 
     * @param mixed $data 
     * @return void 
     * @throws InvalidArgumentException 
     */
    protected function show_login_view($data)
    {
        $content =
            view("templates/header", $data) .
            view("app/login", $data) .
            view("templates/footer");
        echo ($content);
    }

    /**
     * Saves user's data in session
     * @param mixed $user 
     * @return void 
     */
    protected function user_session_save($user)
    {
        if (!empty($user)) {
            $this->session->logged_in = true;
            $this->session->username = $user->username;
            $this->session->manage_users = $user->manage_users;
            $this->session->manage_pools = $user->manage_pools;
        }
    }

    /**
     * Deletes user's data from session
     * @return void 
     */
    private function user_session_clear()
    {
        $this->session->logged_in = false;
        unset($this->session->username);
        unset($this->session->manage_users);
        unset($this->session->manage_pools);
    }

    /**
     * Action for default route
     * @return RedirectResponse 
     * @throws HTTPException 
     * @throws InvalidArgumentException 
     */
    public function index()
    {
        return redirect()->to("/login");
    }

    /**
     * Action for route: /login
     * @return RedirectResponse|void 
     * @throws FileNotFoundException 
     * @throws HTTPException 
     * @throws InvalidArgumentException 
     * @throws ValidationException 
     */
    public function login()
    {
        helper(["form", "app_helper"]);

        if (user_logged_in($this->session))
            return redirect()->to("/hosts");

        $validation = \Config\Services::validation();

        $data = [
            "username" => "",
            "password" => "",
            "validation" => $validation
        ];

        if ($this->request->getMethod() != "post") {
            $this->show_login_view($data);
            return;
        }

        $rules = [
            "username" => "trim|required",
            "password" => "trim|required"
        ];

        if ($this->validate($rules) === false) {
            $this->user_session_clear();
            $this->show_login_view($data);
        } else {
            $username = $this->request->getPost("username");
            $password = $this->request->getPost("password");
            $users_model = new Users();
            $userdata = $users_model->get_user_data($username, $password);
            if ($userdata === false) {
                return redirect()->to("/login");
            } else {
                $this->user_session_save($userdata);
                return redirect()->to("/hosts");
            }
        }
    }

    /**
     * Action for route /logout
     * @return RedirectResponse 
     * @throws HTTPException 
     * @throws InvalidArgumentException 
     */
    public function logout()
    {
        $this->user_session_clear();
        return redirect()->to("/login");
    }
}
