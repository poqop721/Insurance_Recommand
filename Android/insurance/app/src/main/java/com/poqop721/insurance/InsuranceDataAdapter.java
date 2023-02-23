package com.poqop721.insurance;

import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.LinearLayout;
import android.widget.TextView;

import androidx.core.content.ContextCompat;

import java.util.ArrayList;

public class InsuranceDataAdapter extends BaseAdapter {
    ArrayList<InsuranceData> datas;
    LayoutInflater inflater;
    String mainTitle;
    public InsuranceDataAdapter(LayoutInflater inflater, ArrayList<InsuranceData> datas,String mainTitle) {
        this.datas= datas;
        this.inflater= inflater;
        this.mainTitle = mainTitle;
    }

    @Override
    public int getCount() {
        return datas.size();
    }

    @Override
    public Object getItem(int position) {
        return datas.get(position);
    }

    @Override
    public long getItemId(int position) {
        // TODO Auto-generated method stub
        return position;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        // TODO Auto-generated method stub
        if( convertView==null){
            convertView= inflater.inflate(R.layout.list_row, null);
        }
        TextView ins_name= (TextView)convertView.findViewById(R.id.ins_name);
        TextView ins_product_name= (TextView)convertView.findViewById(R.id.ins_product_name);
        TextView ins_price= (TextView)convertView.findViewById(R.id.ins_price);
        TextView ins_comp= (TextView)convertView.findViewById(R.id.ins_comp);

        ins_name.setText( datas.get(position).getName() );
        ins_product_name.setText( datas.get(position).getProduct_name() );
        ins_price.setText( datas.get(position).getPrice() );
        ins_comp.setText( datas.get(position).getComp() );
        if(mainTitle.equals("내가 찜한 보험상품")) {
            ins_name.setBackgroundColor(Color.parseColor("#8388e0"));
        }
        else if (mainTitle.equals("위험 요소 추천 보험")){
            ins_name.setBackgroundColor(ContextCompat.getColor(convertView.getContext(), R.color.my_red));
        } else if (mainTitle.equals("주의 요소 추천 보험")){
            ins_name.setBackgroundColor(ContextCompat.getColor(convertView.getContext(), R.color.my_light_blue));
        } else {
            ins_name.setBackgroundColor(ContextCompat.getColor(convertView.getContext(), R.color.my_green));
        }
        if (position == datas.size()){

        }

        return convertView;
    }

}