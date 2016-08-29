import xml.etree.cElementTree as ET
import urllib2
import json

lastLat = ""
lastLng = ""
#"http://172.16.255.195/   54"

def getPokemonLocation():
	try:
		response = urllib2.urlopen("http://192.168.1.4/", timeout = 1)
		return json.load(response)
	except urllib2.URLError as e:
		print e.reason

def generateXML():
	global lastLat, lastLng
	geo = getPokemonLocation()
	if geo != None:
		if geo["lat"] != lastLat or geo["lng"] != lastLng:
			lastLat = geo["lat"]
			lastLng = geo["lng"]
			gpx = ET.Element("gpx", version="1.1", creator="Xcode")
			wpt = ET.SubElement(gpx, "wpt", lat=geo["lat"], lon=geo["lng"])
			ET.SubElement(wpt, "name").text = "PokemonLocation"
			ET.ElementTree(gpx).write("pokemonLocation.gpx")
			print "Location Updated!", "latitude:", geo["lat"], "longitude:" ,geo["lng"]

def start():
	while True:
		generateXML()

start()