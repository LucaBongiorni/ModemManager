<?php namespace pineapple;

/***
Modem Manager <api/module.php>
Written by Foxtrot <foxtrot@realloc.me>
Distributed under the MIT Licence <https://opensource.org/licenses/MIT>
***/

class ModemManager extends Module
{
    public function route()
    {
        switch ($this->request->action) {
            case 'getUSB':
                $this->getUSB();
                break;

            case 'getTTYs':
                $this->getTTYs();
                break;

            case 'checkConnection':
                $this->checkConnection();
                break;

            case 'setConnection':
                $this->setConnection();
                break;

            case 'unsetConnection':
                $this->unsetConnection();
                break;

            case 'loadConfiguration':
                $this->loadConfiguration();
                break;

            case 'saveConfiguration':
                $this->saveConfiguration();
                break;

            case 'resetConfiguration':
                $this->resetConfiguration();
                break;
        }
    }

    private function getUSB()
    {
        /* Execute 'lsusb' and capture its output in the $lsusb variable.
           Then split the output by its newlines. */
        exec('lsusb', $lsusb);
        $lsusb = implode("\n", $lsusb);

        $this->response = array('lsusb' => $lsusb);
    }

    private function getTTYs()
    {
        exec('ls /dev/ttyUSB*', $TTYs);

        if (empty($TTYs)) {
            $this->response = array('success' => false,
                                    'availableTTYs' => false);
        } else {
            $TTYs = implode("\n", $TTYs);
            $this->response = array('success' => true,
                                    'availableTTYs' => $TTYs);
        }
    }

    private function checkConnection()
    {
        /* Check the connection of the wan2 interface. */
        exec('ifconfig wan2', $connectionStatus);
    }

    private function setConnection()
    {
        /* Set the connection of the wan2 interface. */
        $this->execBackground('ifup wan2');
    }

    private function unsetConnection()
    {
        /* Unset the connection of the wan2 interface. */
        $this->execBackground('ifconfig wan2 down');
    }

    private function loadConfiguration()
    {
        /* For easier code reading, assign a variable for each bit of information we require from the system.
           Read more about UCI at https://wiki.openwrt.org/doc/uci.
           For more information about the WiFi Pineapple API, visit https://wiki.wifipineapple.com. */
        $interface     = $this->uciGet('network.wan2.ifname');
        $protocol      = $this->uciGet('network.wan2.proto');
        $service       = $this->uciGet('network.wan2.service');
        $device        = $this->uciGet('network.wan2.device');
        $apn           = $this->uciGet('network.wan2.apn');
        $username      = $this->uciGet('network.wan2.username');
        $password      = $this->uciGet('network.wan2.password');
        $dns           = $this->uciGet('network.wan2.dns');
        $peerdns       = $this->uciGet('network.wan2.peerdns');
        $pppredial     = $this->uciGet('network.wan2.ppp_redial');
        $defaultroute  = $this->uciGet('network.wan2.defaultroute');
        $keepalive     = $this->uciGet('network.wan2.keepalive');
        $pppdoptions   = $this->uciGet('network.wan2.pppd_options');

        /* Now send a response inside of an array, with keys being 'interface', 'protocol' etc
           and their values being those we obtained from uciGet(). */
        $this->response = array('success'      => true,
                                'interface'    => $interface,
                                'protocol'     => $protocol,
                                'service'      => $service,
                                'device'       => $device,
                                'apn'          => $apn,
                                'username'     => $username,
                                'password'     => $password,
                                'dns'          => $dns,
                                'peerdns'      => $peerdns,
                                'pppredial'    => $pppredial,
                                'defaultroute' => $defaultroute,
                                'keepalive'    => $keepalive,
                                'pppdoptions'  => $pppdoptions);
    }

    private function saveConfiguration()
    {
        /* In the same way as loadConfiguration(), get the desired information and assign it to a variable.
           However this time get the that was sent with the request from the JS. */
        $interface     = $this->request->interface;
        $protocol      = $this->request->protocol;
        $service       = $this->request->service;
        $device        = $this->request->device;
        $apn           = $this->request->apn;
        $username      = $this->request->username;
        $password      = $this->request->password;
        $dns           = $this->request->dns;
        $peerdns       = $this->request->peerdns;
        $pppredial     = $this->request->pppredial;
        $defaultroute  = $this->request->defaultroute;
        $keepalive     = $this->request->keepalive;
        $pppdoptions   = $this->request->pppdoptions;

        /* Using the APIs uciSet() function, set the UCI properties to
           what the JS request gave us. */
        $this->uciSet('network.wan2',              'interface');
        $this->uciSet('network.wan2.ifname',       $interface);
        $this->uciSet('network.wan2.proto',        $protocol);
        $this->uciSet('network.wan2.service',      $service);
        $this->uciSet('network.wan2.device',       $device);
        $this->uciSet('network.wan2.apn',          $apn);
        $this->uciSet('network.wan2.username',     $username);
        $this->uciSet('network.wan2.password',     $password);
        $this->uciSet('network.wan2.dns',          $dns);
        $this->uciSet('network.wan2.peerdns',      $peerdns);
        $this->uciSet('network.wan2.ppp_redial',   $pppredial);
        $this->uciSet('network.wan2.defaultroute', $defaultroute);
        $this->uciSet('network.wan2.keepalive',    $keepalive);
        $this->uciSet('network.wan2.pppd_options', $pppdoptions);

        $this->response = array('success' => true);
    }

    private function resetConfiguration()
    {
        /* Delete the network.wan2 section,
           Set a new network.wan2 section and set all the required fields empty. */
        exec('uci del network.wan2');
        $this->uciSet('network.wan2',              'interface');
        $this->uciSet('network.wan2.ifname',       '');
        $this->uciSet('network.wan2.proto',        '');
        $this->uciSet('network.wan2.service',      '');
        $this->uciSet('network.wan2.device',       '');
        $this->uciSet('network.wan2.apn',          '');
        $this->uciSet('network.wan2.username',     '');
        $this->uciSet('network.wan2.password',     '');
        $this->uciSet('network.wan2.dns',          '');
        $this->uciSet('network.wan2.peerdns',      '');
        $this->uciSet('network.wan2.ppp_redial',   '');
        $this->uciSet('network.wan2.defaultroute', '');
        $this->uciSet('network.wan2.keepalive',    '');
        $this->uciSet('network.wan2.pppd_options', '');

        $this->response = array('success' => true);
    }
}
