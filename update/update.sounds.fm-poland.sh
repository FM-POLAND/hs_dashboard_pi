echo "###-START-###"
echo "--- sounds download ---"
tagname=$(curl -sl https://api.github.com/repos/SP0DZ/hotspot.fm-poland.sounds/releases/latest | jq -r .tag_name)
zipball=$(curl -sl https://api.github.com/repos/SP0DZ/hotspot.fm-poland.sounds/releases/latest | jq -r .zipball_url)
cd /opt
rm src -R
mkdir src
cd src
echo "--- sounds download --"
wget $zipball
unzip *
mv SP0DZ-hotspot.fm-poland.sounds* sounds
echo "--- sounds backup ---"
cp -R /usr/share/svxlink/sounds /usr/share/svxlink/sounds.$(date +"%Y%m%dT%H%M%s")
echo "--- sounds migration ---"
rm -R /usr/share/svxlink/sounds
mv /opt/src/sounds /usr/share/svxlink/sounds
chown svxlink -R /usr/share/svxlink
echo "--- sounds version update & cleanup ---"
echo $tagname > /opt/version.sounds
cd /opt
rm -R /opt/src
echo "--- SVXlink service restart"
sudo service svxlink restart
echo "###-FINISH-####"
