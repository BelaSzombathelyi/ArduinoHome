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


#include <ESP8266HTTPClient.h>

//#### GLOBALS ####

SerialConnection logger;
TemperatureSensor *temperatureSensor = NULL;

//#### SETUP ####

void setup() {
  logger.setup(9600);
  temperatureSensor = new TemperatureSensor(D2);
}

//#### LOOP ####

void loop() {
  delay(500);
  
  float temperature = 0;
  bool error = false;
  temperatureSensor->readNewValue(temperature, error);
  if (!error) {
    logger.logLine(String("Temperature is: ") + String(temperature) + String(" C"));  
  } else {
    logger.logLine("ERROR: Can not read Temperature!");
  }
}
