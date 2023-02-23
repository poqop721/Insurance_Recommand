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
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.content.ContextCompat;
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

public class ThirdTabActivity extends AppCompatActivity {
    private static final String TAG = "MAIN";
    private RequestQueue requestQueue;
    FragmentPagerAdapter adapterViewPager;
    private static ArrayList<String> keys;
    private static ArrayList<ArrayList<ArrayList<String>>> resultArrayGroup;
    private String sessionId;

    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.resultlist);

        getSupportActionBar().setBackgroundDrawable(new ColorDrawable(getResources().getColor(R.color.my_green)));
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            Window window = this.getWindow();
            window.addFlags(WindowManager.LayoutParams.FLAG_DRAWS_SYSTEM_BAR_BACKGROUNDS);
            window.setStatusBarColor(getResources().getColor(R.color.my_green_stbar));
        }

        requestQueue = Volley.newRequestQueue(this);
        String url = "http://poqop721.dothome.co.kr/insurance/android/allins.php";

        Intent resultIntent = getIntent();
        sessionId = resultIntent.getStringExtra("sessionId");

        resultArrayGroup = new ArrayList<ArrayList<ArrayList<String>>>();

        final StringRequest stringRequest = new StringRequest(Request.Method.POST, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    JSONArray resultArray = new JSONArray(response);
                    JSONObject element;
                    keys = new ArrayList<String>();
                    ArrayList<String> values = new ArrayList<String>();
                    int count = 0;
                    for(int i = 0;i<resultArray.length();i++){
                        element = (JSONObject) resultArray.get(i);
                        Iterator iter = element.keys();
                        while(iter.hasNext())
                        {
                            String b = iter.next().toString();
                            keys.add(b);
                            values.add(element.getString(b));
                        }
                    }
                    for(int i = 0;i<values.size();i++){
                        JSONObject eachElement;
                        ArrayList<String> eachValues = new ArrayList<String>();
                        JSONArray eachArray = new JSONArray(values.get(i));
                        ArrayList<ArrayList<String>> detailVals = new ArrayList<ArrayList<String>>();
                        for(int j = 0;j<eachArray.length();j++){
                            ArrayList<String> dedetailvals = new ArrayList<String>();
                            eachElement = (JSONObject) eachArray.get(j);
                            Iterator iter = eachElement.keys();
                            while(iter.hasNext())
                            {
                                String b = iter.next().toString();
                                dedetailvals.add(eachElement.getString(b));
                            }
                            detailVals.add(dedetailvals);
                        }
                        resultArrayGroup.add(detailVals);
                        count++;
                    }
                    ViewPager vpPager = (ViewPager) findViewById(R.id.vpPager);
                    adapterViewPager = new MyPagerAdapter(getSupportFragmentManager(),count,sessionId,"전체 보험상품");
                    vpPager.setAdapter(adapterViewPager);
                    CircleIndicator indicator = (CircleIndicator) findViewById(R.id.indicatorThird);
                    indicator.setViewPager(vpPager);
                    vpPager.setPageTransformer(true, new ZoomOutPageTransformer());


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
            Fragment fragment = new Fragment();
            return fragment.newInstance(position, keys.get(position),(Serializable)resultArrayGroup.get(position),sessionId,mainTitle);
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
