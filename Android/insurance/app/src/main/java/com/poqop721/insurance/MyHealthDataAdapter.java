package com.poqop721.insurance;

import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import androidx.core.content.ContextCompat;

import java.util.ArrayList;

public class MyHealthDataAdapter extends BaseAdapter {
    ArrayList<MyHealthData> datas;
    LayoutInflater inflater;
    public MyHealthDataAdapter(LayoutInflater inflater, ArrayList<MyHealthData> datas) {
        this.datas= datas;
        this.inflater= inflater;
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
            convertView= inflater.inflate(R.layout.my_health_list_row, null);
        }
        TextView health_date = (TextView)convertView.findViewById(R.id.health_date);
        TextView health_danger= (TextView)convertView.findViewById(R.id.health_danger);
        TextView health_warning= (TextView)convertView.findViewById(R.id.health_warning);

        health_date.setText( datas.get(position).getdate() );
        health_danger.setText( datas.get(position).getdanger() );
        health_warning.setText( datas.get(position).getwarning() );


        return convertView;
    }

}