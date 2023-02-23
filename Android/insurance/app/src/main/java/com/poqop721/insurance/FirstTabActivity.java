package com.poqop721.insurance;

import android.content.Intent;
import android.graphics.Color;
import android.graphics.Typeface;
import android.graphics.drawable.ColorDrawable;
import android.os.BadParcelableException;
import android.os.Build;
import android.os.Bundle;
import android.os.Parcelable;
import android.text.Editable;
import android.text.Spannable;
import android.text.SpannableString;
import android.text.TextWatcher;
import android.text.style.ForegroundColorSpan;
import android.text.style.RelativeSizeSpan;
import android.text.style.StyleSpan;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.Button;
import android.widget.EditText;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.ViewFlipper;

import androidx.annotation.Nullable;
import androidx.annotation.RequiresApi;
import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class FirstTabActivity extends AppCompatActivity {
    private static final String TAG = "TAB1";
    private RequestQueue requestQueue;
    Button tab1Return, tab1Next, tab1Prev;
    ViewFlipper tab1vFlipper;
    TextView slideTitle, tab1desc, tab2desc, tab3desc, tab4desc, tab5desc,
            tab6desc, tab7desc, tab8_1desc,tab8_2desc,tab8_3desc;
    EditText inputAge, inputHeight, inputWeight, BP,BOS,BFP,muscle,SMM,MBW,BM;
    RadioButton sex_man, sex_woman;
    Animation slideInLeft, slideInRight, slideOutLeft,slideOutRight;
    Intent intentTab2;
    String name, sex,age, weight, height;

    String feedback;
    List<String> warningList;
    List<String> dangerList;
    List<String> warningFeedback;
    List<String> dangerFeedback;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.firsttab);

        getSupportActionBar().setBackgroundDrawable(new ColorDrawable(getResources().getColor(R.color.my_blue)));
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            Window window = this.getWindow();
            window.addFlags(WindowManager.LayoutParams.FLAG_DRAWS_SYSTEM_BAR_BACKGROUNDS);
            window.setStatusBarColor(getResources().getColor(R.color.my_blue_stbar));
        }

        Intent tab1Intent = getIntent();
        String sessionId = tab1Intent.getStringExtra("sessionId");

        slideTitle = (TextView) findViewById(R.id.slideTitle);
        tab1desc = (TextView) findViewById(R.id.tab1desc);
        tab2desc = (TextView) findViewById(R.id.tab2desc);
        tab3desc = (TextView) findViewById(R.id.tab3desc);
        tab4desc = (TextView) findViewById(R.id.tab4desc);
        tab5desc = (TextView) findViewById(R.id.tab5desc);
        tab6desc = (TextView) findViewById(R.id.tab6desc);
        tab7desc = (TextView) findViewById(R.id.tab7desc);
        tab8_1desc = (TextView) findViewById(R.id.tab8_1desc);
        tab8_2desc = (TextView) findViewById(R.id.tab8_2desc);
        tab8_3desc = (TextView) findViewById(R.id.tab8_3desc);
        inputAge = (EditText) findViewById(R.id.age);
        inputHeight = (EditText) findViewById(R.id.height);
        inputWeight = (EditText) findViewById(R.id.weight);
        sex_man = (RadioButton) findViewById(R.id.sex_man);
        sex_woman = (RadioButton) findViewById(R.id.sex_woman);
        BP = (EditText) findViewById(R.id.BP);
        BOS = (EditText) findViewById(R.id.BOS);
        BFP = (EditText) findViewById(R.id.BFP);
        muscle = (EditText) findViewById(R.id.muscle);
        SMM = (EditText) findViewById(R.id.SMM);
        MBW = (EditText) findViewById(R.id.MBW);
        BM = (EditText) findViewById(R.id.BM);


        slideInLeft = AnimationUtils.loadAnimation(this, R.anim.slide_in_left);
        slideInRight = AnimationUtils.loadAnimation(this, R.anim.slide_in_right);
        slideOutLeft = AnimationUtils.loadAnimation(this, R.anim.slide_out_left);
        slideOutRight = AnimationUtils.loadAnimation(this, R.anim.slide_out_right);

        tab1Next = (Button) findViewById(R.id.tab1NextBtn);
        tab1Prev = (Button) findViewById(R.id.tab1PrevBtn);
        tab1vFlipper = (ViewFlipper)findViewById(R.id.tab1Flipper);


        requestQueue = Volley.newRequestQueue(this);
        String url = "http://poqop721.dothome.co.kr/insurance/android/tab1info.php";

        final StringRequest stringRequest = new StringRequest(Request.Method.POST, url, new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            JSONObject jsonObject = new JSONObject(response);
                            name = jsonObject.getString("name");
                            age = jsonObject.getString("age");
                            sex = jsonObject.getString("sex");
                            height = jsonObject.getString("height");
                            weight = jsonObject.getString("weight");
                            vFlipperTitle();
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(getApplicationContext(), "서버와 연결하는데 문제가 발생했습니다.", Toast.LENGTH_SHORT).show();
            }
        }){
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<String, String>();
                params.put("ID", sessionId);
                return params;
            }
        };

        stringRequest.setTag(TAG);

        requestQueue.add(stringRequest);

        tab1Next.setOnClickListener(new View.OnClickListener(){
            @RequiresApi(api = Build.VERSION_CODES.N)
            @Override
            public void onClick(View v) {
                if(tab1vFlipper.getDisplayedChild() == 0){
                    if(inputAge.getText().length()==0 || inputHeight.getText().length()==0 ||
                            inputWeight.getText().length()==0 || (!sex_man.isChecked() && !sex_woman.isChecked())){
                        Toast.makeText(getApplicationContext(),"모든 정보를 입력해주세요.",Toast.LENGTH_SHORT).show();
                    }
                    else{
                        tab1vFlipper.setInAnimation(slideInRight);
                        tab1vFlipper.setOutAnimation(slideOutLeft);
                        tab1vFlipper.showNext();
                        vFlipperTitle();
                    }
                }
                else {
                    if (tab1vFlipper.getDisplayedChild() != 7) {
                        tab1vFlipper.setInAnimation(slideInRight);
                        tab1vFlipper.setOutAnimation(slideOutLeft);
                        tab1vFlipper.showNext();
                        vFlipperTitle();
                    } else {
                        calcResult();
                        intentTab2 = new Intent(getApplicationContext(), SecondTabActivity.class);
                        intentTab2.putExtra("sessionId",sessionId);
                        intentTab2.putExtra("BP",String.valueOf(BP.getText()));
                        intentTab2.putExtra("BOS",String.valueOf(BOS.getText()));
                        intentTab2.putExtra("BFP",String.valueOf(BFP.getText()));
                        intentTab2.putExtra("SMM",String.valueOf(SMM.getText()));
                        intentTab2.putExtra("MBW",String.valueOf(MBW.getText()));
                        intentTab2.putExtra("BM",String.valueOf(BM.getText()));
                        intentTab2.putExtra("feedback",feedback);
                        intentTab2.putExtra("warning", (Serializable) warningList);
                        intentTab2.putExtra("danger", (Serializable) dangerList);
                        intentTab2.putExtra("warningFeedback", (Serializable) warningFeedback);
                        intentTab2.putExtra("dangerFeedback", (Serializable) dangerFeedback);
                        intentTab2.putExtra("clear","true");
                        startActivity(intentTab2);
                    }
                }
            }
        });

        tab1Prev.setOnClickListener(new View.OnClickListener(){
            @Override
            public void onClick(View v) {
                tab1vFlipper.setInAnimation(slideInLeft);
                tab1vFlipper.setOutAnimation(slideOutRight);
                tab1vFlipper.showPrevious();
                vFlipperTitle();
            }
        });
        muscle.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {

            }

            @Override
            public void onTextChanged(CharSequence arg0, int arg1, int arg2, int arg3) {
                if(muscle.getText().length() != 0) {
                    Double calcSMM = (Double.parseDouble(String.valueOf(muscle.getText())) * 0.577);
                    double calcBFP = Math.round(calcSMM * 1000) / 1000.0;
                    SMM.setText(String.valueOf(calcBFP));
                }else SMM.setText("");
            }

            @Override
            public void afterTextChanged(Editable arg0) {
// TODO Auto-generated method stub

            }
            });
        }
    protected void onStop() {
        super.onStop();
        if (requestQueue != null) {
            requestQueue.cancelAll(TAG);
        }
    }
    public void vFlipperTitle() {
        String inAge, inHeight, inWeight, inSex;
        inAge = String.valueOf(inputAge.getText());
        inHeight = String.valueOf(inputHeight.getText());
        inWeight = String.valueOf(inputWeight.getText());
        if(sex_man.isChecked()) inSex = "남성";
        else inSex = "여성";
        switch (tab1vFlipper.getDisplayedChild()) {
            case 0:
                slideTitle.setText("1/8");
                tab1Prev.setVisibility(View.INVISIBLE);
                tab1desc.setText(nameColor("다음은 "+name+" 회원님의 신체 정보입니다.\n" +
                        "수정이 필요하신 경우 수정하신 후 위 '다음' 버튼을 터치해주세요.",name));
                inputAge.setText(age);
                inputHeight.setText(height);
                inputWeight.setText(weight);
                switch (sex){
                    case "남성":
                        sex_man.setChecked(true);
                        break;
                    case "여성":
                        sex_woman.setChecked(true);
                        break;
                }
                break;
            case 1:
                slideTitle.setText("2/8");
                tab1Prev.setVisibility(View.VISIBLE);
                tab2desc.setText("· 혈압 측정 전 최소 5분 동안 안정하며,조용한 \n\t\t환경에서 측정합니다.\n" +
                        "· 측정 30분 전 카페인 섭취, 운동, 흡연, 목욕, \n\t\t음주를 삼가야 합니다.\n" +
                        "· 혈압 측정 중에는 이야기를 하지 않아야 \n\t\t합니다.\n" +
                        "· 등은 바르게 기대고 앉아서 측정합니다.\n" +
                        "· 양발은 평평한 평지 위에 내리고, 발을 꼬지 \n\t\t앉습니다.\n" +
                        "· 위팔을 테이블에 놓고 와이셔츠 정도의 얇은 \n\t\t옷 위에서 측정합니다.");
                break;
            case 2:
                slideTitle.setText("3/8");
                tab3desc.setText("· 혈중산소포화도란 혈중 산소 농도를 \n\t\t가리킵니다.\n" +
                        "· 혈중산소포화도의 정상범위는 95~100 \n\t\t입니다.\n" +
                        "· 측정 수치가 90~95일 때는 저산소증 주의 \n\t\t단계입니다.\n" +
                        "· 측정 수치가 80~90 일 때는 저산소증으로 \n\t\t호흡이 곤란한 상태가 됩니다.\n" +
                        "· 측정 수치가 80 이하로 떨어지면 위독한 \n\t\t상태 입니다.");
                break;
            case 3:
                slideTitle.setText("4/8");
                double meterHeight = (Integer.parseInt(inHeight)) * 0.01;
                double calcBFP = Math.round(((Integer.parseInt(inWeight)) / (meterHeight*meterHeight))*100)/100.0;
                tab4desc.setText(nameColor(name+" 회원님의 키와 체중으로 계산된 체지방률은 "+calcBFP+"% 입니다.\n" +
                        "맞으시다면 다음으로 넘어가 주시고\n수정이 필요할 시 수정해주세요.\n\n" +
                        "· 체지방률 계산법\n체중(kg)를 키(m)의 제곱으로 나눈 값",name));
                BFP.setText(String.valueOf(calcBFP));
                break;
            case 4:
                slideTitle.setText("5/8");
                tab5desc.setText("골격근량 계산법은 근육량 X 0.577입니다.\n" +
                        "근육량을 입력하시면 골격근량을 계산해드립니다.\n" +
                        "혹은 골격근량을 아시는 경우 '골격근량' 칸에 바로 입력해주세요.");
                break;
            case 5:
                slideTitle.setText("6/8");
                tab6desc.setText("· 체수분량은 신체의 조직, 혈액, 근육 등 \n모든 곳에 존재하는 수분의 양을 말합니다.\n" +
                        "· 남성 적정 체수분량 : 60%\n· 여성 적정 체수분량 : 50~55%\n" +
                        "· 내 몸에 맞는 하루 물 섭취량을 알아보려면 \n체중(kg)에 0.03을 곱하면 됩니다.");
                break;
            case 6:
                slideTitle.setText("7/8");
                Double calcBM = 0.0;
                if(inSex.equals("남성")){
                    calcBM = 66.47+(13.75*Double.parseDouble(inWeight))+(5*Double.parseDouble(inHeight))-(6.76*Double.parseDouble(inAge));
                } else if(inSex.equals("여성")){
                    calcBM = 655.1+(9.56*Double.parseDouble(inWeight))+(1.85*Double.parseDouble(inHeight))-(4.68*Double.parseDouble(inAge));
                }
                double calcBMCon = Math.round(calcBM*100)/100.0;
                tab7desc.setText(nameColor(name+" 회원님의 체중, 키, 나이, 성별로 계산된\n기초대사량은 "+calcBMCon+"kcal 입니다.\n" +
                        "맞으시다면 다음으로 넘어가 주시고\n수정이 필요할 시 수정해주세요.\n" +
                        "\n" +
                        "· 남성 계산법\n66.47 + (13.75 X 체중) + (5 X 키) - (6.76 X 나이)\n" +
                        "· 여성 계산법\n655.1 + (9.56 X 체중) + (1.85 X 키)-(4.68 X 나이)",name));
                BM.setText(String.valueOf(calcBMCon));
                tab1Next.setText("다음");
                tab1Next.setBackground(getResources().getDrawable(R.drawable.first_tab_btn));
                break;
            case 7:
                slideTitle.setText("8/8");
                tab8_1desc.setText(nameColor(name+" 회원님의 건강 정보입니다.",name));
                String fBP = String.valueOf(BP.getText());
                String fBOS = String.valueOf(BOS.getText());
                String fBFP = String.valueOf(BFP.getText());
                String fSMM = String.valueOf(SMM.getText());
                String fMBW = String.valueOf(MBW.getText());
                String fBM = String.valueOf(BM.getText());
                if(BP.getText().length() == 0 || String.valueOf(BP.getText()).equals("0")){
                    BP.setText("0");
                    fBP = "입력안됨";
                }
                if(BOS.getText().length() == 0 || String.valueOf(BOS.getText()).equals("0")){
                    BOS.setText("0");
                    fBOS = "입력안됨";
                }
                if(BFP.getText().length() == 0 || String.valueOf(BFP.getText()).equals("0")){
                    BFP.setText("0");
                    fBFP = "입력안됨";
                }
                if(SMM.getText().length() == 0 || String.valueOf(SMM.getText()).equals("0")){
                    SMM.setText("0");
                    fSMM = "입력안됨";
                }
                if(MBW.getText().length() == 0 || String.valueOf(MBW.getText()).equals("0")){
                    MBW.setText("0");
                    fMBW = "입력안됨";
                }
                if(BM.getText().length() == 0 || String.valueOf(BM.getText()).equals("0")){
                    BM.setText("0");
                    fBM = "입력안됨";
                }
                String finalCheck = "· "+inAge+"세, "+inSex+"\n" +
                        "· 키는 "+inHeight+" cm, 체중은 "+inWeight+" kg\n" +
                        "· 혈압 : "+fBP+" mmhg\n" +
                        "· 혈중산소포화도 : "+fBOS+" %\n" +
                        "· 체지방률 : "+fBFP+" %\n" +
                        "· 골격근량 : "+fSMM+" kg\n" +
                        "· 체수분량 : "+fMBW+" %\n" +
                        "· 기초대사량 : "+fBM+" kcal";
                tab8_2desc.setText(finalCheck);
                tab8_3desc.setText("전부 맞으시다면 위 제출 버튼을 눌러주세요.");
                tab1Next.setText("제출");
                tab1Next.setBackground(getResources().getDrawable(R.drawable.result_danger_btn));
                break;
            default:
                break;
        }
    }
    public SpannableString nameColor(String content, String word){
        SpannableString spannableString = new SpannableString(content);
        int start = content.indexOf(word);
        int end = start + word.length();
        spannableString.setSpan(new ForegroundColorSpan(Color.parseColor("#1263ef")), start, end, Spannable.SPAN_EXCLUSIVE_EXCLUSIVE);
        spannableString.setSpan(new RelativeSizeSpan(1.2f), start, end, SpannableString.SPAN_EXCLUSIVE_EXCLUSIVE);
        return spannableString;
    }
    @RequiresApi(api = Build.VERSION_CODES.N)
    public void calcResult(){
        int cBP = Integer.parseInt(String.valueOf(BP.getText()));
        int cBOS = Integer.parseInt(String.valueOf(BOS.getText()));
        Double cBFP = Double.parseDouble(String.valueOf(BFP.getText()));
        Double cSMM = Double.parseDouble(String.valueOf(SMM.getText()));
        int cMBW = Integer.parseInt(String.valueOf(MBW.getText()));
        Double cBM = Double.parseDouble(String.valueOf(BM.getText()));

        Map<String, String> healthDict = new HashMap<>();
        //혈압 추천
        if(cBP == 0) healthDict.put("BP","입력안됨");
        else if(cBP < 120) healthDict.put("BP","정상");
        else if(120 <= cBP && cBP <= 140) healthDict.put("BP","주의");
        else healthDict.put("BP","고혈압");

        //혈중산소포화도
        if(cBOS == 0) healthDict.put("BOS","입력안됨");
        else if(cBOS >= 95) healthDict.put("BOS","정상");
        else if (90 <= cBOS && cBOS < 95) healthDict.put("BOS","주의");
        else if (80 < cBOS && cBOS < 90) healthDict.put("BOS","저산소증");
        else healthDict.put("BOS","위독");

        if(sex_man.isChecked()){
            //체지방률
            if(Integer.parseInt(String.valueOf(inputAge.getText())) >= 30){
                if(cBFP < 17) healthDict.put("BFP","여윔");
                else if(17 <= cBFP && cBFP < 23) healthDict.put("BFP","표준");
                else if (23 <= cBFP && cBFP < 28) healthDict.put("BFP","경비만");
                else if (28 <= cBFP && cBFP < 38) healthDict.put("BFP","중비만");
                else healthDict.put("BFP","과비만");
            }
            else{
                if(cBFP < 14) healthDict.put("BFP","여윔");
                else if(14 <= cBFP && cBFP < 20) healthDict.put("BFP","표준");
                else if (20 <= cBFP && cBFP < 25) healthDict.put("BFP","경비만");
                else if (25 <= cBFP && cBFP < 35) healthDict.put("BFP","중비만");
                else healthDict.put("BFP","과비만");
            }
            //골격근량
            if(cSMM == 0) healthDict.put("SMM","입력안됨");
            else if(Integer.parseInt(String.valueOf(inputWeight.getText()))*0.4 <= cSMM) healthDict.put("SMM","정상");
            else healthDict.put("SMM","위험");
            //체수분
            if(cMBW == 0) healthDict.put("MBW","입력안됨");
            else if(50 <= cMBW && cMBW <= 70) healthDict.put("MBW","정상");
            else if (cMBW < 50) healthDict.put("MBW","낮음");
            else if (cMBW > 50) healthDict.put("MBW","높음");
            //기초대사량
            if(Integer.parseInt(String.valueOf(inputAge.getText())) < 30){
                if((1728-368.2)<=cBM && cBM <=(1728+368.2)) healthDict.put("BM","정상");
                else if (cBM > (1728+368.2)) healthDict.put("BM","높음");
                else healthDict.put("BM","낮음");
            } else if (30 <= Integer.parseInt(String.valueOf(inputAge.getText())) && Integer.parseInt(String.valueOf(inputAge.getText())) < 50){
                if((1669.5-302.1)<=cBM && cBM <=(1669.5+302.1)) healthDict.put("BM","정상");
                else if (cBM > (1669.5+302.1)) healthDict.put("BM","높음");
                else healthDict.put("BM","낮음");
            } else {
                if((1493.8-315.3)<=cBM && cBM <=(1493.8+315.3)) healthDict.put("BM","정상");
                else if (cBM > (1493.8+315.3)) healthDict.put("BM","높음");
                else healthDict.put("BM","낮음");
            }
        } else if(sex_woman.isChecked()){
            //체지방률
            if(Integer.parseInt(String.valueOf(inputAge.getText())) >= 30){
                if(cBFP < 20) healthDict.put("BFP","여윔");
                else if(20 <= cBFP && cBFP < 27) healthDict.put("BFP","표준");
                else if (27 <= cBFP && cBFP < 33) healthDict.put("BFP","경비만");
                else if (33 <= cBFP && cBFP < 43) healthDict.put("BFP","중비만");
                else healthDict.put("BFP","과비만");
            }
            else{
                if(cBFP < 17) healthDict.put("BFP","여윔");
                else if(17 <= cBFP && cBFP < 24) healthDict.put("BFP","표준");
                else if (24 <= cBFP && cBFP < 30) healthDict.put("BFP","경비만");
                else if (30 <= cBFP && cBFP < 40) healthDict.put("BFP","중비만");
                else healthDict.put("BFP","과비만");
            }
            //골격근량
            if(cSMM == 0) healthDict.put("SMM","입력안됨");
            else if(Integer.parseInt(String.valueOf(inputWeight.getText()))*0.35 <= cSMM) healthDict.put("SMM","정상");
            else healthDict.put("SMM","위험");
            //체수분
            if(45 <= cMBW && cMBW <= 65) healthDict.put("MBW","정상");
            else if (cMBW < 45) healthDict.put("MBW","낮음");
            else healthDict.put("MBW","높음");
            //기초대사량
            if(Integer.parseInt(String.valueOf(inputAge.getText())) < 30){
                if((1311.5-233)<=cBM && cBM <=(1311.5+233)) healthDict.put("BM","정상");
                else if (cBM > (1311.5+233)) healthDict.put("BM","높음");
                else healthDict.put("BM","낮음");
            } else if (30 <= Integer.parseInt(String.valueOf(inputAge.getText())) && Integer.parseInt(String.valueOf(inputAge.getText())) < 50){
                if((1316.8-225.9)<=cBM && cBM <=(1316.8+225.9)) healthDict.put("BM","정상");
                else if (cBM > (1316.8+225.9)) healthDict.put("BM","높음");
                else healthDict.put("BM","낮음");
            } else {
                if((1252.5-228.6)<=cBM && cBM <=(1252.5+228.6)) healthDict.put("BM","정상");
                else if (cBM > (1252.5+228.6)) healthDict.put("BM","높음");
                else healthDict.put("BM","낮음");
            }
        }

        feedback = "";
        String resname = "";
        String str = "";
        String dan = "";
        String dan2 = "";
        String[] sortArr = new String[6];
        for(Map.Entry<String,String> entry : healthDict.entrySet()){
            if(!entry.getValue().equals("입력안됨")){
                switch (entry.getKey()) {
                    case "BP":
                        resname = "혈압";
                        dan = "고혈압";
                        dan2 = "뇌혈관";
                        break;
                    case "BOS":
                        resname = "혈중 산소 포화도";
                        dan = "심혈관";
                        dan2 = "뇌혈관";
                        break;
                    case "BFP" :
                        resname = "체지방률";
                        dan = "당뇨";
                        dan2 = "실비";
                        break;
                    case "SMM" :
                        resname = "골격근량";
                        dan = "실비";
                        dan2 = null;
                        break;
                    case "MBW" :
                        resname = "체수분";
                        if(entry.getValue().equals("낮음")) dan = "고혈압";
                        else if (entry.getValue().equals("높음")) dan = "심혈관";
                        else dan = null;
                        dan2 = null;
                        break;
                    case "BM" :
                        resname = "기초대사량";
                        dan = "심혈관";
                        dan2 = null;
                        break;
                }
                if(entry.getValue().equals("정상") || entry.getValue().equals("표준") || entry.getValue().equals("입력안됨")) str = resname + " : " + entry.getValue() + "\n";
                else if (entry.getValue().equals("고혈압") || entry.getValue().equals("위독") || entry.getValue().equals("과비만") || entry.getValue().equals("위험")){
                    str = resname + " : "+ entry.getValue() + "\n";
                    if (dan != null) {
                        dangerList.add(dan);
                        dangerFeedback.add(resname);
                    }
                    if(dan2 != null) {
                        dangerList.add(dan2);
                        dangerFeedback.add(resname);
                    }
                }
                else {
                    str = resname + " : "+ entry.getValue() + "\n";
                    if (dan != null) {
                        warningList.add(dan);
                        warningFeedback.add(resname);
                    }
                    if(dan2 != null) {
                        warningList.add(dan2);
                        warningFeedback.add(resname);
                    }
                }
                if(entry.getKey().equals("BP")) sortArr[0] = str;
                else if(entry.getKey().equals("BOS")) sortArr[1] = str;
                else if(entry.getKey().equals("BFP")) sortArr[2] = str;
                else if(entry.getKey().equals("SMM")) sortArr[3] = str;
                else if(entry.getKey().equals("MBW")) sortArr[4] = str;
                else if(entry.getKey().equals("BM")) sortArr[5] = str;
            }
        }
        for(int i = 0;i<sortArr.length;i++){
            if(sortArr[i] != null) feedback = feedback + sortArr[i];
        }
        feedback = feedback.substring(0, feedback.length() - 1);
    }
    protected void onResume(){
        super.onResume();
        tab1vFlipper.setDisplayedChild(0);
        feedback ="";
        warningList = new ArrayList<String>();
        dangerList = new ArrayList<String>();
        warningFeedback = new ArrayList<String>();
        dangerFeedback = new ArrayList<String>();
        slideTitle.setText("1/8");
        tab1Prev.setVisibility(View.INVISIBLE);
        tab1Next.setText("다음");
        tab1Next.setBackground(getResources().getDrawable(R.drawable.first_tab_btn));
    }

}