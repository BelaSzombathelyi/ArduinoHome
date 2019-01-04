#include "Arduino.h"

#include <ESP8266HTTPClient.h>

class HTTPConnection {
  HTTPClient httpClient;
  public:
  void connect(const String& domain, int port, const String& path) {
    httpClient.begin(domain.c_str(), port, path.c_str());
  }
  String readResponse() {
    String response;

    int httpCode = httpClient.GET();
    if (httpCode != HTTP_CODE_OK) {
      return "[LocalError] Http code";
    }
    // get lenght of document (is -1 when Server sends no Content-Length header)
    int len = httpClient.getSize();

    // create buffer for read

    const size_t buffSize = 128;
    
    uint8_t buff[buffSize + 1] = { 0 };

    // get tcp stream
    WiFiClient * stream = httpClient.getStreamPtr();

    // read all data from server
    while(httpClient.connected() && (len > 0 || len == -1)) {
      // get available data size
      size_t size = stream->available();

      if(size) {
        // read up to 128 byte
        int c = stream->readBytes(buff, ((size > buffSize) ? buffSize : size));

        buff[c] = '\0';     
        response += String((char *)buff);
        if(len > 0) {
          len -= c;
        }
      }
      delay(1);
    }
    return response;
  }

  void close() {
    httpClient.end();
  }
};
