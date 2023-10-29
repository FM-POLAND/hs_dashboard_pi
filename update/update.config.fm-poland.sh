echo "###-START-###"

echo "--- config download ---"
tagname=$(curl -sl https://api.github.com/repos/SP0DZ/hotspot.fm-poland.config/releases/latest | jq -r .tag_name)
zipball=$(curl -sl https://api.github.com/repos/SP0DZ/hotspot.fm-poland.config/releases/latest | jq -r .zipball_url)

cd /opt
rm src -R
mkdir src
cd src
echo "--- config download --"
wget $zipball
unzip *
mv SP0DZ-hotspot.fm-poland.config* config

echo "--- events - local - backup ---"
cp -R /usr/share/svxlink/events.d/local    /usr/share/svxlink/events.d/local.$(date +"%Y%m%dT%H%M%s")

echo "--- events - local - migration ---"
rm -R /usr/share/svxlink/events.d/local
mv /opt/src/config/events.d/local /usr/share/svxlink/events.d/local
chown svxlink -R /usr/share/svxlink

echo "--- config version update & cleanup ---"
echo $tagname > /opt/version.config 

cd /opt
rm -R /opt/src
echo "--- SVXlink service restart"
sudo service svxlink restart

echo "###-FINISH-####"
