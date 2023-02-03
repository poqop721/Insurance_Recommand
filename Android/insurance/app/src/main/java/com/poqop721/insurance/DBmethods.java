package com.poqop721.insurance;

import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;

import java.util.HashMap;
import java.util.Map;

public class DBmethods extends AppCompatActivity {
    static RequestQueue requestQueue;

    public DBmethods(RequestQueue rq){
        this.requestQueue = rq;
    }
    public boolean login() {

        final boolean[] isLogin = {false};
        //php url 입력
        String URL = "http://poqop721.dothome.co.kr/insurance/android/login.php";

        StringRequest request = new StringRequest(Request.Method.POST, URL, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                //응답이 되었을때 response로 값이 들어옴
                isLogin[0] = true;
                Toast.makeText(getApplicationContext(), response, Toast.LENGTH_SHORT).show();
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                //에러나면 error로 나옴
                Toast.makeText(getApplicationContext(), "에러:" + error.getMessage(), Toast.LENGTH_SHORT).show();

            }

        }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> param = new HashMap<String, String>();
                //php로 설정값을 보낼 수 있음
                return param;
            }
        };


        request.setShouldCache(false);
        requestQueue.add(request);
        return isLogin[0];
    }
}
