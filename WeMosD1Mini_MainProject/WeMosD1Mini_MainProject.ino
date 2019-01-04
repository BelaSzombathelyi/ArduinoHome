/*
 * WeMos D1 MINI!!!
 * 
 * "DS18B20 Temperature"
 * Digital DS18B20 Temperature Measurement Module Detection Sensor Board for Arduino DC 5V Dupont Wire 18B20 Digital Signal
 * https://www.aliexpress.com/item/Digital-DS18B20-Temperature-Module-Detection-Sensor-Borad-for-Arduino-DC-5V-With-Dupont-Wire/32770146947.html?spm=a2g0s.9042311.0.0.uTQlZi
 * 
 * Need to download 'OneWire' && 'DallasTemperature'
 *   from Sketch/Include Library/Manage Libraries...
 * 
 * And WeMos D1 board -> 'esp8266'
 * https://wiki.wemos.cc/tutorials:get_started:get_started_in_arduino
 */

 //https://www.instructables.com/id/Programming-a-HTTP-Server-on-ESP-8266-12E/


#include "SerialConnection.h"
#include "TemperatureSensor.h"
#include "WiFiConnection.h"
#include "HTTPConnection.h"
#include "Arduino.h"

//#### GLOBALS ####

SerialConnection logger;
TemperatureSensor *temperatureSensor = NULL;
WiFiConnection wifi;

//#### SETUP ####

void setup() {
  logger.setup(9600);
  temperatureSensor = new TemperatureSensor(D2);
  for(uint8_t t = 4; t > 0; t--) {
    logger.logLine(String("[SETUP] WAIT ") + String(t, DEC));  
    delay(1000);
  }
  wifi.setup("SSID", "PW");
}

//#### LOOP ####

void loop() {
  float temperature = 0;
  bool error = false;
  temperatureSensor->readNewValue(temperature, error);
  if (error) {
    logger.logLine("ERROR: Can not read Temperature!");
    return;
  } else {
    logger.logLine(String("Temperature is: ") + String(temperature) + String(" C"));  
  }

  if (wifi.isConnected()) {
    HTTPConnection connection;
    String path = "/register_value.php?type=temperature&unit=C&value=";
    path += String(temperature);
    connection.connect("YOUR_HOST", 80, path);

    String response = connection.readResponse();
    if (response.indexOf("OK") != -1) {
      logger.logLine("Server response: is expected :-)");  
    } else {
      logger.logLine(String("[ERROR] Server response: '") + response + String("'"));
    }
  } else {
    logger.logLine("Try to connect to WiFi AP");
  }
  delay(3000);
}
