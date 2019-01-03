#include <OneWire.h>
#include <DallasTemperature.h>

// Ez lehet, hogy megoldja a WeMos D1 R2-es problémát
//https://community.blynk.cc/t/wemos-esp8266-standalone-d1-r2-ds18b20/21052/6 
//DeviceAddress insideThermometer = {0x28, 0xFF, 0x7D, 0x8B, 0xB5, 0x16, 0x05, 0x74  };;
//sensors.setResolution(insideThermometer, 9);

 
OneWire oneWire(D2); 
DallasTemperature sensors(&oneWire);
 
void setup() {
  Serial.begin(9600);
  sensors.begin();
  Serial.println(String("device count: ") + sensors.getDeviceCount());
}

void loop() {
  delay(1000);
  sensors.requestTemperatures();
  Serial.println(String("Temperature: ") +
      String(sensors.getTempCByIndex(0)) + String(" C"));
}
