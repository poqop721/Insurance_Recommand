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
import android.widget.Button;
import android.widget.CompoundButton;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Switch;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

import androidx.appcompat.app.AppCompatActivity;

public class ChangeInfoActivity extends AppCompatActivity {
    private static final String TAG = "CHANGE";
    private RequestQueue requestQueue;
    Button changeBtn,goBackBtn,signupSubmit,goBackHome;
    TextView changeTitle,idTitle,curPwTitle,pwTitle,pwCheckTitle;
    EditText id, pw, pwCheck, name, height, weight, email,phone,curPw;
    LinearLayout switchLayout;
    Switch pwSwitch;
    DatePicker birth;
    RadioGroup sex;
    RadioButton sex_man,sex_woman;
    ArrayList<String> myInfo;
    String sessionId;

    protected void onCreate(Bundle savedInstanceState){
        super.onCreate(savedInstanceState);
        setContentView(R.layout.signup);
        setTitle("개인 정보 수정");

        getSupportActionBar().setBackgroundDrawable(new ColorDrawable(getResources().getColor(R.color.my_purple)));
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            Window window = this.getWindow();
            window.addFlags(WindowManager.LayoutParams.FLAG_DRAWS_SYSTEM_BAR_BACKGROUNDS);
            window.setStatusBarColor(getResources().getColor(R.color.my_purple_stbar));
        }

        Intent changeInfo = getIntent();
        sessionId = changeInfo.getStringExtra("sessionId");
        myInfo = changeInfo.getStringArrayListExtra("myInfo");

        idTitle = (TextView) findViewById(R.id.idTitle);
        changeTitle = (TextView) findViewById(R.id.changeTitle);
        id = (EditText) findViewById(R.id.signupID);
        pwSwitch = (Switch)findViewById(R.id.pwSwitch);
        curPwTitle = (TextView) findViewById(R.id.currentPwTitle);
        curPw = (EditText) findViewById(R.id.currentPw);
        switchLayout = (LinearLayout)findViewById(R.id.pwSwitchLayout);
        pwTitle = (TextView) findViewById(R.id.signupPWTitle);
        pw = (EditText) findViewById(R.id.signupPW);
        pwCheckTitle = (TextView) findViewById(R.id.signupPwCheckTitle);
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
        signupSubmit = (Button) findViewById(R.id.signupSubmit);
        goBackHome = (Button) findViewById(R.id.goBackHome);
        changeBtn = (Button) findViewById(R.id.changeSubmitBtn);
        goBackBtn = (Button) findViewById(R.id.changeBackBtn);

        changeTitle.setText("개인 정보 수정");
        changeTitle.setTextColor(Color.parseColor("#6368dc"));
        id.setVisibility(View.GONE);
        idTitle.setVisibility(View.GONE);
        curPwTitle.setVisibility(View.VISIBLE);
        curPw.setVisibility(View.VISIBLE);
        switchLayout.setVisibility(View.VISIBLE);
        pwTitle.setVisibility(View.GONE);
        pw.setVisibility(View.GONE);
        pwCheckTitle.setVisibility(View.GONE);
        pwCheck.setVisibility(View.GONE);
        pwSwitch.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton compoundButton, boolean isChecked) {
                if (isChecked) {
                    pwTitle.setText("새 비밀번호");
                    pwTitle.setVisibility(View.VISIBLE);
                    pwCheckTitle.setText("비밀번호 확인");
                    pwCheckTitle.setVisibility(View.VISIBLE);
                    pw.setVisibility(View.VISIBLE);
                    pwCheck.setVisibility(View.VISIBLE);
                } else {
                    pwTitle.setVisibility(View.GONE);
                    pwCheckTitle.setVisibility(View.GONE);
                    pw.setVisibility(View.GONE);
                    pwCheck.setVisibility(View.GONE);
                }
            }
        });
        Log.d("testtest",myInfo.get(1));
        name.setHint(myInfo.get(1));
        name.setText(myInfo.get(1));
        String[] birthDate = myInfo.get(2).split("-");
        birth.updateDate(Integer.parseInt(birthDate[0]), Integer.parseInt(birthDate[1])-1,
                Integer.parseInt(birthDate[2]));
        height.setHint(myInfo.get(4));
        height.setText(myInfo.get(4));
        weight.setHint(myInfo.get(3));
        weight.setText(myInfo.get(3));
        if(myInfo.get(5).equals("남성")){
            sex_man.setChecked(true);
            sex_woman.setChecked(false);
        }else {
            sex_man.setChecked(false);
            sex_woman.setChecked(true);
        }
        email.setHint(myInfo.get(6));
        email.setText(myInfo.get(6));
        phone.setHint(myInfo.get(7));
        phone.setText(myInfo.get(7));
        changeBtn.setVisibility(View.VISIBLE);
        changeBtn.setText("수정하기");
        goBackBtn.setVisibility(View.VISIBLE);
        goBackBtn.setTextColor(Color.WHITE);
        goBackBtn.setBackgroundColor(Color.parseColor("#6368dc"));
        goBackBtn.setBackgroundResource(R.drawable.mypage_editbtn);
        signupSubmit.setVisibility(View.GONE);
        goBackHome.setVisibility(View.GONE);

        requestQueue = Volley.newRequestQueue(this);
        String url = "http://poqop721.dothome.co.kr/insurance/android/changeInfo.php";

        final StringRequest stringRequest = new StringRequest(Request.Method.POST, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                if(response.equals("true")){
                    Toast.makeText(getApplicationContext(), "정보 수정이 완료되었습니다.\n 다시 로그인 해 주세요.", Toast.LENGTH_SHORT).show();
                    Intent intent = new Intent(getApplicationContext(),MainActivity.class);
                    intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                    startActivity(intent);
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
                params.put("ID", sessionId);
                params.put("CPW", curPw.getText().toString());
                if(pwSwitch.isChecked()) {
                    params.put("NPW", pw.getText().toString());
                    params.put("NPWC", pwCheck.getText().toString());
                }
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

        changeBtn.setOnClickListener(new View.OnClickListener() {
            @Override

            public void onClick(View v) {
                if(checkNull())
                    requestQueue.add(stringRequest);
            }
        });

        goBackBtn.setOnClickListener(new View.OnClickListener() {
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
        if(curPw.getText().toString().length()==0) {
            Toast.makeText(getApplicationContext(), "기존 비밀번호를 입력해 주세요.", Toast.LENGTH_SHORT).show();
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
        else if(pwSwitch.isChecked()){
            if(pw.getText().toString().length()==0) {
                Toast.makeText(getApplicationContext(), "새 비밀번호를 입력해주세요.", Toast.LENGTH_SHORT).show();
                return false;
            }
            else if(pwCheck.getText().toString().length()==0) {
                Toast.makeText(getApplicationContext(), "비밀번호 확인을 입력해주세요.", Toast.LENGTH_SHORT).show();
                return false;
            }
            else return true;
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
