# hs_dashboard_pi
HotSpot dashboard repository inspired by pi star dashboard


(re) innstallation script

```
cd /var/www
cp -R html html.$(date +"%Y%m%dT%H%M%s")
wget https://github.com/FM-POLAND/hs_dashboard_pi/archive/refs/heads/main.zip
unzip main.zip
rm main.zip
rm -R html
mv hs_dashboard_pi-main html
chown svxlink -r html
```

Some functions of main.ripnet can be limitted by user permitions.
For full functionality use this code: 

```
sudo usermod -aG sudo svxlink
```

To activate mDNS (host.local) use:
```
 sudo apt-get install avahi-daemon avahi-utils
```



The svxlink dashboard created by SP2ONG, SP0DZ

and some ideas from:

https://github.com/ea1jay/tetrasvxdashboard

https://github.com/kc1awv/SvxLink-Dashboard



