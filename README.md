racktables
==========
this is just a standard racktables installations with custom totaling fields for rows, racks, and server chassis. Will also total resourses based on tag. 

Totals include: 

-Memory 

-Storage

-CPU cores.

Also has adjusted color scheme for racks and binary images for easier equipment identification.  

- Server chassis = Light blue 
- Network Switch = Green 
- Servers and nodes = Dark blue 
- free space = gray


secret.php has been removed. you will need to complete the standard racktables install first. 

Then over write all files with this project. 

Additional functionality is added with the plugin hardwareFailure.php and hardwareFailureReportSummary.php. Requires report system plugin located here: http://www.mogilowski.net/lang/en-us/projects/racktables/

There is a racktable scripts folder that contains two bash scripts to easily add and assign ips to hosts and add hosts to racks: 

- add_ips.sh is a menu driven script that will take a list of hosts and ips and assign them to the proper objects in racktables. 

- add_hosts.sh will add a 4U compute chassis with 8 nodes inside to each rack and lay out the nodes inside the chassis accordingly. 
