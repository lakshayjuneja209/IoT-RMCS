#include <ESP8266WiFi.h>
#include "DHT.h"
/*
#include <SPI.h>
#include <Ethernet.h>
*/

const char* ssid = "Lakshay";
const char* password = "22751990";
//byte mac[] = { 0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED };
 
// Enter the IP address for Arduino, as mentioned we will use 192.168.0.16
// Be careful to use , insetead of . when you enter the address here
//IPAddress ip(192,168,0,16);
WiFiServer server(80);
const int dataSize = 20;
const int gasPin = A0;
#define DHTPIN 4
struct dcw {
  float t_c;
  float hu;
  float smoke;
}data[dataSize];

#define DHTTYPE DHT11 
DHT dht(DHTPIN, DHTTYPE);
//char server[] = "127.0.0.1"; // IMPORTANT: If you are using XAMPP you will have to find out the IP address of your computer and put it here (it is explained in previous article). If you have a web page, enter its address (ie. "www.yourwebpage.com")

// Initialize the Ethernet server library
//EthernetClient client;


int i = -1;
void getData(){
 // while(1) {
    float t_f = dht.readTemperature(true);  //read temperature in Fahrenheit
    i++;
    if(i >= dataSize){
      for(int j=0;j<dataSize-1;j++){
        data[j] = data[j+1];
      }
      i = dataSize-1;
    }
    data[i].hu = dht.readHumidity();
    data[i].t_c = (t_f-32)/1.8; // convert temperature to centigrade
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

void setup() {
 
  // Serial.begin starts the serial connection between computer and Arduino
  Serial.begin(115200);
  dht.begin();
  delay(10);
  pinMode(5, OUTPUT);
  pinMode(4, OUTPUT);
  pinMode(0, OUTPUT);
  pinMode(13, OUTPUT);
  digitalWrite(5, LOW);
  digitalWrite(4, LOW);
  digitalWrite(0, LOW);
  digitalWrite(13, LOW);
 
  // Connect to WiFi network
  Serial.println();
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);
 
  WiFi.begin(ssid, password);
 
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connected");
 
  // Start the server
  server.begin();
  Serial.println("Server started");
 
  // Print the IP address
  Serial.print("Use this URL to connect: ");
  Serial.print("http://");
  Serial.print(WiFi.localIP());
  Serial.println("/");
  // start the Ethernet connection
  //Ethernet.begin(mac, ip);
    
}
int c = 0;
void loop() {
  WiFiClient client = server.available();
  if (!client) {
    return;
  }
  String request = client.readStringUntil('\r');
  Serial.println(request);
  client.flush();

  if ( c == 0){
    delay(2000);
    getData();
    c = 1;
  }
  // Match the request
  if (request.indexOf("/UpdateData") > 0){
    getData();
  }
  else{
  if (request.indexOf("/device1on") > 0)  {
    digitalWrite(5, HIGH);
   
  }
  if (request.indexOf("/device1off") >0)  {
    digitalWrite(5, LOW);
   
  }

  if (request.indexOf("/device2on") > 0)  {
    digitalWrite(4, HIGH);
   
  }
  if (request.indexOf("/device2off") >0)  {
    digitalWrite(4, LOW);
   
  }
  if (request.indexOf("/device3on") >0)  {
    digitalWrite(0, HIGH);
   
  }
  if (request.indexOf("/device3off") > 0)  {
    digitalWrite(0, LOW);
   
  }
  if (request.indexOf("/device4on") > 0)  {
    digitalWrite(13, HIGH);
   
  }
  if (request.indexOf("/device4off") > 0)  {
    digitalWrite(13, LOW);
   
  }
  }
  // Set ledPin according to the request
  //digitalWrite(ledPin, value);
 
  // Return the response
  client.println("HTTP/1.1 200 OK");
  client.println("Content-Type: text/html");
  client.println(""); //  do not forget this one
  client.println("<!DOCTYPE HTML>");
  client.println("<html>");
  client.println("<head>");
  client.println("<title> Weather Monitoring and Device Management </title>");
  client.println("<meta name='apple-mobile-web-app-capable' content='yes' />");
  client.println("<meta name='apple-mobile-web-app-status-bar-style' content='black-translucent' />");
  client.println("</head>");
  client.println("<body bgcolor = \"66ff66\">"); 
  client.println("<hr/><hr>");
  client.println("<h4><center> Weather Management and Electrical Device Control </center></h4>");
  client.println("<hr/><hr>");
  client.println("<br><br>");
  client.println("<br><br>");
  
  client.println("<center>");
  client.println("<a href=\"/UpdateData\"\"><button>Update Data </button></a>");
  client.println("</center>");
  
  client.println("<br><br>");
  client.println("<center>");  
  client.println("<table border=\"5\">");
  client.println("<tr>");
  client.println("<td>");
  client.println("S.No.");
  client.println("</td>");
  client.println("<td>");
  client.println("Temperature");
  client.println("</td>");
  client.println("<td>");
  client.println("Humidity");
  client.println("</td>");
  client.println("<td>");
  client.println("Smoke");
  client.println("</td>");
  client.println("</tr>");
  for(int j=0; j<=i; j++){
    client.println("<tr>");
    client.println("<td>");  
    client.println(j+1);
    client.println("</td>");
    client.println("<td>");
    client.println(data[j].t_c);
    client.println("</td>");
    client.println("<td>");
    client.println(data[j].hu);
    client.println("</td>");
    client.println("<td>");
    client.println(data[j].smoke);
    client.println("</td>");
    client.println("</tr>");
  }
  client.println("</table>");
  client.println("</center>");
  
  client.println("<br><br>");
  client.println("<br><br>");
  client.println("<br><br>");
  
  client.println("<center>");
  client.println("Device 1");
  client.println("<a href=\"/device1on\"\"><button>Turn On </button></a>");
  client.println("<a href=\"/device1off\"\"><button>Turn Off </button></a><br />");  
  client.println("</center>");   
  client.println("<br><br>");
  client.println("<center>");
  client.println("Device 2");
  client.println("<a href=\"/device2on\"\"><button>Turn On </button></a>");
  client.println("<a href=\"/device2off\"\"><button>Turn Off </button></a><br />");  
  client.println("</center>"); 
  client.println("<br><br>");
  client.println("<center>");
  client.println("Device 3");
  client.println("<a href=\"/device3on\"\"><button>Turn On </button></a>");
  client.println("<a href=\"/device3off\"\"><button>Turn Off </button></a><br />");  
  client.println("</center>"); 
  client.println("<br><br>");
  client.println("<center>");
  client.println("Device 4");
  client.println("<a href=\"/device4on\"\"><button>Turn On </button></a>");
  client.println("<a href=\"/device4off\"\"><button>Turn Off </button></a><br />");  
  client.println("</center>"); 
  client.println("<br><br>");
  client.println("<center>");
  client.println("<table border=\"5\">");
  client.println("<tr>");
  if (digitalRead(5))
  { 
    client.print("<td>Device 1 is ON</td>");
  }
  else
  {
     client.print("<td>Device 1 is OFF</td>");
  }
  client.println("<br />");
  if (digitalRead(4))
  { 
     client.print("<td>Device 2 is ON</td>");
  }
  else
  {
    client.print("<td>Device 2 is OFF</td>");
  }
  client.println("</tr>");
  client.println("<tr>");
  if (digitalRead(0))
  { 
    client.print("<td>Device 3 is ON</td>");
  }
  else
  {
    client.print("<td>Device 3 is OFF</td>");
  }
  if (digitalRead(13))
  {
    client.print("<td>Device 4 is ON</td>");
  }
  else
  {
    client.print("<td>Device 4 is OFF</td>");
  }
  client.println("</tr>");
  client.println("</table>");
  client.println("</center>");
  client.println("</html>"); 
  delay(1);
  Serial.println("Client disonnected");
  Serial.println("");
}
