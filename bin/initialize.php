<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of db
 *
 * @author mgris
 */
class AppConfig {
    static  $DB_SERVER    = "localhost";
    static $DB_NAME      = "dailyboost";
    static $DB_USERNAME  = "dailyboost";
    static $DB_PASSWORD  = "dailyboost";
    
    static $MAIL_HOST = "smtp.gmail.com";
    static $MAIL_PORT = 587;
    static $MAIL_USER = "mgrisoster@gmail.com";
    static $MAIL_PASSWORD = "grqqyrmpjdoilxia";
    static $MAIL_ENABLESSL = true;
    
    static $GOOGLE_CLIENT_ID = "YOUR_GOOGLE_ID";
    static $GOOGLE_CLIENT_SECRET = "";
    static $GOOGLE_REDIRECT_URI = "";
}