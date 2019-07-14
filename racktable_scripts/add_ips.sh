#!/bin/bash

#Script to add ips to racktables. list must be in "<host> <ip>" format in file.


menu_line(){
echo "================================================="
}

menu(){
menu_line
echo "|         Racktables Helper v1.0                |"
menu_line
echo ""
read -p "Please enter the name of the file with hostnames and ips.: " iplist
echo ""
read -p  "Would you like to assign an interface? Answering no will assign p3p1. (Y/N): " choice

case $choice in
    y|Y)
         read -p  "Please enter interface name: " if_name
      ;;
    n|N)
      ;;
      *)
        echo "Please answer Yy or nN"
      ;;
esac

}



#configure starting vars.
source cred_file
mysql="mysql -u ${usr} -D <db_name> -B -N"

if [ -z ${if_name}  ]; then
    if_name="p3p1"
fi

#I stole this but it works to convert IPs to decimal
convert_ip(){
ip=$1; ip_num=0
for (( i=0 ; i<4 ; ++i )); do
((ip_num+=${ip%%.*}*$((256**$((3-${i}))))))
ip=${ip#*.}
done
ip_converted=$ip_num
}

#main loop
assign_ips() {

for i in $(awk '{print $2}' ${iplist}); do

    host=$(grep ${i} ${iplist} | awk '{print $1}')
    object_id=$(${mysql} -e "select id from Object where name='${host}'")
    if_name_exists=$(${mysql} -e "select name from IPv4Allocation where object_id = ${object_id}")
    if [ "${if_name}" == "${if_name_exists}" ]; then
        echo "WARNING! - Interface name exists. Please choose a diffferent interface name. Exiting..."
        exit 0
    fi

    if [ -z ${object_id} ]; then
        echo "WARNING! No object id was found for Host=${host} IP=${ip}. Please make sure the hostnames match."
    else
        convert_ip ${i}
        #assing ips to objects
        ${mysql} -e "insert into IPv4Allocation(object_id,ip,name,type) values(${object_id},${ip_converted},'${if_name}','regular')"
        #remove reservations, set names and comment so we know the script was used to set these ips.
        ${mysql} -e "replace into IPv4Address(ip,name,comment,reserved) values(${ip_converted},'${host}','set by script','no')"
    fi

done
echo ""
echo "Complete!!"
}

menu
assign_ips
