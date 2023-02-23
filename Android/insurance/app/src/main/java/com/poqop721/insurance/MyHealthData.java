package com.poqop721.insurance;

public class MyHealthData {
    String date;
    String danger;
    String warning;

    public MyHealthData(String date, String danger, String warning) {
        this.date= date;
        this.danger=danger;
        this.warning=warning;
    }

    public void setdate(String date) {
        this.date = date;
    }

    public void setdanger(String  danger) {
        this.danger = danger;
    }

    public void setwarning(String warning) {
        this.warning = warning;
    }




    public String getdate() {
        return date;
    }

    public String getdanger() {
        return danger;
    }

    public String getwarning() {
        return this.warning;
    }

}
