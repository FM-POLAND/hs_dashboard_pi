# hs_dashboard_pi
HotSpot dashboard repository inspired by pi star dashboard


(re) installation script

```
cd /var/www
cp -R html html.$(date +"%Y%m%dT%H%M%s")
wget https://github.com/FM-POLAND/hs_dashboard_pi/archive/refs/heads/main.zip
unzip main.zip
rm main.zip
rm -R html
mv hs_dashboard_pi-main html
chown svxlink -R html
```

Some functions of network mangement or configuration file changes can be limitted by user permitions.
For full functionality use this code: 

```
sudo usermod -aG sudo svxlink
```

To activate mDNS (host.local) use:
```
 sudo apt-get install avahi-daemon avahi-utils
```

RF configurator is based on sa818 programming library (https://github.com/0x9900/SA818)
```
sudo apt install python3
sudo apt install python3-pip
sudo pip3 install sa818
```


BUG warning!
Wrapper has a bug. Any section needs to contain not only numbers parameters (like ex. MACROS).
To avoid bug:
Please add comment line with non numeric parameter like:
```
# one = 1
```
in INI files (svxlink.conf and TetraLogic.conf). 

The svxlink dashboard created by SP2ONG, SP0DZ

and some ideas from:

https://github.com/ea1jay/tetrasvxdashboard

https://github.com/kc1awv/SvxLink-Dashboard



