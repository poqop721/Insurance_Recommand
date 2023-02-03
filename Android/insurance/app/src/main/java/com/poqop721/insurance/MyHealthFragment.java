package com.poqop721.insurance;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.graphics.Color;
import android.os.Bundle;

import androidx.fragment.app.FragmentManager;
import androidx.fragment.app.FragmentTransaction;
import androidx.fragment.app.ListFragment;

import android.os.Parcelable;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.EditText;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;


public class MyHealthFragment extends ListFragment {
    StringRequest stringRequest;
    private static final String TAG = "DELHEALTH";
    private RequestQueue requestQueue;
    // Store instance variables
    private int page;
    private ArrayList<ArrayList<String>> arrList;
    private ArrayList<MyHealthData> adpArray;
    MyHealthDataAdapter adapter;
    ListView listView;
    TextView noHealthTv;
    private String sessionId;
    private String delNum = "error";
    private String reset = "false";

    public MyHealthFragment() {
    }

    // newInstance constructor for creating fragment with arguments
    public static MyHealthFragment newInstance(int page, Serializable arrayGroup,String
            sessionId) {
        MyHealthFragment mhfragment = new MyHealthFragment();
        Bundle args = new Bundle();
        args.putInt("someInt", page);
        args.putSerializable("arrList",arrayGroup);
        args.putString("sessionId",sessionId);
        mhfragment.setArguments(args);
        return mhfragment;
    }

    // Store instance variables based on arguments passed
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        page = getArguments().getInt("someInt", 0);
        arrList = (ArrayList<ArrayList<String>>)getArguments().getSerializable("arrList");
        sessionId = getArguments().getString("sessionId");
    }
    // Inflate the view for the fragment based on layout XML
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment, container, false);
        TextView tvLabel = (TextView) view.findViewById(R.id.about);
        noHealthTv = (TextView) view.findViewById(R.id.noHealth);
        listView = (ListView) view.findViewById(android.R.id.list);

        setHasOptionsMenu(true);

        requestQueue = Volley.newRequestQueue(getActivity());
        String url = "http://poqop721.dothome.co.kr/insurance/android/delHealth.php";

        tvLabel.setText("나의 건강정보");
        tvLabel.setTextColor(Color.parseColor("#e2924e"));

        reset = "false";
        if(arrList.size() != 0){
            noHealthTv.setVisibility(View.GONE);
            listView.setVisibility(View.VISIBLE);
            tvLabel.setVisibility(View.VISIBLE);
            adpArray = new ArrayList<MyHealthData>();
            for (int i = 0; i < arrList.size(); i++) {
                String warnNum, danNum;
                if (arrList.get(i).get(8).equals("")) warnNum = "0";
                else {
                    String num[] = arrList.get(i).get(8).split(",");
                    warnNum = String.valueOf(num.length);
                }
                if (arrList.get(i).get(9).equals("")) danNum = "0";
                else {
                    String num[] = arrList.get(i).get(9).split(",");
                    danNum = String.valueOf(num.length);
                }
                adpArray.add(new MyHealthData(arrList.get(i).get(1).replace(" "," 일 ") + " 시","위험 : "+danNum,"주의 : " + warnNum));
            }
            adapter = new MyHealthDataAdapter(getLayoutInflater(), adpArray);
            listView.setAdapter(adapter);
        } else {
            noHealthTv.setVisibility(View.VISIBLE);
            noHealthTv.setText("입력된 건강 정보가 없습니다.\n'보험 추천받기' 탭에서 먼저 보험을 추천받아 주세요.");
            listView.setVisibility(View.GONE);
            tvLabel.setVisibility(View.GONE);
        }


        stringRequest = new StringRequest(Request.Method.POST, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                Toast.makeText(getActivity(), response, Toast.LENGTH_SHORT).show();
                try {
                    adapter.notifyDataSetChanged();
                } catch (Exception e){
                    Toast.makeText(getActivity(), "건강 기록이 초기화되었습니다.", Toast.LENGTH_SHORT).show();
                }
                if(arrList.size() == 0){
                    noHealthTv.setVisibility(View.VISIBLE);
                    noHealthTv.setText("입력된 건강 정보가 없습니다.\n'보험 추천받기' 탭에서 먼저 보험을 추천받아 주세요.");
                    listView.setVisibility(View.GONE);
                    tvLabel.setVisibility(View.GONE);
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(getActivity(), "오류가 발생했습니다. 다시 시도해주세요.", Toast.LENGTH_SHORT).show();
            }
        }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<String, String>();
                params.put("ID", sessionId);
                params.put("del_num", delNum);
                params.put("reset", reset);
                return params;
            }
        };

        stringRequest.setTag(TAG);

        return view;
    }
    public void onStop() {
        super.onStop();
        if (requestQueue != null) {
            requestQueue.cancelAll(TAG);
        }
    }
    public void onListItemClick(ListView l, View v, int position, long id) {
        super.onListItemClick(l, v, position, id);
        AlertDialog.Builder dlg = new AlertDialog.Builder(getActivity(),R.style.MyHealthAlertDialog);

        String[] health = new String[6];
        if(arrList.get(position).get(2).equals("null"))health[0] = "입력안됨";
        else health[0] = arrList.get(position).get(2) + " mmHg";
        if(arrList.get(position).get(3).equals("null"))health[1] = "입력안됨";
        else health[1] = arrList.get(position).get(3) + " %";
        if(arrList.get(position).get(4).equals("null"))health[2] = "입력안됨";
        else health[2] = arrList.get(position).get(4) + " %";
        if(arrList.get(position).get(5).equals("null"))health[3] = "입력안됨";
        else health[3] = arrList.get(position).get(5) + " kg";
        if(arrList.get(position).get(6).equals("null"))health[4] = "입력안됨";
        else health[4] = arrList.get(position).get(6) + " %";
        if(arrList.get(position).get(7).equals("null"))health[5] = "입력안됨";
        else health[5] = arrList.get(position).get(7) + " kcal";

        if(!arrList.get(position).get(8).equals("")) {
            String warNum[] = arrList.get(position).get(8).split(",");
            for (String i : warNum) {
                Integer j = Integer.parseInt(i)-1;
                health[j] = health[j] + "\t\t<주의>";
            }
        }
        if(!arrList.get(position).get(9).equals("")) {
            String danNum[] = arrList.get(position).get(9).split(",");
            for (String i : danNum) {
                Integer j = Integer.parseInt(i)-1;
                health[j] = health[j] + "\t<위험>";
            }
        }

        dlg.setTitle(String.valueOf(arrList.get(position).get(1)).replace(" ","일 ")+" 건강정보");
        dlg.setMessage("혈압 : " + health[0]
                +"\n혈중 산소 포화도 : " + health[1]
                +"\n체지방률 : " + health[2]
                +"\n골격근량 : " + health[3]
                +"\n체수분 : " + health[4]
                +"\n기초대사량 : " + health[5]);
        dlg.setNegativeButton("취소",null);
        dlg.setPositiveButton("기록에서 제거",
                new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        delNum = String.valueOf(arrList.get(position).get(0));
                        arrList.remove(position);
                        adpArray.remove(position);
                        reset = "false";
                        requestQueue.add(stringRequest);
                    }
                });
        dlg.show();
    }
    public void onCreateOptionsMenu(Menu menu, MenuInflater inflater){
        super.onCreateOptionsMenu(menu,inflater);
        inflater.inflate(R.menu.resetmenu,menu);
    }
    public boolean onOptionsItemSelected(MenuItem item){
        if(arrList.size() == 0) Toast.makeText(getActivity(),"기록된 건강 정보가 없습니다."
                ,Toast.LENGTH_SHORT).show();
        else {
            reset = "true";
            requestQueue.add(stringRequest);
            arrList.clear();
            adpArray.clear();
        }
        return true;
    }
}