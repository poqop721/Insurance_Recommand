package com.poqop721.insurance;

import android.content.Intent;
import android.graphics.drawable.ColorDrawable;
import android.os.Build;
import android.os.Bundle;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import java.util.HashMap;
import java.util.Map;

import androidx.appcompat.app.AppCompatActivity;

public class SignUpActivity extends AppCompatActivity {
    private static final String TAG = "SIGNUP";
    private RequestQueue requestQueue;
    Button submit,goBack;
    EditText id, pw, pwCheck, name, height, weight, email,phone;
    DatePicker birth;
    RadioGroup sex;
    RadioButton sex_man,sex_woman;


    protected void onCreate(Bundle savedInstanceState){
        super.onCreate(savedInstanceState);
        setContentView(R.layout.signup);
        setTitle("회원가입");

        getSupportActionBar().setBackgroundDrawable(new ColorDrawable(getResources().getColor(R.color.my_blue)));
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            Window window = this.getWindow();
            window.addFlags(WindowManager.LayoutParams.FLAG_DRAWS_SYSTEM_BAR_BACKGROUNDS);
            window.setStatusBarColor(getResources().getColor(R.color.my_blue_stbar));
        }

        submit = (Button) findViewById(R.id.signupSubmit);
        goBack = (Button) findViewById(R.id.goBackHome);

        id = (EditText) findViewById(R.id.signupID);
        pw = (EditText) findViewById(R.id.signupPW);
        pwCheck = (EditText) findViewById(R.id.signupPwCheck);
        name = (EditText) findViewById(R.id.signUpName);
        birth = (DatePicker) findViewById(R.id.signUpBirth);
        height = (EditText) findViewById(R.id.signUpHeight);
        weight = (EditText) findViewById(R.id.signUpWeight);
        sex = (RadioGroup) findViewById(R.id.signupSex);
        sex_man = (RadioButton) findViewById(R.id.signupMan);
        sex_woman = (RadioButton) findViewById(R.id.signupWoman);
        email = (EditText) findViewById(R.id.signUpEmail);
        phone = (EditText) findViewById(R.id.signUpPhone);

        requestQueue = Volley.newRequestQueue(this);
        String url = "http://poqop721.dothome.co.kr/insurance/android/signup.php";

        final StringRequest stringRequest = new StringRequest(Request.Method.POST, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                if(response.equals("true")){
                    Toast.makeText(getApplicationContext(), "회원가입이 완료되었습니다.", Toast.LENGTH_SHORT).show();
                    finish();
                }
                else Toast.makeText(getApplicationContext(), response, Toast.LENGTH_SHORT).show();
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
                params.put("ID", id.getText().toString());
                params.put("PW", pw.getText().toString());
                params.put("PWC", pwCheck.getText().toString());
                params.put("name", name.getText().toString());
                params.put("birth", getBirth());
                params.put("height", height.getText().toString());
                params.put("weight", weight.getText().toString());
                if(sex_man.isChecked()) params.put("sex", "남성");
                else if(sex_woman.isChecked()) params.put("sex", "여성");
                params.put("email", email.getText().toString());
                params.put("phone", phone.getText().toString());
                return params;
            }
        };

        stringRequest.setTag(TAG);

        submit.setOnClickListener(new View.OnClickListener() {
            @Override

            public void onClick(View v) {
                if(checkNull())
                    requestQueue.add(stringRequest);
            }
        });

        goBack.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
    }
    protected void onStop() {
        super.onStop();
        if (requestQueue != null) {
            requestQueue.cancelAll(TAG);
        }
    }

    protected boolean checkNull(){
        if(id.getText().toString().length()==0) {
            Toast.makeText(getApplicationContext(), "아이디를 입력해주세요.", Toast.LENGTH_SHORT).show();
            return false;
        }
        else if(pw.getText().toString().length()==0) {
            Toast.makeText(getApplicationContext(), "비밀번호를 입력해주세요.", Toast.LENGTH_SHORT).show();
            return false;
        }
        else if(pwCheck.getText().toString().length()==0) {
            Toast.makeText(getApplicationContext(), "비밀번호 확인을 입력해주세요.", Toast.LENGTH_SHORT).show();
            return false;
        }
        else if(name.getText().toString().length()==0) {
            Toast.makeText(getApplicationContext(), "이름을 입력해주세요.", Toast.LENGTH_SHORT).show();
            return false;
        }
        else if(height.getText().toString().length()==0) {
            Toast.makeText(getApplicationContext(), "키를 입력해주세요.", Toast.LENGTH_SHORT).show();
            return false;
        }
        else if(weight.getText().toString().length()==0) {
            Toast.makeText(getApplicationContext(), "몸무게를 입력해주세요.", Toast.LENGTH_SHORT).show();
            return false;
        }
        else if(!sex_man.isChecked() && !sex_woman.isChecked()) {
            Toast.makeText(getApplicationContext(), "성별을 선택해주세요.", Toast.LENGTH_SHORT).show();
            return false;
        }
        else if(email.getText().toString().length()==0) {
            Toast.makeText(getApplicationContext(), "이메일을 입력해주세요.", Toast.LENGTH_SHORT).show();
            return false;
        }
        else if(phone.getText().toString().length()==0) {
            Toast.makeText(getApplicationContext(), "전화번호를 입력해주세요.", Toast.LENGTH_SHORT).show();
            return false;
        }
        else return true;
    }
    protected String getBirth(){
        int month = birth.getMonth()+1;
        String monthStr = String.valueOf(month);
        if(month<10) monthStr = "0" + monthStr;
        String date = String.format("%d-%s-%d",birth.getYear(),
                monthStr,birth.getDayOfMonth());
        return date;
    }
}
