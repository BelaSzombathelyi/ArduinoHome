#include "Arduino.h"

#include <OneWire.h> 
#include <DallasTemperature.h>

//DS18B20 (digital temperature sensor)
// '-' = föld = GND
// '+' = táp = 5V
// 'out' = adat = valamelyik digitális port (DIGITAL PWM)

class TemperatureSensor {
  OneWire oneWire;
  DallasTemperature sensors;
public:
  TemperatureSensor(const TemperatureSensor&) = delete;
  TemperatureSensor& operator = (const TemperatureSensor&) = delete;

  TemperatureSensor(int digitalPin): oneWire(digitalPin), sensors(&oneWire) {
    sensors.begin();
  }

  void readNewValue(float &value, bool &hasError) {
    if (sensors.requestTemperaturesByIndex(0)) {
      value = sensors.getTempCByIndex(0);
      hasError = false;
    } else {
      hasError = true;
    }
  }
};
