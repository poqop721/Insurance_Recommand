package com.poqop721.insurance;

import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Build;
import android.os.Bundle;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.LinearLayout;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.FragmentManager;
import androidx.fragment.app.FragmentPagerAdapter;
import androidx.viewpager.widget.ViewPager;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;

import me.relex.circleindicator.CircleIndicator;

public class FourthTabActivity extends AppCompatActivity {
    private static final String TAG = "MYHEALTH";
    private RequestQueue requestQueue;
    FragmentPagerAdapter adapterViewPager;
    private static ArrayList<ArrayList<String>> resultArrayGroup;
    private String sessionId;

    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.resultlist);

        getSupportActionBar().setBackgroundDrawable(new ColorDrawable(getResources().getColor(R.color.my_orange)));
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            Window window = this.getWindow();
            window.addFlags(WindowManager.LayoutParams.FLAG_DRAWS_SYSTEM_BAR_BACKGROUNDS);
            window.setStatusBarColor(getResources().getColor(R.color.my_orange_stbar));
        }

        requestQueue = Volley.newRequestQueue(this);
        String url = "http://poqop721.dothome.co.kr/insurance/android/myHealthInfo.php";

        Intent healthIntent = getIntent();
        sessionId = healthIntent.getStringExtra("sessionId");

        resultArrayGroup = new ArrayList<ArrayList<String>>();

        final StringRequest stringRequest = new StringRequest(Request.Method.POST, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    JSONArray resultArray = new JSONArray(response);
                    JSONObject element;
                    ArrayList<String> values = new ArrayList<String>();
                    for(int i = 0;i<resultArray.length();i++){
                        element = (JSONObject) resultArray.get(i);
                        ArrayList<String> detailvals = new ArrayList<String>();
                        detailvals.add(element.getString("DEL_NUM"));
                        detailvals.add(element.getString("CREATED_DATE"));
                        detailvals.add(element.getString("BP"));
                        detailvals.add(element.getString("BOS"));
                        detailvals.add(element.getString("BFP"));
                        detailvals.add(element.getString("SMM"));
                        detailvals.add(element.getString("MBW"));
                        detailvals.add(element.getString("BM"));
                        detailvals.add(element.getString("WARN_LIST"));
                        detailvals.add(element.getString("DAN_LIST"));
                        resultArrayGroup.add(detailvals);
                    }
                    ViewPager vpPager = (ViewPager) findViewById(R.id.vpPager);
                    adapterViewPager = new MyPagerAdapter(getSupportFragmentManager(),1,sessionId);
                    vpPager.setAdapter(adapterViewPager);
                } catch (JSONException e) {
                    Toast.makeText(getApplicationContext(), "데이터를 갖고 오는데 문제가 발생했습니다.\n " +
                            "다시 시도해주세요.", Toast.LENGTH_SHORT).show();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(getApplicationContext(), "서버와 연결하는데 문제가 발생했습니다.", Toast.LENGTH_SHORT).show();
            }
        }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<String, String>();
                params.put("ID", sessionId);
                return params;
            }
        };

        stringRequest.setTag(TAG);
        requestQueue.add(stringRequest);
    }
    protected void onStop() {
        super.onStop();
        if (requestQueue != null) {
            requestQueue.cancelAll(TAG);
        }
    }

    public static class MyPagerAdapter extends FragmentPagerAdapter {
        private int NUM_ITEMS;
        private String sessionId;

        public MyPagerAdapter(FragmentManager fragmentManager,int number,String sessionId) {
            super(fragmentManager);
            this.NUM_ITEMS = number;
            this.sessionId = sessionId;
        }

        // Returns total number of pages
        @Override
        public int getCount() {
            return NUM_ITEMS;
        }

        // Returns the fragment to display for that page
        @Override
        public androidx.fragment.app.Fragment getItem(int position) {
            MyHealthFragment mhfragment = new MyHealthFragment();
            return mhfragment.newInstance(position, (Serializable)resultArrayGroup,sessionId);
        }

        // Returns the page title for the top indicator
        @Override
        public CharSequence getPageTitle(int position) {
            return "Page " + position;
        }

    }
}
