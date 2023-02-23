package com.poqop721.insurance;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.graphics.Color;
import android.graphics.drawable.Drawable;
import android.os.Bundle;

import androidx.core.content.ContextCompat;
import androidx.fragment.app.ListFragment;

import android.os.Parcelable;
import android.util.Log;
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


public class Fragment extends ListFragment {
    StringRequest stringRequest;
    private static final String TAG = "ZZIM,MYPAGE";
    private RequestQueue requestQueue;
    // Store instance variables
    private String title;
    private int page;
    InsuranceDataAdapter adapter;
    private ArrayList<ArrayList<String>> arrList;
    private ArrayList<InsuranceData> adpArray;
    ListView listView;
    TextView noHealthTv;
    private String sessionId;
    private String zzimName = "error";
    private String reset = "false";
    private String mainTitle;

    public Fragment() {
    }

    // newInstance constructor for creating fragment with arguments
    public static Fragment newInstance(int page, String title, Serializable arrayGroup,String sessionId,String mainTitle) {
        Fragment fragment = new Fragment();
        Bundle args = new Bundle();
        args.putInt("someInt", page);
        args.putString("someTitle", title);
        args.putSerializable("arrList",arrayGroup);
        args.putString("sessionId",sessionId);
        args.putString("mainTitle",mainTitle);
        fragment.setArguments(args);
        return fragment;
    }

    // Store instance variables based on arguments passed
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        page = getArguments().getInt("someInt", 0);
        title = getArguments().getString("someTitle");
        arrList = (ArrayList<ArrayList<String>>)getArguments().getSerializable("arrList");
        sessionId = getArguments().getString("sessionId");
        mainTitle = getArguments().getString("mainTitle");
    }

    // Inflate the view for the fragment based on layout XML
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment, container, false);
        TextView tvLabel = (TextView) view.findViewById(R.id.about);
        noHealthTv = (TextView) view.findViewById(R.id.noHealth);
        listView = (ListView) view.findViewById(android.R.id.list);
        requestQueue = Volley.newRequestQueue(getActivity());
        String url;
        reset = "false";

        if(mainTitle.equals("내가 찜한 보험상품")) {
            tvLabel.setTextColor(getResources().getColor(R.color.my_purple));
            url = "http://poqop721.dothome.co.kr/insurance/android/unzzim.php";
            setHasOptionsMenu(true);
        }
        else if (mainTitle.equals("위험 요소 추천 보험")){
            tvLabel.setTextColor(getResources().getColor(R.color.my_red));
            url = "http://poqop721.dothome.co.kr/insurance/android/zzim.php";
        } else if (mainTitle.equals("주의 요소 추천 보험")){
            tvLabel.setTextColor(getResources().getColor(R.color.my_light_blue));
            url = "http://poqop721.dothome.co.kr/insurance/android/zzim.php";
        } else {
            tvLabel.setTextColor(getResources().getColor(R.color.my_green));
            url = "http://poqop721.dothome.co.kr/insurance/android/zzim.php";
        }

        tvLabel.setText(title);
        if(arrList.size() != 0) {
            noHealthTv.setVisibility(View.GONE);
            listView.setVisibility(View.VISIBLE);
            tvLabel.setVisibility(View.VISIBLE);
            adpArray = new ArrayList<InsuranceData>();
            for (int i = 0; i < arrList.size(); i++) {
                adpArray.add(new InsuranceData(arrList.get(i).get(0),arrList.get(i).get(1),
                        "가입금액 : "+arrList.get(i).get(2),"보장금액 : "+arrList.get(i).get(3)));
            }
            adapter = new InsuranceDataAdapter(getLayoutInflater(),
                    adpArray,mainTitle);
            listView.setAdapter(adapter);
        }
        else {
            noHealthTv.setVisibility(View.VISIBLE);
            noHealthTv.setText("찜 한 보험사가 아직 없습니다. \n보험을 추천받고 찜해보세요.");
            listView.setVisibility(View.GONE);
            tvLabel.setVisibility(View.GONE);
        }

        stringRequest = new StringRequest(Request.Method.POST, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                Toast.makeText(getActivity(), response, Toast.LENGTH_LONG).show();
                try {
                    if(title.equals("내가 찜한 보험상품")) {
                        adapter.notifyDataSetChanged();
                    }
                } catch (Exception e){
                    Toast.makeText(getActivity(), "찜 목록을 비웠습니다.", Toast.LENGTH_SHORT).show();
                }
                if(arrList.size() == 0){
                    noHealthTv.setVisibility(View.VISIBLE);
                    noHealthTv.setText("찜 한 보험사가 아직 없습니다. \n보험을 추천받고 찜해보세요.");
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
                params.put("zzim", zzimName);
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
        AlertDialog.Builder dlg;
        if(mainTitle.equals("위험 요소 추천 보험"))
            dlg = new AlertDialog.Builder(getActivity(),R.style.DangerAlertDialog);
        else if(mainTitle.equals("주의 요소 추천 보험"))
            dlg = new AlertDialog.Builder(getActivity(),R.style.WarningAlertDialog);
        else if(mainTitle.equals("내가 찜한 보험상품"))
            dlg = new AlertDialog.Builder(getActivity(),R.style.ZzimAlertDialog);
        else dlg = new AlertDialog.Builder(getActivity(),R.style.AllinsAlertDialog);
        dlg.setTitle(String.valueOf(arrList.get(position).get(1)));
        dlg.setMessage("보험사 : " + arrList.get(position).get(0)
                +"\n주 보장 항목 : " + arrList.get(position).get(4)
                +"\n가입금액 : " + arrList.get(position).get(2)
                +"\n보장금액 : " + arrList.get(position).get(3)
                +"\n전화번호 : " + arrList.get(position).get(6)
                +"\nURL : " + arrList.get(position).get(5));
        dlg.setNegativeButton("취소",null);
        if(title.equals("내가 찜한 보험상품")){
            dlg.setPositiveButton("찜 제거",
                    new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialog, int which) {
                            zzimName = String.valueOf(arrList.get(position).get(1));
                            Log.d("zzim",zzimName);
                            arrList.remove(position);
                            adpArray.remove(position);
                            requestQueue.add(stringRequest);
                        }
                    });
            dlg.show();
        }
        else {
            dlg.setPositiveButton("찜하기",
                    new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialog, int which) {
                            reset = "false";
                            zzimName = String.valueOf(arrList.get(position).get(1));
                            requestQueue.add(stringRequest);
                        }
                    });
            dlg.show();
        }
    }

    public void onCreateOptionsMenu(Menu menu, MenuInflater inflater){
        super.onCreateOptionsMenu(menu,inflater);
        inflater.inflate(R.menu.resetzzimmenu,menu);
    }
    public boolean onOptionsItemSelected(MenuItem item){
        if(arrList.size() == 0) Toast.makeText(getActivity(),"이미 찜 목록이 비어있습니다."
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