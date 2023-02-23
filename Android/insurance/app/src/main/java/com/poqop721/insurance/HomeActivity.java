package com.poqop721.insurance;

import androidx.appcompat.app.AppCompatActivity;

import android.app.Activity;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.drawable.ColorDrawable;
import android.os.Build;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.Toast;

public class HomeActivity extends AppCompatActivity {
    private final long finishtimeed = 3000;
    private long presstime = 0;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.home);
        setTitle("보험 추천");

        getSupportActionBar().setBackgroundDrawable(new ColorDrawable(getResources().getColor(R.color.my_blue)));
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            Window window = this.getWindow();
            window.addFlags(WindowManager.LayoutParams.FLAG_DRAWS_SYSTEM_BAR_BACKGROUNDS);
            window.setStatusBarColor(getResources().getColor(R.color.my_blue_stbar));
        }

        Intent homeIntent = getIntent();
        String sessionId = homeIntent.getStringExtra("sessionId");

        Button tab1Btn, tab2Btn,tab3Btn,tab4Btn,tab5Btn;
        tab1Btn = (Button) findViewById(R.id.tab1Btn);
        tab1Btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intentTab1 = new Intent(getApplicationContext(),FirstTabActivity.class);
                intentTab1.putExtra("sessionId",sessionId);
                startActivity(intentTab1);
            }
        });

        tab2Btn = (Button) findViewById(R.id.tab2Btn);

        tab2Btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intentTab2 = new Intent(getApplicationContext(),SecondTabActivity.class);
                intentTab2.putExtra("sessionId",sessionId);
                intentTab2.putExtra("clear","false");
                startActivity(intentTab2);
            }
        });
        tab3Btn = (Button) findViewById(R.id.tab3Btn);

        tab3Btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intentTab3 = new Intent(getApplicationContext(),ThirdTabActivity.class);
                intentTab3.putExtra("sessionId",sessionId);
                startActivity(intentTab3);
            }
        });
        tab4Btn = (Button) findViewById(R.id.tab4Btn);

        tab4Btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intentTab4 = new Intent(getApplicationContext(),FourthTabActivity.class);
                intentTab4.putExtra("sessionId",sessionId);
                startActivity(intentTab4);
            }
        });

        tab5Btn = (Button) findViewById(R.id.tab5Btn);

        tab5Btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intentTab5 = new Intent(getApplicationContext(),FifthTabActivity.class);
                intentTab5.putExtra("sessionId",sessionId);
                startActivity(intentTab5);
            }
        });
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        super.onCreateOptionsMenu(menu);
        MenuInflater menuInflater = getMenuInflater();
        menuInflater.inflate(R.menu.homemu,menu);
        return true;
    }
    public boolean onOptionsItemSelected(MenuItem item){
        Toast.makeText(getApplicationContext(), "로그아웃 되었습니다.", Toast.LENGTH_SHORT).show();
        finish();
        return true;
    }
    public void onBackPressed() {
        long tempTime = System.currentTimeMillis();
        long intervalTime = tempTime - presstime;

        if (0 <= intervalTime && finishtimeed >= intervalTime)
        {
            Toast.makeText(getApplicationContext(), "로그아웃 되었습니다.", Toast.LENGTH_SHORT).show();
            finish();
        }
        else
        {
            presstime = tempTime;
            Toast.makeText(getApplicationContext(), "한번더 누르시면 로그아웃 됩니다", Toast.LENGTH_SHORT).show();
        }
    }
}