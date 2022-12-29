#!/bin/bash

ip=$(ec2-metadata -v | awk '{print $2}')

echo $ip
#aws ec2 modify-security-group-rules --group-id sg-05d60a27767105b58  --security-group-rules SecurityGroupRuleId=sgr-03f3344d3fe370e00,SecurityGroupRule='{Description='ansible-ip',IpProtocol=-1,CidrIpv4='$ip'/32}'
