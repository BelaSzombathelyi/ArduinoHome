/*
 * "Arduino UNO R3"
 *  for Arduino (with LOGO) MEGA328P ATMEGA16U2 1PCS UNO R3
 * https://www.aliexpress.com/item/high-quality-One-set-UNO-R3-MEGA328P-CH340G-For-Arduino-UNO-R3-Original-Chip-16Mhz/32833268761.html?spm=a2g0s.9042311.0.0.uTQlZi
 * 
 * "DS18B20 Temperature"
 * Digital DS18B20 Temperature Measurement Module Detection Sensor Board for Arduino DC 5V Dupont Wire 18B20 Digital Signal
 * https://www.aliexpress.com/item/Digital-DS18B20-Temperature-Module-Detection-Sensor-Borad-for-Arduino-DC-5V-With-Dupont-Wire/32770146947.html?spm=a2g0s.9042311.0.0.uTQlZi
 * 
 * Need to download 'OneWire' && 'DallasTemperature'
 *   from Sketch/Include Library/Manage Libraries...
 */

//#### DigitalLed ####

class DigitalLed {
  int pin;
  public:
 
  void setup(int pin) {
    this->pin = pin;
    pinMode(pin, OUTPUT);
  }

  void setValue(int value) {
    digitalWrite(pin, value);
  }
};


//#### SerialConnection ####

class SerialConnection {
  public:
  
  void setup(int boud) {
    Serial.begin(boud);
  }

  void write(const String& str) {
    int bytesSent = Serial.write(str.c_str());
    Serial.flush();
  }

  void logLine(String str) {
    unsigned long time = millis();
    char timeStr[20];
    sprintf(timeStr, "%08lu", time);
    String result = String("[") + String(timeStr) + String("] ") + str + String('\n');
    this->write(result);
  }
};

//#### TemperatureSensor ####

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
  TemperatureSensor(int digitalPin): oneWire(digitalPin), sensors(&oneWire) {
    sensors.begin(); 
  }
  
  float readNewValue() {
    sensors.requestTemperatures();
    return sensors.getTempCByIndex(0);
  }
};

//#### GLOBALS ####

DigitalLed digitalLed;
SerialConnection usbSerial;
TemperatureSensor temperatureSensor(2);

//#### SETUP ####

void setup() {
  digitalLed.setup(LED_BUILTIN);
  usbSerial.setup(9600);
}

//#### LOOP ####

void loop() {
  float temperature = temperatureSensor.readNewValue();
  usbSerial.logLine(String("Temperature is: ") + String(temperature) + String(" C"));
  digitalLed.setValue(HIGH);
  delay(500);
  digitalLed.setValue(LOW);
  delay(200);
}
