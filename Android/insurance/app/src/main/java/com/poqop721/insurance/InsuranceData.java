package com.poqop721.insurance;

public class InsuranceData {
    String name;
    String product_name;
    String price;
    String comp;

    public InsuranceData(String name, String product_name, String price,String comp) {
        this.name= name;
        this.product_name=product_name;
        this.price=price;
        this.comp=comp;
    }

    public void setName(String name) {
        this.name = name;
    }

    public void setProduct_name(String  product_name) {
        this.product_name = product_name;
    }

    public void setPrice(String price) {
        this.price = price;
    }

    public void setComp(String comp) {
        this.comp = comp;
    }


    public String getName() {
        return name;
    }

    public String getProduct_name() {
        return product_name;
    }

    public String getPrice() {
        return this.price;
    }

    public String getComp() {
        return this.comp;
    }
}
