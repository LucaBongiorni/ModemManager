# Modem Manager
This is a module for the WiFi Pineapple intended to manage cellular modems.

## In Development
~~This module does not work yet. Features are missing, and one major problem is that I no longer have access to a 3G/4G modem.~~
Scratch that, the module _kind of_ works. It will dial on serial modems currently (the majority of modems, i think). I also got a modem.
Features are still missing, but they are well on their way.

As such, It's hard for me to test if this module even _actually_ works. I think it should atleast dial in, but who knows.

If you have a modem, testing would be greatly appreciated! If you have any problems or want to give feedback, please use the
[forum thread](https://forums.hak5.org/index.php?/topic/38593-official-modem-manager/) over on the Hak5 Forums.

## Notes
After saving the configuration, you currently will need to reboot the pineapple to load the kernel module with the right VID/PID. Currently I am unable to remove and re-insert the module. You will then need to execute "ifup wan2" to dial the modem. Be patient, could take a second or two.

## To do
- Add CDC support
- Some info about the connection
- Fix the connect button
- Eliminate rebooting

## Installation
As the module isn't yet available, you need to install it manually. To do so:

1. Download the "master" branch of this module.
2. Create the folder `/pineapple/modules/ModemManager/` on the WiFi Pineapple.
3. Extract the downloaded `master.zip` into the `/pineapple/modules/ModemManager/` directory.
4. Refresh the WiFi Pineapple UI to find the module.