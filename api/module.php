<?php namespace pineapple;

/***
Modem Manager <api/module.php>
Written by Foxtrot <foxtrotnull@gmail.com>
Distributed under the MIT Licence <https://opensource.org/licenses/MIT>

This PHP file handles the calls to the system recieved from the AngularJS (js/module.js)
and will send data to the JS.
***/

class ModemManager extends Module
{
    public function route()
    {
        switch ($this->request->action) {
            /* If the requested action from the JS is 'getUSB', run the getUSB() function
               Repeat for each case... */
            case 'getUSB':
                $this->getUSB();
                break;

            case 'getTTYs':
                $this->getTTYs();
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

        /* Send the response 'lsusb' back to JS with the variable $lsusb as content. */
        $this->response = array('lsusb' => $lsusb);
    }

    private function getTTYs()
    {
        /* Get the listing of /dev/ttyUSB* and store it as %TTYs. */
        exec('ls /dev/ttyUSB*', $TTYs);

        /* If the variable $TTYs is empty, send back a response:
           'success' as false, and 'availableTTYs' as false.

           Else send 'success' as true, and 'availableTTYs' as the content of $TTYs */
        if (empty($TTYs)) {
            $this->response = array('success' => false,
                                    'availableTTYs' => false);
        } else {
            $TTYs = implode("\n", $TTYs);
            $this->response = array('success' => true,
                                    'availableTTYs' => $TTYs);
        }
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

        $this->response = array('success' => true);
    }

    private function resetConfiguration()
    {
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

        $this->response = array('success' => true);
    }
}
