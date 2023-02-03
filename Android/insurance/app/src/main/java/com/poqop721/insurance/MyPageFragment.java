package com.poqop721.insurance;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;

import androidx.fragment.app.ListFragment;

import android.os.Parcelable;
import android.text.InputType;
import android.util.Log;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TableLayout;
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


public class MyPageFragment extends ListFragment {
    StringRequest stringRequest;
    private static final String TAG = "MYPAGE";
    private RequestQueue requestQueue;
    // Store instance variables
    private String title;
    private int page;
    private ArrayList<String> myInfo;
    private String sessionId;

    public MyPageFragment() {
    }

    // newInstance constructor for creating fragment with arguments
    public static MyPageFragment newInstance(int page, String title, ArrayList<String> arrayGroup,String sessionId) {
        MyPageFragment mypagefragment = new MyPageFragment();
        Bundle args = new Bundle();
        args.putInt("someInt", page);
        args.putString("someTitle", title);
        args.putStringArrayList("myInfo",arrayGroup);
        args.putString("sessionId",sessionId);
        mypagefragment.setArguments(args);
        return mypagefragment;
    }

    // Store instance variables based on arguments passed
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        page = getArguments().getInt("someInt", 0);
        title = getArguments().getString("someTitle");
        myInfo = getArguments().getStringArrayList("myInfo");
        sessionId = getArguments().getString("sessionId");
    }

    // Inflate the view for the fragment based on layout XML
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment, container, false);
        TextView tvLabel = (TextView) view.findViewById(R.id.about);
        title = myInfo.get(1) + title;
        tvLabel.setText(title);
        tvLabel.setTextColor(Color.parseColor("#6368dc"));
        ListView listView = (ListView) view.findViewById(android.R.id.list);
        listView.setVisibility(View.GONE);
        TextView id = (TextView)view.findViewById(R.id.myPageid);
        TextView name = (TextView)view.findViewById(R.id.myPageName);
        TextView birth = (TextView)view.findViewById(R.id.myPageBirth);
        TextView height = (TextView)view.findViewById(R.id.myPageHeight);
        TextView weight = (TextView)view.findViewById(R.id.myPageWeight);
        TextView sex = (TextView)view.findViewById(R.id.myPageSex);
        TextView email = (TextView)view.findViewById(R.id.myPageEmail);
        TextView phone = (TextView)view.findViewById(R.id.myPagePhone);
        TextView reg = (TextView)view.findViewById(R.id.myPageReg);
        Button change = (Button) view.findViewById(R.id.change);
        Button signout = (Button) view.findViewById(R.id.signout);
        TableLayout myPage = (TableLayout) view.findViewById(R.id.myPageLayout);
        EditText signoutEt = new EditText(getActivity());
        signoutEt.setTextColor(Color.WHITE);
        myPage.setVisibility(View.VISIBLE);

        requestQueue = Volley.newRequestQueue(getActivity());
        String url = "http://poqop721.dothome.co.kr/insurance/android/signout.php";

        id.setText(myInfo.get(0));
        name.setText(myInfo.get(1));
        birth.setText(myInfo.get(2));
        height.setText(myInfo.get(4) + " cm");
        weight.setText(myInfo.get(3) +" kg");
        sex.setText(myInfo.get(5));
        email.setText(myInfo.get(6));
        phone.setText(myInfo.get(7));
        String[] regDate = myInfo.get(8).split(" ");
        reg.setText(regDate[0]);

        change.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent changeInfo = new Intent(getContext(),ChangeInfoActivity.class);
                changeInfo.putExtra("sessionId",sessionId);
                changeInfo.putStringArrayListExtra("myInfo",myInfo);
                startActivity(changeInfo);
            }
        });

        signout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                signoutEt.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD);
                signoutEt.setMaxLines(1);
                signoutEt.setGravity(Gravity.CENTER_HORIZONTAL);
                FrameLayout container = new FrameLayout(getContext());
                FrameLayout.LayoutParams params = new  FrameLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.WRAP_CONTENT);
                params.leftMargin = getResources().getDimensionPixelSize(R.dimen.dialog_margin);
                params.rightMargin = getResources().getDimensionPixelSize(R.dimen.dialog_margin);
                signoutEt.setLayoutParams(params);
                if (signoutEt.getParent() != null)
                    ((ViewGroup) signoutEt.getParent()).removeView(signoutEt);
                container.addView(signoutEt);
                final AlertDialog.Builder dlg = new AlertDialog.Builder(getActivity(),R.style.MyPageAlertDialog);
                dlg.setTitle("회원 탈퇴");
                dlg.setMessage("\n회원 탈퇴를 위해 비밀번호를 입력해주세요.");
                if (container.getParent() != null)
                    ((ViewGroup) container.getParent()).removeView(container);
                dlg.setView(container);
                dlg.setPositiveButton("확인", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        requestQueue.add(stringRequest);
                    }
                });
                dlg.setNegativeButton("취소",null);
                AlertDialog alertDialog = dlg.create();
                dlg.show();
            }
        });

        stringRequest = new StringRequest(Request.Method.POST, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                if(response.equals("yes")){
                    Toast.makeText(getActivity(), "회원 탈퇴가 완료 되었습니다.", Toast.LENGTH_SHORT).show();
                    Intent intent = new Intent(getContext(),MainActivity.class);
                    intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                    startActivity(intent);
                }
                else Toast.makeText(getActivity(), "비밀번호가 일치하지 않습니다.", Toast.LENGTH_SHORT).show();
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
                params.put("PW", signoutEt.getText().toString());
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
}