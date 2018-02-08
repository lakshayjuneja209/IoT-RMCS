#include <ESP8266WiFi.h>
#include "DHT.h"

//static const uint8_t D0   = 16;
//static const uint8_t D1   = 5;
//static const uint8_t D2   = 4;
//static const uint8_t D3   = 0;
//static const uint8_t D4   = 2;
//static const uint8_t D5   = 14;
//static const uint8_t D6   = 12;
//static const uint8_t D7   = 13;
//static const uint8_t D8   = 15;
//static const uint8_t D9   = 3;
//static const uint8_t D10  = 1;

char* ssid     = "<SSID>"; //Replace <SSID> with your wifi's ssid 
char* password = "<PASS>"; //Replace <PASS> with your wifi's password

const char* host = "<HOST>"; //Replace <HOST> with the address where 'webPortal' is hosted

const int dataSize = 20;
const int gasPin = A0;
#define DHTPIN 14
struct dcw {
  float t_c;
  float hu;
  float smoke;
} data[dataSize];

#define DHTTYPE DHT11
DHT dht(DHTPIN, DHTTYPE);

// Data coming from Bluetooth
String dataFromBT;

int i = -1;
void getData() {
  // while(1) {
  float t_f = dht.readTemperature(true);  //read temperature in Fahrenheit
  i++;
  if (i >= dataSize) {
    for (int j = 0; j < dataSize - 1; j++) {
      data[j] = data[j + 1];
    }
    i = dataSize - 1;
  }
  data[i].hu = dht.readHumidity();
  data[i].t_c = (t_f - 32) / 1.8; // convert temperature to centigrade
  float smokeR[8];
  float smokeSum = 0;
  for(int j=0;j<8;j++){
    smokeR[j] = analogRead(gasPin);
    smokeSum = smokeSum + smokeR[j];
  }  
  data[i].smoke = ((smokeSum/8)/1024)*5;
  //    if (!isnan(t_f) && !isnan(data[i].hu)) {
  //      break;
  //    }
  Serial.println(data[i].t_c);
  Serial.println(data[i].hu);
  Serial.println(data[i].smoke);
  //  }
}

void connectWiFi(){ 
  Serial.print("Connecting to ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
}

const int d1 = 5;
const int d2 = 4;
const int d3 = 0;
const int d4 = 13;

void setup() {
  Serial.begin(9600);
  delay(100);
  dht.begin();
  delay(10);
  pinMode(d1, OUTPUT);
  pinMode(d2, OUTPUT);
  pinMode(d3, OUTPUT);
  pinMode(d4, OUTPUT);
  digitalWrite(d1, LOW);
  digitalWrite(d2, LOW);
  digitalWrite(d3, LOW);
  digitalWrite(d4, LOW);

  // We start by connecting to a WiFi network

  Serial.println();
  Serial.println();
  connectWiFi();
}

float current_temp = 0;

int value = 0;

void loop() {
  //Check if new Wifi ssid and password are availible
  if (Serial.available() >0){ 
    dataFromBT = Serial.readString();
    int pos = dataFromBT.indexOf("(!@#$%^&*)");                                                         //parsing
    (dataFromBT.substring(0,pos)).toCharArray(ssid, pos+1);                                             //ssid and
    (dataFromBT.substring(pos+9,dataFromBT.length())).toCharArray(password, dataFromBT.length()-pos-8); //password from the string sent by android app
    connectWiFi();
  }
  delay(1500);
  int d1_db, d2_db, d3_db, d4_db;//values of device status in database
  ++value;

  Serial.print("connecting to ");
  Serial.println(host);

  // Use WiFiClient class to create TCP connections
  WiFiClient client;
  const int httpPort = 80;
  if (!client.connect(host, httpPort)) {
    Serial.println("connection failed");
    return;
  }
  if (value % 20 == 5) {
    getData();
    current_temp = data[i].t_c;
    // We now create a URI for the request
    String url = "/dcwm/write_data.php?temp1=";
    url = url + data[i].t_c + "&humid1=" + data[i].hu + "&smoke1=" + data[i].smoke;
    Serial.print("Requesting URL: ");
    Serial.println(url);

    // This will send the request to the server
    client.print(String("GET ") + url + " HTTP/1.1\r\n" +
                 "Host: " + host + "\r\n" +
                 "Connection: close\r\n\r\n");
    delay(500);

    // Read all the lines of the reply from server and print them to Serial
    while (client.available()) {
      String line = client.readStringUntil('\r');
      Serial.print(line);
    }
  }
  if(current_temp<=50){
    //calling php script which'll return the status to be set
    String url2 = "/dcwm/device_status.php";
    Serial.print("Requesting URL: ");
    Serial.println(url2);
    client.print(String("GET ") + url2 + " HTTP/1.1\r\n" +
                 "Host: " + host + "\r\n" +
                 "Connection: close\r\n\r\n");
    delay(500);
    while (client.available()) {
      String line2 = client.readStringUntil('\r');
      Serial.println(line2);
      //    Serial.println(line2.indexOf("Device"));
      //    Serial.println(line2.indexOf("Device 2 is on"));
      //    Serial.println(line2.indexOf("Device 2 is off"));
      if (!(line2.indexOf("Device"))) {
        continue;
      }
      if (line2.indexOf("Device 1 is on") >= 0) {
        digitalWrite(d1, HIGH);
        d1_db = 1;
      }
      else if (line2.indexOf("Device 1 is off") >= 0) {
        digitalWrite(d1, LOW);
        d1_db = 0;
      }
      if (line2.indexOf("Device 2 is on") >= 0) {
        digitalWrite(d2, HIGH);
        d2_db = 1;
      }
      else if (line2.indexOf("Device 2 is off") >= 0) {
        digitalWrite(d2, LOW);
        d2_db = 0;
      }
      if (line2.indexOf("Device 3 is on") >= 0) {
        digitalWrite(d3, HIGH);
        d3_db = 1;
      }
      else if (line2.indexOf("Device 3 is off") >= 0) {
        digitalWrite(d3, LOW);
        d3_db = 0;
      }
      if (line2.indexOf("Device 4 is on") >= 0) {
        digitalWrite(d4, HIGH);
        d4_db = 1;
      }
      else if (line2.indexOf("Device 4 is off") >= 0) {
        digitalWrite(d4, LOW);
        d4_db = 0;
      }
    }
  }
  else{
    digitalWrite(d1, LOW);
    d1_db = 0;
    changeDeviceStatusInDB(1, 0, client);
    
    digitalWrite(d1, LOW);
    d2_db = 0;
    changeDeviceStatusInDB(2, 0, client);
    
    digitalWrite(d3, LOW);
    d3_db = 0;
    changeDeviceStatusInDB(3, 0, client);
    
    digitalWrite(d4, LOW);
    d4_db = 0;
    changeDeviceStatusInDB(4, 0, client);
  }
  //Checking for discrepencies in the status value in db with respect to actual state of relay
  if (digitalRead(d1)==HIGH && d1_db==0)
    changeDeviceStatusInDB(1, 0, client);
  else if (digitalRead(d1)==LOW && d1_db==1)
    changeDeviceStatusInDB(1, 1, client);

  if (digitalRead(d2)==HIGH && d2_db==0)
    changeDeviceStatusInDB(2, 0, client);
  else if (digitalRead(d2)==LOW && d1_db==1)
    changeDeviceStatusInDB(2, 1, client);

  if (digitalRead(d3)==HIGH && d3_db==0)
    changeDeviceStatusInDB(3, 0, client);
  else if (digitalRead(d3)==LOW && d3_db==1)
    changeDeviceStatusInDB(3, 1, client);

  if (digitalRead(d4)==HIGH && d4_db==0)
    changeDeviceStatusInDB(4, 0, client);
  else if (digitalRead(d4)==LOW && d4_db==1)
    changeDeviceStatusInDB(4, 1, client);
    
  Serial.println();
  Serial.println("closing connection");
}
void changeDeviceStatusInDB(int dID, int status, WiFiClient client) {
  String url = "/dcwm/change_device_status.php";
  url = url + "?status=" + status + "&device_id=" + dID;
  Serial.print("Requesting URL: ");
  Serial.println(url);
  client.print(String("GET ") + url + " HTTP/1.1\r\n" +
               "Host: " + host + "\r\n" +
               "Connection: close\r\n\r\n");
  delay(500);
}

