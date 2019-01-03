#include "Arduino.h"

class DigitalLed {
private:
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