#include "Arduino.h"

#include <ESP8266WiFi.h>
#include <ESP8266WiFiMulti.h>

class WiFiConnection {
  ESP8266WiFiMulti WiFiMulti;
  public:
  void setup(const String& ssid, const String& password) {
    WiFiMulti.addAP(ssid.c_str(), password.c_str());
  }
  bool isConnected() {
    return WiFiMulti.run() == WL_CONNECTED;
  }
};
