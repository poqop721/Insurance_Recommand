package com.poqop721.insurance;

import android.app.ActionBar;
import android.app.Activity;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Build;
import android.os.Bundle;
import android.text.Html;
import android.text.Spannable;
import android.text.SpannableString;
import android.text.style.ForegroundColorSpan;
import android.text.style.RelativeSizeSpan;
import android.util.Log;
import android.view.View;
import android.view.ViewParent;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

public class SecondTabActivity extends AppCompatActivity {
    private static final String TAG = "RESULT";
    private RequestQueue requestQueue;
    String feedback;
    TextView fdb;
    String rcDanger, rcWarning;
    Button warnBtn, danBtn;
    String BP,BOS,BFP,SMM,MBW,BM;
    ArrayList<String> warning,danger,warningFeedback,dangerFeedback;
    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.secondtab);

        getSupportActionBar().setBackgroundDrawable(new ColorDrawable(getResources().getColor(R.color.my_red)));
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            Window window = this.getWindow();
            window.addFlags(WindowManager.LayoutParams.FLAG_DRAWS_SYSTEM_BAR_BACKGROUNDS);
            window.setStatusBarColor(getResources().getColor(R.color.my_red_stbar));
        }

        fdb = (TextView) findViewById(R.id.feedback) ;
        warnBtn = (Button) findViewById(R.id.warnBtn);
        danBtn = (Button) findViewById(R.id.danBtn);

        requestQueue = Volley.newRequestQueue(this);
        String url = "http://poqop721.dothome.co.kr/insurance/android/getHealth.php";

        Intent resultIntent = getIntent();
        String sessionId = resultIntent.getStringExtra("sessionId");
        String isSaved = resultIntent.getStringExtra("clear");
        if(isSaved.equals("true")) {
            BP = resultIntent.getStringExtra("BP");
            BOS = resultIntent.getStringExtra("BOS");
            BFP = resultIntent.getStringExtra("BFP");
            SMM = resultIntent.getStringExtra("SMM");
            MBW = resultIntent.getStringExtra("MBW");
            BM = resultIntent.getStringExtra("BM");
            feedback = resultIntent.getStringExtra("feedback");
             warning = (ArrayList<String>) resultIntent.getSerializableExtra("warning");
             danger = (ArrayList<String>) resultIntent.getSerializableExtra("danger");
             warningFeedback = (ArrayList<String>) resultIntent.getSerializableExtra("warningFeedback");
             dangerFeedback = (ArrayList<String>) resultIntent.getSerializableExtra("dangerFeedback");
        }

        final StringRequest stringRequest = new StringRequest(Request.Method.POST, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                fdb.setText(feedback);
                try {
                    if(response.equals("no")){
                        danBtn.setVisibility(View.GONE);
                        warnBtn.setEnabled(false);
                        warnBtn.setBackgroundColor(Color.rgb(156,156,156));
                        warnBtn.setTextColor(Color.WHITE);
                        warnBtn.setText("추천드릴 보험이 없습니다.");
                        rcWarning = "no";
                        rcDanger = "[]";
                    }
                    else {
                        JSONObject jsonObject = new JSONObject(response);
                        rcDanger = jsonObject.getString("위험");
                        rcWarning = jsonObject.getString("주의");
                        if (rcDanger.equals("[]")) danBtn.setVisibility(View.GONE);
                        else danBtn.setVisibility(View.VISIBLE);
                        if (rcWarning.equals("[]")) warnBtn.setVisibility(View.GONE);
                        else warnBtn.setVisibility(View.VISIBLE);
                    }
                } catch (JSONException e) {
                    Toast.makeText(getApplicationContext(),"DB를 불러오는데 실패했습니다.\n다시 시도해주세요.",Toast.LENGTH_SHORT);
                    finish();
                    e.printStackTrace();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {

            }
        }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<String, String>();
                if(isSaved.equals("true")) {
                    params.put("ID", sessionId);
                    params.put("BP", BP);
                    params.put("BOS", BOS);
                    params.put("BFP", BFP);
                    params.put("SMM", SMM);
                    params.put("MBW", MBW);
                    params.put("BM", BM);
                    params.put("warning", warning.toString());
                    params.put("danger", danger.toString());
                    params.put("warningFeedback", warningFeedback.toString());
                    params.put("dangerFeedback", dangerFeedback.toString());
                }
                return params;
            }
        };


        danBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent dangerIntent = new Intent(getApplicationContext(), ResultListActivity.class);
                dangerIntent.putExtra("resultList", rcDanger);
                dangerIntent.putExtra("sessionId", sessionId);
                dangerIntent.putExtra("title", "위험 요소 추천 보험");
                startActivity(dangerIntent);
            }
        });

        warnBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent warnIntent = new Intent(getApplicationContext(), ResultListActivity.class);
                warnIntent.putExtra("resultList", rcWarning);
                warnIntent.putExtra("sessionId", sessionId);
                warnIntent.putExtra("title", "주의 요소 추천 보험");
                startActivity(warnIntent);
            }
        });

        stringRequest.setTag(TAG);
        if(isSaved.equals("true")) {
            requestQueue.add(stringRequest);
        }
    }
    protected void onStop() {
        super.onStop();
        if (requestQueue != null) {
            requestQueue.cancelAll(TAG);
        }
    }
    protected void onPause(){
        super.onPause();
        saveState();
    }
    protected void saveState(){
        SharedPreferences pref = getSharedPreferences("pref", Activity.MODE_PRIVATE);
        SharedPreferences.Editor editor = pref.edit();
        editor.putString("rcWarning",rcWarning);
        editor.putString("rcDanger",rcDanger);
        editor.putString("savedFeedback",feedback);
        editor.commit();
    }
    protected void onResume(){
        super.onResume();
        Intent clear = getIntent();
        if(clear.getStringExtra("clear").equals("true")) {
            clearMyPrefs();
        }
        else restoreState();
    }
    protected void restoreState(){
        SharedPreferences pref = getSharedPreferences("pref",Activity.MODE_PRIVATE);
        if(!pref.contains("rcWarning")||!pref.contains("rcDanger")||!pref.contains("savedFeedback")){
            Toast.makeText(getApplicationContext(), "먼저 보험을 추천받아주세요.", Toast.LENGTH_SHORT).show();
            finish();
        }
        else {
            if ((pref != null) && (pref.contains("rcWarning"))) {
                rcWarning = pref.getString("rcWarning", "");
                if (rcWarning.equals("[]")) warnBtn.setVisibility(View.GONE);
                else if(rcWarning.equals("no")){
                    warnBtn.setEnabled(false);
                    warnBtn.setText("추천드릴 보험이 없습니다.");
                    warnBtn.setBackgroundColor(Color.rgb(156,156,156));
                    warnBtn.setTextColor(Color.WHITE);
                }
                else warnBtn.setVisibility(View.VISIBLE);
            }
            if ((pref != null) && (pref.contains("rcDanger"))) {
                rcDanger = pref.getString("rcDanger", "");
                if (rcDanger.equals("[]")) danBtn.setVisibility(View.GONE);
                else danBtn.setVisibility(View.VISIBLE);
            }
            if ((pref != null) && (pref.contains("savedFeedback"))) {
                feedback = pref.getString("savedFeedback", "");
                fdb.setText(feedback);
            }
        }
    }
    protected void clearMyPrefs(){
        SharedPreferences preferences = getSharedPreferences("pref",Activity.MODE_PRIVATE);
        SharedPreferences.Editor editor = preferences.edit();
        editor.clear();
        editor.commit();
    }
}
