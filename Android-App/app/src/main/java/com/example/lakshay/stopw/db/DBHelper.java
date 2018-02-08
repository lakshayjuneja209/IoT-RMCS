package com.example.lakshay.stopw.db;
import java.util.ArrayList;
import java.util.List;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;
import android.util.Log;

public class DBHelper extends SQLiteOpenHelper {

    // All Static variables
    // Database Version
    private static final int DATABASE_VERSION = 1;

    // Database Name
    private static final String DATABASE_NAME = "PastRecords";

    // Contacts table name
    private static final String TABLE_RECORDS = "record";

    // Contacts Table Columns names
    private static final String KEY_ID = "id";
    private static final String KEY_TIME = "time";
    private static final String KEY_TOUCH_AREA = "TouchArea";

    public DBHelper (Context context) {
        super(context, DATABASE_NAME, null, DATABASE_VERSION);
    }

    // Creating Tables
    @Override
    public void onCreate(SQLiteDatabase db) {
        String CREATE_RECORD_TABLE = "CREATE TABLE " + TABLE_RECORDS + "("
                + KEY_ID + " INTEGER PRIMARY KEY AUTOINCREMENT," + KEY_TIME + " DECIMAL(3,5),"
                + KEY_TOUCH_AREA + " DECIMAL(3,5)" + ")";
        db.execSQL(CREATE_RECORD_TABLE);
    }

    // Upgrading database
    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        // Drop older table if existed
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_RECORDS);

        // Create tables again
        onCreate(db);
    }

    /**
     * All CRUD(Create, Read, Update, Delete) Operations
     */

    // Adding new contact
   public void addRecord(records record) {
        SQLiteDatabase db = this.getWritableDatabase();

        ContentValues values = new ContentValues();
        values.put(KEY_TIME, record.getTime()); // Time taken to return
        values.put(KEY_TOUCH_AREA, record.getAreaTouched()); // Area touched

        // Inserting Row
        db.insert(TABLE_RECORDS, null, values);
        db.close(); // Closing database connection
    }

    // Getting single contact
    records getRecord(int id) {
        SQLiteDatabase db = this.getReadableDatabase();

        Cursor cursor = db.query(TABLE_RECORDS, new String[] { KEY_ID,
                        KEY_TIME, KEY_TOUCH_AREA }, KEY_ID + "=?",
                new String[] { String.valueOf(id) }, null, null, null, null);
        if (cursor != null)
            cursor.moveToFirst();

        records record = new records(Integer.parseInt(cursor.getString(0)),
                Float.parseFloat(cursor.getString(1)),Float.parseFloat( cursor.getString(2)));
        // return contact
        return record;
    }

    // Getting All Contacts
    public List<records> getAllRecords() {
        List<records> recordList = new ArrayList<records>();
        // Select All Query
        String selectQuery = "SELECT  * FROM " + TABLE_RECORDS;

        SQLiteDatabase db = this.getWritableDatabase();
        Cursor cursor = db.rawQuery(selectQuery, null);

        // looping through all rows and adding to list
        if (cursor.moveToFirst()) {
            do {
                records contact = new records();
                contact.setID(Integer.parseInt(cursor.getString(0)));
                contact.setTime(Float.parseFloat(cursor.getString(1)));
                contact.setAreaTouched(Float.parseFloat(cursor.getString(2)));
                // Adding contact to list
                recordList.add(contact);
            } while (cursor.moveToNext());
        }

        // return contact list
        return recordList;
    }
/*
    // Updating single contact
    public int updateContact(records record) {
        SQLiteDatabase db = this.getWritableDatabase();

        ContentValues values = new ContentValues();
        values.put(KEY_TIME, record.getTime());
        values.put(KEY_TOUCH_AREA, record.getAreaTouched());

        // updating row
        return db.update(TABLE_RECORDS, values, KEY_ID + " = ?",
                new String[] { String.valueOf(record.getID()) });
    }

    // Deleting single contact
    public void deleteContact(records record) {
        SQLiteDatabase db = this.getWritableDatabase();
        db.delete(TABLE_RECORDS, KEY_ID + " = ?",
                new String[] { String.valueOf(record.getID()) });
        db.close();
    }

*/
    // Getting contacts Count
    public int getRecordsCount() {
        String countQuery = "SELECT  * FROM " + TABLE_RECORDS;
        SQLiteDatabase db = this.getReadableDatabase();
        Cursor cursor = db.rawQuery(countQuery, null);
        int size=cursor.getCount();
        cursor.close();

        // return count
        return size;
    }

}