#include "Arduino.h"

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