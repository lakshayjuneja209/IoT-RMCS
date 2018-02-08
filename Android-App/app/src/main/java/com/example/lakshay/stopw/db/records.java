<<<<<<< HEAD
package com.example.lakshay.stopw.db;

/**
 * Created by lakshay on 23-09-2016.
 */
public class records {
    //private variables
    int id;
    float time;
    float area_touched;

    // Empty constructor
    public records(){

    }
    // constructor
    public records(int id, float time, float areaTouched){
        this.id = id;
        this.time = time;
        this.area_touched =areaTouched;
    }

    // constructor
    public records(float time, float areaTouched){
        this.time = time;
        this.area_touched = areaTouched;
    }
    // getting ID
    public int getID(){
        return this.id;
    }

    // setting id
    public void setID(int id){
        this.id = id;
    }

    // getting name
    public float getTime(){
        return this.time;
    }

    // setting name
    public void setTime(float time){
        this.time=time;
    }

    // getting phone number
    public float getAreaTouched(){
        return this.area_touched;
    }

    // setting phone number
    public void setAreaTouched(float areaTouched){
        this.area_touched = areaTouched;
    }
=======
package com.example.lakshay.stopw.db;

/**
 * Created by lakshay on 23-09-2016.
 */
public class records {
    //private variables
    int id;
    float time;
    float area_touched;

    // Empty constructor
    public records(){

    }
    // constructor
    public records(int id, float time, float areaTouched){
        this.id = id;
        this.time = time;
        this.area_touched =areaTouched;
    }

    // constructor
    public records(float time, float areaTouched){
        this.time = time;
        this.area_touched = areaTouched;
    }
    // getting ID
    public int getID(){
        return this.id;
    }

    // setting id
    public void setID(int id){
        this.id = id;
    }

    // getting name
    public float getTime(){
        return this.time;
    }

    // setting name
    public void setTime(float time){
        this.time=time;
    }

    // getting phone number
    public float getAreaTouched(){
        return this.area_touched;
    }

    // setting phone number
    public void setAreaTouched(float areaTouched){
        this.area_touched = areaTouched;
    }
>>>>>>> eb3cf4fd966880dca453773e4798c8240d7aa817
}