#!/bin/bash
####
#Author: Jeff Mazzone
#Script to quickly addd compute nodes to racktables. Currently only works with 4U compute chassis
#Be sure to change the for loop to the number of compute nodes you have!!
###
mysqlpw=""
mod=9999
atom="front"
unit_no=0
rack_id=$1
object_id=$2

if [ -z $1 ]; then
        echo "Script requires a Rack ID and Starting Object ID."
        echo "example: ./add_hosts.sh 978 1002"
        exit 0
fi

for i in {1..56}; do
case $atom in
                "front")
                        if [ $mod -eq 0 ]; then
                                atom="rear"
                                unit_no=$((unit_no-4))
                        fi
                        ;;
                "rear")
                        if [ $mod -eq 0 ]; then
                                atom="front"
                        fi
                        ;;
                *)
                ;;
esac

unit_no=$((unit_no+1))
#echo $object_id "-" $unit_no "-" $atom
#echo "mysql -u root -p${mysqlpw} --skip-column-names <db_name> -e 'insert into RackSpace(rack_id, unit_no, atom, state, object_id) values ('$rack_id','$unit_no','$atom','T','$object_id')'"
mysql -u root -p${msqlpw} --skip-column-names <db_name> -e "insert into RackSpace(rack_id, unit_no, atom, state, object_id) values ('$rack_id','$unit_no','$atom','T','$object_id')"
mod=$(($i%4))
object_id=$((object_id+1))

done
