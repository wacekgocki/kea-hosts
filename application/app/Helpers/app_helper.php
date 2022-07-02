<?php

/**
 * Makes pretty MAC address string adding separator after each two bytes
 * @param  string $mac       raw string with MAC adress bytes
 * @param  string $separator separator string
 * @return string            nice string with MAC address
 */
function mac_add_separator($mac, $separator = ":")
{
    return join($separator, str_split($mac, 2));
}

/**
 * Checks if string is a valid MAC address using both filter_var and custom rule
 * Only MAC addresess with separators ":" or "-" are considered valid
 * @param  string $mac string with MAC address
 * @return boolean     true if MAC is valid, false otherwise
 */
function mac_valid($mac)
{
    if (filter_var($mac, FILTER_VALIDATE_MAC)) {
        // basic validation succeeded, now check if we have MACs with : or -
        if (strpos($mac, ":") === false && strpos($mac, "-") === false)
            return false;
        else
            return true;
    }
    return false;
}

/**
 * convert MAC address to version with ":" separator and uppercase all chars
 * @param  string $mac [description]
 * @return string      [description]
 */
function mac_normalize($mac)
{
    return str_replace("-", ":", strtoupper($mac));
}

/**
 * [div_alert description]
 * @param  [type] $error_msg [description]
 * @return [type]            [description]
 */
function div_alert($error_msg)
{
    $ret = "";
    $s = trim($error_msg);
    if (!empty($s)) {
        $ret = "<div class=\"alert alert-danger my-1\">" . $s . "</div>";
    }

    return $ret;
}

/**
 * get the first IP and last IP from cidr (network id and mask length)
 * based on code from PHP Manual ip2long page written by admin@wudimei.com
 * @param string $cidr ip address/mask, e.g 56.15.0.6/16
 * @return array       array(0 =>"first IP of the network", 1=>"last IP of the network" );
 *                     each element of returned array"s type is string
 */
function ip_get_range($cidr) // e.q. "192.168.1.2/24
{
    list($ip_str, $net_mask_bitcount) = explode("/", $cidr);
    $net_mask = ~((1 << (32 - $net_mask_bitcount)) - 1);
    $net_mask_inv = ~$net_mask;
    $ip = ip2long($ip_str);
    $net_addr = $ip & $net_mask;
    $ip_start = $net_addr + 1; // ignore network ID (eg: 192.168.1.0)
    $ip_end = ($net_addr | $net_mask_inv) - 1; // ignore broadcast IP (eg: 192.168.1.255)
    return array(long2ip($ip_start), long2ip($ip_end));
}

/**
 * checks if given ip belongs to given network
 * based one code from PHP Manual ip2long page written by claudiu at cnixs dot com
 * @param  string $ip       ip address being checked, e.g. 172.16.1.239
 * @param  string $net_cidr network address with mask, e.g. 172.16.1.0/24
 * @return boolean          true if $ip belongs to $net_cidr, false otherwise
 */
function ip_in_range($ip, $net_cidr)
{
    list($net_addr, $net_mask_bitcount) = explode("/", $net_cidr);
    $net_mask = ~((1 << (32 - $net_mask_bitcount)) - 1);
    $net_net_addr = ip2long($net_addr) & $net_mask;
    $ip_net_addr = ip2long($ip) & $net_mask;
    return $net_net_addr == $ip_net_addr ? true : false;
}

/**
 * find the first IP from cidr (network id and mask length) that not exists in
 * given array
 * @param string $cidr        ip address/mask length e.g 172.16.1.0/24
 * @param array  $arr_ip_used array of IPs, that should be omitted during search,
 *                            array("172.16.1.1", "172.16.1.2", "172.16.1.4", ...)
 * @return string|false       returns found IP address or false if no ip address was found
 */
function ip_find_first_not_used($cidr, $arr_ip_used)
{
    if (empty($cidr))
        return false;

    $idx = array();
    if (!empty($arr_ip_used)) {
        foreach ($arr_ip_used as $ip)
            $idx[ip2long($ip)] = NULL;  // we need key only
    }

    list($ip_start, $ip_end) = ip_get_range($cidr);
    $start = ip2long($ip_start);
    $end = ip2long($ip_end);
    for ($i = $start; $i <= $end; $i++) {
        if (!array_key_exists($i, $idx))
            return long2ip($i);
    }
    return false;
}

/**
 * Returns true if user is logged in or false
 * @param mixed $session 
 * @return bool 
 */
function user_logged_in($session)
{
    if (isset($session->logged_in) && $session->logged_in === true)
        return true;
    else
        return false;
}
