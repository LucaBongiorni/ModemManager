registerController('USBController', ['$api', '$scope', function($api, $scope) {
    $scope.lsusb = "";
    $scope.availableTTYs = "";

    $api.request({
        module: 'ModemManager',
        action: 'getUSB'
    }, function(response) {
        if (response.lsusb) {
            $scope.lsusb = response.lsusb;
        }
    });

    $api.request({
        module: 'ModemManager',
        action: 'getTTYs'
    }, function(response) {
        if (response.success) {
            $scope.availableTTYs = response.availableTTYs;
        } else {
            $scope.availableTTYs = "No TTYs Found.";
        }
    });
}]);

registerController('ModemController', ['$api', '$scope', '$timeout', function($api, $scope, $timeout) {
    $scope.savedConfiguration = false;
    $scope.resetConfigurationSuccess = false;
    $scope.saveConfigurationError = "";
    $scope.loadConfigurationError = "";
    $scope.resetConfigurationError = "";

    $scope.loadConfiguration = (function() {
        $api.request({
            module: 'ModemManager',
            action: 'loadConfiguration'
        }, function(response) {
            if (response.success === true) {
                $scope.interface = response.interface;
                $scope.protocol = response.protocol;
                $scope.service = response.service;
                $scope.device = response.device;
                $scope.apn = response.apn;
                $scope.username = response.username;
                $scope.password = response.password;
                $scope.dns = response.dns;
                $scope.peerdns = response.peerdns;
                $scope.pppredial = response.pppredial;
                $scope.defaultroute = response.defaultroute;
                $scope.keepalive = response.keepalive;
                $scope.pppdoptions = response.pppdoptions;
            } else {
                $scope.loadConfigurationError = "Failed to load configuration.";
            }
        });
    });

    $scope.saveConfiguration = (function() {
        $api.request({
            module: 'ModemManager',
            action: 'saveConfiguration',
            interface: $scope.interface,
            protocol: $scope.protocol,
            service: $scope.service,
            device: $scope.device,
            apn: $scope.apn,
            username: $scope.username,
            password: $scope.password,
            dns: $scope.dns,
            peerdns: $scope.peerdns,
            pppredial: $scope.pppredial,
            defaultroute: $scope.defaultroute,
            keepalive: $scope.keepalive,
            pppdoptions: $scope.pppdoptions
        }, function(response) {
            if (response.success === true) {
                $scope.savedConfiguration = true;
                $timeout(function(){
                    $scope.savedConfiguration = false;
                }, 2000);
            } else {
                $scope.saveConfigurationError = "Failed to save configuration.";
            }
        });
    });

    $scope.resetConfiguration = (function() {
        $api.request({
            module: 'ModemManager',
            action: 'resetConfiguration'
        }, function(response) {
            if (response.success === true) {
                $scope.resetConfigurationSuccess = true;
                $scope.loadConfiguration();
                $timeout(function(){
                    $scope.resetConfigurationSuccess = false;
                }, 2000);
            } else {
                $scope.resetConfigurationError = "Failed to open configuration.";
            }
        });
    });

    $scope.loadConfiguration();
}]);
