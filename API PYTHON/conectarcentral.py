import psutil
import platform
import requests
from time import sleep
from datetime import datetime
import os
import subprocess
import sys
from getmac import get_mac_address as gma
cpu = "Default"
error = "OK"
ram_total = 0
cpu_percent = 0
red = ""
def get_size(bytes, suffix="B"):
    factor = 1024
    for unit in ["", "K", "M", "G", "T", "P"]:
        if bytes < factor:
            return f"{bytes:.2f}{unit}{suffix}"
        bytes /= factor
try:
    tokenread = open("token.txt")
    texto = tokenread.read()
    tokenread.close()
except:
    texto = ''
try:
    hostread = open("host_web.txt")
    host = hostread.read()
    hostread.close()
except:
    host = ''
    print("no se ha encontrado host.txt")
print("host en memoria: "+host)
print("token en memoria: "+texto)
if(len(host)) == 0:
    print("Introduzca la direccion del servidor:")
    hoststr = input()
    host = open("host_web.txt", "w")
    host.write(hoststr)
    host.close()
    print('Host "' + hoststr + '" se ha guardado')
else:
    hoststr = host
    print("usando host: " + hoststr)
if(len(texto)) == 0:
    print("Introduzca el token API:")
    apitoken = input()
    token = open("token.txt", "w")
    token.write(apitoken)
    token.close()
    print('token "' + apitoken + '" se ha guardado')
else:
    apitoken = texto
    print("usando token: " + apitoken)
print(gma())
consola = ""
while True:
    mac = gma()
    disco_total = 0
    red = ''
    output = ''
    uname = platform.uname()
    cpu = uname.processor
    cpufreq = psutil.cpu_freq()
    cpu_percent = psutil.cpu_percent()
    svmem = psutil.virtual_memory()
    ram_total = get_size(svmem.total)
    partitions = psutil.disk_partitions()
    for partition in partitions:
        try:
            partition_usage = psutil.disk_usage(partition.mountpoint)
            disco_total += int(partition_usage.total)
        except:
            disco_total += 0
    if_addrs = psutil.net_if_addrs()
    for interface_name, interface_addresses in if_addrs.items():
        for address in interface_addresses:
            if str(address.family) == 'AddressFamily.AF_INET':
                red = red + str(interface_name) + '::' + str(address.address) + '::' + str(address.netmask) + '::' + str(address.broadcast) + ';'
    disco_total = get_size(disco_total)
    url = "https://"+hoststr+"/api.php"
    myobj = {
        'tipo': '0',
        'token' : apitoken,
        'cpu' : cpu,
        'cpupercent' : disco_total,
        'ramtotal' : ram_total,
        'discototal' : disco_total,
        'red' : red,
        'mac' : mac,
        'consola' : consola
            }
    x = requests.post(url, data = myobj)
    texto = x.text.split(';')
    lines = 0
    for line in texto:
        if(line == 'apagar'):
            os.system('pmset sleepnow')
            output = os.popen('shutdown /s').read()
            print('Suspendiendo...')
        if 'ping' in line:
            comando = line.split(': ')
            hostname = comando[1].split(',')
            if consola != '':
                consola = ''
            for ip in hostname:
                if ip != '':
                    print("Pingeando a " + ip + "\r")
                    response = sp.getoutput("ping " + ip)
                    print(response)
                    if response == 0:
                        consola += ip + ":si,"
                        print(ip + " conectado!\r")
                    else:
                        print(ip + " sin respuesta\r")
        print(line , sep='',end ='\r')
    
    sleep(3)