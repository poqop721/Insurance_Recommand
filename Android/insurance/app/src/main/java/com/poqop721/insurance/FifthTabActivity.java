package com.poqop721.insurance;

import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
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

public class FifthTabActivity extends AppCompatActivity {
    private static final String TAG = "MYPAGE";
    private RequestQueue requestQueue;
    FragmentPagerAdapter adapterViewPager;
    private static ArrayList<String> myInfoArr;
    private static ArrayList<ArrayList<String>> zzimArrayGroup;
    private String sessionId;

    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.resultlist);

        getSupportActionBar().setBackgroundDrawable(new ColorDrawable(getResources().getColor(R.color.my_purple)));
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            Window window = this.getWindow();
            window.addFlags(WindowManager.LayoutParams.FLAG_DRAWS_SYSTEM_BAR_BACKGROUNDS);
            window.setStatusBarColor(getResources().getColor(R.color.my_purple_stbar));
        }

        requestQueue = Volley.newRequestQueue(this);
        String url = "http://poqop721.dothome.co.kr/insurance/android/myPage.php";

        Intent myPageIntent = getIntent();
        sessionId = myPageIntent.getStringExtra("sessionId");

        zzimArrayGroup = new ArrayList<ArrayList<String>>();

        final StringRequest stringRequest = new StringRequest(Request.Method.POST, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    JSONObject resultObj = new JSONObject(response);
                    JSONObject myinfo = new JSONObject(resultObj.getString("myinfo"));
                    myInfoArr = new ArrayList<String>();
                    Iterator myInfoIter = myinfo.keys();
                    while(myInfoIter.hasNext())
                    {
                        String b = myInfoIter.next().toString();
                        myInfoArr.add(myinfo.getString(b));
                    }

                    JSONArray zzim = new JSONArray(resultObj.getString("zzim"));
                    for(int i = 0;i<zzim.length();i++){
                        ArrayList<String> zzimArr = new ArrayList<String>();
                        JSONObject element = (JSONObject) zzim.get(i);
                        Iterator zzimIter = element.keys();
                        while(zzimIter.hasNext())
                        {
                            String b = zzimIter.next().toString();
                            zzimArr.add(element.getString(b));
                        }
                        zzimArrayGroup.add(zzimArr);
                    }
                    ViewPager vpPager = (ViewPager) findViewById(R.id.vpPager);
                    adapterViewPager = new MyPagerAdapter(getSupportFragmentManager(),2,sessionId,"내가 찜한 보험상품");
                    vpPager.setAdapter(adapterViewPager);
                    CircleIndicator indicator = (CircleIndicator) findViewById(R.id.indicatorpurple);
                    indicator.setViewPager(vpPager);
                    vpPager.setPageTransformer(true, new ZoomOutPageTransformer());
                } catch (Exception e) {
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
        private String mainTitle;

        public MyPagerAdapter(FragmentManager fragmentManager,int number,String sessionId,String mainTitle) {
            super(fragmentManager);
            this.NUM_ITEMS = number;
            this.sessionId = sessionId;
            this.mainTitle = mainTitle;
        }

        // Returns total number of pages
        @Override
        public int getCount() {
            return NUM_ITEMS;
        }

        // Returns the fragment to display for that page
        @Override
        public androidx.fragment.app.Fragment getItem(int position) {
            switch (position){
                case 0:
                    MyPageFragment mypagef = new MyPageFragment();
                    return mypagef.newInstance(position," 님의 개인정보",myInfoArr,sessionId);
                case 1:
                    Fragment fragment = new Fragment();
                    return fragment.newInstance(position, "내가 찜한 보험상품",(Serializable)zzimArrayGroup,sessionId,mainTitle);
                default:
                    return null;
            }
        }

        // Returns the page title for the top indicator
        @Override
        public CharSequence getPageTitle(int position) {
            return "Page " + position;
        }
    }
    public class ZoomOutPageTransformer implements ViewPager.PageTransformer {
        private static final float MIN_SCALE = 0.85f;
        private static final float MIN_ALPHA = 0.5f;

        public void transformPage(View view, float position) {
            int pageWidth = view.getWidth();
            int pageHeight = view.getHeight();

            if (position < -1) { // [-Infinity,-1)
                // This page is way off-screen to the left.
                view.setAlpha(0f);

            } else if (position <= 1) { // [-1,1]
                // Modify the default slide transition to shrink the page as well
                float scaleFactor = Math.max(MIN_SCALE, 1 - Math.abs(position));
                float vertMargin = pageHeight * (1 - scaleFactor) / 2;
                float horzMargin = pageWidth * (1 - scaleFactor) / 2;
                if (position < 0) {
                    view.setTranslationX(horzMargin - vertMargin / 2);
                } else {
                    view.setTranslationX(-horzMargin + vertMargin / 2);
                }

                // Scale the page down (between MIN_SCALE and 1)
                view.setScaleX(scaleFactor);
                view.setScaleY(scaleFactor);

                // Fade the page relative to its size.
                view.setAlpha(MIN_ALPHA +
                        (scaleFactor - MIN_SCALE) /
                                (1 - MIN_SCALE) * (1 - MIN_ALPHA));

            } else { // (1,+Infinity]
                // This page is way off-screen to the right.
                view.setAlpha(0f);
            }
        }
    }
}

