<?php

namespace App\Models;

class Users
{
    private $data = NULL;

    public function __construct()
    {
        $this->data = json_decode($this->read_users_json());
    }

    /**
     * Reads users data from JSON
     * @return string|false 
     */
    private function read_users_json()
    {
        $ret = "";
        $fname = APPPATH . "/Models/users.json";
        $handle = fopen($fname, "r");
        if ($handle) {
            $ret = fread($handle, filesize($fname));
        }
        return $ret;
    }

    /**
     * Get all users data
     * @return mixed 
     */
    public function get_all_users()
    {
        return $this->data->users;
    }

    /**
     * Get data of specified user id
     * @param mixed $id 
     * @return mixed 
     */
    public function get_user_by_id($id)
    {
        if (empty($id))
            return FALSE;

        foreach ($this->data->users as $user) {
            if ($user->id == $id)
                return $user;
        }
        return FALSE;
    }

    /**
     * Get data of specified user name
     * @param mixed $username 
     * @return mixed 
     */
    public function get_user_by_name($username)
    {
        if (empty($username))
            return FALSE;

        $s_username = trim($username);

        foreach ($this->data->users as $user) {
            if ($user->username == $s_username)
                return $user;
        }
        return FALSE;
    }

    /**
     * Find user data that match for given login and password
     * @param mixed $username 
     * @param mixed $password 
     * @return mixed 
     */
    public function get_user_data($username, $password)
    {
        if (empty($username))
            return false;

        if (empty($password))
            return false;

        $_hash = hash("sha1", $password);
        foreach ($this->data->users as $user) {
            if ($user->username == $username && $_hash == $user->password_hash)
                return $user;
        }
        return false;
    }
}
